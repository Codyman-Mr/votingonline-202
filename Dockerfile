FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Set the correct DocumentRoot to Yii2's frontend web directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/frontend/web

# Update Apache config and add DirectoryIndex
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}/../!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

# Copy only composer files first (optional, for caching)
COPY composer.json composer.lock /var/www/html/

# Install system dependencies and composer
RUN apt-get update && apt-get install -y unzip git curl && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install composer dependencies (without --no-dev for dev dependencies)
RUN composer install --optimize-autoloader --working-dir=/var/www/html

# Now copy rest of the application files
COPY . /var/www/html

# Set proper permissions for www-data user
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
