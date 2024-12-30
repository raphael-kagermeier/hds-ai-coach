#!/bin/bash

# Source utility functions
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "$SCRIPT_DIR/ssm-helpers.sh"

# Function to generate and store a new APP_KEY in SSM
generate_and_store_app_key() {
    local force=${1:-false}
    
    # Get the project root directory
    local PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
    
    # Generate new APP_KEY using the KeyReturnCommand and get only the last line
    # which should be the key, ignoring deprecation notices
    local app_key=$(php "${PROJECT_ROOT}/artisan" key:return 2>/dev/null | tail -1)
    if [ -z "$app_key" ]; then
        echo "Error: Failed to generate APP_KEY"
        return 1
    fi

    # Store the APP_KEY in SSM
    store_ssm_parameter "app_key" "$app_key" "$force"
    return $?
}