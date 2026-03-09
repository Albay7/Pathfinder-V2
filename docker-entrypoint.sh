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

# Laravel setup (if artisan exists)
if [ -f "artisan" ]; then
    echo "Clearing Laravel caches..."
    php artisan config:clear || true
    php artisan cache:clear || true
    php artisan route:clear || true
    php artisan view:clear || true

    # Run migrations (safe, non-destructive)
    echo "Running database migrations..."
    php artisan migrate --force || echo "Migrations failed, continuing..."

    # Cache config and routes for performance
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
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
