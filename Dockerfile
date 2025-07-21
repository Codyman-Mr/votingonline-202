FROM php:8.1-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy full app contents into container
COPY . /var/www/html/

# Change Apache's DocumentRoot to frontend/web
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/frontend/web|g' /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set file permissions (optional but recommended)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
