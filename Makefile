LOCAL_DOCKER_EXEC =	docker exec -it php

.DEFAULT_GOAL := help

.PHONY: help
help: ## Show list of available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

up_app: ## Start containers
	docker compose up -d

up_build_app: ## Build and start containers
	docker compose up -d --build

stop_app: ## Stop containers
	docker compose down

#unit_test: TODO
#	$(LOCAL_DOCKER_EXEC) vendor/bin/phpunit
#
#code_check: TODO
#	$(LOCAL_DOCKER_EXEC) composer check

migrations_up: ## Run database migrations
	$(LOCAL_DOCKER_EXEC) bin/console doctrine:migrations:migrate

composer_install: ## Install composer dependencies
	$(LOCAL_DOCKER_EXEC) composer install

init_app: ## Full project initialization (build + install + migrations)
	make up_build_app \
	&& sleep 5 \
	&& make composer_install \
	&& sleep 5 \
	&& make migrations_up

##
phpcs: ## Check code style against PSR-12
	$(LOCAL_DOCKER_EXEC) vendor/bin/phpcs --standard=phpcs.xml

phpcbf: ## Automatically fix code style according to PSR-12
	$(LOCAL_DOCKER_EXEC) vendor/bin/phpcbf --standard=phpcs.xml