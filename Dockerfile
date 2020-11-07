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

RUN yes | pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo xdebug.remote_enable=1 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo xdebug.remote_port=9000 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo xdebug.remote_autostart=1 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo xdebug.remote_handler=dbgp >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo xdebug.remote_connect_back=0 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo xdebug.idekey=PHPSTORM >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo xdebug.remote_host=172.17.0.1 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini