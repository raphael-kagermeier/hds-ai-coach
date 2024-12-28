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

# Get project root directory and change to it
PROJECT_ROOT="$(dirname "${SCRIPT_DIR}")"
cd "${PROJECT_ROOT}"

######################################################
# Laravel Optimizations
######################################################

# Laravel Optimizations
php artisan filament:optimize-clear
php artisan filament:optimize

# clear caches
php artisan config:clear
php artisan view:clear   
php artisan cache:clear
php artisan route:clear

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

# seed the database
serverless bref:cli --stage $STAGE --args='db:seed --class=DeploymentSeeder --force'

# reset cache
php artisan filament:optimize-clear