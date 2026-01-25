#!/bin/bash

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
  echo "Generating application key..."
  APP_KEY=$(php artisan key:generate --show)
  echo "APP_KEY=$APP_KEY" >> .env
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

echo "Deployment completed successfully!"