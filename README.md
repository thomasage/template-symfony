# Opinionated template to bootstrap a Symfony project

## Requirements

* Linux, MacOS or WSL2
* Docker Desktop
* `git`
* `mkcert`

## Usage

### First time only

* Clone the repository
* Optional: change domain name "acme.localhost" in `Makefile`
* Run `make install` to create the certificate and build the containers

### To start/stop the project

* Run `make start` to start the containers
* Run `make stop` to stop the containers
* Run `make` to get help
