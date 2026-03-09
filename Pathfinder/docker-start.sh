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

# Create .env file if it doesn't exist (Railway injects env vars)
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from environment variables..."
    touch /var/www/html/.env
    # Write essential Laravel env vars from Railway's environment
    [ -n "$APP_NAME" ] && echo "APP_NAME=$APP_NAME" >> /var/www/html/.env
    [ -n "$APP_ENV" ] && echo "APP_ENV=$APP_ENV" >> /var/www/html/.env
    [ -n "$APP_KEY" ] && echo "APP_KEY=$APP_KEY" >> /var/www/html/.env
    [ -n "$APP_DEBUG" ] && echo "APP_DEBUG=$APP_DEBUG" >> /var/www/html/.env
    [ -n "$APP_URL" ] && echo "APP_URL=$APP_URL" >> /var/www/html/.env
    [ -n "$DB_CONNECTION" ] && echo "DB_CONNECTION=$DB_CONNECTION" >> /var/www/html/.env
    [ -n "$DB_HOST" ] && echo "DB_HOST=$DB_HOST" >> /var/www/html/.env
    [ -n "$DB_PORT" ] && echo "DB_PORT=$DB_PORT" >> /var/www/html/.env
    [ -n "$DB_DATABASE" ] && echo "DB_DATABASE=$DB_DATABASE" >> /var/www/html/.env
    [ -n "$DB_USERNAME" ] && echo "DB_USERNAME=$DB_USERNAME" >> /var/www/html/.env
    [ -n "$DB_PASSWORD" ] && echo "DB_PASSWORD=$DB_PASSWORD" >> /var/www/html/.env
    [ -n "$SESSION_DRIVER" ] && echo "SESSION_DRIVER=$SESSION_DRIVER" >> /var/www/html/.env
    [ -n "$CACHE_STORE" ] && echo "CACHE_STORE=$CACHE_STORE" >> /var/www/html/.env
    [ -n "$QUEUE_CONNECTION" ] && echo "QUEUE_CONNECTION=$QUEUE_CONNECTION" >> /var/www/html/.env
    [ -n "$LOG_CHANNEL" ] && echo "LOG_CHANNEL=$LOG_CHANNEL" >> /var/www/html/.env
    [ -n "$LOG_LEVEL" ] && echo "LOG_LEVEL=$LOG_LEVEL" >> /var/www/html/.env
    [ -n "$MAIL_MAILER" ] && echo "MAIL_MAILER=$MAIL_MAILER" >> /var/www/html/.env
    [ -n "$MAIL_HOST" ] && echo "MAIL_HOST=$MAIL_HOST" >> /var/www/html/.env
    [ -n "$MAIL_PORT" ] && echo "MAIL_PORT=$MAIL_PORT" >> /var/www/html/.env
    [ -n "$MAIL_USERNAME" ] && echo "MAIL_USERNAME=$MAIL_USERNAME" >> /var/www/html/.env
    [ -n "$MAIL_PASSWORD" ] && echo "MAIL_PASSWORD=$MAIL_PASSWORD" >> /var/www/html/.env
    [ -n "$MAIL_FROM_ADDRESS" ] && echo "MAIL_FROM_ADDRESS=$MAIL_FROM_ADDRESS" >> /var/www/html/.env
    [ -n "$MAIL_FROM_NAME" ] && echo "MAIL_FROM_NAME=$MAIL_FROM_NAME" >> /var/www/html/.env
    [ -n "$YOUTUBE_API_KEY" ] && echo "YOUTUBE_API_KEY=$YOUTUBE_API_KEY" >> /var/www/html/.env
    [ -n "$NEWS_API_KEY" ] && echo "NEWS_API_KEY=$NEWS_API_KEY" >> /var/www/html/.env
    chown www-data:www-data /var/www/html/.env
fi

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
