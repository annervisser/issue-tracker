FROM php:8.1-fpm
RUN apt-get update \
    && apt-get install -y zlib1g-dev g++ wget git libicu-dev libzip-dev zip \
	&& docker-php-ext-install intl opcache pdo pdo_mysql \
	&& pecl install apcu \
	&& docker-php-ext-enable apcu \
	&& docker-php-ext-configure zip \
	&& docker-php-ext-install zip

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY . /srv/app
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Add user/group 1000:1000 docker:docker
RUN groupadd --system -g 1000 docker
RUN adduser --system --uid 1000 --disabled-password --ingroup docker docker
USER docker:docker

WORKDIR /srv/app
