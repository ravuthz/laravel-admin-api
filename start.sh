#!/bin/bash

# ls -lah

export APP_URL=https://laravel-admin-api.onrender.com

export APP_ENV=dev
export APP_NAME=API
export APP_DEBUG=true
export APP_TIMEZONE=UTC

export LOG_LEVEL=debug
export LOG_STACK=daily
export LOG_CHANNEL=syslog

export CACHE_STORE=file
export DB_CONNECTION=sqlite

printenv

composer install --no-dev --quiet --no-progress --no-suggest --no-interaction --optimize-autoloader

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan optimize:clear

php artisan optimize

php artisan storage:link
