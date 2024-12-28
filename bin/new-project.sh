#!/bin/bash

# Source utility functions
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "$SCRIPT_DIR/utils/input-helpers.sh"
source "$SCRIPT_DIR/utils/file-validators.sh"
source "$SCRIPT_DIR/utils/env-updater.sh"
source "$SCRIPT_DIR/utils/project-updater.sh"
source "$SCRIPT_DIR/utils/docker-updater.sh"
source "$SCRIPT_DIR/utils/laravel-setup.sh"
source "$SCRIPT_DIR/utils/readme-updater.sh"

# Initialize variables
APP_NAME=""
APP_ID=""

# Parse arguments
parse_arguments APP_NAME APP_ID "$@"

# Prompt for values if not provided
prompt_if_empty "$APP_NAME" "Enter app name: " APP_NAME
prompt_if_empty "$APP_ID" "Enter app ID: " APP_ID

# Validate inputs
validate_inputs "$APP_NAME" "$APP_ID"

# Copy .env.local to .env if .env does not exist
copy_env_local_to_env

# Now validate required files after potentially creating .env
validate_required_files

# Update configuration files
update_env_file "$APP_NAME" "$APP_ID"
update_project_file "$APP_NAME" "$APP_ID"
update_docker_file "$APP_ID"
update_readme_file "$APP_NAME"

# Run Laravel setup
setup_laravel "$APP_ID"

# Output success message
echo "App configuration updated:"
echo "APP_NAME: $APP_NAME"
echo "APP_ID: $APP_ID"
echo "Files modified: .env, project.yml, docker-compose.yml"