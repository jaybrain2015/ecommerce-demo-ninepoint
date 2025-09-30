
FROM php:8.2-cli


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


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html


COPY . /var/www/html


RUN composer install --no-interaction --prefer-dist \
    && php artisan config:clear || true


RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000


CMD ["bash", "-lc", "if [ ! -f .env ] && [ -f .env.example ]; then cp .env.example .env && php artisan key:generate; fi; php artisan serve --host=0.0.0.0 --port=8000"]
