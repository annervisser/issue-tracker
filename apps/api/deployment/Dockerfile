FROM docker.io/php:8.1-fpm-alpine

# Setup install-php-extension script
ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/1.5.8/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# Setup php extensions
RUN install-php-extensions apcu igbinary zip intl opcache pdo_mysql gmp bcmath uuid xdebug

# PHP INI
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./deployment/php.ini "$PHP_INI_DIR/conf.d/php.app.ini"

# Setup composer
COPY --from=docker.io/composer /usr/bin/composer /usr/bin/composer

# Add user/group 1000:1000 docker:docker
RUN addgroup -S -g 1000 docker
RUN adduser --system --uid 1000 --disabled-password --ingroup docker docker
USER docker:docker

# Let environment know we're running in docker
ENV docker=true

# Setup app
COPY --chown=docker composer.json composer.lock /srv/app/
WORKDIR /srv/app

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --apcu-autoloader \
    && composer clear-cache

COPY --chown=docker . /srv/app/

# Bootstrap app once to compile container
RUN php config/bootstrap.php
