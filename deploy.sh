#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR"

echo "==> Ekahal Product Management — deployment setup"

if ! command -v php >/dev/null 2>&1; then
    echo "Error: PHP is required. Install PHP 8.2+ or use Docker setup."
    exit 1
fi

if ! command -v composer >/dev/null 2>&1; then
    echo "Error: Composer is required."
    exit 1
fi

if [ ! -f .env ]; then
    echo "==> Creating .env from .env.example"
    cp .env.example .env
fi

echo "==> Installing PHP dependencies"
composer install --no-interaction --prefer-dist

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    echo "==> Generating application key"
    php artisan key:generate --force
fi

echo "==> Running database migrations"
php artisan migrate --force

echo "==> Seeding database (admin & demo users)"
php artisan db:seed --force

if command -v npm >/dev/null 2>&1; then
    echo "==> Building frontend assets"
    npm ci
    npm run build
else
    echo "Warning: npm not found — skip asset build or install Node.js"
fi

echo "==> Optimizing application"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "Setup complete."
echo "  Start server: php artisan serve"
echo "  Login URL:    http://127.0.0.1:8000/login"
echo "  Admin:        admin.ekahal@gmail.com / Admin@123"
echo "  User:         manav.ekahal@gmail.com / Manav@123"
