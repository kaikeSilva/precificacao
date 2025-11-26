#!/bin/bash

# Copiar .env se não existir
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Criar banco SQLite se não existir
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

# Rodar migrations
php artisan migrate --force

# Limpar caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Iniciar servidor
exec "$@"
