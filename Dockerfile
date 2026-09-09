# Tumia official PHP image
FROM php:8.2-cli

# Set working directory
WORKDIR /app

# Copy project files
COPY . /app

# Install dependencies kama composer ikiwa unatumia
RUN apt-get update && apt-get install -y unzip git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install || true

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
