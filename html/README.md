# Fogon Dominicano

Aplicacion Laravel para gestionar el menu del dia, reservas y pedidos personalizados de El Fogon Dominicano.

## Requisitos

- Docker Desktop en Windows o Docker Engine en Linux
- Docker Compose (`docker compose` o `docker-compose`)

## Arranque rapido

Desde la raiz del proyecto:

```bash
setup.bat
```

En Linux:

```bash
chmod +x setup.sh
./setup.sh
```

Estos scripts:

- crean `html/.env` desde `html/.env.docker.example` si no existe
- levantan Docker con build limpio
- esperan a PHP y MySQL
- instalan dependencias de PHP y Node dentro del contenedor
- generan la clave de Laravel
- ejecutan migraciones y seeders
- crean el enlace `storage`
- compilan el frontend

## Desarrollo local

Dentro de `html/`:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
npm install
npm run build
php artisan serve
```

## Acceso administrativo

- Usuario local: configurado por `ADMIN_USERNAME`, `ADMIN_EMAIL` y `ADMIN_PASSWORD`
- Correos Google autorizados: `ADMIN_ALLOWED_EMAILS`
- Login Blade: `resources/views/auth/login.blade.php`

## Personalizacion de la home

Variables utiles:

- `RESTAURANT_HOME_HERO_IMAGE`
- `RESTAURANT_HOME_HERO_FOCUS`
- `RESTAURANT_HOME_CHEF_IMAGE`
- `RESTAURANT_HOME_CHEF_FOCUS`
- `RESTAURANT_CUSTOM_ORDER_URL`
- `RESTAURANT_RESERVATION_MAIL_DELIVERY`

## Produccion

La guia de despliegue y prepublicacion esta en [DEPLOYMENT.md](DEPLOYMENT.md).
