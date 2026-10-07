COMPOSE = docker compose -f docker-compose.yml -f docker-compose.dev.yml

# Host port for the app (override: make dev HTTP_PORT=8090)
HTTP_PORT ?= 8080
export HTTP_PORT

.PHONY: help setup dev down restart logs shell test lint phpcs build seed reset

help:
	@echo "Zender development environment"
	@echo ""
	@echo "  make setup   Install composer + npm dependencies (host)"
	@echo "  make dev     Build and start the dev stack (app + db, auto-seeded)"
	@echo "  make test    Run PHPUnit inside the app container"
	@echo "  make lint    PHP syntax lint + ESLint"
	@echo "  make phpcs   Coding standard check (new code)"
	@echo "  make build   Compile theme SCSS"
	@echo "  make seed    Re-import install.sql into the dev database"
	@echo "  make shell   Open a shell in the app container"
	@echo "  make logs    Follow app logs"
	@echo "  make down    Stop the dev stack"
	@echo "  make reset   Stop the dev stack and delete volumes (re-seeds on next start)"

setup:
	./tools/setup.sh

dev:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

restart:
	$(COMPOSE) restart

logs:
	$(COMPOSE) logs -f app

shell:
	$(COMPOSE) exec app bash

test:
	$(COMPOSE) exec -T app sh -c 'XDEBUG_MODE=off composer test'

lint:
	composer lint
	npm run lint

phpcs:
	composer phpcs

build:
	npm run build

seed:
	./tools/seed-db.sh
	./tools/seed-db.sh zender_test

reset:
	$(COMPOSE) down -v
