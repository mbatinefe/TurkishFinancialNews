FROM php:8.1-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the application files to the container
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Set the permissions for the application files
RUN chown -R www-data:www-data /var/www/html/

# Expose port 80
EXPOSE 80