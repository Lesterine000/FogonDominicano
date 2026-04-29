# Fogon Dominicano (El Fogon Dominicano)

Aplicacion Laravel para vender un **menu del dia** (un solo plato por dia), con raciones limitadas, reservas de clientes, gestion de pedidos desde admin y estadisticas de ganancias.

English version: [`README.md`](README.md)

Estructura del repositorio:
- `html/`: aplicacion Laravel
- `docker-compose.yml`, `Dockerfile`: entorno local con Docker
- `Docker/nginx/`: configuracion Nginx para el entorno local
- `setup.bat`, `setup.sh`: arranque rapido en un comando

## Flujo Principal

1. El admin programa **un menu por fecha de servicio** en `/admin` (nombre, precio, raciones, imagen opcional).
2. El cliente solo puede reservar **el menu activo de hoy** en `/`.
3. Cuando las raciones llegan a 0, la home muestra **"No mas raciones por hoy"**.
4. El admin gestiona pedidos en `/orders` (pagado/pendiente + entregado/pendiente/cancelado).
5. `/statistics` calcula ganancias y raciones vendidas **solo con pedidos cobrados y entregados**.

## Funcionalidades

- Landing publica con menu del dia + formulario de reserva.
- Panel admin para gestionar el menu diario (crear/editar/eliminar con soft delete).
- Panel de pedidos filtrado por `service_date`.
- Estadisticas:
  - Solo cuentan pedidos con `is_paid = true` y `status = completed` (entregado).
  - Filtros: hoy, este mes, historico total o rango de fechas.
- Correo de confirmacion de reserva (envio sin cola o con cola).
- Acceso admin:
  - Usuario admin local (seedeado con variables `ADMIN_*`).
  - Login con Google (lista permitida con `ADMIN_ALLOWED_EMAILS`).

## Instalacion Local (Docker)

Requisitos:
- Docker Desktop (Windows/macOS) o Docker Engine (Linux)
- Docker Compose (`docker compose`)

Desde la raiz del repo:

Windows:
```powershell
.\setup.bat
```

Linux/macOS:
```bash
chmod +x setup.sh
./setup.sh
```

Luego abre:
- `http://localhost:8000`

## Variables de Entorno Importantes

Laravel:
- `APP_ENV`, `APP_DEBUG`, `APP_URL`, `APP_KEY`, `APP_TIMEZONE`
- `DB_*`
- `MAIL_*`

Admin:
- `ADMIN_USERNAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`
- `ADMIN_ALLOWED_EMAILS` (separados por comas)

Restaurante:
- `RESTAURANT_PICKUP_TIME_START`, `RESTAURANT_PICKUP_TIME_END`
- `RESTAURANT_RESERVATION_MAIL_DELIVERY` (`sync` o `queue`)
- `RESTAURANT_CUSTOM_ORDER_URL`
- `RESTAURANT_HOME_HERO_IMAGE`, `RESTAURANT_HOME_HERO_FOCUS`
- `RESTAURANT_HOME_CHEF_IMAGE`, `RESTAURANT_HOME_CHEF_FOCUS`
- `RESTAURANT_HOME_CHEF_NAME`, `RESTAURANT_HOME_CHEF_ROLE`

## Correos (Confirmacion de Reserva)

- Usa `RESTAURANT_RESERVATION_MAIL_DELIVERY=sync` si no vas a ejecutar un worker de cola.
- Si usas `queue`, necesitas un worker activo en produccion o los correos no saldran.

## Notas de Integridad de Datos

- Los platos usan **soft deletes**, asi que eliminar un plato no borra sus pedidos.
- Los pedidos guardan `dish_name` y `unit_price` para mantener el historico incluso si el plato se modifica despues.
- Las estadisticas se agrupan por `dish_name` y se filtran por `service_date`.

## Tests

```bash
docker exec fogon-app php artisan test --compact
```

## Listo Para Produccion

La app es desplegable, pero antes de publicarla en la web conviene pasar un checklist:
- `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` real (HTTPS) y `APP_KEY` real.
- Base de datos real (MySQL/Postgres) y backups.
- SMTP real (`MAIL_*`) y decidir `sync` vs `queue` para correos.
- `php artisan storage:link` y asegurar que `storage/` es persistente y escribible.
- Compilar assets (`npm run build`) y cachear (`config:cache`, `route:cache`, `view:cache`).
- Anadir proteccion basica anti-spam (rate limiting y/o CAPTCHA) si la reserva es publica.

Guia de despliegue:
- [`html/DEPLOYMENT.md`](html/DEPLOYMENT.md)
