#!/usr/bin/env bash

_term() {
  echo "Caught SIGTERM signal!"
  kill -TERM "$child"
  exit 0
}

trap _term SIGTERM

# Start PHP
if [ -d /var/log/php ]; then
    chmod -R 777 /var/log/php;
fi
mkdir -p /run/php/;
php-fpm7.2 -F -R &

child=$!
wait "$child"