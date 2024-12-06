FROM php:8.3-alpine

RUN apk add --no-cache --virtual build-dependencies icu-dev libxml2-dev mysql-client $PHPIZE_DEPS \
    && apk add --update linux-headers sqlite\
    && docker-php-ext-install -j$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) mysqli opcache pdo_mysql xml fileinfo pdo exif pcntl\
  && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer