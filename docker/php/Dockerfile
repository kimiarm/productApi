FROM php:8.2-fpm
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y \ libpq-dev \ libzip-dev \ unzip \ curl \ git \ && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN pecl install mongodb \ && docker-php-ext-enable mongodb

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \ && chmod -R 775 /var/www/html/storage

RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000 CMD ["php-fpm"]