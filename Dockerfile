FROM php:7.3.0-apache
RUN apt-get update && \
    apt-get install -y zip unzip libzip-dev

RUN a2enmod rewrite \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

#git
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer