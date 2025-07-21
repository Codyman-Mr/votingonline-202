FROM php:8.2-apache

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/frontend/web

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}/../!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

COPY . /var/www/html

RUN apt-get update && apt-get install -y unzip git && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --optimize-autoloader --working-dir=/var/www/html

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
