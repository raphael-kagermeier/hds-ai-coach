#!/bin/bash

get_project_folder_name() {
    # Get the absolute path of the project root
    local project_root
    project_root=$(git rev-parse --show-toplevel 2>/dev/null)
    
    # If not in a git repository, use current directory
    if [ -z "$project_root" ]; then
        project_root="$PWD"
    fi
    
    # Extract just the folder name using basename
    basename "$project_root"
}

# If script is run directly (not sourced), execute the function
if [ "${BASH_SOURCE[0]}" -ef "$0" ]; then
    get_project_folder_name
fi 