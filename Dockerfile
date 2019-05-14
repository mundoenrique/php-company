FROM php:7.0-apache

WORKDIR /var/www/html

COPY build ./
COPY src/httpd/default.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update -y \
	&& apt-get install -y curl libmcrypt-dev libssh2-1-dev nano \
	&& docker-php-ext-install -j$(nproc) mcrypt \
	&& pecl install ssh2-1.0 \
	&& docker-php-ext-enable ssh2 \
	&& a2enmod headers rewrite \
	&& a2ensite 000-default.conf \
	&& mkdir -p assets/Co/bash assets/Pe/bash assets/Usd/bash assets/Ve/bash assets/Ec-bp/bash \
	&& chmod 0755 assets/Co/bash assets/Pe/bash assets/Usd/bash assets/Ve/bash assets/Ec-bp/bash \
	&& mkdir -p ../sessions \
	&& chmod 0700 ../sessions \
	&& chown -R www-data:www-data ../ \
	&& apachectl restart
