FROM php:8.0.0rc1-fpm

RUN apt-get update && apt-get install -y \
    apt-utils \
    libmcrypt-dev \
    libzip-dev \
    libpq-dev \
    libxml2-dev \
    curl \
    git \
    wget

RUN docker-php-ext-install zip soap bcmath sockets

RUN mkdir -p /usr/src/php/ext/mongodb && \
    curl -fsSL https://pecl.php.net/get/mongodb | tar xvz -C "/usr/src/php/ext/mongodb" --strip 1 && \
    docker-php-ext-install mongodb && \
    docker-php-ext-enable mongodb

RUN dpkg --add-architecture i386

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --filename=composer
RUN php -r "unlink('composer-setup.php');"
RUN mv composer /usr/local/bin/composer

WORKDIR /usr/src/app
