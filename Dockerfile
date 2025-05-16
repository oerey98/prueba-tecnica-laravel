FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    netcat

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql zip mbstring xml gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD bash -c "\
  until nc -z -v -w30 mysql 3306; do \
    echo 'Esperando base de datos...'; sleep 3; \
  done; \
  php artisan migrate --force; \
  php artisan db:seed --force; \
  php-fpm"
