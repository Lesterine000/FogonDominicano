# Despliegue

## 1. Preparar entorno

1. Copia `.env.production.example` a `.env`.
2. Rellena `APP_KEY`, `APP_URL`, base de datos y credenciales SMTP reales.
3. Decide el envio de correos de reserva:
   - `RESTAURANT_RESERVATION_MAIL_DELIVERY=sync`: opcion segura si no hay worker.
   - `RESTAURANT_RESERVATION_MAIL_DELIVERY=queue`: requiere worker de cola activo.

## 2. Publicar la app

Ejecuta estos comandos dentro de `html/`:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
npm install
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 3. Cola de correos

Si dejas `RESTAURANT_RESERVATION_MAIL_DELIVERY=queue`, levanta un worker:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Si no vas a ejecutar worker, usa `sync` para que las confirmaciones salgan en la misma peticion.

## 4. Checklist final

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_TIMEZONE=Europe/Madrid` (o tu zona horaria real)
- `APP_URL` con dominio real y HTTPS
- imagen de portada y foto de la chef presentes en `public/images` o `storage`
- `php artisan test --compact`
- reserva real probada con correo
- acceso admin validado con `admin` o los correos autorizados
