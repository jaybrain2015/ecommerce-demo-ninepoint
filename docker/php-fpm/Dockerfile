# PHP-FPM image for Laravel (production-oriented)
FROM php:8.2-fpm

# Install system packages and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libpng-dev \
        libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        intl \
        zip \
    && rm -rf /var/lib/apt/lists/*

# Opcache for performance
RUN docker-php-ext-install opcache
COPY ./docker/php-fpm/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /var/www/html

# Copy application source
COPY . /var/www/html

# Install PHP dependencies (no dev) and optimize autoloader
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader \
    && mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Expose FPM port
EXPOSE 9000

# Default command
CMD ["php-fpm"]
