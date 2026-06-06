# Deploy Laravel len Render bang Docker

Bo cau hinh nay dung PHP 8.3, Nginx, PHP-FPM, Redis/Render Key Value cho cache va queue, va Render Postgres mac dinh. Neu dung MySQL ben ngoai Render, xem muc "Doi sang MySQL".

## File da them

- `Dockerfile`: multi-stage production image, build Vite assets, cai Composer dependencies, PHP 8.3 extensions, Nginx, Supervisor va Redis extension.
- `.dockerignore`: loai bo `.env`, `vendor`, `node_modules`, cache/log khoi Docker context.
- `.render/nginx.conf.template`: Nginx virtual host, lang nghe cong `$PORT` cua Render.
- `.render/php.ini`: cau hinh PHP production va OPcache.
- `.render/supervisord.conf`: chay `php-fpm` va `nginx` trong Web Service.
- `.render/start.sh`: entrypoint web, tao Nginx config, clear/cache Laravel config-route-view.
- `.render/start-worker.sh`: entrypoint worker, chay `php artisan queue:work redis`.
- `render.yaml`: Render Blueprint tao Web Service, Worker, Render Key Value va Render Postgres.
- `.env.render.example`: mau bien moi truong production de doi chieu tren Render Dashboard.

## Deploy bang Render Blueprint

1. Push code len GitHub/GitLab.
2. Vao Render Dashboard > Blueprints > New Blueprint.
3. Chon repository co file `render.yaml`.
4. Render se tao:
   - `websitesellfood-web`: Docker Web Service.
   - `websitesellfood-worker`: Docker Background Worker cho queue.
   - `websitesellfood-redis`: Render Key Value/Redis.
   - `websitesellfood-db`: Render PostgreSQL.
5. Khi Render hoi bien `sync: false`, nhap:
   - `APP_KEY`: tao bang `php artisan key:generate --show`.
   - `APP_URL`: URL Render hoac domain rieng, vi du `https://websitesellfood-web.onrender.com`.
   - `MOMO_PARTNER_CODE`, `MOMO_ACCESS_KEY`, `MOMO_SECRET_KEY`: thong tin MoMo sandbox/production.
6. Deploy. `preDeployCommand: php artisan migrate --force` se tu dong migrate truoc khi Web Service start.

## Cau hinh thu cong tren Render Dashboard

Neu khong dung Blueprint:

1. Tao Web Service:
   - Type: Web Service.
   - Runtime: Docker.
   - Dockerfile path: `./Dockerfile`.
   - Docker context: `.`.
   - Pre-deploy command: `php artisan migrate --force`.
   - Health check path: `/`.
2. Tao Background Worker:
   - Runtime: Docker.
   - Docker command: `start-worker`.
3. Tao PostgreSQL database va Render Key Value.
4. Gan bien moi truong cho ca Web Service va Worker:

```env
APP_NAME="Website Selling Food"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
APP_URL=https://your-service.onrender.com
LOG_CHANNEL=stderr
LOG_LEVEL=error

MOMO_PARTNER_CODE=<momo-partner-code>
MOMO_ACCESS_KEY=<momo-access-key>
MOMO_SECRET_KEY=<momo-secret-key>

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

DB_CONNECTION=pgsql
DATABASE_URL=<Render Postgres Internal Database URL>
DB_HOST=<Render Postgres internal host>
DB_PORT=5432
DB_DATABASE=<database name>
DB_USERNAME=<database user>
DB_PASSWORD=<database password>

REDIS_CLIENT=phpredis
REDIS_URL=<Render Key Value internal Redis URL>
REDIS_PASSWORD=
REDIS_DB=0
REDIS_CACHE_DB=1
```

## Doi sang MySQL

Render khong tao MySQL managed database trong `render.yaml` nhu PostgreSQL. Neu dung MySQL ngoai Render, giu Web Service/Worker/Redis va thay cac bien DB:

```env
DB_CONNECTION=mysql
DATABASE_URL=
DB_HOST=<mysql-host>
DB_PORT=3306
DB_DATABASE=<mysql-database>
DB_USERNAME=<mysql-user>
DB_PASSWORD=<mysql-password>
MYSQL_ATTR_SSL_CA=
```

Docker image da cai san `pdo_mysql` va `pdo_pgsql`, nen co the doi PostgreSQL/MySQL bang bien moi truong ma khong can build lai image.

## Laravel cache va queue

Web entrypoint chay:

```sh
php artisan optimize:clear
php artisan config:cache
php artisan route:cache || echo "Route cache skipped; check for Closure routes."
php artisan view:cache
```

`route:cache` co the bi bo qua neu app con Closure route, vi Laravel khong cache duoc route dang Closure. Worker chay:

```sh
php artisan queue:work redis --sleep=3 --tries=3 --timeout=90 --memory=256
```

## Luu y

- Khong commit `.env`; Render lay cau hinh tu Environment Variables.
- Neu upload anh/san pham trong production, nen dung object storage hoac gan persistent disk cho `storage/app/public`, vi filesystem container co the mat du lieu khi redeploy.
- Repo hien tai dang khai bao `laravel/framework` `^9.19` trong `composer.json`; cau hinh Docker van dung PHP 8.3 va co the tiep tuc dung sau khi nang len Laravel 11.
