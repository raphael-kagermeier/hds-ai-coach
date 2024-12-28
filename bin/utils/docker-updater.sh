#!/bin/bash

# Function to update docker-compose.yml file based on OS
update_docker_file() {
    local app_id=$1
    local docker_file="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)/docker-compose.yml"
    local replacements=(
        "lft\.test/${app_id}\.test"
        "lft-8\.3/${app_id}-8\.3"
        "lft-pgsql/${app_id}-pgsql"
        "lft-redis/${app_id}-redis"
        "lft-meilisearch/${app_id}-meilisearch"
    )

    if [[ "$OSTYPE" == "darwin"* ]]; then
        # MacOS
        for replacement in "${replacements[@]}"; do
            sed -i '' "s/$replacement/" "$docker_file"
        done
    else
        # Linux
        for replacement in "${replacements[@]}"; do
            sed -i "s/$replacement/" "$docker_file"
        done
    fi
} 