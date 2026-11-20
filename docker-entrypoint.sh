#!/bin/sh
set -e

# Cache configuration, routes, and views if in production / uat
if [ "$APP_ENV" = "production" ] || [ "$APP_ENV" = "uat" ]; then
    echo "Caching configuration, routes, and views for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Ensure storage directories are writable
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

if command -v frankenphp >/dev/null 2>&1; then
    exec frankenphp run --config /etc/caddy/Caddyfile
elif command -v php-fpm >/dev/null 2>&1; then
    exec php-fpm
else
    exec php artisan serve --host=0.0.0.0 --port=8000
fi
