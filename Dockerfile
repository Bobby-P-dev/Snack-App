# ==========================================
# Stage 1: Install Composer Dependencies (No Dev)
# ==========================================
FROM composer:2 AS vendor-builder
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts \
    --ignore-platform-reqs

# ==========================================
# Stage 2: Build Frontend Assets (Vite)
# ==========================================
FROM node:20-alpine AS frontend-builder
WORKDIR /app

COPY package*.json ./
RUN npm ci --prefer-offline --no-audit

COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public

# Copy Ziggy package from vendor-builder so Vite can resolve ZiggyVue
COPY --from=vendor-builder /app/vendor/tightenco/ziggy ./vendor/tightenco/ziggy

RUN npm run build && rm -rf node_modules

# ==========================================
# Stage 3: Production Runtime (FrankenPHP Alpine - No Nginx)
# ==========================================
FROM dunglas/frankenphp:php8.3-alpine AS runner

LABEL maintainer="Padu Kue Team"
LABEL description="Production image for Padu Kue without Nginx (Direct HTTP for Nginx Proxy Manager)"

# Install runtime packages (curl for healthcheck, chromium & nodejs for PDF invoices)
RUN apk add --no-cache \
    curl \
    chromium \
    nodejs \
    font-noto

# Install PHP extensions using FrankenPHP's official installer
RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    gd \
    bcmath \
    intl \
    zip \
    opcache \
    pcntl \
    exif

# Configure FrankenPHP to listen on plain HTTP port 80 (TLS terminated by Nginx Proxy Manager)
ENV SERVER_NAME=":80" \
    CHROME_PATH=/usr/bin/chromium-browser \
    PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium-browser

COPY docker/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

# Copy application source code
COPY --chown=www-data:www-data . .

# Copy pre-built vendor from Stage 1
COPY --from=vendor-builder --chown=www-data:www-data /app/vendor ./vendor

# Copy pre-built frontend assets from Stage 2
COPY --from=frontend-builder --chown=www-data:www-data /app/public/build ./public/build

# Ensure proper storage & cache permissions
RUN mkdir -p storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             storage/app/public \
             bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

HEALTHCHECK --interval=15s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
