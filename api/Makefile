#!/bin/bash

DOCKER_BE = symfony-api-platform-be
OS := $(shell uname)

ifeq ($(OS),Darwin)
	UID = $(shell id -u)
else ifeq ($(OS),Linux)
	UID = $(shell id -u)
else
	UID = 1000
endif

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

run: ## Start the containers
	docker network create symfony-api-platform-network || true
	U_ID=${UID} docker-compose up -d

run-ro: ## Start the containers && remove orphans containers
	docker network create symfony-api-platform-network || true
    U_ID=${UID} docker-compose up -d --remove-orphans

build:
	U_ID=${UID} docker-compose build

down: ## Stop the containers
	U_ID=${UID} docker-compose down --remove-orphans

kill:
	U_ID=${UID} docker-compose kill

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

prepare: ## Runs backend commands
	$(MAKE) composer-install

containers-list:
	docker ps

# Backend commands
composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} composer install --no-scripts --no-interaction --optimize-autoloader

migrations: ## Runs the migrations
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bin/console doctrine:migrations:migrate -n

cache-clear:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bin/console cache:clear

cache-clear-test:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bin/console cache:clear --env=test

be-logs: ## Tails the Symfony dev log
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} tail -f var/log/dev.log

# End backend commands
ssh-be: ## ssh's into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

code-style: ## Runs php-cs to fix code styling following Symfony rules
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony

generate-ssh-keys: ## Generates SSH keys for the JWT library
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} mkdir -p config/jwt
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} openssl genrsa -passout pass:767b453a97ac019714eb7ccbce781d16 -out config/jwt/private.pem -aes256 4096
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} openssl rsa -pubout -passin pass:767b453a97ac019714eb7ccbce781d16 -in config/jwt/private.pem -out config/jwt/public.pem
