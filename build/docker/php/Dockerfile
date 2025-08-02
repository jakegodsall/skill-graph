FROM php:8.3-fpm-alpine AS builder

WORKDIR /var/www/html

RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    icu-dev \
    libjpeg-turbo-dev \
    shadow && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    docker-php-ext-configure pcntl --enable-pcntl && \
    docker-php-ext-install pcntl && \
    groupmod --gid 1000 www-data && \
    usermod --uid 1000 --gid 1000 www-data

COPY --chown=1000:1000 src /var/www/html

# Run Composer to install dependencies
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-dev --prefer-dist --no-interaction

FROM php:8.3-fpm-alpine AS prod

# Install pdo_mysql for production
RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    icu-dev \
    libjpeg-turbo-dev && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql

WORKDIR /var/www/html

COPY --from=builder /var/www/html /var/www/html

RUN mkdir /var/www/html/storage/fonts && chown 1000:1000 -R /var/www/html/storage/fonts

VOLUME ["/var/www/html"]

FROM prod AS dev

# Install development utilities
RUN apk add --no-cache \
    bash \
    nano \
    rsync \
    zip \
    unzip \
    nodejs \
    npm && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql
RUN apk add --update sqlite sqlite-libs sqlite-dev nodejs npm

# Copy Composer from the builder stage
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY --chown=1000:1000 build/certs/fullchain.pem /etc/ssl/certificate.chained.crt
COPY --chown=1000:1000 build/certs/privkey.pem /etc/ssl/wildcard.private.key