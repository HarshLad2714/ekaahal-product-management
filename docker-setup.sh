#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR"

if ! command -v docker >/dev/null 2>&1; then
    echo "Error: Docker is required."
    exit 1
fi

echo "==> Ekahal — Docker setup"

if [ ! -f .env ]; then
    cp .env.docker.example .env
    echo "Created .env from .env.docker.example"
fi

echo "==> Starting containers"
docker compose up -d --build mysql nginx

echo "==> Waiting for MySQL..."
sleep 10

echo "==> Installing dependencies inside app container"
docker compose run --rm --user root app sh -c "
    composer install --no-interaction &&
    chown -R www-data:www-data storage bootstrap/cache &&
    php artisan key:generate --force &&
    php artisan migrate --force &&
    php artisan db:seed --force
"

if docker compose run --rm node sh -c "npm ci && npm run build" 2>/dev/null; then
    echo "==> Assets built via node profile"
else
    echo "==> Building assets on host (if Node is available)"
    command -v npm >/dev/null && npm ci && npm run build || echo "Run: npm ci && npm run build"
fi

echo ""
echo "Docker setup complete."
echo "  App URL:  http://localhost:8080/login"
echo "  MySQL:    localhost:3307 (user: ekahal / secret)"
