#!/bin/bash

# Check if project name is provided as argument
if [ -z "$1" ]; then
    echo "Please provide the project name as an argument"
    echo "Usage: ./generate-app-key.sh project-name [--force]"
    echo "  --force    Overwrite existing APP_KEY if it exists"
    exit 1
fi

PROJECT_NAME=$1
FORCE=false

# Check for --force flag
if [ "$2" = "--force" ]; then
    FORCE=true
fi

SSM_PARAM_NAME="/${PROJECT_NAME}/app_key"

# Check if parameter already exists
if aws ssm get-parameter --name "$SSM_PARAM_NAME" --region eu-central-1 &> /dev/null; then
    if [ "$FORCE" = false ]; then
        echo "APP_KEY already exists at: $SSM_PARAM_NAME"
        echo "Use --force flag to overwrite existing key"
        exit 0
    fi
fi

# Generate a secure 32-byte random key and encode it to base64
APP_KEY=$(php -r '
    $bytes = random_bytes(32);
    echo base64_encode($bytes);
')

# Store the app key in SSM Parameter Store
aws ssm put-parameter \
    --region eu-central-1 \
    --name "$SSM_PARAM_NAME" \
    --type "String" \
    --value "$APP_KEY" \
    --overwrite

# Check if the command was successful
if [ $? -eq 0 ]; then
    echo "Successfully stored APP_KEY in SSM Parameter Store at: $SSM_PARAM_NAME"
else
    echo "Failed to store APP_KEY in SSM Parameter Store"
    exit 1
fi 