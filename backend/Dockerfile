FROM php:8.2-fpm-alpine

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo  pdo_pgsql

EXPOSE 8081



COPY scripts/passport.sh  /usr/local/bin/passport.sh

RUN chmod +x /usr/local/bin/passport.sh
ENTRYPOINT ["/usr/local/bin/passport.sh"]

CMD ["/usr/local/bin/php", "artisan", "serve", "--host=0.0.0.0", "--port=8081"]
