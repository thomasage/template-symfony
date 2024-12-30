# Opinionated template to bootstrap a Symfony application

## Requirements

* Linux, MacOS or WSL2
* `make`
* `mkcert`
* Docker Desktop

## Installation

* Clone the repository
* Run `make install`

## Start the project

* Run `make start`
* Open [https://acme.localhost](https://acme.localhost) in your browser
* Run `make` for more commands

## Customization

* Configure the Docker services prefix:
    * `compose.yaml` (default: `acme`)
* Configure the database name, username and password:
    * `compose.yaml` (default: `acme`)
    * `docker/mysql/docker-entrypoint-initdb.d/create_database_test.sql` (default: `acme`)
* Configure the domain name:
    * `Makefile` (default: `acme.localhost`)

## Docker services

* HTTP server: [Caddy](https://caddyserver.com/) (port: 443)
* [PHP-FPM](https://www.php.net/) (version: 8.3) with [Symfony](https://symfony.com/) (version: 7.2)
* Database: [MySQL](https://www.mysql.com/) (8.0.32)
* Messages queue: [Redis](https://redis.io/)
* Messages worker
