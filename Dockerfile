FROM php:8.3.2-apache AS build-stage

WORKDIR /tmp/build

COPY . /tmp/build

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install intl zip mysqli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --prefer-dist --no-progress --no-suggest

RUN pnpm install --frozen-lockfile && pnpm run dev

FROM php:8.3.2-apache

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install intl zip mysqli

COPY --from=build-stage /tmp/build/vendor /app/vendor
COPY --from=build-stage /tmp/build/public /app/public
COPY --from=build-stage /tmp/build/app /app/app
COPY --from=build-stage /tmp/build/bootstrap /app/bootstrap
COPY --from=build-stage /tmp/build/config /app/config
COPY --from=build-stage /tmp/build/database /app/database
COPY --from=build-stage /tmp/build/resources /app/resources
COPY --from=build-stage /tmp/build/routes /app/routes
COPY --from=build-stage /tmp/build/storage /app/storage
COPY --from=build-stage /tmp/build/tests /app/tests
COPY --from=build-stage /tmp/build/artisan /app/artisan
COPY --from=build-stage /tmp/build/composer.json /app/composer.json
COPY --from=build-stage /tmp/build/composer.lock /app/composer.lock
COPY --from=build-stage /tmp/build/package.json /app/package.json

RUN php artisan migrate --force

CMD php artisan serve --host=0.0.0.0 --port=8000
