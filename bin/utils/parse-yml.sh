#!/bin/bash

# Function to parse app_id from project.yml
get_app_id() {
    # Get the project root directory (two levels up from utils)
    PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
    PROJECT_YML="${PROJECT_ROOT}/project.yml"

    if [ ! -f "$PROJECT_YML" ]; then
        echo "Error: project.yml not found at $PROJECT_YML" >&2
        return 1
    fi

    # First check if the id line exists at all
    local id_line=$(grep -A 5 "app:" "$PROJECT_YML" | grep "id:")
    if [ -z "$id_line" ]; then
        echo "Error: Could not find app.id in project.yml" >&2
        return 1
    fi

    # Check if the value is properly quoted
    if ! echo "$id_line" | grep -q '".*"'; then
        echo "Error: app.id must be in quotes in project.yml" >&2
        echo "Current format: $id_line" >&2
        echo "Expected format: id: &app_id \"your-project-id\"" >&2
        return 1
    fi

    # Extract the quoted value
    local app_id=$(echo "$id_line" | grep -o '"[^"]*"' | sed 's/"//g')
    
    if [ -n "$app_id" ]; then
        echo "$app_id"
        return 0
    else
        echo "Error: Could not parse app.id value" >&2
        return 1
    fi
} 