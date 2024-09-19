#!/bin/sh

# Function to handle termination signals
terminate() {
    echo "Terminating processes..."
    kill -TERM "$php_fpm_pid" 2>/dev/null
    kill -TERM "$caddy_pid" 2>/dev/null
    wait "$php_fpm_pid"
    wait "$caddy_pid"
    exit 0
}

# Trap SIGTERM signal
trap terminate TERM

if [ "$#" -gt 0 ]; then
    case "$1" in
        migrate)
            php artisan migrate
            ;;
        seed)
            php artisan db:seed
            ;;
        migrate:seed)
            php artisan migrate
            php artisan db:seed
            ;;
        *)
            echo "Unknown argument: $1"
            exit 1
            ;;
    esac
else
    php-fpm &
    php_fpm_pid=$!
    caddy run -c /app/Caddyfile &
    caddy_pid=$!
    wait "$php_fpm_pid"
    wait "$caddy_pid"
fi
