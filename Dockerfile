FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Set the correct DocumentRoot to Yii2's frontend web directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/frontend/web

# Update Apache config and add DirectoryIndex
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}/../!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

# Copy application files
COPY . /var/www/html

# Install system dependencies, composer, and PHP extensions if needed
RUN apt-get update && apt-get install -y unzip git && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    # Install all composer dependencies including dev dependencies (no --no-dev)
    composer install --optimize-autoloader --working-dir=/var/www/html

# Set proper permissions for www-data user
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
