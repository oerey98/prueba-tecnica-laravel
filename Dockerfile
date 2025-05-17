FROM php:8.2-fpm

# Establecer locale para evitar warnings
RUN apt-get update && apt-get install -y locales && locale-gen en_US.UTF-8

# Actualizar repositorios y luego instalar paquetes (dividido para mejor debugging)
RUN apt-get update
RUN apt-get install -y \
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

# Limpiar cache para imagen ligera
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar GD con soporte para freetype y jpeg
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql zip mbstring xml gd

# Copiar composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Instalar dependencias PHP sin desarrollo
RUN composer install --no-dev --optimize-autoloader

# Dar permisos a storage y cache
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

# Ejecutar php-fpm
CMD ["php-fpm"]
