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

# var/ est monté comme volume nommé (perf) : www-data doit pouvoir y écrire
# (cache, logs) dès la première création du volume, qui copie ce contenu.
RUN mkdir -p var/cache var/log && chown -R www-data:www-data var

CMD ["php-fpm"]
