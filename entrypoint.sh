#!/bin/sh

set -e

# Run Laravel migrations
php artisan migrate --force

# Start the PHP development server
exec "$@"

