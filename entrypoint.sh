#!/bin/sh

set -e

# Run Laravel migrations
php artisan migrate --force

php artisan serve --host=0.0.0.0 --port=12000

# Start the PHP development server
exec "$@"

