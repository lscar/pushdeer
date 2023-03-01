#!/bin/bash

if [ -e '/app/vendor' ]; then
  echo 'Third-party packages are installed'
else
  composer -d /app install --optimize-autoloader --no-dev
fi

if [ -e '/app/.env' ]; then
  echo 'Config file initialized'
else
  cp /app/.env.example /app/.env
  php /app/artisan cache:clear
  php /app/artisan jwt:secret --force
  php /app/artisan key:generate --force
  php /app/artisan migrate
  php /app/artisan route:cache
  php /app/artisan config:cache
  php /app/artisan view:cache
fi

if [ -e '/app/docker/push/c.pem' ]; then
  echo 'ios pem file exist'
else
  openssl pkcs12 -in /app/docker/push/c.p12 -passin pass:64wtMhU4mULj -out /app/docker/push/c.pem -passout pass:64wtMhU4mULj
fi

if [ -e '/app/docker/push/cc.pem' ]; then
  echo 'ios-clip pem file exist'
else
  openssl pkcs12 -in /app/docker/push/cc.p12 -passin pass:64wtMhU4mULj -out /app/docker/push/cc.pem -passout pass:64wtMhU4mULj
fi

if [ -e '/etc/supervisor/conf.d/supervisord-pushdeer.conf' ]; then
  echo 'supervisord file exist'
else
  cp /app/docker/supervisord.conf /etc/supervisor/conf.d/supervisord-pushdeer.conf
fi