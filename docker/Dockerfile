FROM php:8.4-fpm

# Arguments for user/group ID
ARG UID=1000
ARG GID=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN groupadd -g ${GID} laravel && \
    useradd -u ${UID} -g laravel -m laravel && \
    mkdir -p /var/www/html && \
    chown -R laravel:laravel /var/www

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=laravel:laravel ../backend /var/www/html

# Set permissions for Laravel directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache && \
    chown -R laravel:laravel storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

USER laravel

EXPOSE 9000

CMD ["php-fpm"]
