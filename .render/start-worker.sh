#!/usr/bin/env sh
set -eu

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan optimize:clear
php artisan config:cache

exec php artisan queue:work redis --sleep=3 --tries=3 --timeout=90 --memory=256
