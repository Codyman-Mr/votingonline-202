FROM php:8.2-apache

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Set the correct DocumentRoot to Yii2's frontend web directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/frontend/web

# Update Apache config and add DirectoryIndex
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}/../!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

# Install system dependencies needed for PHP extensions and composer
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        zip \
        gd \
        mbstring \
        intl \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy only composer files first for caching
COPY composer.json composer.lock /var/www/html/

# Install composer dependencies
RUN composer install --optimize-autoloader --working-dir=/var/www/html

# Copy rest of the application files
COPY . /var/www/html

# Set proper permissions for www-data user
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# ✅ Start Apache server properly
CMD ["apache2-foreground"]
