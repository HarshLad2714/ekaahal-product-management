.PHONY: help setup docker-up docker-down docker-setup migrate seed test fresh

help:
	@echo "Ekahal Product Management"
	@echo "  make setup         - Local install (composer, migrate, seed, build)"
	@echo "  make docker-setup  - Docker install (compose + migrate + seed)"
	@echo "  make docker-up     - Start Docker stack"
	@echo "  make docker-down   - Stop Docker stack"
	@echo "  make migrate       - Run migrations"
	@echo "  make seed          - Run seeders"
	@echo "  make test          - Run PHPUnit"
	@echo "  make fresh         - Fresh migrate + seed"

setup:
	bash deploy.sh

docker-setup:
	bash docker-setup.sh

docker-up:
	docker compose up -d

docker-down:
	docker compose down

migrate:
	php artisan migrate --force

seed:
	php artisan db:seed --force

test:
	php artisan test

fresh:
	php artisan migrate:fresh --seed --force
