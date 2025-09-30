# Simple Laravel dev Dockerfile (PHP CLI + artisan serve)
# Build: docker build -t ecommerce-app .
# Run:   docker run --rm -p 8000:8000 --name ecommerce-app \
#              -e APP_URL=http://127.0.0.1:8000 \
#              ecommerce-app

FROM php:8.2-cli

# Install system dependencies and PHP extensions commonly needed by Laravel
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

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app source
COPY . /var/www/html

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist \
    && php artisan config:clear || true

# Ensure writable dirs
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

# Generate app key on first run if no .env present (dev convenience)
CMD ["bash", "-lc", "if [ ! -f .env ] && [ -f .env.example ]; then cp .env.example .env && php artisan key:generate; fi; php artisan serve --host=0.0.0.0 --port=8000"]
