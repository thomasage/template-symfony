#syntax=docker/dockerfile:1

FROM dunglas/frankenphp:1-php8.5 AS frankenphp_base

WORKDIR /app

VOLUME /app/var/

# The FrankenPHP Symfony runtime is used in every environment (safe for CLI too)
ENV APP_RUNTIME="Runtime\FrankenPhpSymfony\Runtime"
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install --no-install-recommends -y \
	git \
	unzip \
	&& rm -rf /var/lib/apt/lists/*

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		gd \
		intl \
		mbstring \
		opcache \
		pdo_mysql \
		redis \
		zip \
	;

RUN git config --global --add safe.directory /app

COPY --link docker/frankenphp/conf.d/10-app.ini $PHP_INI_DIR/conf.d/
COPY --link --chmod=755 docker/frankenphp/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

ENTRYPOINT ["docker-entrypoint"]

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

# --- Development image ---
FROM frankenphp_base AS frankenphp_dev

ENV APP_ENV=dev
ENV XDEBUG_MODE=off

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN set -eux; \
	install-php-extensions pcov;

COPY --link docker/frankenphp/conf.d/20-app.dev.ini $PHP_INI_DIR/conf.d/

# --- Production image ---
FROM frankenphp_base AS frankenphp_prod

ENV APP_ENV=prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --link docker/frankenphp/conf.d/20-app.prod.ini $PHP_INI_DIR/conf.d/

# Copy the application sources (respecting .dockerignore) and install prod deps
COPY --link . ./
RUN rm -rf docker/

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer install --no-cache --no-dev --optimize-autoloader --classmap-authoritative --no-interaction; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; \
	sync;
