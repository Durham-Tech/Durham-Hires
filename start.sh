#!/bin/sh

# Wait for db to be up
sleep 5

php artisan migrate --seed
php artisan serve --host 0.0.0.0
