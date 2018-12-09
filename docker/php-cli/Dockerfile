FROM php:cli-alpine

# Install PDO_mysql
RUN docker-php-ext-install pdo_mysql

# Install, configure, enable Xdebug
RUN apk --no-cache add --virtual .build-dependencies \
        autoconf \
        g++ \
        make \
    && pecl install xdebug-2.6.1 \
    && apk --no-cache del .build-dependencies
COPY xdebug.ini ${PHP_INI_DIR}/conf.d/xdebug.ini

# Install Composer
RUN apk --no-cache add zlib-dev \
    && docker-php-ext-install zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

VOLUME /opt/project
WORKDIR /opt/project
