#!/bin/bash

# Log all output to a file and stdout
exec > >(tee -a /var/log/entrypoint.log) 2>&1

echo "Starting entrypoint script (simplified for debugging)..."

# Substitute PORT in Nginx config
echo "Setting Nginx to listen on port ${PORT:-8080}..."
sed -i "s/PORT_PLACEHOLDER/${PORT:-8080}/g" /etc/nginx/sites-available/default

# Ensure PHP-FPM config allows listening on 9000
if [ -f "/usr/local/etc/php-fpm.d/www.conf" ]; then
    echo "Configuring PHP-FPM to listen on 127.0.0.1:9000..."
    sed -i "s|^listen = .*|listen = 127.0.0.1:9000|" /usr/local/etc/php-fpm.d/www.conf
fi

# Start Supervisord to manage PHP-FPM and Nginx
echo "Starting Supervisord..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
