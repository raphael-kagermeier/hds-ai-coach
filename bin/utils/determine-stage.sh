#!/bin/bash

# Array of allowed stages
ALLOWED_STAGES=("production" "staging" "development")

# Function to validate stage
validate_stage() {
    local stage=$1
    for allowed in "${ALLOWED_STAGES[@]}"; do
        if [ "$stage" = "$allowed" ]; then
            return 0
        fi
    done
    return 1
}

# Function to determine stage from branch name
determine_stage_from_branch() {
    local branch=$1

    # Production environments
    if [[ "$branch" =~ ^(main|master|production)$ ]]; then
        echo "production"
        return 0
    fi

    # Staging environments
    if [[ "$branch" =~ ^(staging|develop)$ ]] || \
       [[ "$branch" =~ ^(release/|hotfix/) ]]; then
        echo "staging"
        return 0
    fi

    # Development environments
    if [[ "$branch" =~ ^(feature/|feat-|fix/|bugfix/) ]]; then
        echo "development"
        return 0
    fi

    # Default to development for unknown patterns
    echo "development"
    return 0
}

# Function to get stage (from parameter or branch)
get_stage() {
    local provided_stage=$1
    local stage=""

    if [ -n "$provided_stage" ]; then
        stage="$provided_stage"

        # Validate provided stage
        if ! validate_stage "$stage"; then
            echo "Error: Invalid stage '$stage'. Allowed values: ${ALLOWED_STAGES[*]}" >&2
            return 1
        fi
    else
        # Determine branch name
        if [ -n "$GITHUB_REF_NAME" ]; then
            BRANCH="$GITHUB_REF_NAME"
        else
            BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "unknown")
        fi

        # Handle error in getting branch name
        if [ "$BRANCH" = "unknown" ]; then
            echo "Error: Could not determine branch name" >&2
            return 1
        fi

        # Determine stage from branch
        stage=$(determine_stage_from_branch "$BRANCH")
        echo "Detected branch '$BRANCH', using '$stage' environment" >&2
    fi

    echo "$stage"
    return 0
} 