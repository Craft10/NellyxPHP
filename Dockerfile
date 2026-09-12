ARG PHP_VERSION=8.3
FROM php:${PHP_VERSION}-fpm-bookworm

ARG PHP_VERSION
ENV PHP_VERSION=${PHP_VERSION} \
    DEBIAN_FRONTEND=noninteractive

# --- Dependencias del sistema + extensiones PHP ---
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        git \
        curl \
        unzip \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mysqli \
        gd \
        zip \
        exif \
        intl \
        opcache \
        bcmath \
        mbstring \
        xml \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
    && rm -rf /var/lib/apt/lists/*

# --- Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- Configuración de PHP-FPM y Nginx ---
COPY config/php-fpm.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY config/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY config/nginx.conf /etc/nginx/nginx.conf

# --- Entrypoint ---
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

# Copia el código de la app (ajusta según tu proyecto)
COPY src/ /var/www/html/

RUN mkdir -p /var/log/nginx /run/nginx \
    && chown -R www-data:www-data /var/www/html /var/log/nginx /run/nginx

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -fs http://127.0.0.1/ || exit 1

ENTRYPOINT ["entrypoint.sh"]
