#!/bin/sh

# Exit on error
set -e

echo "Starting deployment setup..."

# Cache config and routes
echo "Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

echo "Starting services..."
# Start PHP-FPM
php-fpm -D

# Start Nginx
exec nginx -g "daemon off;"
