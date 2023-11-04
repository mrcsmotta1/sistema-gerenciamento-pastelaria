FROM php:8.2-fpm-alpine

RUN apk --no-cache add linux-headers

RUN apk add --no-cache \
    $PHPIZE_DEPS

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo_mysql
RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apk del pcre-dev ${PHPIZE_DEPS}

COPY . /app
RUN chown -R www-data:www-data /app/storage
