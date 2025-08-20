#!/bin/bash

echo "Start entrypoint file"

echo "APACHE_REMOTE_IP_HEADER: ${APACHE_REMOTE_IP_HEADER}"
echo "APACHE_REMOTE_IP_TRUSTED_PROXY: ${APACHE_REMOTE_IP_TRUSTED_PROXY}"
echo "APACHE_REMOTE_IP_INTERNAL_PROXY: ${APACHE_REMOTE_IP_INTERNAL_PROXY}"

echo "Setup TZ"
php -r "date_default_timezone_set('${TZ}');"
php -r "echo date_default_timezone_get();"

touch .env && cp -rf /vault/secrets/secrets.env /var/www/html/.env
echo "ENV_ARG: ${ENV_ARG}"

# echo "Install composer"
# composer dump-autoload

chmod 766 /var/www/html/probe-check.sh

echo "Permissions setup for NPM:"
chmod -R a+w node_modules

echo "Starting apache in the background:"
/usr/sbin/apache2ctl start

echo "Clear cache"
php artisan cache:clear


echo "End entrypoint"
while :; do
sleep 300
done
