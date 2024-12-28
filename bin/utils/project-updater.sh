#!/bin/bash

# Function to update project.yml file based on OS
update_project_file() {
    local app_name=$1
    local app_id=$2
    local project_file="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)/project.yml"

    if [[ "$OSTYPE" == "darwin"* ]]; then
        # MacOS
        sed -i '' "s/^  name: &app_name.*$/  name: \&app_name \"$app_name\"/" "$project_file"
        sed -i '' "s/^  id: &app_id.*$/  id: \&app_id \"$app_id\"/" "$project_file"
    else
        # Linux
        sed -i "s/^  name: &app_name.*$/  name: \&app_name \"$app_name\"/" "$project_file"
        sed -i "s/^  id: &app_id.*$/  id: \&app_id \"$app_id\"/" "$project_file"
    fi
} 