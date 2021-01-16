# Build with:
# docker build -t comicfeeds .

# Run with Self-Serve with php -S built-in HTTPD:
# docker run -e SELF_SERVE=1 --rm -it -p 127.0.0.1:8080:8080 comicfeeds

# Run with Serve with PHP-FPM:
# docker run --rm -it -p 127.0.0.1:9001:9000 comicfeeds 

FROM php:8-fpm-alpine

## Install PHP extensions
# XSLT stuff from https://github.com/docker-library/php/issues/915#issuecomment-560832266
RUN set -xe; \
    \
    apk --update add --no-cache --virtual .php-ext-install-deps \
        $PHPIZE_DEPS \
        # xsl deps
        libxslt-dev \
        libgcrypt-dev \
    \
    && docker-php-ext-install -j$(nproc) \
        xsl \
    && docker-php-source delete \
    \
    && runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )" \
    && apk add --no-cache $runDeps \
    \
    && apk del --no-network .php-ext-install-deps

# Our stuff
COPY docker/crontab.txt /crontab.txt
COPY docker/entry.sh /entry.sh
COPY docker/fetch.sh /fetch.sh
COPY src /app
COPY --from=composer /usr/bin/composer /composer

RUN cat /crontab.txt >> /etc/crontabs/root \
    && chmod 755 /entry.sh /fetch.sh \
    && cd /app \
    && /composer install --no-dev \
    && /composer require phpunit/phpunit \
    && /app/vendor/phpunit/phpunit/phpunit \
    && /composer remove phpunit/phpunit \
    && rm /composer \
    # Change our root dir for the php app \
    && sed -i 's/;chdir = \/var\/www/chdir = \/app\/web/' /usr/local/etc/php-fpm.d/www.conf

ENTRYPOINT ["/entry.sh"]
