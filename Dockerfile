FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libzip-dev \
    libonig-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mysqli \
        gd \
        zip \
        mbstring \
        exif \
        opcache

# Enable Apache modules
RUN a2enmod rewrite headers

# Copy Apache config
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy PHP config
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Set working directory
WORKDIR /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
