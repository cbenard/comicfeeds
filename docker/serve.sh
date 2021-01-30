#!/bin/sh

# Serve PHP Web
if [ "$SELF_SERVE" = "1" ]; then
    exec php -S 0.0.0.0:8080 -t /app/web
else
    exec php-fpm
fi
