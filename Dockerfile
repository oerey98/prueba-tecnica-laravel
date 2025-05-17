FROM php:8.2-fpm

# Actualiza los índices y instala las dependencias necesarias sin recomendaciones extras
RUN apt-get update && apt-get install -y --no-install-recommends \
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
    netcat \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# Configura GD con soporte para jpeg y freetype
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Instala extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql zip mbstring xml gd

# Copia composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define directorio de trabajo y copia archivos
WORKDIR /var/www/html
COPY . .

# Instala dependencias de PHP con composer
RUN composer install --no-dev --optimize-autoloader

# Ajusta permisos para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Expone puerto de PHP-FPM
EXPOSE 9000

# Comando por defecto (ajústalo si usas otro entrypoint)
CMD ["php-fpm"]
