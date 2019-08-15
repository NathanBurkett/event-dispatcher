ARG PHP_VERSION=7.2

FROM composer as builder

RUN apt update && \
    apt upgrade

WORKDIR /app

COPY composer.json composer.lock /app/

RUN composer validate

RUN composer install  \
    --ignore-platform-reqs \
    --no-ansi \
    --no-autoloader \
    --no-interaction \
    --no-scripts

# We need to copy our whole application so that we can generate the autoload file inside the vendor folder.
COPY . /app

RUN composer dump-autoload \
    --optimize \
    --classmap-authoritative

FROM php:${PHP_VERSION}-fpm as common

RUN apt update && \
    apt upgrade

# We only install xdebug in development.
RUN pecl install xdebug-2.6.0; \
    docker-php-ext-enable xdebug

WORKDIR /app

# Copy our application.
COPY . /app/

# Copy the downloaded dependencies from the previous stage.
COPY --from=builder /app/vendor /app/vendor
RUN composer dump-autoload \
    --optimize \
    --classmap-authoritative

RUN /app/vendor/bin/phpunit \
    --config /app/phpunit.xml.dist
