.DEFAULT_GOAL:=help
.DOCKER_COMPOSE := docker-compose -f docker-compose.yml
.DOCKER_RUN_PHP := $(.DOCKER_COMPOSE) run --rm php-cli
.RUN := $(.DOCKER_RUN_PHP)

.DOCKER_COMPOSE_TEST := docker-compose -f docker-compose.yml -f docker-compose-test.yml
.DOCKER_RUN_PHP_TEST := $(.DOCKER_COMPOSE_TEST) run -e SYMFONY_DEPRECATIONS_HELPER=$(SYMFONY_DEPRECATIONS_HELPER) --rm php-cli
.RUN_TEST := $(.DOCKER_RUN_PHP_TEST)

define banner
	@echo "#########################################################"
	@echo ""
	@echo "You can now go to http://localhost.elewant.com"
	@echo ""
	@echo "#########################################################"
endef

.PHONY: setup destroy ci test qa shell
setup: dependencies cache-clear migrate eventstore grunt up ## Setup the Project
destroy: down-with-volumes ## Destroy the project
ci: qa test ## Run all actions required to pass the build
test: clear-cache-test phpspec phpunit ## Run the test suite
qa: php-lint phpstan php-cs ## Run the quality assurance suite
shell: shell-sh ## Start a shell in docker container

# -- UTILITY -- #
.PHONY: up down down-with-volumes shell-sh
up: ## Start all containers
	$(.DOCKER_COMPOSE) up -d --build
	$(call banner)

down: ## Stop all containers
	$(.DOCKER_COMPOSE) down

down-with-volumes:
	$(.DOCKER_COMPOSE) down --remove-orphans --volumes

shell-sh:
	$(.DOCKER_RUN_PHP) sh

# -- SETUP -- #
.PHONY: dependencies composer-install npm-install cache-clear migrate eventstore grunt
dependencies: composer-install npm-install

composer-install:
	$(.RUN) composer install --no-interaction --no-suggest --no-scripts --ansi

npm-install: node_modules

node_modules: package.json
	docker run --rm -ti -v $(shell pwd):/src:rw mkenney/npm:node-6.9-debian /usr/local/bin/npm install

cache-clear:
	$(.RUN) bin/console cache:clear --no-interaction

migrate:
	$(.RUN) bin/console doctrine:migrations:migrate --no-interaction

eventstore:
	$(.RUN) bin/console event-store:event-stream:create --no-interaction

grunt:
	docker run --rm -ti -v $(shell pwd):/src:rw mkenney/npm:node-6.9-debian /usr/local/bin/grunt

# -- TEST -- #
.PHONY: clear-cache-test phpspec phpunit
clear-cache-test:
	$(.RUN_TEST) bin/console cache:clear --no-interaction --env=test

phpspec: ## Test: Run PHPspec
	$(.RUN_TEST) vendor/bin/phpspec run --no-interaction

phpunit: ## Test: Run PHPUnit
	$(.RUN_TEST) vendor/bin/phpunit

# -- QA -- #
.PHONY: php-lint phpstan php-cs
php-lint: ## QA: Run php the linter
	$(.RUN_TEST) vendor/bin/parallel-lint --colors bin config public specs src tests

phpstan: ## QA: Run phpstan
	$(.RUN_TEST) vendor/bin/phpstan analyse --configuration phpstan.neon --level max --no-progress src

php-cs: ## QA: Run php-cs
	$(.RUN_TEST) vendor/bin/phpcs


# Based on https://suva.sh/posts/well-documented-makefiles/
help:  ## Display this help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)
