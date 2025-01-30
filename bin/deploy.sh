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
# Serverless Deployment
######################################################

echo "Deploying to $STAGE environment..."
serverless deploy --stage $STAGE --verbose

######################################################
# Post Deployment
######################################################

serverless bref:cli --stage $STAGE --args='db:provision'
serverless bref:cli --stage $STAGE --args='migrate --force'
serverless bref:cli --stage $STAGE --args='config:cache'
serverless bref:cli --stage $STAGE --args='route:cache'
serverless bref:cli --stage $STAGE --args='view:cache'

# seed the database
serverless bref:cli --stage $STAGE --args='db:seed --class=DeploymentSeeder --force'