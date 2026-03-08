# Use the official PHP 8.2 image as a base
FROM php:8.2-fpm as vendor

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libicu-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip exif pcntl gd bcmath intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files from Pathfinder subdirectory and install dependencies
COPY Pathfinder/composer.json Pathfinder/composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

# Use a separate image for the frontend build
FROM node:18 as frontend

# Set working directory
WORKDIR /app

# Copy all files from Pathfinder subdirectory for build
COPY Pathfinder/ .

# Install dependencies and build assets
RUN npm install && npm run build

# Use a final image for the application
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    procps \
    supervisor

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip exif pcntl gd bcmath intl

# Set working directory
WORKDIR /var/www/html

# Copy application code from Pathfinder subdirectory
COPY Pathfinder/ .

# Copy vendor directory from the vendor image
COPY --from=vendor /app/vendor ./vendor

# Copy built assets from the frontend image
COPY --from=frontend /app/public ./public

# Copy Nginx configuration
COPY .docker/nginx.conf /etc/nginx/sites-available/default

# Copy supervisor configuration
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy and setup entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Use entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]
