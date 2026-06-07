#!/usr/bin/env sh
set -eu

: "${PORT:=10000}"
: "${NGINX_CLIENT_MAX_BODY_SIZE:=25m}"
: "${CACHE_DRIVER:=file}"
: "${SESSION_DRIVER:=file}"
: "${QUEUE_CONNECTION:=sync}"
: "${RUN_DATABASE_SETUP:=true}"

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

export PORT NGINX_CLIENT_MAX_BODY_SIZE CACHE_DRIVER SESSION_DRIVER QUEUE_CONNECTION APP_KEY RUN_DATABASE_SETUP

envsubst '${PORT} ${NGINX_CLIENT_MAX_BODY_SIZE}' \
    < /etc/nginx/templates/default.conf.template \
    > /etc/nginx/conf.d/default.conf

nginx -t

mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

if [ "$RUN_DATABASE_SETUP" = "true" ]; then
    php artisan migrate --force || echo "Database migration failed; continuing startup."
    php artisan db:seed --force || echo "Database seeding failed; continuing startup."
fi

(
    php artisan storage:link || true
    php artisan optimize:clear || true
    php artisan config:cache || true
    php artisan route:cache || echo "Route cache skipped; check for Closure routes."
    php artisan view:cache || true
) &

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
