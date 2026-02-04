FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql mysqli

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN echo "display_errors=On\nerror_reporting=E_ALL" > /usr/local/etc/php/conf.d/errors.ini

RUN echo "upload_max_filesize=512M\npost_max_size=512M" > /usr/local/etc/php/conf.d/uploads.ini

RUN echo "ServerName crm-local" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/public

RUN a2enmod rewrite && \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/000-default.conf

RUN echo "zend_extension=xdebug.so\n\
xdebug.mode=develop,debug\n\
xdebug.start_with_request=yes\n\
xdebug.client_host=host.docker.internal\n\
xdebug.client_port=9003" > /usr/local/etc/php/conf.d/xdebug.ini
