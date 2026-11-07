#!/usr/bin/env bash
set -e

if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY belum di-set di environment." >&2
  exit 1
fi

php artisan storage:link || true
php artisan migrate --force
php artisan db:seed --class=DeploySeeder --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
