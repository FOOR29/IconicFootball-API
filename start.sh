#!/bin/sh

# Iniciar PHP-FPM en background
php-fpm -D

# Copiar configuración de Nginx
cp /var/www/docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Iniciar Nginx en foreground
nginx -g 'daemon off;'
