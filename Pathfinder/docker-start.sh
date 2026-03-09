#!/bin/bash
set -e

echo "Starting application..."

# Set proper permissions for Laravel directories
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set default PORT if not provided by Railway
export PORT=${PORT:-8080}
echo "Using PORT: $PORT"

# Configure Apache to listen on the correct port
cat > /etc/apache2/ports.conf <<EOF
Listen ${PORT}
EOF

cat > /etc/apache2/sites-available/000-default.conf <<EOF
<VirtualHost *:${PORT}>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

a2ensite 000-default > /dev/null 2>&1 || true

# Remove any .env file that may have been baked into the image
# Railway injects env vars directly into the process - no .env file needed
rm -f /var/www/html/.env

# Clear caches
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations
echo "Running database migrations..."
for i in 1 2 3 4 5; do
    if php artisan migrate --force 2>&1; then
        echo "Migrations completed successfully"
        break
    else
        echo "Migration attempt $i failed, retrying in 5 seconds..."
        sleep 5
    fi
done

# Cache config for performance
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Fix Apache MPM conflict at runtime (build cache may skip this)
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*
if [ ! -f /etc/apache2/mods-enabled/mpm_prefork.load ]; then
    ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
    ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
fi

# Start Apache
echo "Starting Apache in foreground on port $PORT..."
apache2-foreground
