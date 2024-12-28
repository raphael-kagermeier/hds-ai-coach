#!/bin/bash

# Source the utility scripts
SCRIPT_DIR="$(dirname "${BASH_SOURCE[0]}")"
source "${SCRIPT_DIR}/utils/parse-yml.sh"
source "${SCRIPT_DIR}/utils/determine-stage.sh"

# Get app_id from project.yml
APP_ID=$(get_app_id)
if [ $? -ne 0 ]; then
    echo "Failed to get app_id from project.yml"
    exit 1
fi

# Get stage from parameter or determine from branch
STAGE=$(get_stage "$1")
if [ $? -ne 0 ]; then
    exit 2
fi

######################################################
# Laravel Optimizations
######################################################

# Get the project root directory (one level up from SCRIPT_DIR)
PROJECT_ROOT="$(dirname "${SCRIPT_DIR}")"

# Laravel Optimizations
php "${PROJECT_ROOT}/artisan" filament:optimize-clear
php "${PROJECT_ROOT}/artisan" filament:optimize

# clear caches
php "${PROJECT_ROOT}/artisan" config:clear
php "${PROJECT_ROOT}/artisan" view:clear   
php "${PROJECT_ROOT}/artisan" cache:clear
php "${PROJECT_ROOT}/artisan" route:clear

######################################################
# Serverless Deployment
######################################################

echo "Deploying to $STAGE environment..."
serverless deploy --stage $STAGE --verbose

######################################################
# Post Deployment
######################################################

# generate app key if not already set
"${SCRIPT_DIR}/generate-app-key.sh" $APP_ID

serverless bref:cli --stage $STAGE --args='db:provision'
serverless bref:cli --stage $STAGE --args='migrate --force'
serverless bref:cli --stage $STAGE --args='config:cache'

# reset cache
php "${PROJECT_ROOT}/artisan" filament:optimize-clear
