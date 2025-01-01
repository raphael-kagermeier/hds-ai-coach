#!/bin/bash

# Source common utilities
DIR="${BASH_SOURCE%/*}"
if [[ ! -d "$DIR" ]]; then DIR="$PWD"; fi
. "$DIR/input-helpers.sh"
. "$DIR/file-validators.sh"

validate_git_repository() {
    if ! git rev-parse --git-dir > /dev/null 2>&1; then
        echo "Error: Not a git repository"
        return 1
    fi
}

validate_origin_remote() {
    if ! git remote | grep -q '^origin$'; then
        echo "Error: 'origin' remote does not exist"
        return 1
    fi
}

setup_template_remote() {
    echo "Renaming 'origin' remote to 'template'..."
    git remote rename origin template

    echo "Fetching from template remote..."
    git fetch template

    echo "Setting up template remote to track only main branch..."
    # Remove all fetch refs except main
    git config --unset-all remote.template.fetch
    git config remote.template.fetch "+refs/heads/main:refs/remotes/template/main"
}

validate_github_url() {
    local url=$1
    if [[ ! $url =~ ^https://github\.com/.+/.+\.git$ ]] && [[ ! $url =~ ^git@github\.com:.+/.+\.git$ ]]; then
        echo "Error: Invalid GitHub URL format. Please use HTTPS (https://github.com/user/repo.git) or SSH (git@github.com:user/repo.git) format."
        return 1
    fi
}

setup_new_origin() {
    local new_origin_url=$1
    echo "Adding new origin remote..."
    git remote add origin "$new_origin_url"
}

create_initial_commit() {
    if [ -z "$(git log -1 2>/dev/null)" ]; then
        echo "Creating initial commit..."
        git add .
        git commit -m "Initial commit"
    fi
}

push_to_origin() {
    echo "Pushing to new origin remote..."
    git push -u origin main
}

setup_git_remotes() {
    local new_origin_url=$1
    
    validate_git_repository || return 1
    validate_origin_remote || return 1
    
    setup_template_remote
    
    if [ -z "$new_origin_url" ]; then
        echo -e "\nPlease enter the new GitHub repository URL for 'origin':"
        read -r new_origin_url
    fi
    
    validate_github_url "$new_origin_url" || return 1
    setup_new_origin "$new_origin_url"
    create_initial_commit
    push_to_origin
    
    echo "Remote setup completed successfully!"
    echo "Template remote is now configured to track only the main branch"
    echo "New origin remote has been set up and initial commit pushed"
    
    echo -e "\nCurrent remote configuration:"
    git remote -v
}

# If script is run directly (not sourced), execute setup with no arguments
if [ "${BASH_SOURCE[0]}" -ef "$0" ]; then
    setup_git_remotes
fi 