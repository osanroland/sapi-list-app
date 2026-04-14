FROM php:8.4-apache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

ENTRYPOINT ["docker/apache/entrypoint.sh"]
