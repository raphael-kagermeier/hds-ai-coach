#!/bin/bash

# Source utility functions
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "$SCRIPT_DIR/utils/input-helpers.sh"
source "$SCRIPT_DIR/utils/ssm-helpers.sh"

# Initialize variables
PARAM_NAME=""
PARAM_VALUE=""
FORCE=false

# Function to show usage
show_usage() {
    echo "Usage: $0 [param-name] [param-value] [--force]"
    echo "  param-name    The name of the parameter to store"
    echo "  param-value   The value to store"
    echo "  --force       Overwrite existing parameter if it exists"
    exit 1
}

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --force)
            FORCE=true
            shift
            ;;
        --help)
            show_usage
            ;;
        *)
            if [ -z "$PARAM_NAME" ]; then
                PARAM_NAME="$1"
            elif [ -z "$PARAM_VALUE" ]; then
                PARAM_VALUE="$1"
            else
                echo "Error: Unexpected argument: $1"
                show_usage
            fi
            shift
            ;;
    esac
done

# Prompt for values if not provided
prompt_if_empty "$PARAM_NAME" "Enter parameter name: " PARAM_NAME
prompt_if_empty "$PARAM_VALUE" "Enter parameter value: " PARAM_VALUE

# Store the parameter in SSM
store_ssm_parameter "$PARAM_NAME" "$PARAM_VALUE" "$FORCE" 