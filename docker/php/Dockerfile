FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y zlib1g-dev libzip-dev gcc \
    && docker-php-ext-install zip pdo_mysql

RUN apt-get install -y imagemagick libmagickwand-dev pkg-config
RUN echo \n | pecl install imagick

COPY php.ini /usr/local/etc/php/

RUN docker-php-ext-enable imagick

#Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

ENV COMPOSER_HOME /composer

ENV PATH $PATH:/composer/vendor/bin

WORKDIR /var/www

RUN composer global require "laravel/installer"
