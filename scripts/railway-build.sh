#!/usr/bin/env bash
set -euo pipefail

# Build phase for Railway (Nixpacks will run this if configured as a build command)

# 1) PHP deps (no dev, optimized autoload)
composer install --no-interaction --prefer-dist --no-dev -o

# 2) Frontend assets (only if package.json exists)
if [ -f package.json ]; then
  if command -v npm >/dev/null 2>&1; then
    npm ci
    npm run build
  fi
fi

# 3) Storage link (ignore if already exists)
php artisan storage:link || true

# NOTE:
# APP_KEY is better provided as an environment variable in Railway.
# Alternatively, we also ensure it at runtime in railway-start.sh.
