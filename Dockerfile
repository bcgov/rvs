# syntax=docker/dockerfile:1.7-labs

#################################
# Composer deps (builder)
#################################
FROM composer:2 AS composer_deps
WORKDIR /app
COPY composer.json ./
# Cache Composer downloads between builds
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    composer install --no-dev --prefer-dist --no-ansi --no-interaction --no-progress

#################################
# Node build (builder)
#################################
FROM node:20-alpine AS node_build
WORKDIR /app
COPY package*.json ./
# Cache npm cache and node_modules between builds
RUN --mount=type=cache,target=/root/.npm \
    npm ci --no-audit --no-fund
# Copy only the assets needed for build (adjust for your setup)
COPY resources/ resources/
COPY vite.config.* webpack.mix.js* postcss.config.* tailwind.config.* ./
# If using Vite:
RUN npm run build
# If using Laravel Mix:
# RUN npm run production

#################################
# Final image
#################################
FROM php:8.3-apache

ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=America/Vancouver
ENV ORACLE_HOME=/opt/oracle/instantclient

WORKDIR /var/www/html

# ---- System libs & PHP extensions (consolidated) ----
RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      apt-utils \
      git \
      unzip \
      zip \
      wget \
      nano \
      curl \
      libzip-dev \
      libxml2-dev \
      zlib1g-dev \
      g++ \
      libicu-dev \
      libpq-dev \
      libcurl4-openssl-dev \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      libpng-dev \
      libaio-dev \
      libnsl2; \
    # PHP extensions (single pass, parallelized)
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
      bcmath \
      intl \
      opcache \
      zip \
      soap \
      pdo \
      pdo_pgsql \
      pgsql \
      curl \
      gd; \
    pecl install apcu pcov; \
    docker-php-ext-enable apcu pcov; \
    a2enmod rewrite headers remoteip; \
    rm -rf /var/lib/apt/lists/*

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


# (2) Now run composer install/update (no lock)
WORKDIR /var/www/html
COPY composer.json ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    COMPOSER_ALLOW_SUPERUSER=1 \
    composer install --no-dev --prefer-dist --no-ansi --no-interaction --no-scripts

# ---- Apache tweaks (abbreviated) ----
RUN set -eux; \
    sed -ri -e 's!ServerTokens OS!ServerTokens Prod!g' /etc/apache2/conf-available/security.conf; \
    sed -ri -e 's!ServerSignature On!ServerSignature Off!g' /etc/apache2/conf-available/security.conf

# ---- Copy prebuilt vendor & assets ----
# 1) Vendor from composer builder
COPY --from=composer_deps /app/vendor ./vendor
# 2) Built assets from node builder (adjust for Mix/Vite outputs)
#   Vite:   /app/dist or /app/public/build
#   Mix:    /app/public/js, /app/public/css, /app/public/mix-manifest.json
COPY --from=node_build /app/public ./public

# ---- Copy app source last (max cache reuse for vendor/assets) ----
COPY . .

# Permissions (keep your OpenShift-friendly bits if needed)
RUN mkdir -p storage bootstrap/cache \
 && chmod -R ug+rwx storage bootstrap/cache \
 && a2enmod rewrite

EXPOSE 8080
CMD ["apache2-foreground"]
