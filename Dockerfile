FROM dunglas/frankenphp:1-php8.3-alpine

# Set environment
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    SERVER_NAME=":8000"

# Install system dependencies and PHP extensions for PostgreSQL, Zip, and Performance
RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    zip \
    opcache \
    bcmath \
    pcntl \
    intl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy dependency manifests first for build caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy application source
COPY . .

# Ensure storage directories exist with appropriate permissions
RUN mkdir -p storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chmod +x docker-entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["./docker-entrypoint.sh"]
