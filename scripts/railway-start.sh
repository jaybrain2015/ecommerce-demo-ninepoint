#!/usr/bin/env bash
set -euo pipefail

# Ensure required env vars
: "${PORT:=8080}"
: "${APP_ENV:=production}"
: "${APP_DEBUG:=false}"

# Generate APP_KEY if missing
if [ -z "${APP_KEY:-}" ] || [ "${APP_KEY}" = "" ]; then
  echo "[railway-start] APP_KEY missing; generating one..."
  php artisan key:generate --force
fi

# Clear any old cached config so new env vars take effect
php artisan optimize:clear || true

# Optimize (safe on boot)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run migrations if DB is configured
if [ -n "${DB_CONNECTION:-}" ]; then
  echo "[railway-start] Running migrations..."
  php artisan migrate --force || true
fi

# Basic diagnostics
echo "[railway-start] APP_ENV=${APP_ENV} APP_DEBUG=${APP_DEBUG} DB_CONNECTION=${DB_CONNECTION:-unset} SESSION_DRIVER=${SESSION_DRIVER:-unset}"

# Start the PHP built-in server for Laravel's public directory
# Railway will provide $PORT
php -S 0.0.0.0:"$PORT" -t public
