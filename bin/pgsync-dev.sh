#!/bin/bash

# Load environment variables from .env
set -a
source .env
set +a

# Check if required env variables are set
if [ -z "$DB_USERNAME" ] || [ -z "$DB_PASSWORD" ] || [ -z "$DB_EXTERNAL_PORT" ] || [ -z "$DB_DATABASE" ]; then
    echo "Error: Required database environment variables are not set in .env"
    echo "Please ensure DB_USERNAME, DB_PASSWORD, DB_PORT, and DB_DATABASE are set"
    exit 1
fi

# Check if pgsync is installed
if ! command -v pgsync &> /dev/null; then
    echo "pgsync is not installed. Installing..."
    brew install pgsync
fi

# Check if aws cli is installed
if ! command -v aws &> /dev/null; then
    echo "AWS CLI is not installed. Please install it first."
    exit 1
fi

# Get AWS region from project.yml
AWS_REGION=$(grep 'default_region:' project.yml | awk '{print $2}')
if [ -z "$AWS_REGION" ]; then
    echo "Failed to get AWS region from project.yml"
    exit 1
fi
echo "Using AWS Region: $AWS_REGION"

# Get the app ID from project.yml (handling YAML structure)
APP_ID=$(sed -n '/^app:/,/^[^[:space:]]/p' project.yml | grep '  id:' | cut -d'"' -f2)
if [ -z "$APP_ID" ]; then
    echo "Failed to get APP_ID from project.yml"
    exit 1
fi
echo "Using APP_ID: $APP_ID"

# Ask for stage with default
read -p "Enter stage (default: development): " STAGE
STAGE=${STAGE:-development}
echo "Using stage: $STAGE"

# Construct cloud database name
CLOUD_DB_NAME="${APP_ID}-${STAGE}"
echo "Using cloud database: $CLOUD_DB_NAME"

# Ensure we have the RDS root certificate
if [ ! -f "rds-ca-2019-root.pem" ]; then
    echo "Downloading RDS root certificate..."
    curl -o rds-ca-2019-root.pem https://truststore.pki.rds.amazonaws.com/global/global-bundle.pem
fi

# Get RDS password from SSM Parameter Store using stage-specific path
echo "Fetching RDS password from SSM..."
SSM_PATH="/prod-primary-projects/rds-password"
RDS_PASSWORD=$(aws ssm get-parameter --name "$SSM_PATH" --with-decryption --query "Parameter.Value" --output text --region "$AWS_REGION")

if [ -z "$RDS_PASSWORD" ]; then
    echo "Failed to retrieve RDS password from SSM at path: $SSM_PATH"
    exit 1
fi

# Get RDS endpoint from SSM
echo "Fetching RDS endpoint from SSM..."
RDS_ENDPOINT_PATH="/prod-primary-projects/rds-endpoint"
RDS_ENDPOINT=$(aws ssm get-parameter --name "$RDS_ENDPOINT_PATH" --with-decryption --query "Parameter.Value" --output text --region "$AWS_REGION")

if [ -z "$RDS_ENDPOINT" ]; then
    echo "Failed to retrieve RDS endpoint from SSM at path: $RDS_ENDPOINT_PATH"
    exit 1
fi

# Construct local database URL from env variables
LOCAL_DB_URL="postgres://${DB_USERNAME}:${DB_PASSWORD}@127.0.0.1:${DB_EXTERNAL_PORT}/${DB_DATABASE}?sslmode=disable"
CLOUD_DB_URL="postgres://postgres:${RDS_PASSWORD}@${RDS_ENDPOINT}:5432/${CLOUD_DB_NAME}?sslmode=verify-full&sslrootcert=rds-ca-2019-root.pem"

# Ask for sync direction
echo "Select sync direction:"
echo "1) Local -> Cloud"
echo "2) Cloud -> Local"
read -p "Enter your choice (1 or 2): " direction

case $direction in
    1)
        echo "Syncing from local to cloud..."
        FROM_URL="$LOCAL_DB_URL"
        TO_URL="$CLOUD_DB_URL"
        ;;
    2)
        echo "Syncing from cloud to local..."
        FROM_URL="$CLOUD_DB_URL"
        TO_URL="$LOCAL_DB_URL"
        ;;
    *)
        echo "Invalid choice. Please select 1 or 2."
        exit 1
        ;;
esac

# Run the sync
echo "Please confirm the following settings:"
echo "--------------------------------"
echo "Stage: $STAGE"
echo "AWS Region: $AWS_REGION"
echo "SSM Path: $SSM_PATH"
echo "RDS Endpoint: $RDS_ENDPOINT"
echo "Local Database: $DB_DATABASE"
echo "Cloud Database: $CLOUD_DB_NAME"
echo "FROM_URL: $FROM_URL"
echo "TO_URL: $TO_URL"
echo "--------------------------------"

read -p "Do you want to proceed with the sync? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    echo "Sync cancelled."
    exit 1
fi

echo "Starting database sync..."

pgsync \
  --from "$FROM_URL" \
  --to "$TO_URL" \
  --to-safe \
  --defer-constraints