#!/bin/bash

# Substitute PORT in Nginx config
echo "Setting Nginx to listen on port ${PORT:-8080}..."
sed -i "s/PORT_PLACEHOLDER/${PORT:-8080}/g" /etc/nginx/sites-available/default

# Clear caches (if Laravel exists)
if [ -f "artisan" ]; then
    echo "Clearing Laravel caches..."
    php artisan config:clear || true
    php artisan cache:clear || true
    php artisan route:clear || true
    php artisan view:clear || true

    # Run migrations if enabled
    if [ "$RUN_MIGRATIONS" = "true" ]; then
        echo "Running migrations..."
        php artisan migrate --force || echo "Migrations failed, continuing..."
    fi
fi

# Set permissions for storage and bootstrap/cache
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Start Supervisord to manage PHP-FPM and Nginx
echo "Starting Supervisord..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
