#!/bin/bash


# Enable alias expansion
shopt -s expand_aliases

# Source shell configuration files to get aliases
# First try to load bash_profile as it often sources other files
if [ -f ~/.bash_profile ]; then
    . ~/.bash_profile
elif [ -f ~/.profile ]; then
    . ~/.profile
fi

# Then load bashrc if it exists (some aliases might be here)
if [ -f ~/.bashrc ]; then
    . ~/.bashrc
fi

# Finally load zshrc if we're in zsh
if [ -n "$ZSH_VERSION" ] && [ -f ~/.zshrc ]; then
    . ~/.zshrc
fi

# Source common utilities
DIR="${BASH_SOURCE%/*}"
if [[ ! -d "$DIR" ]]; then DIR="$PWD"; fi
. "$DIR/get-project-name.sh"

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
    
    # Get the URL of the origin remote
    origin_url=$(git remote get-url origin)
    # Return 2 if origin is not pointing to template (but exists)
    if [[ ! "$origin_url" =~ github\.com/raphael-kagermeier/LaravelFilamentTemplate ]]; then
        return 2
    fi
    return 0
}

setup_template_remote() {
    # Check if template remote already exists
    if git remote | grep -q '^template$'; then
        echo "Template remote already exists, updating configuration..."
        git fetch template
    else
        # Check if origin is the template
        if validate_origin_remote; then
            echo "Setting up template remote..."
            git remote rename origin template
            git fetch template
        else
            echo "Adding template remote..."
            git remote add template "https://github.com/raphael-kagermeier/LaravelFilamentTemplate.git"
            git fetch template
        fi
    fi

    # Configure template remote to track only main branch
    git config --unset-all remote.template.fetch
    git config remote.template.fetch "+refs/heads/main:refs/remotes/template/main"
}

validate_repo_name() {
    local repo_name=$1
    if [[ ! $repo_name =~ ^[a-zA-Z0-9_-]+$ ]]; then
        echo "Error: Invalid repository name. Only alphanumeric characters, hyphens, and underscores are allowed."
        return 1
    fi
}

create_github_repository() {
    local repo_name=$1
    
    if ! command -v gh >/dev/null 2>&1; then
        echo "Error: GitHub CLI (gh) is not installed. Please install it from: https://cli.github.com/"
        return 1
    fi

    if ! gh auth status >/dev/null 2>&1; then
        echo "Error: Not logged in to GitHub CLI. Please run 'gh auth login' first"
        return 1
    fi

    # Check if repository already exists
    if gh repo view "raphael-kagermeier/$repo_name" >/dev/null 2>&1; then
        echo "Repository already exists, skipping creation"
        return 0
    fi

    if ! gh repo create "raphael-kagermeier/$repo_name" --private --confirm >/dev/null 2>&1; then
        echo "Error: Failed to create repository"
        return 1
    fi
}

add_github_secrets() {
    local repo_name=$1
    
    if ! command -v enp >/dev/null 2>&1; then
        echo "Error: Enpass CLI not found. Please install from: https://github.com/hazcod/enpass-cli"
        return 1
    fi
    
    if [ ! -f project.yml ]; then
        echo "Error: project.yml not found"
        return 1
    fi

    # Extract secret keys and their Enpass field names
    local secrets_section
    secrets_section=$(sed -n '/^github:/,/^[^ ]/p' project.yml | grep -v '^github:' | grep -v '^$' | grep -v '^[^ ]')
    
    if [ -z "$secrets_section" ]; then
        echo "Error: No secrets configuration found in project.yml"
        return 1
    fi

    echo "$secrets_section" | while read -r line; do
        # Extract key and value, trimming whitespace
        local secret_key=$(echo "$line" | cut -d: -f1 | sed 's/^[[:space:]]*//' | sed 's/[[:space:]]*$//')
        local enpass_key=$(echo "$line" | cut -d: -f2 | sed 's/^[[:space:]]*//' | sed 's/[[:space:]]*$//')
        
        if [ -z "$secret_key" ] || [ -z "$enpass_key" ]; then
            echo "Error: Invalid configuration line: $line"
            continue
        fi
        
        echo "Processing GitHub secret: $secret_key"
        local secret_value
        secret_value=$(enp pass "$enpass_key")
        
        if [ -z "$secret_value" ]; then
            echo "Warning: Could not fetch $secret_key from Enpass"
            continue
        fi
        
        if ! gh secret set "$secret_key" -b"$secret_value" -R "raphael-kagermeier/$repo_name"; then
            echo "Warning: Failed to set GitHub secret: $secret_key"
        fi
    done
}

setup_git_remotes() {
    local repo_name=$1
    
    validate_git_repository || return 1
    
    # Check origin remote status but don't fail if it's not template
    origin_status=$(validate_origin_remote; echo $?)
    
    setup_template_remote
    
    if [ -z "$repo_name" ]; then
        local default_name
        default_name=$(get_project_folder_name)
        read -p "Enter repository name [$default_name]: " repo_name
        repo_name=${repo_name:-$default_name}
    fi
    
    validate_repo_name "$repo_name" || return 1
    create_github_repository "$repo_name" || return 1
    
    read -p "Add deployment secrets to repository? (y/N): " add_secrets
    if [[ $add_secrets =~ ^[Yy]$ ]]; then
        add_github_secrets "$repo_name"
    fi
    
    # Only add origin if it doesn't exist or if it was the template
    if [ "$origin_status" -ne 2 ]; then
        git remote add origin "https://github.com/raphael-kagermeier/${repo_name}.git"
    fi
    
    if [ -z "$(git log -1 2>/dev/null)" ]; then
        git add .
        git commit -m "Initial commit"
    fi
    
    git push -u origin main
    
    echo "Remote setup completed successfully!"
    git remote -v
}

# If script is run directly (not sourced), execute setup
if [ "${BASH_SOURCE[0]}" -ef "$0" ]; then
    setup_git_remotes "$1"
fi 
