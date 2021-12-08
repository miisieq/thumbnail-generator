FROM php:8.0-cli
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN \
    # Set PHP configuration \
    echo 'memory_limit = 256M' >> /usr/local/etc/php/conf.d/app.ini && \
    \
    # Update sources
    apt-get update -y && \
    apt-get upgrade -y && \
    \
    # Install PHP "zip" extension with dependencies
    apt-get install -y libzip-dev zip  && \
    pecl install zip  && \
    docker-php-ext-enable zip && \
    \
    # Install PHP "gd" extension with dependencies
    apt-get install -y libpng-dev libjpeg-dev && \
    docker-php-ext-configure gd --enable-gd --with-jpeg && \
    docker-php-ext-install gd && \
    \
    # Install PHP "xdebug" extension
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    \
    # Remove caches
    rm -rf /var/lib/apt/lists/* rm -rf /tmp/pear

WORKDIR /app
