FROM php:8.4-apache

RUN apt-get update && apt-get install -y libzip-dev && docker-php-ext-install pdo_mysql zip \
    && a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . .

RUN chown -R www-data:www-data uploads system/storage

EXPOSE 80
