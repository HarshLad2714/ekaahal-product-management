# Ekahal Product Management

A production-oriented **Laravel 12** application for managing products with **Laravel Sanctum** authentication, role-based access control (RBAC), optimized search, service-layer architecture, and a modern admin panel.

## Features

| Area | Details |
|------|---------|
| **Authentication** | [Laravel Sanctum](https://laravel.com/docs/sanctum) — web session (stateful) + API Bearer tokens |
| **RBAC** | Custom roles: `Admin` (all products) vs `Standard User` (own products only) via `UserRole` enum, policies & middleware |
| **Product CRUD** | Title, rich-text description (**Trix** editor + **HTML Purifier**), price, date available |
| **Soft delete** | Products are moved to trash (`deleted_at`), not permanently removed |
| **Search** | Keyword filter; MySQL `FULLTEXT` index (3+ chars) or indexed `LIKE` fallback on SQLite |
| **Admin UI** | Responsive panel, **Font Awesome** action icons, Tailwind CSS (Vite or CDN fallback) |
| **Architecture** | Repository + Service layers, Form Requests, thin controllers, `ProductPolicy` |
| **Security** | Eloquent (SQL injection safe), XSS protection (Purifier + Blade escaping), CSRF on forms |
| **Deployment** | `deploy.sh`, `composer setup`, `Makefile`, Docker Compose |

## Packages used

| Package | Purpose |
|---------|---------|
| `laravel/framework` ^12 | Core framework |
| `laravel/sanctum` ^4 | Authentication (web + API tokens) |
| `mews/purifier` ^3 | Sanitize rich-text HTML (XSS) |

> RBAC is **custom** (enum + DB column + policies), not Spatie Permission.

## Default accounts (after seeding)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin.ekahal@gmail.com | Admin@123 |
| Standard User | manav.ekahal@gmail.com | Manav@123 |

Sample products are seeded for both users.

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 20+ & npm (recommended for production assets; CDN fallback works without npm)
- SQLite (default in `.env.example`) or **MySQL 8+** (recommended for full-text search)

---

## Option 1 — Local setup (Composer)

### Quick start

```bash
cd ekaahal-product-management
bash deploy.sh
```

**Windows:** use Git Bash / WSL, or run the manual steps below in PowerShell.

`deploy.sh` will:

1. Copy `.env.example` → `.env` if missing  
2. Run `composer install`  
3. Generate `APP_KEY`  
4. Run migrations and seeders  
5. Build frontend assets with npm (skipped if npm is missing)  
6. Cache config, routes, and views  

**Alternative (Composer script):**

```bash
composer setup
php artisan serve
```

### Manual steps

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm ci && npm run build
php artisan serve
```

### Environment (important)

```env
APP_NAME="Ekahal Products"
APP_URL=http://127.0.0.1:8000

# Sanctum — required for web admin session auth
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,127.0.0.1:8000
```

### Access

| Page | URL |
|------|-----|
| Login | http://127.0.0.1:8000/login |
| Dashboard | http://127.0.0.1:8000/admin |
| Products | http://127.0.0.1:8000/admin/products |

### Vite manifest error

If you see `Vite manifest not found`:

1. Install [Node.js LTS](https://nodejs.org/) (includes npm)  
2. Run `npm ci && npm run build`  

Until then, the app automatically uses a **Tailwind CDN fallback** (see `resources/views/partials/assets.blade.php`).

### Makefile shortcuts

```bash
make setup      # bash deploy.sh
make migrate    # php artisan migrate --force
make seed       # php artisan db:seed --force
make test       # php artisan test
make fresh      # migrate:fresh --seed
```

### MySQL (local, optional)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekahal_products
DB_USERNAME=root
DB_PASSWORD=your_password
```

```bash
php artisan migrate --seed
```

Full-text search on `title` and `description` is created automatically on MySQL.

---

## Option 2 — Docker setup

### Quick start

```bash
bash docker-setup.sh
# or
make docker-setup
```

This will:

1. Create `.env` from `.env.docker.example`  
2. Start **MySQL**, **PHP-FPM**, and **Nginx**  
3. Run Composer, migrations, and seeders inside the app container  
4. Build frontend assets (if Node is available)  

### Access

| Service | URL / Port |
|---------|------------|
| App | http://localhost:8080/login |
| MySQL | `localhost:3307` — database: `ekahal_products`, user: `ekahal`, password: `secret` |

### Day-to-day

```bash
make docker-up
make docker-down
docker compose logs -f nginx
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

---

## Web admin panel

- Login with seeded admin or user credentials  
- **Admin:** view/edit/delete all products  
- **Standard user:** only own products (policy-enforced)  
- **Delete** = soft delete (record kept with `deleted_at`)  
- **Actions:** Font Awesome edit / trash icons on product list  
- Rich description via Trix editor on create/edit forms  

---

## API authentication (Sanctum)

### Login

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"admin.ekahal@gmail.com\",\"password\":\"Admin@123\"}"
```

Response includes `token`, `token_type` (`Bearer`), and `user` (with `role`).

### Authenticated requests

```bash
curl http://127.0.0.1:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### API routes

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/login` | Guest | Issue Bearer token |
| POST | `/api/logout` | Sanctum | Revoke current token |
| GET | `/api/user` | Sanctum | Current user + role |
| GET | `/api/products` | Sanctum | List products (role-scoped) |
| POST | `/api/products` | Sanctum | Create product |
| GET | `/api/products/{id}` | Sanctum | Show product |
| PUT/PATCH | `/api/products/{id}` | Sanctum | Update (policy) |
| DELETE | `/api/products/{id}` | Sanctum | Soft delete (policy) |

Query `?search=keyword` on `GET /api/products` for filtering.

**Web routes** use `auth:sanctum` with stateful cookies; **API routes** use Bearer tokens only (no session).

---

## Project structure

```
app/
├── Enums/UserRole.php
├── Http/
│   ├── Controllers/Admin/      # Web admin (products, dashboard)
│   ├── Controllers/Api/        # Sanctum API (auth, products)
│   ├── Controllers/Auth/       # Web login/logout
│   ├── Middleware/
│   │   ├── EnsureUserIsAdmin.php
│   │   └── EnsureUserHasRole.php   # e.g. middleware('role:admin')
│   ├── Requests/               # Server-side validation
│   └── Resources/              # API JSON resources
├── Models/Product.php          # SoftDeletes
├── Policies/ProductPolicy.php
├── Repositories/               # Repository pattern
│   ├── Contracts/
│   └── Eloquent/
└── Services/                   # Business logic
    ├── Auth/AuthService.php
    └── Product/ProductService.php

routes/
├── web.php                     # Admin panel + login
└── api.php                     # Sanctum API

database/seeders/
├── UserSeeder.php              # Admin + Manav accounts
└── ProductSeeder.php           # Sample products
```

---

## Security

| Threat | Mitigation |
|--------|------------|
| SQL injection | Eloquent / parameterized queries |
| XSS | `mews/purifier` on save; Blade `{{ }}` for plain text |
| CSRF | `@csrf` on all web forms |
| Auth | Sanctum tokens + session; passwords hashed (bcrypt) |

---

## Testing

```bash
php artisan test
# or
make test
```

Covers web login, Sanctum API auth, product CRUD, RBAC, search, and soft delete.

---

## Troubleshooting

| Issue | Fix |
|-------|-----|
| Vite manifest missing | `npm ci && npm run build` or use CDN fallback (automatic) |
| 419 / CSRF | Clear browser cookies; `php artisan optimize:clear` |
| Sanctum 401 on web | Check `SANCTUM_STATEFUL_DOMAINS` matches your host/port |
| Permission denied on product | Standard users can only edit/delete their own products |
| Docker port in use | Change ports in `docker-compose.yml` |

```bash
php artisan optimize:clear
php artisan config:clear
php artisan migrate --seed
```

---

## Assignment checklist (reference)

- [x] Secure login (Sanctum)  
- [x] RBAC — Admin vs Standard User  
- [x] Product CRUD with validation  
- [x] Rich text description (Trix + Purifier)  
- [x] Search with DB indexing / full-text  
- [x] Soft delete  
- [x] Repository + Service architecture  
- [x] Deployment automation (`deploy.sh`, Docker, Makefile)  
- [x] Dual setup: Composer + Docker  
- [x] Seeded admin & user accounts  

## License

MIT
