# Fogon Dominicano (El Fogon Dominicano)

Laravel app to sell a single **Menu of the Day** dish (one dish per day), with limited servings, customer reservations, admin order management, and revenue statistics.

Spanish version: [`README.es.md`](README.es.md)

This repository is structured as:
- `html/`: the Laravel application
- `docker-compose.yml`, `Dockerfile`: local Docker environment
- `Docker/nginx/`: Nginx config for local setup
- `setup.bat`, `setup.sh`: one-command local bootstrap

## Core Workflow

1. An admin schedules **exactly one dish per service date** in `/admin` (name, price, servings, optional image).
2. Customers can only reserve **today's active dish** on `/`.
3. When servings reach 0, the home page shows **"No more servings today"**.
4. Admin manages orders in `/orders` (paid/unpaid + delivered/pending/cancelled).
5. `/statistics` shows revenue and servings sold **only for orders that are paid and delivered**.

## Features

- Public landing page with menu-of-the-day + reservation form.
- Admin panel to manage the daily menu (create/update/soft-delete dishes).
- Orders dashboard filtered by `service_date`.
- Statistics:
  - Counts **only** `is_paid = true` and `status = completed` (delivered).
  - Filters: today, month, total, or a custom date range.
- Reservation confirmation email (sync or queued delivery).
- Admin access:
  - Local seeded admin user (`ADMIN_*` env vars).
  - Google OAuth login with allow-list (`ADMIN_ALLOWED_EMAILS`).

## Local Setup (Docker)

Requirements:
- Docker Desktop (Windows/macOS) or Docker Engine (Linux)
- Docker Compose (`docker compose`)

From the repo root:

Windows:
```powershell
.\setup.bat
```

Linux/macOS:
```bash
chmod +x setup.sh
./setup.sh
```

Then open:
- `http://localhost:8000`

## Important Environment Variables

Laravel:
- `APP_ENV`, `APP_DEBUG`, `APP_URL`, `APP_KEY`, `APP_TIMEZONE`
- `DB_*`
- `MAIL_*`

Admin:
- `ADMIN_USERNAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`
- `ADMIN_ALLOWED_EMAILS` (comma-separated)

Restaurant:
- `RESTAURANT_PICKUP_TIME_START`, `RESTAURANT_PICKUP_TIME_END`
- `RESTAURANT_RESERVATION_MAIL_DELIVERY` (`sync` or `queue`)
- `RESTAURANT_CUSTOM_ORDER_URL`
- `RESTAURANT_HOME_HERO_IMAGE`, `RESTAURANT_HOME_HERO_FOCUS`
- `RESTAURANT_HOME_CHEF_IMAGE`, `RESTAURANT_HOME_CHEF_FOCUS`
- `RESTAURANT_HOME_CHEF_NAME`, `RESTAURANT_HOME_CHEF_ROLE`

## Emails (Reservation Confirmation)

- Use `RESTAURANT_RESERVATION_MAIL_DELIVERY=sync` if you don't run a queue worker.
- If you set it to `queue`, you must run a worker in production (or emails won't go out).

## Data Integrity Notes

- Dishes use **soft deletes** so deleting a dish does not delete its orders.
- Orders store `dish_name` and `unit_price` snapshots to preserve reporting accuracy even if a dish changes later.
- Statistics aggregate by `dish_name` and filter using `service_date`.

## Tests

```bash
docker exec fogon-app php artisan test --compact
```

## Deployment Readiness

The app is functionally deployable, but before putting it on the public web you should complete a production checklist:
- Set `APP_ENV=production`, `APP_DEBUG=false`, correct `APP_URL` (HTTPS), and a real `APP_KEY`.
- Use a real database (MySQL/Postgres) and enable backups.
- Configure SMTP (`MAIL_*`) and decide `sync` vs `queue` for reservation emails.
- Run `php artisan storage:link` and ensure `storage/` is persistent and writable.
- Build assets (`npm run build`) and enable caches (`config:cache`, `route:cache`, `view:cache`).
- Add basic protection against spam (rate limiting and/or CAPTCHA) if the reservation form is public.

See the deployment guide:
- [`html/DEPLOYMENT.md`](html/DEPLOYMENT.md)
