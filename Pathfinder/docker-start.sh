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

# Always create .env from Railway-injected environment variables
echo "Creating .env from environment variables..."
cat > /var/www/html/.env <<ENVFILE
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG}"
APP_URL="${APP_URL}"
DB_CONNECTION="${DB_CONNECTION}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"
SESSION_DRIVER="${SESSION_DRIVER}"
CACHE_STORE="${CACHE_STORE}"
QUEUE_CONNECTION="${QUEUE_CONNECTION}"
LOG_CHANNEL="${LOG_CHANNEL}"
LOG_LEVEL="${LOG_LEVEL}"
MAIL_MAILER="${MAIL_MAILER}"
MAIL_HOST="${MAIL_HOST}"
MAIL_PORT="${MAIL_PORT}"
MAIL_USERNAME="${MAIL_USERNAME}"
MAIL_PASSWORD="${MAIL_PASSWORD}"
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS}"
MAIL_FROM_NAME="${MAIL_FROM_NAME}"
YOUTUBE_API_KEY="${YOUTUBE_API_KEY}"
NEWS_API_KEY="${NEWS_API_KEY}"
ENVFILE
chown www-data:www-data /var/www/html/.env

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

# Start Apache
echo "Starting Apache in foreground on port $PORT..."
apache2-foreground
