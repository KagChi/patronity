#!/bin/bash

# Run migrations
php artisan migrate --force

# Start the application
php artisan serve --host=0.0.0.0 --port=8000