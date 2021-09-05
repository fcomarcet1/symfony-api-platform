#FROM php:7.4.6-fpm
FROM php:8.0.1-fpm

ARG UID
EXPOSE $UID

RUN adduser -u ${UID} --disabled-password --gecos "" appuser
RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser/
RUN echo "StrictHostKeyChecking no" >> /home/appuser/.ssh/config
RUN echo "export COLUMNS=300" >> /home/appuser/.bashrc
RUN echo "alias sf=/appdata/www/bin/console" >> /home/appuser/.bashrc

COPY ./php.ini /usr/local/etc/php/php.ini

#RUN apt-get update \
    #&& apt-get install -y git acl openssl openssh-client wget zip vim nano librabbitmq-dev libssh-dev \
    #&& apt-get install -y libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev \
    #&& docker-php-ext-install intl pdo pdo_mysql zip gd soap bcmath sockets \
    #&& pecl install xdebug amqp \
    #&& docker-php-ext-enable --ini-name 05-opcache.ini opcache amqp xdebug

# Install packages and PHP extensions
RUN apt update \
    && apt install -y git acl openssl openssh-client wget zip vim librabbitmq-dev libssh-dev \
    && apt install -y libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip soap bcmath sockets \
    && pecl install xdebug \
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache xdebug

# Install and update composer
RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
RUN composer self-update

# Install PHP-CS-FIXER
RUN wget https://cs.symfony.com/download/php-cs-fixer-v2.phar -O php-cs-fixer
RUN chmod a+x php-cs-fixer
RUN mv php-cs-fixer /usr/local/bin/php-cs-fixer

# Install Symfony Installer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Install Git
RUN git config --global user.email "fcomarcet1@gmail.com" \ 
    && git config --global user.name "fcomarcet1"

# Config XDEBUG
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN mkdir -p /appdata/www

USER appuser

WORKDIR /appdata/www
