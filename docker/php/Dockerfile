# Use an official PHP image as the base image
FROM php:8.3-fpm

# Set the working directory inside the container
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install zip exif pcntl bcmath gd
# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb
# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Copy composer.json and composer.lock (if it exists)
COPY composer.json composer.lock ./

# Install dependencies (use --no-dev for production)
#RUN composer install

# Copy the rest of the application code
COPY . .
RUN chown -R www-data:www-data /var/www/html
# Expose port 9000 (if using PHP-FPM)
EXPOSE 9000
