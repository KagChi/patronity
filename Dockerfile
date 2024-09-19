FROM ghcr.io/hazmi35/node:20-dev-alpine AS node-build

WORKDIR /tmp/build

COPY . .

RUN apk add --no-cache libicu-dev libzip-dev git python3

RUN corepack enable && corepack prepare pnpm@latest

RUN pnpm install --frozen-lockfile && pnpm run build

FROM php:8.2-fpm-alpine

WORKDIR /app

COPY --from=node-build /tmp/build .

# Install needed services
RUN apk add --no-cache --update libcurl caddy libzip-dev libpng-dev postgresql-dev

# Configure php extensions
RUN docker-php-ext-configure zip \
    && docker-php-ext-install gd pdo_pgsql pdo_mysql zip

# Install composer and the dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && cp .env.example .env \
    && mkdir -p bootstrap/cache/ storage/logs storage/framework/sessions storage/framework/views storage/framework/cache \
    && chmod 777 -R bootstrap storage \
    && composer install --no-dev --optimize-autoloader \
    && rm -rf .env bootstrap/cache/*.php \
    && mkdir -p /app/storage/logs/ 

RUN chmod +x /app/entrypoint.sh

ENTRYPOINT [ "/bin/sh", "/app/entrypoint.sh" ]

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
