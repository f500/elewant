FROM php:cli-alpine

# Install PDO_mysql
RUN docker-php-ext-install pdo_mysql

# Install Composer
RUN apk --no-cache add zlib-dev \
    && docker-php-ext-install zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

VOLUME /opt/project
WORKDIR /opt/project
