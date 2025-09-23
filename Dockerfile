# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    gettext-base \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
RUN npm install && npm run prod

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache to use Railway's PORT environment variable
RUN echo 'Listen ${PORT}\n\
<VirtualHost *:${PORT}>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expose port 80 (Railway will override with PORT env var)
EXPOSE 80

# Create startup script with better error handling
RUN echo '#!/bin/bash\n\
set -e\n\
echo "Starting application..."\n\
\n\
# Set default PORT if not provided by Railway\n\
export PORT=${PORT:-80}\n\
echo "Using PORT: $PORT"\n\
\n\
# Set ServerName to suppress Apache warning\n\
echo "ServerName localhost" >> /etc/apache2/apache2.conf\n\
\n\
# Configure Apache to use the PORT environment variable\n\
envsubst < /etc/apache2/sites-available/000-default.conf > /tmp/vhost.conf\n\
mv /tmp/vhost.conf /etc/apache2/sites-available/000-default.conf\n\
\n\
# Wait for database to be ready\n\
echo "Waiting for database connection..."\n\
php artisan migrate:status || echo "Database not ready yet, will retry migrations"\n\
\n\
# Run migrations with retry logic\n\
echo "Running database migrations..."\n\
for i in {1..5}; do\n\
    if php artisan migrate --force; then\n\
        echo "Migrations completed successfully"\n\
        break\n\
    else\n\
        echo "Migration attempt $i failed, retrying in 5 seconds..."\n\
        sleep 5\n\
    fi\n\
done\n\
\n\
# Generate application key if not exists\n\
php artisan key:generate --force || echo "Key generation failed, continuing..."\n\
\n\
# Clear and cache config\n\
php artisan config:cache || echo "Config cache failed, continuing..."\n\
\n\
# Start Apache\n\
echo "Starting Apache server on port $PORT..."\n\
apache2-foreground' > /start.sh && chmod +x /start.sh

# Start Apache
CMD ["/start.sh"]