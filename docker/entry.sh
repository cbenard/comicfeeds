#!/bin/sh

# Ideas from https://stackoverflow.com/a/37016878/448

# Start cron
crond -f -l 8 &

# Do initial fetch
/fetch.sh 2>&1 &

# Serve PHP Web
if [ "$SELF_SERVE" = "1" ]; then
    exec php -S 0.0.0.0:8080 -t /app/web
else
    exec php-fpm
fi
