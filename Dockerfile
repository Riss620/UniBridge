# Stage 1: Build Frontend Assets
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
COPY vite.config.js ./
COPY tailwind.config.js* ./
COPY postcss.config.js* ./
COPY resources ./resources
COPY public ./public
RUN npm ci && npm run build

# Stage 2: PHP Application Runtime
FROM php:8.3-fpm-alpine
WORKDIR /var/www/html

# Install System Dependencies and Nginx
RUN apk update && apk add --no-cache \
    nginx \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    postgresql-dev \
    bash

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip gd bcmath opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Copy Vite compiled assets from stage 1
COPY --from=node-builder /app/public/build ./public/build

# Copy Nginx Configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Setup correct permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod +x /var/www/html/docker/entrypoint.sh

# Expose HTTP port
EXPOSE 80

# Configure entrypoint
ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
