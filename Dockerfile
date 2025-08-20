FROM php:8.3-apache
ARG DEBIAN_VERSION=20.04
ARG APACHE_OPENIDC_VERSION=2.4.10
ARG TZ=America/Vancouver
ARG CA_HOSTS_LIST
ARG TEST_ARG
ARG USER_ID
ARG DEBIAN_FRONTEND=noninteractive
ARG DEVENV=prod

# set entrypoint variables
ENV USER_NAME=${USER_ID}
ENV USER_HOME=/var/www/html

# Set the Oracle environment variables
ENV ORACLE_HOME /opt/oracle/instantclient

ENV APACHE_REMOTE_IP_HEADER=X-Forwarded-For
ENV APACHE_REMOTE_IP_TRUSTED_PROXY="10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 10.97.6.0/16 10.97.6.1"
ENV APACHE_REMOTE_IP_INTERNAL_PROXY="10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 10.97.6.0/16 10.97.6.1"

# System - Set default timezone
ENV TZ=${TZ}
ENV APACHE_SERVER_NAME=__default__

WORKDIR /

RUN apt-get -y update --fix-missing \
    && apt-get update && apt-get install -y --no-install-recommends apt-utils \
#php setup, install extensions, setup configs \
    && apt-get install --no-install-recommends -y \
    libzip-dev \
    libxml2-dev \
    zip \
    unzip \
    && pecl install zip pcov && docker-php-ext-enable zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install soap \
    && docker-php-source delete \
#disable exposing server information \
    && sed -ri -e 's!expose_php = On!expose_php = Off!g' $PHP_INI_DIR/php.ini-production \
    && sed -ri -e 's!ServerTokens OS!ServerTokens Prod!g' /etc/apache2/conf-available/security.conf \
    && sed -ri -e 's!ServerSignature On!ServerSignature Off!g' /etc/apache2/conf-available/security.conf \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && apt-get install -yq zlib1g-dev g++ libicu-dev libpq-dev git nano netcat-traditional curl apache2 dialog locate libcurl4 libcurl3-dev psmisc \
	libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libmcrypt-dev \
    libpng-dev \
    libaio-dev \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-install intl opcache\
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
# Install Postgre PDO
    && apt-get install -y libonig-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql && docker-php-ext-install curl  \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/  \
    && docker-php-ext-install -j$(nproc) gd && a2enmod rewrite

