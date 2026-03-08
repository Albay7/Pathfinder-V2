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
    zip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

# Use a separate image for the frontend build
FROM node:18 as frontend

# Set working directory
WORKDIR /app

# Copy package.json and package-lock.json
COPY package.json package-lock.json ./

# Install dependencies and build assets
RUN npm install && npm run build

# Use a final, smaller image for the application
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl gd

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Copy vendor directory from the vendor image
COPY --from=vendor /app/vendor ./vendor

# Copy built assets from the frontend image
COPY --from=frontend /app/public ./public

# Copy Nginx configuration
COPY ./.docker/nginx.conf /etc/nginx/sites-available/default

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Nginx and PHP-FPM
CMD ["/bin/bash", "-c", "php-fpm & nginx -g 'daemon off;'"]
