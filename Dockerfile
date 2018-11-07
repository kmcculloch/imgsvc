FROM php:apache

ENV APACHE_DOCUMENT_ROOT /var/www/imgsvc/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

RUN apt-get update && apt-get install -yq \
        libmcrypt-dev \
        libjpeg-dev \
        libpng-dev

RUN docker-php-ext-configure gd \
        --with-png-dir=/usr/include \
        --with-jpeg-dir=/usr/include

RUN docker-php-ext-install gd

RUN mkdir /var/www/derivatives && chmod 777 /var/www/derivatives

COPY . /var/www/imgsvc
