#!/bin/sh
set -e

# Only run app bootstrap for the server / console entrypoints
if [ "$1" = 'frankenphp' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	# Ensure writable runtime directories exist
	mkdir -p var/cache var/log

	# In non-prod, install dependencies from the mounted source on first boot
	if [ "$APP_ENV" != 'prod' ] && [ ! -d vendor ]; then
		composer install --prefer-dist --no-progress --no-interaction
	fi

	# Optionally run migrations when AUTO_MIGRATE=1
	if [ "$AUTO_MIGRATE" = '1' ]; then
		php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
	fi
fi

exec docker-php-entrypoint "$@"
