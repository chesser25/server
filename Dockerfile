FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www/html
RUN chmod -R 777 /var/www/html
RUN chown -R root .

# Set user
USER root

# Install dependencies
RUN apt-get update && apt-get install -y \
build-essential \
zip \
unzip \
git \
curl \
netcat \
zlib1g-dev \
libpng-dev \
libjpeg-dev \
libfreetype6-dev

RUN docker-php-ext-install exif
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd
RUN docker-php-ext-install pdo_mysql mysqli

# Install composer
RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]