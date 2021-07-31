#!/bin/bash

RABBIT = symfony-api-platform-rabbitmq
UID = $(shell id -u)

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

run: ## Start the containers
	U_ID=${UID} docker-compose up -d

build:
	U_ID=${UID} docker-compose build

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

kill:
	U_ID=${UID} docker-compose kill

down: ## Stop the containers
	U_ID=${UID} docker-compose down

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

ssh: ## ssh's into the container
	U_ID=${UID} docker exec -it --user ${UID} ${RABBIT} bash
