#!/bin/bash

# Function to check if required files exist
validate_required_files() {
    local PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
    local required_files=(
        "${PROJECT_ROOT}/.env"
        "${PROJECT_ROOT}/project.yml"
        "${PROJECT_ROOT}/docker-compose.yml"
    )
    
    for file in "${required_files[@]}"; do
        if [ ! -f "$file" ]; then
            echo "$(basename "$file") file not found!"
            exit 1
        fi
    done
} 