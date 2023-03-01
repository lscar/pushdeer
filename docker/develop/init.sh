#!/bin/bash

if [ -e '/app/vendor' ]; then
  echo 'Third-party packages are installed'
else
  composer -d /app install
fi

if [ -e '/app/.env' ]; then
  echo 'Config file initialized'
else
  cd /app
  cp .env.example .env
  php artisan jwt:secret --force
  php artisan key:generate
  php artisan migrate --force
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