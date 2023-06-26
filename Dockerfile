# Use the official PHP base image
FROM php:8.1-fpm

# Set the working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    sqlite3 \
    && docker-php-ext-install pdo_mysql

# Copy project files to the container
COPY . /var/www/html

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Set file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose the application port
EXPOSE 8000

# Run the application
CMD php artisan serve --host=0.0.0.0 --port=8000


COPY entrypoint.sh /var/www/html/entrypoint.sh

CMD ["/bin/sh", "/var/www/html/entrypoint.sh"]


