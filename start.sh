#!/bin/bash

set -e

echo "Starting the application..."

if [ ! -f backend/.env ]; then
  echo "Creating .env file from example..."
  cp backend/.env.example backend/.env
else
  echo ".env already exists. Skipping creation."
fi

echo "Uping Docker containers..."
docker compose -f docker/docker-compose.yml up -d --build

echo "Installing backend dependencies..."
docker compose -f docker/docker-compose.yml exec backend bash -c "cd /app && composer install"

echo "Generating application key..."
docker compose -f docker/docker-compose.yml exec backend bash -c "cd /app && php artisan key:generate"

echo "Running database migrations..."
docker compose -f docker/docker-compose.yml exec backend bash -c "cd /app && php artisan migrate"

echo "Seeding the database..."
docker compose -f docker/docker-compose.yml exec backend bash -c "cd /app && php artisan db:seed"

echo "Application started successfully!"
echo "You can access the application at http://localhost:8000"

