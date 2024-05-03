#!/bin/sh
set -e

/usr/local/bin/php artisan migrate
/usr/local/bin/php artisan backend-passport:install --password  -n

# Run upstream entrypoint

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

exec "$@"
