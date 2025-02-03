# Use the official PHP image with Apache
FROM php:8.3-apache

# Set the working directory
WORKDIR /var/www/space-universal

# Install necessary system packages
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    git \
    zip \
    unzip \
    && docker-php-ext-install \
    intl \
    pdo \
    pdo_pgsql

# Enable Apache mod_rewrite for Symfony routing
RUN a2enmod rewrite

# Copy application source
COPY . /var/www/space-universal

RUN chown -R www-data:www-data /var/www/space-universal \
    && chmod -R 755 /var/www/space-universal
    
# Ensure var directory exists
RUN mkdir -p /var/www/space-universal/var \
    && chown -R www-data:www-data /var/www/space-universal/var \
    && chmod -R 775 /var/www/space-universal/var

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ✅ Install dependencies using Composer (ensure git, zip, and unzip are available)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Update DocumentRoot
RUN sed -ri -e 's!/var/www/html!/var/www/space-universal/public!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/conf-available/docker-php.conf

# Insert or replace the <Directory> block
RUN sed -i '/DocumentRoot/a <Directory /var/www/space-universal/public>\nAllowOverride All\nRequire all granted\n</Directory>' \
    /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
