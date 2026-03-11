FROM php:8.4-fpm

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libonig-dev curl \
    && docker-php-ext-install pdo pdo_mysql

# Node.js e npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copia o projeto
COPY . .

# Copia entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Instala dependências Laravel
RUN composer install

# Define entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 8000
EXPOSE 5173