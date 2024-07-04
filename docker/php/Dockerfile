# Set build arguments
ARG PHP_VERSION
#ARG PHP_EXTENSIONS
#ARG DOCKER_PHP_VERSION
#ARG DOCKER_NODE_VERSION

FROM php:${PHP_VERSION}-fpm

# Set environment variables
#ENV PHP_VERSION=${PHP_VERSION}
#ENV NODE_VERSION=${NODE_VERSION}
#ENV PHP_EXTENSIONS=${PHP_EXTENSIONS}
#ENV DOCKER_PHP_VERSION=${DOCKER_PHP_VERSION}
#ENV DOCKER_NODE_VERSION=${DOCKER_NODE_VERSION}

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www/

COPY ./docker/php/entrypoint.sh /var/www/entrypoint.sh
COPY ./docker/php/local.ini /usr/local/etc/php/conf.d/
#COPY ./docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY . /var/www/

# mix
RUN apt-get update \
  && apt-get install -y iputils-ping unzip build-essential zlib1g-dev libzip-dev libpq-dev default-mysql-client \
  && docker-php-ext-install zip pdo_mysql pdo_pgsql pgsql

# vim git curl default-mysql-client gnupg procps

# intl
#RUN apt-get install -y libicu-dev \
#  && docker-php-ext-configure intl \
#  && docker-php-ext-install intl

# gd
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
docker-php-ext-install gd

# redis
#RUN pecl install redis && docker-php-ext-enable redis

# pcov
#RUN pecl install pcov && docker-php-ext-enable pcov

# Xdebug
# RUN pecl install xdebug \
# && docker-php-ext-enable xdebug \
# && echo ";zend_extension=xdebug" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Node.js, NPM, Yarn
#RUN curl -fsSL "https://deb.nodesource.com/setup_18.x" | bash -
#RUN apt-get install -y nodejs
#RUN npm install npm@latest -g
#RUN npm install yarn -g

# Composer
# Install from Official Source by php - Just need php installed
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# Install from Official Source by sh, curl - Required curl installed
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install from docker image
#COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

#RUN chmod +x ./entrypoint.sh && ./entrypoint.sh

EXPOSE 9000

CMD ["php-fpm"]
