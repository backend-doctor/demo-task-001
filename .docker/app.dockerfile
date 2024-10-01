FROM ghcr.io/shyim/wolfi-php/base:latest

ARG user
ARG uid
ARG group

WORKDIR /var/www/html

COPY . .

RUN apk add --update-cache \
    php-8.2 \
    php-8.2-fpm \
    php-8.2-pdo \
    php-8.2-pdo_mysql \
    php-8.2-pdo_pgsql \
    php-8.2-mysqli \
    php-8.2-pdo_sqlite\
    php-8.2-mysqlnd \
    php-8.2-gd \
    php-8.2-xml \
    php-8.2-dom \
    php-8.2-phar \
    php-8.2-mbstring \
    php-8.2-curl \
    php-8.2-exif \
    php-8.2-intl \
    php-8.2-zip \
    php-8.2-soap \
    php-8.2-openssl \
    php-8.2-bcmath \
    php-8.2-xmlwriter \
    php-8.2-fileinfo

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN addgroup -S ${user} && adduser -S -G ${group},root --uid ${uid} --ingroup ${user}  ${user}


COPY .docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY .docker/php/conf.d/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini

# xdebug installation
# RUN pecl install xdebug-3.1.6 \
#    && docker-php-ext-enable xdebug


USER ${user}

EXPOSE 9000

CMD [ "php-fpm" ]
