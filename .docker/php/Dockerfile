FROM composer:2 AS composer

FROM php:8.5-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install zip \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY .docker/php/php-custom.ini /usr/local/etc/php/conf.d/php-custom.ini

WORKDIR /var/www

CMD ["php-fpm"]
