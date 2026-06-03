# --- Stage 1: Build frontend assets ---
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install
COPY . .
RUN npm run build

# --- Stage 2: App container ---
# Use a imagem base oficial do PHP 8.4 com Apache
FROM php:8.4-apache

# Argumentos para build seguro e não-interativo
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependências de sistema necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    nano \
    && rm -rf /var/lib/apt/lists/*

# Configurar e instalar extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql pdo_pgsql zip intl opcache

# Ativar módulo de reescrita do Apache
RUN a2enmod rewrite

# Alterar o DocumentRoot do Apache para a pasta public do Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar o Composer (gerenciador de dependências do PHP)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos de dependência primeiro (aproveita cache do Docker)
COPY composer.json composer.lock ./

# Instalar dependências do Composer sem rodar scripts que exigem o código completo
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copiar o restante do código da aplicação
COPY . .

# Copiar os assets compilados do builder de frontend
COPY --from=frontend-builder /app/public/build ./public/build

# Gerar o autoloader otimizado e rodar os scripts do Composer
RUN composer dump-autoload --optimize --no-dev \
    && composer run-script post-root-package-install || true \
    && composer run-script post-create-project-cmd || true

# Configurar o script de inicialização (Entrypoint)
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expor a porta 80 do Apache
EXPOSE 80

# Definir o entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
