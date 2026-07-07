DOCKER_COMPOSE = docker compose
PHP            = $(DOCKER_COMPOSE) exec php-fpm
NODE           = $(DOCKER_COMPOSE) exec node

.PHONY: build build-external start up down sw ps \
        in in-web in-db in-node watch \
        quality quality-front quality-front-fix clean \
        test test-front test-coverage

## Installation

build:
	$(DOCKER_COMPOSE) build

build-external:
	$(DOCKER_COMPOSE) build --network=host

start: build up
	$(PHP) composer install
	$(NODE) npm install
	$(PHP) bin/console doctrine:database:drop --force --if-exists
	$(PHP) bin/console doctrine:database:create
	$(PHP) bin/console doctrine:migrations:migrate --no-interaction
	$(PHP) bin/console doctrine:fixtures:load --no-interaction
	$(NODE) npm run build

## Cycle conteneurs

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

sw:
	$(DOCKER_COMPOSE) restart

ps:
	$(DOCKER_COMPOSE) ps

## Entrer dans les conteneurs

in:
	$(PHP) sh

in-web:
	$(DOCKER_COMPOSE) exec web sh

in-db:
	$(DOCKER_COMPOSE) exec db psql -U postgres -d dev

in-node:
	$(NODE) sh

## Frontend

watch:
	$(NODE) npm run watch

## Qualite

quality:
	$(PHP) vendor/bin/ecs check --fix
	$(PHP) vendor/bin/phpstan analyse

quality-front:
	$(NODE) npm run lint:script
	$(NODE) npm run lint:scss
	$(NODE) npm run typecheck
	$(NODE) npm run prettier

quality-front-fix:
	$(NODE) npm run lint:script:fix
	$(NODE) npm run lint:scss:fix
	$(NODE) npm run prettier:fix

clean:
	$(DOCKER_COMPOSE) down -v
	$(PHP) rm -rf var/cache

## Tests

test:
	$(PHP) vendor/bin/phpunit

test-front:
	$(NODE) npm run test:script

test-coverage:
	$(PHP) vendor/bin/phpunit --coverage-html var/coverage
