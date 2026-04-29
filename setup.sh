#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

echo "[INFO] Preparando El Fogon Dominicano para Linux..."

detect_compose() {
    if docker compose version >/dev/null 2>&1; then
        COMPOSE_CMD=(docker compose)
        return 0
    fi

    if command -v docker-compose >/dev/null 2>&1; then
        COMPOSE_CMD=(docker-compose)
        return 0
    fi

    echo "[ERROR] Docker Compose no esta disponible."
    echo "[ERROR] Instala Docker y Docker Compose antes de continuar."
    exit 1
}

run_step() {
    echo
    echo "[STEP] $*"
    "$@"
}

prepare_env() {
    if [[ -f html/.env ]]; then
        echo "[INFO] Usando html/.env existente."
        echo "[INFO] Si viene de otro entorno, revisa DB_CONNECTION y DB_HOST antes de continuar."
        return 0
    fi

    if [[ -f html/.env.docker.example ]]; then
        cp html/.env.docker.example html/.env
        echo "[OK] html/.env creado desde .env.docker.example"
        return 0
    fi

    cp html/.env.example html/.env
    echo "[OK] html/.env creado desde .env.example"
}

wait_for_app() {
    for _ in $(seq 1 30); do
        if docker exec fogon-app php -v >/dev/null 2>&1; then
            echo "[OK] Contenedor PHP listo."
            return 0
        fi

        echo "[INFO] Esperando al contenedor PHP..."
        sleep 2
    done

    echo "[ERROR] El contenedor fogon-app no estuvo listo a tiempo."
    exit 1
}

wait_for_mysql() {
    for _ in $(seq 1 45); do
        if docker exec fogon-db mysqladmin ping -h127.0.0.1 -uroot -proot --silent >/dev/null 2>&1; then
            echo "[OK] MySQL listo."
            return 0
        fi

        echo "[INFO] Esperando a MySQL..."
        sleep 2
    done

    echo "[ERROR] MySQL no respondio a tiempo."
    exit 1
}

ensure_app_key() {
    if grep -Eq '^APP_KEY=.+$' html/.env; then
        echo "[INFO] APP_KEY ya configurada, se mantiene la actual."
        return 0
    fi

    run_step docker exec fogon-app php artisan key:generate --force
}

if ! docker info >/dev/null 2>&1; then
    echo "[ERROR] Docker no esta corriendo o no esta instalado."
    exit 1
fi

detect_compose
prepare_env

run_step "${COMPOSE_CMD[@]}" up -d --build
wait_for_app
wait_for_mysql

run_step docker exec fogon-app composer install --no-interaction --prefer-dist
run_step docker exec fogon-app npm install
ensure_app_key
run_step docker exec fogon-app php artisan optimize:clear
run_step docker exec fogon-app php artisan migrate --seed --force
run_step docker exec fogon-app php artisan storage:link --force
run_step docker exec fogon-app npm run build

echo
echo "[OK] Instalacion completada."
echo "[INFO] Abre http://localhost:8000"
echo "[INFO] Para detener los contenedores: ${COMPOSE_CMD[*]} down"
