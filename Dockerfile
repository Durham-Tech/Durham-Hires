FROM php:7.4-fpm-alpine
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

WORKDIR /app

COPY . /app

RUN composer install -n

RUN php artisan key:generate

RUN chmod +x start.sh
ENTRYPOINT ./start.sh
