FROM php:8.4.15-fpm-bookworm

RUN apt-get update && \
    apt-get install -y unzip watchman && \
    rm -rf /var/lib/apt/lists/*

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN install-php-extensions apcu gd intl mbstring opcache pdo pdo_mysql pcov redis zip

WORKDIR "/srv"
