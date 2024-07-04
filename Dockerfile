FROM php:8.3-fpm

ENV TZ=UTC

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

RUN apt-get update \
  && apt-get install -y unzip build-essential zlib1g-dev libzip-dev libpq-dev gnupg procps \
  && apt-get install -y nginx supervisor \
  && docker-php-ext-install zip pdo_mysql pdo_pgsql pgsql
# vim git curl iputils-ping openssh-server

RUN apt-get install -y libicu-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl

RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

RUN rm -rf /var/lib/apt/lists/*

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

RUN curl -fsSL "https://deb.nodesource.com/setup_20.x" | bash -
RUN apt-get install -y nodejs
RUN npm install npm@latest -g
RUN npm install yarn -g

WORKDIR /var/www/html

COPY . /var/www/html

RUN chmod +x ./start.sh && ./start.sh

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/default.conf /etc/nginx/sites-available/default

ADD ./docker/php/info.php /var/www/html/public/info.php

# RUN sed -i "s|root /var/www/public;|root /var/www/html/public;|g" /etc/nginx/sites-available/default \
#     && if [ -L /etc/nginx/sites-enabled/default ]; then rm /etc/nginx/sites-enabled/default; fi \
#     && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# RUN <<EOF
#     sed -i "s|root /var/www/public;|root /var/www/html/public;|g" /etc/nginx/sites-available/default
#     if [ -L /etc/nginx/sites-enabled/default ]; then rm /etc/nginx/sites-enabled/default; fi
#     ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
# EOF

# Configure SSH & USER
# RUN mkdir /var/run/sshd
# RUN sed -i 's/#Port 22/Port 2222/' /etc/ssh/sshd_config
# RUN sed -i 's/PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

# RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd
# RUN echo 'root:123123' | chpasswd
# RUN useradd -m -s /bin/bash adminz
# RUN echo "adminz:123123" | chpasswd

COPY ./docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

RUN chown -R www-data:www-data /var/www/html

# RUN printenv

EXPOSE 80 443
# 2222

CMD ["/usr/bin/supervisord", "-n"]

HEALTHCHECK CMD curl --fail http://localhost:9000 || exit 1
