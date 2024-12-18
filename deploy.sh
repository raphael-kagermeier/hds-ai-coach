#!/bin/bash

# Initialize variables
STAGE=""
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
determine_stage() {
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

# Get stage from parameter or determine from branch
if [ -n "$1" ]; then
    STAGE="$1"

    # Validate provided stage
    if ! validate_stage "$STAGE"; then
        echo "Error: Invalid stage '$STAGE'. Allowed values: ${ALLOWED_STAGES[*]}"
        exit 2
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
        echo "Error: Could not determine branch name"
        exit 3
    fi

    # Determine stage from branch
    STAGE=$(determine_stage "$BRANCH")
    echo "Detected branch '$BRANCH', deploying to '$STAGE' environment"
fi

######################################################
# Laravel Optimizations
######################################################

# Optimize filament components and blade icons
php artisan filament:optimize-clear
php artisan filament:optimize

# Optimize view, routes, events, configs
php artisan optimize:clear
php artisan optimize

######################################################
# Serverless Deployment
######################################################

echo "Deploying to $STAGE environment..."
serverless deploy --stage $STAGE --verbose

######################################################
# Post Deployment
######################################################

serverless bref:cli --stage $STAGE --args='db:provision'
serverless bref:cli --stage $STAGE --args='migrate --force'
