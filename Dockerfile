# --- STAGE 1: Build stage with dependencies ---
FROM php:8.3-fpm as builder


RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev


RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install --no-interaction --no-scripts --optimize-autoloader

COPY . .

# --- STAGE 2: Final production image ---
FROM php:8.3-fpm-alpine

WORKDIR /var/www

COPY --from=builder /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zlib-dev \
    netcat-openbsd \
    sqlite-dev


RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite

COPY --from=builder /var/www /var/www

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

COPY docker/app/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]