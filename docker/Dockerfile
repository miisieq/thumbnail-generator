FROM php:8.0-cli
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN \
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
    apt-get install -y libpng-dev && \
    docker-php-ext-install gd && \
    \
    # Install PHP "xdebug" extension
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    \
    # Remove caches
    rm -rf /var/lib/apt/lists/* rm -rf /tmp/pear

WORKDIR /app
