#!/bin/bash

# Source utility functions
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "$SCRIPT_DIR/parse-yml.sh"

# Function to store a parameter in AWS SSM Parameter Store
store_ssm_parameter() {
    local param_name=$1
    local param_value=$2
    local force=${3:-false}
    
    if [ -z "$param_name" ] || [ -z "$param_value" ]; then
        echo "Error: Missing required parameters"
        echo "Usage: store_ssm_parameter param-name param-value [force]"
        return 1
    fi

    # Get app_id from project.yml
    local project_name=$(get_app_id)
    if [ $? -ne 0 ]; then
        echo "Error: Failed to get app_id from project.yml"
        return 1
    fi

    local ssm_param_name="/${project_name}/${param_name}"

    # Check if parameter already exists
    if aws ssm get-parameter --name "$ssm_param_name" --region eu-central-1 &> /dev/null; then
        if [ "$force" = false ]; then
            echo "Parameter already exists at: $ssm_param_name"
            echo "Set force=true to overwrite existing parameter"
            return 0
        fi
    fi

    # Store the parameter in SSM Parameter Store
    if aws ssm put-parameter \
        --region eu-central-1 \
        --name "$ssm_param_name" \
        --type "SecureString" \
        --value "$param_value" \
        --overwrite &> /dev/null; then
        echo "Successfully stored parameter in SSM Parameter Store at: $ssm_param_name"
        return 0
    else
        echo "Failed to store parameter in SSM Parameter Store"
        return 1
    fi
} 