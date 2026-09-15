# ──────────────────────────────────────────────────────────────
#  Base PHP 8.3 + Apache
# ──────────────────────────────────────────────────────────────
FROM php:8.3-apache-bookworm

# Evita prompts interativos
ENV DEBIAN_FRONTEND=noninteractive

# Timezone
ENV TZ=America/Sao_Paulo

# Compatibilidade webpack antigo / Laravel Mix
ENV NODE_OPTIONS=--openssl-legacy-provider

# ──────────────────────────────────────────────────────────────
#  Dependências do sistema
# ──────────────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
    apt-transport-https \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libc-client-dev \
    libkrb5-dev \
    libcurl4-openssl-dev \
    libbz2-dev \
    libicu-dev \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    wget \
    sudo \
    g++ \
    mariadb-client \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    ca-certificates \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# ──────────────────────────────────────────────────────────────
#  Node.js 18 (mais compatível com Laravel Mix)
# ──────────────────────────────────────────────────────────────
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# ──────────────────────────────────────────────────────────────
#  Extensões PHP
# ──────────────────────────────────────────────────────────────
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        bcmath \
        bz2 \
        calendar \
        exif \
        gd \
        iconv \
        imap \
        intl \
        mbstring \
        opcache \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        zip && \
    docker-php-ext-enable opcache

# ──────────────────────────────────────────────────────────────
#  Apache
# ──────────────────────────────────────────────────────────────
RUN a2enmod rewrite

# ──────────────────────────────────────────────────────────────
#  Composer
# ──────────────────────────────────────────────────────────────
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ──────────────────────────────────────────────────────────────
#  Diretório da aplicação
# ──────────────────────────────────────────────────────────────
WORKDIR /var/www/html

# ──────────────────────────────────────────────────────────────
#  Copia arquivos da aplicação
# ──────────────────────────────────────────────────────────────
COPY src/. /var/www/html
COPY .env /var/www/html/.env
COPY apache2.conf /etc/apache2/apache2.conf

# ──────────────────────────────────────────────────────────────
#  Diretórios obrigatórios Laravel
# ──────────────────────────────────────────────────────────────
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# ──────────────────────────────────────────────────────────────
#  Permissões iniciais
# ──────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# ──────────────────────────────────────────────────────────────
#  Dependências PHP
# ──────────────────────────────────────────────────────────────
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --no-scripts

# Instala dependências JS
RUN npm install --legacy-peer-deps

# Garante versões compatíveis com Laravel Mix 6
RUN npm install \
    webpack@5.64.4 \
    webpack-cli@4.9.1 \
    laravel-mix@6.0.49 \
    --save-dev \
    --legacy-peer-deps

# Diagnóstico (remover depois que funcionar)
RUN npm list webpack
RUN npm list webpack-cli
RUN npm list laravel-mix

# Build
RUN npm run prod

# ──────────────────────────────────────────────────────────────
#  Limpeza
# ──────────────────────────────────────────────────────────────
RUN rm -rf node_modules && \
    npm cache clean --force

# ──────────────────────────────────────────────────────────────
#  Cache Laravel
# ──────────────────────────────────────────────────────────────
RUN php artisan config:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true

# ──────────────────────────────────────────────────────────────
#  Permissões finais
# ──────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# ──────────────────────────────────────────────────────────────
#  Entrypoint
# ──────────────────────────────────────────────────────────────
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

# ──────────────────────────────────────────────────────────────
#  Porta
# ──────────────────────────────────────────────────────────────
EXPOSE 80
