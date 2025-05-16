FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones PHP
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
    netcat-openbsd \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_mysql zip mbstring xml gd \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copia Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el proyecto
COPY . .

# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Da permisos a Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Expone el puerto del FPM
EXPOSE 9000

# Comando de inicio: espera a MySQL, luego migra, seed y levanta FPM
CMD bash -c "\
  until nc -z -v -w30 mysql 3306; do \
    echo 'Esperando base de datos...'; sleep 3; \
  done; \
  php artisan migrate --force; \
  php artisan db:seed --force; \
  php-fpm"
