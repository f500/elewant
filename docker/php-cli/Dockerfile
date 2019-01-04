FROM php:7.2-cli-alpine

# Install PDO_mysql
RUN docker-php-ext-install pdo_mysql

# Install, configure, but do not enable Xdebug
RUN apk --no-cache add --virtual .build-dependencies \
        autoconf \
        g++ \
        make \
    && pecl install xdebug-2.6.1 \
    && apk --no-cache del .build-dependencies
COPY xdebug.ini ${PHP_INI_DIR}/conf.d/xdebug.ini

# Allow XDebug enable by setting PHP_INI_SCAN_DIR
COPY xdebug-enable.ini ${PHP_INI_DIR}/debug/xdebug-enable.ini

# Install Composer
RUN apk --no-cache add zlib-dev \
    && docker-php-ext-install zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

VOLUME /opt/project
WORKDIR /opt/project
