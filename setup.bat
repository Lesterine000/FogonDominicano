@echo off
setlocal EnableExtensions EnableDelayedExpansion

cd /d "%~dp0"

echo [INFO] Preparando El Fogon Dominicano para Windows...

call :detect_compose || exit /b 1
call :check_docker || exit /b 1
call :prepare_env || exit /b 1

call :run %COMPOSE_CMD% up -d --build || exit /b 1
call :wait_for_app || exit /b 1
call :wait_for_mysql || exit /b 1

call :run docker exec fogon-app composer install --no-interaction --prefer-dist || exit /b 1
call :run docker exec fogon-app npm install || exit /b 1
call :ensure_app_key || exit /b 1
call :run docker exec fogon-app php artisan optimize:clear || exit /b 1
call :run docker exec fogon-app php artisan migrate --seed --force || exit /b 1
call :run docker exec fogon-app php artisan storage:link --force || exit /b 1
call :run docker exec fogon-app npm run build || exit /b 1

echo.
echo [OK] Instalacion completada.
echo [INFO] Abre http://localhost:8000
echo [INFO] Para detener los contenedores: %COMPOSE_CMD% down
pause
exit /b 0

:detect_compose
docker compose version >nul 2>&1
if not errorlevel 1 (
    set "COMPOSE_CMD=docker compose"
    goto :eof
)

docker-compose version >nul 2>&1
if not errorlevel 1 (
    set "COMPOSE_CMD=docker-compose"
    goto :eof
)

echo [ERROR] Docker Compose no esta disponible.
echo [ERROR] Instala Docker Desktop y vuelve a intentarlo.
exit /b 1

:check_docker
docker info >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker no esta corriendo o no esta instalado.
    echo [ERROR] Abre Docker Desktop y vuelve a lanzar este script.
    exit /b 1
)
goto :eof

:prepare_env
if exist html\.env (
    echo [INFO] Usando html\.env existente.
    echo [INFO] Si viene de otro entorno, revisa DB_CONNECTION y DB_HOST antes de continuar.
    goto :eof
)

if exist html\.env.docker.example (
    copy /Y html\.env.docker.example html\.env >nul
    echo [OK] html\.env creado desde .env.docker.example
    goto :eof
)

copy /Y html\.env.example html\.env >nul
echo [OK] html\.env creado desde .env.example
goto :eof

:wait_for_app
set /a APP_ATTEMPTS=0

:wait_for_app_loop
set /a APP_ATTEMPTS+=1
docker exec fogon-app php -v >nul 2>&1
if not errorlevel 1 (
    echo [OK] Contenedor PHP listo.
    goto :eof
)

if !APP_ATTEMPTS! GEQ 30 (
    echo [ERROR] El contenedor fogon-app no estuvo listo a tiempo.
    exit /b 1
)

echo [INFO] Esperando al contenedor PHP...
timeout /t 2 /nobreak >nul
goto wait_for_app_loop

:wait_for_mysql
set /a DB_ATTEMPTS=0

:wait_for_mysql_loop
set /a DB_ATTEMPTS+=1
docker exec fogon-db mysqladmin ping -h127.0.0.1 -uroot -proot --silent >nul 2>&1
if not errorlevel 1 (
    echo [OK] MySQL listo.
    goto :eof
)

if !DB_ATTEMPTS! GEQ 45 (
    echo [ERROR] MySQL no respondio a tiempo.
    exit /b 1
)

echo [INFO] Esperando a MySQL...
timeout /t 2 /nobreak >nul
goto wait_for_mysql_loop

:ensure_app_key
findstr /R /C:"^APP_KEY=." html\.env >nul 2>&1
if not errorlevel 1 (
    echo [INFO] APP_KEY ya configurada, se mantiene la actual.
    goto :eof
)

call :run docker exec fogon-app php artisan key:generate --force || exit /b 1
goto :eof

:run
echo.
echo [STEP] %*
%*
if errorlevel 1 (
    echo [ERROR] El comando fallo: %*
    exit /b 1
)
goto :eof
