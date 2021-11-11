#!/bin/sh

# Stop script on error signal
set -e

cd code

# Pull code
git pull

# Install non-development composer packages
composer install --no-dev --optimize-autoloader

# Cache config and route files
php artisan config:cache
php artisan route:cache

# Clear old cache
php artisan cache:clear

# Migrate any database changes
php artisan migrate

# Restart queues
php artisan queue:restart



#######################
# Per-project settings
#######################