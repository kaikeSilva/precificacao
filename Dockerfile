FROM php:8.3-fpm

# Argumentos
ARG user=appuser
ARG uid=1000

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite3 \
    libsqlite3-dev \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip intl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário para rodar Composer e Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto
COPY --chown=$user:$user . .

# Criar diretórios necessários com permissões corretas
RUN mkdir -p /var/www/vendor /var/www/node_modules /var/www/storage /var/www/bootstrap/cache \
    && chown -R $user:$user /var/www

# Instalar dependências PHP
USER $user
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instalar dependências Node e buildar assets
RUN npm install && npm run build

USER root

# Permissões
RUN chown -R $user:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Criar banco SQLite se não existir
RUN touch /var/www/database/database.sqlite \
    && chown $user:www-data /var/www/database/database.sqlite \
    && chmod 664 /var/www/database/database.sqlite

# Copiar e dar permissão ao entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

USER $user

EXPOSE 8000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
