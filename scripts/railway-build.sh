#!/usr/bin/env bash
set -euo pipefail


composer install --no-interaction --prefer-dist --no-dev -o


if [ -f package.json ]; then
  if command -v npm >/dev/null 2>&1; then
    npm ci
    npm run build
  fi
fi


php artisan storage:link || true

