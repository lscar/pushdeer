FROM phpswoole/swoole:5.0-php8.1

COPY ./docker/product/init.sh /usr/local/boot/boot.sh
COPY ./docker/supervisord.conf /etc/supervisor/available.d/pushdeer.conf

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN set -ex \
    && chmod +x /usr/local/bin/install-php-extensions \
    && IPE_ICU_EN_ONLY=1 install-php-extensions intl pcntl