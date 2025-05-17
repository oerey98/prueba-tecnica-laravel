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
  echo 'Esperando base de datos...'; \
  until php artisan migrate:status > /dev/null 2>&1; do \
    echo 'La base de datos aún no está lista...'; sleep 3; \
  done; \
  echo 'Ejecutando migraciones...'; \
  php artisan migrate --force; \
  echo 'Ejecutando seeders...'; \
  php artisan db:seed --force; \
  echo 'Iniciando PHP-FPM...'; \
  php-fpm"
