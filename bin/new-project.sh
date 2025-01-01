#!/bin/bash

# Source utility functions
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
UTILS_DIR="$SCRIPT_DIR/utils"

# Source all utility scripts
for util in "$UTILS_DIR"/*.sh; do
    source "$util"
done

# Initialize variables
APP_NAME=""
APP_ID=""
GITHUB_URL=""

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

# Generate and store APP_KEY in SSM
generate_and_store_app_key true

# Setup git remotes
setup_git_remotes "$GITHUB_URL"

# Output success message
echo "App configuration completed successfully:"
echo "APP_NAME: $APP_NAME"
echo "APP_ID: $APP_ID"
if [ ! -z "$GITHUB_URL" ]; then
    echo "GITHUB_URL: $GITHUB_URL"
fi
