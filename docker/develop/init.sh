#!/bin/bash

if [ -e '/app/vendor' ]; then
  echo 'Third-party packages are installed'
else
  composer -d /app install
fi

if [ -e '/app/.env' ]; then
  echo 'Config file initialized'
else
  cp /app/.env.example /app/.env
  # 设置秘钥
  php /app/artisan jwt:secret --force
  php /app/artisan key:generate
  # 数据迁移
  php /app/artisan migrate --force
fi

# 清理优化
php /app/artisan optimize:clear

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

enable-supervisord-program.sh pushdeer