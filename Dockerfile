FROM php:8.4-apache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader
ENTRYPOINT ["docker/apache/entrypoint.sh"]
