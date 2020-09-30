FROM php:7.4-fpm

ARG TIMEZONE
ARG USER
ARG UID

RUN apt-get update && apt-get install -y \
    openssl \
    curl \
    zip \
    unzip \
    git

RUN pecl install redis && docker-php-ext-enable redis

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone
RUN printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini
RUN "date"

RUN useradd -G www-data,root -u $UID -d /home/$USER $USER
RUN mkdir -p /home/$USER/.composer && \
    chown -R $USER:$USER /home/$user

WORKDIR /var/www/application
USER $USER