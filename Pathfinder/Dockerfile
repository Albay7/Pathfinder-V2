# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code from Pathfinder subdirectory (build context is repo root)
COPY Pathfinder/ /var/www/html/

# Copy data folders from the root
COPY Courses/ /var/www/html/Courses/
COPY Datasets/ /var/www/html/Datasets/
COPY Jobs/ /var/www/html/Jobs/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
RUN npm install && npm run prod

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Fix Apache MPM conflict: disable mpm_event, keep mpm_prefork (required by mod_php)
RUN a2dismod mpm_event 2>/dev/null || true \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

# Remove default Listen 80 so we only listen on $PORT
RUN echo "# Managed by start.sh" > /etc/apache2/ports.conf

# Set ServerName to suppress warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 8080

# Copy the startup script
COPY Pathfinder/docker-start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
