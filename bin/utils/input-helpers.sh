#!/bin/bash

# Function to prompt for input if not provided
prompt_if_empty() {
    local value=$1
    local prompt=$2
    local var_name=$3
    
    if [ -z "$value" ]; then
        read -p "$prompt" $var_name
    else
        eval $var_name=\$value
    fi
}

# Function to parse named arguments
parse_arguments() {
    local app_name_var=$1
    local app_id_var=$2
    shift 2

    while [ $# -gt 0 ]; do
        case "${1}" in
            --name=*)
                eval "$app_name_var=\"${1#*=}\""
                ;;
            --id=*)
                eval "$app_id_var=\"${1#*=}\""
                ;;
            *)
                echo "Unknown parameter: ${1}"
                echo "Usage: ./new-project.sh --name=\"App Name\" --id=\"app-id\""
                exit 1
                ;;
        esac
        shift
    done
}

# Function to validate inputs
validate_inputs() {
    local app_name=$1
    local app_id=$2

    if [ -z "$app_name" ] || [ -z "$app_id" ]; then
        echo "Both app name and app ID are required."
        exit 1
    fi
} 