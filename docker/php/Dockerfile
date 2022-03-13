FROM php:8.0-alpine

RUN apk add libmcrypt-dev $PHPIZE_DEPS zsh postgresql-dev
RUN pecl install mcrypt
RUN docker-php-ext-enable mcrypt
RUN docker-php-ext-install pdo pdo_pgsql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

WORKDIR /app