# --- Oracle runtime deps & compat symlink for libaio SONAME change ------------
RUN set -eux; \
    apt-get update; \
    if apt-cache show libaio1 >/dev/null 2>&1; then \
        apt-get install -y --no-install-recommends libaio1 libaio-dev; \
    else \
        apt-get install -y --no-install-recommends libaio1t64 libaio-dev; \
    fi; \
    apt-get install -y --no-install-recommends libnsl2; \
    # provide legacy SONAME expected by Instant Client/oci8
    if [ -f /lib/x86_64-linux-gnu/libaio.so.1t64 ] && [ ! -e /lib/x86_64-linux-gnu/libaio.so.1 ]; then \
        ln -s /lib/x86_64-linux-gnu/libaio.so.1t64 /lib/x86_64-linux-gnu/libaio.so.1; \
    fi; \
    echo "/usr/lib/oracle/12.2/client64/lib" > /etc/ld.so.conf.d/oracle-instantclient.conf; \
    ldconfig; \
    rm -rf /var/lib/apt/lists/*

# Installing Oracle instant client
WORKDIR /opt/oracle
RUN apt-get install -y wget unzip \
  && wget https://download.oracle.com/otn_software/linux/instantclient/213000/instantclient-basiclite-linux.x64-21.3.0.0.0.zip \
  && wget https://download.oracle.com/otn_software/linux/instantclient/213000/instantclient-sqlplus-linux.x64-21.3.0.0.0.zip \
  && wget https://download.oracle.com/otn_software/linux/instantclient/213000/instantclient-sdk-linux.x64-21.3.0.0.0.zip \
  && unzip instantclient-sdk-linux.x64-21.3.0.0.0.zip \
  && rm -f instantclient-sdk-linux.x64-21.3.0.0.0.zip \
  && unzip instantclient-basiclite-linux.x64-21.3.0.0.0.zip \
  && rm -f instantclient-basiclite-linux.x64-21.3.0.0.0.zip \
  && unzip instantclient-sqlplus-linux.x64-21.3.0.0.0.zip \
  && rm -f instantclient-sqlplus-linux.x64-21.3.0.0.0.zip \
  && mv /opt/oracle/instantclient_21_3 /opt/oracle/instantclient \
  && cd /opt/oracle/instantclient* \
  && rm -f *jdbc* *occi* *mysql* *README *jar uidrvci genezi adrci \
  && docker-php-ext-configure oci8 --with-oci8=instantclient,$ORACLE_HOME && docker-php-ext-install oci8 \
  && docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,$ORACLE_HOME && docker-php-ext-install pdo_oci \
  && echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient.conf \
  && ldconfig
RUN pear download pecl/oci8-3.2.1 && tar xvzf oci8-3.2.1.tgz && cd oci8-3.2.1  \
    && phpize && ./configure --with-oci8=instantclient,$ORACLE_HOME/ && make install

RUN printf "instantclient,$ORACLE_HOME" \
# Apache - disable Etag
    && a2enmod remoteip \
    && a2enmod rewrite \
    && a2enmod auth_basic \
    && a2enmod authn_file \
    && a2enmod authz_user \
    && a2enmod autoindex \
    && a2enmod deflate \
    && a2enmod filter \
    && a2dismod mpm_event && a2dismod  mpm_worker && a2enmod mpm_prefork \
    && a2enmod reqtimeout \
    && a2enmod setenvif \
    && sed -i 's/%h/%a/g' /etc/apache2/apache2.conf \
    && { \
        echo 'RemoteIPHeader X-Forwarded-For'; \
        echo 'RemoteIPInternalProxy 10.0.0.0/8'; \
        echo 'RemoteIPInternalProxy 172.16.0.0/12'; \
        echo 'RemoteIPInternalProxy 192.168.0.0/16'; \
        echo 'RemoteIPInternalProxy 169.254.0.0/16'; \
        echo 'RemoteIPInternalProxy 127.0.0.0/8'; \
    } | tee "$APACHE_CONFDIR/conf-available/remoteip.conf" && \
    a2enconf remoteip \
# Apache - Hide version
  && sed -i -e 's/^ServerTokens OS$/ServerTokens Prod/g' \
        -e 's/^ServerSignature On$/ServerSignature Off/g' \
        /etc/apache2/conf-available/security.conf \
# Enable apache modules
  && a2enmod rewrite headers

# Install NPM
RUN apt-get install -y ca-certificates gnupg \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    NODE_MAJOR=20 \
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
    apt-get update && apt-get install nodejs -y && apt-get install -y npm

RUN apt-get autoclean && apt-get autoremove && apt-get clean && rm -rf /var/lib/apt/lists/* \
#fix Action '-D FOREGROUND' failed.
    && a2enmod lbmethod_byrequests \
    && mkdir -p /var/log/php  \
    && printf 'error_log=/var/log/php/error.log\nlog_errors=1\nerror_reporting=E_ALL\n' > /usr/local/etc/php/conf.d/custom.ini \
    && mkdir -p /etc/apache2/sites-enabled

# Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && php composer-setup.php --install-dir=/usr/local/bin --filename=composer

WORKDIR /
COPY openshift/apache-oc/image-files/ /
COPY openshift/apache-oc/image-files/etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/000-default.conf


EXPOSE 8080 8443 2525
RUN sed -i -e 's/80/8080/g' -e 's/443/8443/g' -e 's/25/2525/g' /etc/apache2/ports.conf \
    # Apache- Prepare to be run as non root user
    && mkdir -p /var/lock/apache2 /var/run/apache2 \
    && chgrp -R 0 /etc/apache2/mods-* \
        /etc/apache2/sites-* \
        /run /var/lib/apache2 \
        /var/run/apache2 \
        /var/lock/apache2 \
        /var/log/apache2 \
    && chmod -R g=u /etc/passwd \
        /etc/apache2/mods-* \
        /etc/apache2/sites-* \
        /run \
        /var/lib/apache2 \
        /var/run/apache2 \
        /var/lock/apache2 \
        /var/log/apache2 \
    # Apache - Display information (version, module)
    && a2query -v \
    && a2query -M \
    && a2query -m \
    && chmod a+rx /docker-bin/*.sh \
    && /docker-bin/docker-build.sh && export COMPOSER_HOME="$HOME/.config/composer";

COPY entrypoint.sh /sbin/entrypoint.sh
COPY / /var/www/html/

WORKDIR /var/www/html/

RUN mkdir -p storage && mkdir -p bootstrap/cache && chmod -R ug+rwx storage bootstrap/cache \
    && cd /var/www && chown -R ${USER_ID}:root html && chmod -R ug+rw html \
    && chmod 764 /var/www/html/artisan \
#Error: EACCES: permission denied, open '/var/www/html/public/mix-manifest.json' \
    && cd /var/www/html/public && chmod 766 mix-manifest.json \
    && mkdir /.npm && chown -R ${USER_ID}:0 "/.npm" \
#Writing to directory /.config/psysh is not allowed.
    && mkdir -p /.config/psysh && chown -R ${USER_ID}:root /.config && chmod -R 755 /.config \
    && mkdir -p /.composer && chown -R ${USER_ID}:root /.composer && chmod -R 755 /.composer \
    && echo "<?php return ['runtimeDir' => '/tmp'];" >> /.config/psysh/config.php \
# Clean up \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
#openshift will complaine about permission \
    && chmod +x /sbin/entrypoint.sh
USER ${USER_ID}

#composer install
RUN composer install && npm install --prefix /var/www/html/ && npm run --prefix /var/www/html/ ${DEVENV}

ENTRYPOINT ["/sbin/entrypoint.sh"]
# Start!
CMD ["apache2-foreground"]
