#!/bin/bash

# Log all output to a file and stdout
exec > >(tee -a /var/log/entrypoint.log) 2>&1

echo "Starting entrypoint script..."

# Substitute PORT in Nginx config
echo "Setting Nginx to listen on port ${PORT:-8080}..."
sed -i "s/PORT_PLACEHOLDER/${PORT:-8080}/g" /etc/nginx/sites-available/default

# Create some required directories if they don't exist
mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Clear caches (if Laravel exists)
if [ -f "artisan" ]; then
    echo "Clearing Laravel caches..."
    php artisan config:clear || true
    php artisan cache:clear || true
    php artisan route:clear || true
    php artisan view:clear || true

    # Run migrations if enabled
    if [ "$RUN_MIGRATIONS" = "true" ]; then
        echo "Running migrations with fresh database and seeding..."
        php artisan migrate:fresh --seed --force || echo "Migrations failed, continuing..."
    fi
fi

# Ensure PHP-FPM config allows listening on 9000
# In official php-fpm images, it's usually in /usr/local/etc/php-fpm.d/www.conf
if [ -f "/usr/local/etc/php-fpm.d/www.conf" ]; then
    echo "Configuring PHP-FPM to listen on 127.0.0.1:9000..."
    sed -i "s|^listen = .*|listen = 127.0.0.1:9000|" /usr/local/etc/php-fpm.d/www.conf
fi

# Start Supervisord to manage PHP-FPM and Nginx
echo "Starting Supervisord..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
