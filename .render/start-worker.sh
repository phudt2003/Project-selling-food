#!/usr/bin/env sh
set -eu

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

: "${CACHE_DRIVER:=file}"
: "${SESSION_DRIVER:=file}"
: "${QUEUE_CONNECTION:=sync}"

if [ -z "${REDIS_URL:-}" ]; then
    if [ "$CACHE_DRIVER" = "redis" ]; then
        CACHE_DRIVER=file
        echo "REDIS_URL is not set; using CACHE_DRIVER=file."
    fi

    if [ "$SESSION_DRIVER" = "redis" ]; then
        SESSION_DRIVER=file
        echo "REDIS_URL is not set; using SESSION_DRIVER=file."
    fi

    if [ "$QUEUE_CONNECTION" = "redis" ]; then
        QUEUE_CONNECTION=sync
        echo "REDIS_URL is not set; using QUEUE_CONNECTION=sync."
    fi
fi

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
