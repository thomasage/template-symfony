FROM php:8.4.15-fpm-bookworm

RUN apt-get update \
    && apt-get install -y git unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN install-php-extensions apcu gd intl mbstring opcache pdo pdo_mysql pcov redis zip

WORKDIR /builds

COPY composer.json composer.lock symfony.lock ./

RUN composer install --no-progress --prefer-dist --optimize-autoloader --no-scripts --no-autoloader

COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative
