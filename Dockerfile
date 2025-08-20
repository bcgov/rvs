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

# ---- Oracle Instant Client + oci8 (keep only one path) ----
WORKDIR /opt/oracle
RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends wget unzip; \
    wget https://download.oracle.com/otn_software/linux/instantclient/213000/instantclient-basiclite-linux.x64-21.3.0.0.0.zip; \
    wget https://download.oracle.com/otn_software/linux/instantclient/213000/instantclient-sqlplus-linux.x64-21.3.0.0.0.zip; \
    wget https://download.oracle.com/otn_software/linux/instantclient/213000/instantclient-sdk-linux.x64-21.3.0.0.0.zip; \
    unzip instantclient-basiclite-linux.x64-21.3.0.0.0.zip; \
    unzip instantclient-sqlplus-linux.x64-21.3.0.0.0.zip; \
    unzip instantclient-sdk-linux.x64-21.3.0.0.0.zip; \
    mv /opt/oracle/instantclient_21_3 /opt/oracle/instantclient; \
    echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient.conf; \
    ldconfig; \
    rm -f *.zip

ENV LD_LIBRARY_PATH=/opt/oracle/instantclient:${LD_LIBRARY_PATH}

# Build a specific oci8 (only once)
WORKDIR /tmp/oci8
RUN pecl download oci8-3.2.1 && \
    tar xzf oci8-3.2.1.tgz && cd oci8-3.2.1 && \
    phpize && ./configure --with-oci8=instantclient,$ORACLE_HOME && \
    make -j"$(nproc)" install && docker-php-ext-enable oci8

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
