FROM php:8.1-fpm

# Instalar extensiones necesarias, git, unzip, etc.
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql zip mbstring xml

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos de la aplicación
WORKDIR /var/www/html
COPY . .

# Instalar dependencias PHP con composer
RUN composer install --no-dev --optimize-autoloader

# Permisos (ajusta según tu entorno)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto (si usas PHP-FPM con otro contenedor o directo)
EXPOSE 9000

# Comando para esperar la base de datos y correr migraciones y seeders antes de iniciar PHP-FPM
CMD bash -c "\
  until nc -z -v -w30 mysql 3306; do \
    echo 'Esperando a la base de datos...'; \
    sleep 3; \
  done; \
  echo 'Ejecutando migraciones...'; \
  php artisan migrate --force; \
  echo 'Ejecutando seeders...'; \
  php artisan db:seed --force; \
  echo 'Iniciando PHP-FPM...'; \
  php-fpm"
