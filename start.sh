#!/bin/bash
set -e

echo "🚀 Iniciando aplicación..."

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "▶ Iniciando PHP-FPM..."
php-fpm -D

sleep 2

echo "▶ Iniciando Nginx..."
nginx -g 'daemon off;'