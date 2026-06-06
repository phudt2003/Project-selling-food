#!/usr/bin/env sh
set -eu

: "${PORT:=10000}"
: "${NGINX_CLIENT_MAX_BODY_SIZE:=25m}"

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

(
    php artisan storage:link || true
    php artisan optimize:clear || true
    php artisan config:cache || true
    php artisan route:cache || echo "Route cache skipped; check for Closure routes."
    php artisan view:cache || true
) &

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
