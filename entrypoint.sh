#!/bin/sh
set -e

echo "==> Iniciando contenedor PHP ${PHP_VERSION:-desconocido} + Nginx"

# Instala dependencias de Composer si existe composer.json y no hay vendor/
if [ -f /var/www/html/composer.json ] && [ ! -d /var/www/html/vendor ]; then
    echo "==> Ejecutando composer install..."
    cd /var/www/html
    composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist || true
fi

# Asegura permisos correctos (por si el volumen se montó después del build)
chown -R www-data:www-data /var/www/html || true

# Función para propagar señales y apagar limpio ambos procesos
term_handler() {
    echo "==> Señal de apagado recibida, deteniendo servicios..."
    if [ -n "$PHP_FPM_PID" ]; then
        kill -TERM "$PHP_FPM_PID" 2>/dev/null || true
    fi
    if [ -n "$NGINX_PID" ]; then
        kill -TERM "$NGINX_PID" 2>/dev/null || true
    fi
    wait
    exit 0
}

trap term_handler TERM INT

# Arranca PHP-FPM en primer plano (background) y Nginx en foreground
php-fpm --nodaemonize &
PHP_FPM_PID=$!

nginx -g "daemon off;" &
NGINX_PID=$!

wait -n "$PHP_FPM_PID" "$NGINX_PID"
EXIT_CODE=$?

echo "==> Un proceso terminó (código $EXIT_CODE), apagando el otro..."
term_handler
