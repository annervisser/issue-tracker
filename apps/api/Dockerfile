FROM php:8.1-fpm
RUN apt-get update \
    && apt-get install -y zlib1g-dev g++ wget git

# Setup install-php-extension script
ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/1.4.17/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# Setup php extensions
RUN install-php-extensions apcu igbinary zip intl opcache pdo_mysql gmp bcmath uuid xdebug

# Setup composer
COPY --from=composer /usr/bin/composer /usr/bin/composer


COPY . /srv/app
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Add user/group 1000:1000 docker:docker
RUN groupadd --system -g 1000 docker
RUN adduser --system --uid 1000 --disabled-password --ingroup docker docker
USER docker:docker

WORKDIR /srv/app
