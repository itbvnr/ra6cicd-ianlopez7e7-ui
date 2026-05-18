FROM php:8.2-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN php -m | grep mysqli || echo "mysqli not found"
COPY src/ /var/www/html/
