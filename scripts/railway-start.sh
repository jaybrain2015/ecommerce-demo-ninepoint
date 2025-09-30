#!/usr/bin/env bash
set -euo pipefail

# Ensure required env vars
: "${PORT:=8080}"
: "${APP_ENV:=production}"
: "${APP_DEBUG:=false}"


if [ -z "${APP_KEY:-}" ] || [ "${APP_KEY}" = "" ]; then
  echo "[railway-start] APP_KEY missing; generating one..."
  php artisan key:generate --force
fi


php artisan optimize:clear || true


php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true


if [ -n "${DB_CONNECTION:-}" ]; then
  echo "[railway-start] Running migrations..."
  php artisan migrate --force || true
fi


echo "[railway-start] APP_ENV=${APP_ENV} APP_DEBUG=${APP_DEBUG} DB_CONNECTION=${DB_CONNECTION:-unset} SESSION_DRIVER=${SESSION_DRIVER:-unset}"


php -S 0.0.0.0:"$PORT" -t public
