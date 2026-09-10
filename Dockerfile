# Tumia official PHP image (CLI au Apache kulingana na project yako)
FROM php:8.2-apache

# Install dependencies zinazohitajika
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# Copy project files
WORKDIR /var/www/html
COPY . /var/www/html

# Enable Apache rewrite module (useful kwa routing)
RUN a2enmod rewrite

# Expose port
EXPOSE 10000

# Start Apache
CMD ["apache2-foreground"]




