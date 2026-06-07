#!/usr/bin/env sh
set -eu

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

: "${CACHE_DRIVER:=file}"
: "${SESSION_DRIVER:=file}"
: "${QUEUE_CONNECTION:=sync}"

if [ -z "${APP_KEY:-}" ]; then
    APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    echo "APP_KEY was not set; generated a temporary runtime key."
fi

export CACHE_DRIVER SESSION_DRIVER QUEUE_CONNECTION APP_KEY

php artisan optimize:clear
php artisan config:cache

if [ "$QUEUE_CONNECTION" = "sync" ]; then
    echo "QUEUE_CONNECTION=sync; queue worker is not required."
    exec tail -f /dev/null
fi

exec php artisan queue:work "$QUEUE_CONNECTION" --sleep=3 --tries=3 --timeout=90 --memory=256
