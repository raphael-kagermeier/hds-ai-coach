#!/bin/bash

# Initialize STAGE as empty
STAGE=""
# Set STAGE only if parameter was provided
if [ ! -z "$1" ]; then
    STAGE="$1"
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

if [ ! -z "$STAGE" ]; then
    echo "Deploying to $STAGE environment..."
    serverless deploy --stage $STAGE --verbose
else
    echo "Deploying to default environment..."
    serverless deploy --verbose
fi



######################################################
# Post Deployment
######################################################
