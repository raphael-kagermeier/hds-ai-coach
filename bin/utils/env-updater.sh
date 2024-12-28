#!/bin/bash

# Function that copies the .env.local to .env if .env does not exist
copy_env_local_to_env() {
    # Get the project root directory (two levels up from utils)
    PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
    
    if [ ! -f "${PROJECT_ROOT}/.env" ]; then
        if [ ! -f "${PROJECT_ROOT}/.env.example" ]; then
            echo "Error: .env.example not found in ${PROJECT_ROOT}"
            exit 1
        fi
        echo "Creating .env from .env.example..."
        cp "${PROJECT_ROOT}/.env.example" "${PROJECT_ROOT}/.env"
        if [ $? -ne 0 ]; then
            echo "Error: Failed to copy .env.example to .env"
            exit 1
        fi
    fi
}

# Function to update .env file based on OS
update_env_file() {
    local app_name=$1
    local app_id=$2
    local env_file="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)/.env"
    local env_testing_file="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)/.env.testing"

    if [[ "$OSTYPE" == "darwin"* ]]; then
        # MacOS
        sed -i '' "s/^APP_NAME=.*$/APP_NAME=\"$app_name\"/" "$env_file"
        sed -i '' "s/^APP_ID=.*$/APP_ID=\"$app_id\"/" "$env_file"
        sed -i '' "s/^APP_SERVICE=.*$/APP_SERVICE=\"$app_id.app\"/" "$env_file"

        sed -i '' "s/^APP_NAME=.*$/APP_NAME=\"$app_name\"/" "$env_testing_file" 
        sed -i '' "s/^APP_ID=.*$/APP_ID=\"$app_id\"/" "$env_testing_file"
        sed -i '' "s/^APP_SERVICE=.*$/APP_SERVICE=\"$app_id.app\"/" "$env_testing_file"
    else
        # Linux
        sed -i "s/^APP_NAME=.*$/APP_NAME=\"$app_name\"/" "$env_file"
        sed -i "s/^APP_ID=.*$/APP_ID=\"$app_id\"/" "$env_file"
        sed -i "s/^APP_SERVICE=.*$/APP_SERVICE=\"$app_id.app\"/" "$env_file"

        sed -i "s/^APP_NAME=.*$/APP_NAME=\"$app_name\"/" "$env_testing_file"
        sed -i "s/^APP_ID=.*$/APP_ID=\"$app_id\"/" "$env_testing_file"
        sed -i "s/^APP_SERVICE=.*$/APP_SERVICE=\"$app_id.app\"/" "$env_testing_file"
    fi
}