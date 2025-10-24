FROM php:8.3-fpm

# === Instalar dependências do sistema ===
RUN apt-get update && apt-get install -y \
    git curl zip unzip vim libpng-dev libonig-dev libxml2-dev libzip-dev && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# === Instalar Composer globalmente ===
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

# === Diretório de trabalho ===
WORKDIR /var/www/html


COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Corrigir permissões no CMD (em tempo de execução)
CMD chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && php-fpm