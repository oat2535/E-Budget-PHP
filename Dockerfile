FROM dunglas/frankenphp:1-php8.3-alpine

# Environment configuration
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    SERVER_NAME=":8000" \
    PHP_INI_SCAN_DIR=":$PHP_INI_DIR/conf.d"

# Install system dependencies (curl for healthcheck, bash) and required PHP extensions
RUN apk add --no-cache curl bash \
    && install-php-extensions \
        pdo_pgsql \
        pgsql \
        zip \
        opcache \
        bcmath \
        pcntl \
        intl

# Install Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure production PHP settings
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && { \
        echo 'memory_limit = 512M'; \
        echo 'upload_max_filesize = 64M'; \
        echo 'post_max_size = 64M'; \
        echo 'max_execution_time = 300'; \
        echo 'date.timezone = Asia/Bangkok'; \
        echo 'opcache.enable = 1'; \
        echo 'opcache.enable_cli = 1'; \
        echo 'opcache.memory_consumption = 256'; \
        echo 'opcache.interned_strings_buffer = 16'; \
        echo 'opcache.max_accelerated_files = 20000'; \
        echo 'opcache.validate_timestamps = 0'; \
    } > "$PHP_INI_DIR/conf.d/99-ebudget.ini"

WORKDIR /app

# Copy Caddyfile configuration
COPY Caddyfile /etc/caddy/Caddyfile

# Copy composer dependency definitions first for Docker layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy full application codebase
COPY . .

# Ensure storage and bootstrap/cache directories exist with proper permissions
RUN mkdir -p storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chmod +x docker-entrypoint.sh

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -f http://localhost:8000/up || exit 1

ENTRYPOINT ["./docker-entrypoint.sh"]
