.DEFAULT_GOAL := help
.PHONY: console composer install qa restart start start_containers stop

# Show this help
help:
	@cat $(MAKEFILE_LIST) | docker run --rm -i xanders/make-help

##
## Docker
##

# Init database !!! Caution: will flush all data !!!
init_database:
	@docker compose exec php bin/console doctrine:schema:drop --force --full-database
	@docker compose exec php bin/console doctrine:migrations:migrate -n
	@docker compose exec php bin/console doctrine:fixtures:load -n

# Init SSL certificates
init_ssl:
	@mkcert "acme.localhost"
	@mv "acme.localhost.pem" docker/ssl/certificate.pem
	@mv "acme.localhost-key.pem" docker/ssl/certificate-key.pem

# Build the containers
install: init_ssl start init_database stop

set_permissions:
	@docker compose exec php chown -R www-data:www-data /srv/var

# Start the containers
start: start_containers set_permissions

start_containers: compose.yaml
	@docker compose up -d --remove-orphans

# Stop the containers
stop:
	@docker compose stop

# Restart the containers
restart: stop start

vendor: composer.lock
	@docker compose exec php composer install

##
## Tools
##

# Run a console command
# Example: `make composer run="outdated -D"`
composer:
	@docker compose exec php composer $(run)

# Run a console command
# Example: `make console run="cache:clear"`
console:
	@docker compose exec php bin/console $(run)

# Run a PHP script
# Example: `make php run="vendor/bin/phpunit"`
php:
	@docker compose exec php php $(run)

# Run all QA tools
qa:
	@docker compose exec php composer qa
