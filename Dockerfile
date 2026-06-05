FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN cp .env.example .env || true
RUN php artisan key:generate || true

RUN chown -R www-data:www-data storage bootstrap/cache

RUN a2enmod rewrite

COPY ./.render/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

CMD php artisan migrate --force || true && \
    php -S 0.0.0.0:$PORT -t public