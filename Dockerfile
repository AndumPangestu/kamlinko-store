# Use an official PHP runtime as a parent image
FROM php:8.2-fpm
# Set working directory
WORKDIR /var/www/html
# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libzip-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    cron \
    supervisor \
    nano \
    screen

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Set PHP configuration settings directly within the Dockerfile
RUN echo "file_uploads = On\nmemory_limit = 512M\nupload_max_filesize = 24M\npost_max_size = 24M\nmax_execution_time = 600\n" > /usr/local/etc/php/conf.d/uploads.ini

# Install Composer dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN composer update

# Copy Supervisor configuration
COPY supervisor/ /etc/supervisor/conf.d/

# Copy the start script
COPY start_cron.sh /usr/local/bin/start_cron.sh
# Ensure the script is executable
RUN chmod +x /usr/local/bin/start_cron.sh
# Expose port 9000 and set the entry point
EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/start_cron.sh"]