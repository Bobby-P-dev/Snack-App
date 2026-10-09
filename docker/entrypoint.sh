#!/bin/sh
set -e

echo "Starting Padu Kue Production Container (FrankenPHP)..."

# Ensure storage subdirectories exist
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache

# Ensure correct ownership and permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clean any stale bootstrap cache and discover production packages
rm -f /var/www/html/bootstrap/cache/*.php
php artisan package:discover --ansi || true

# Create storage symlink if not already linked
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link --force || true
fi

# Run database migrations if enabled
if [ "${AUTORUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Checking and executing database migrations..."
    php artisan migrate --force || echo "Warning: Migration command exited with error, continuing boot..."
fi

# Cache configuration, routes, and views for maximum production performance
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "Optimizing application cache (config, routes, views, events)..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
    php artisan event:cache || true
fi

# Optional Queue Worker in background
if [ "${AUTORUN_QUEUE_WORKER:-false}" = "true" ]; then
    echo "Starting background queue worker..."
    php artisan queue:work --tries=3 --timeout=90 --sleep=3 &
fi

echo "Starting FrankenPHP Web & Application Server on port 80..."
exec frankenphp run --config /etc/caddy/Caddyfile
