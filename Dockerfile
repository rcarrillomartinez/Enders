FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/enders

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    pdo_mysql \
    pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create app user
RUN groupadd -g 1000 appuser && useradd -r -u 1000 -g appuser appuser

# Copy existing application directory contents
COPY . /var/www/enders

# Copy existing application directory permissions
RUN chown -R appuser:appuser /var/www/enders

# Change current user to appuser
USER appuser

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]
