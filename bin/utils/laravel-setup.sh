#!/bin/bash

setup_laravel() {
    local app_id=$1
    local PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

    echo "Setting up Laravel application..."
    
    # Change to project root directory
    cd "$PROJECT_ROOT" || exit 1
    
    # Run composer install
    echo "Installing composer dependencies..."
    composer install
    
    # Start sail containers
    echo "Building and starting Docker containers..."
    ./vendor/bin/sail up --build -d
    
    # Install npm dependencies
    echo "Installing npm dependencies..."
    ./vendor/bin/sail npm install
    
    # Generate application key
    echo "Generating application key..."
    ./vendor/bin/sail artisan key:generate
    
    # Clear config cache
    echo "Clearing configuration cache..."
    ./vendor/bin/sail artisan config:clear
    
    # Run database provisioning
    echo "Provisioning database..."
    ./vendor/bin/sail artisan db:provision
    
    # Run migrations
    echo "Running database migrations..."
    ./vendor/bin/sail artisan migrate
    
    # Run seeds
    echo "Seeding database..."
    ./vendor/bin/sail artisan db:seed

    echo "Laravel setup completed successfully!"

    # cd back to the original directory
    cd - || exit 1
} 