#!/bin/bash

# Function to update README.md file based on OS
update_readme_file() {
    local app_name=$1
    local readme_file="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)/README.md"

    if [[ "$OSTYPE" == "darwin"* ]]; then
        # MacOS
        sed -i '' "s/# LaravelFilamentTemplate/# ${app_name}/" "$readme_file"
    else
        # Linux
        sed -i "s/# LaravelFilamentTemplate/# ${app_name}/" "$readme_file"
    fi
} 