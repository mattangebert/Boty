FROM ubuntu:18.04

ENV TZ=Europe/Minsk
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

## Install tools
RUN apt-get update --fix-missing \
    && apt-get upgrade -y \
    && apt-get dist-upgrade -y \
    && apt-get install -y software-properties-common curl language-pack-en-base

# Add PHP PPA
#RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys E5267A6C
#RUN LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php

# Add nodejs repository
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -

## Install tools
RUN apt-get update && apt-get install -y vim
RUN apt-get update --fix-missing \
    && apt-get upgrade -y \
    && apt-get dist-upgrade -y \
    && apt-get install -y \
       cron sudo msmtp bzip2 nodejs git \
       php7.2 \
       php7.2-cli \
       php7.2-bcmath \
       php7.2-curl \
       php7.2-fpm \
       php7.2-gd \
       php7.2-intl \
       php7.2-imap \
       php7.2-json \
       php7.2-ldap \
       php7.2-mbstring \
       php7.2-mysql \
       php7.2-soap \
       php7.2-xml \
       php7.2-xsl \
       php7.2-xmlrpc \
       php7.2-zip \
       graphicsmagick \
    && apt-get clean



COPY config/www.conf /etc/php/7.2/fpm/pool.d/zzz.conf
COPY files/xdebug.ini /etc/php/7.2/mods-available/xdebug.ini
COPY files/php.ini /etc/php/7.2/fpm/conf.d/30-user.ini
COPY files/mailhog.ini /etc/php/7.2/fpm/conf.d/31-mailhog.ini
COPY files/mailhog.ini /etc/php/7.2/cli/conf.d/31-mailhog.ini

# Configure php-fpm
RUN mkdir /run/php
RUN echo "TLS_REQCERT never" > /etc/ldap/ldap.conf
RUN usermod -u 1000 www-data

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin/ --filename=composer && \
    php -r "unlink('composer-setup.php');" && \
    mkdir -p /root/.composer

# Install composer prestissimo plugin for speedup install
RUN composer global require hirak/prestissimo

# Install Grunt
RUN npm install -g grunt-cli

# Install Yarn
RUN npm install -g yarn
RUN chmod -R 775 /usr/lib/node_modules/yarn/

# Startup script
COPY files/startup.sh /usr/local/bin/startup
RUN chmod +x /usr/local/bin/startup

CMD ["/usr/local/bin/startup"]
