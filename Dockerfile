# Usar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Habilitar el módulo rewrite de Apache para URLs amigables
RUN a2enmod rewrite

# Instalar dependencias del sistema, las extensiones de PHP y limpiar.
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
