#!/bin/bash

set -e

COMPOSE_FILE="docker/docker-compose.yml"

echo "Starting the application..."

if [ ! -f backend/.env ]; then
  echo "Creating .env file from example..."
  cp backend/.env.example backend/.env
else
  echo ".env already exists. Skipping creation."
fi

echo "Uping Docker containers..."
docker compose -f $COMPOSE_FILE up -d --build

echo "Installing backend dependencies..."
docker compose -f $COMPOSE_FILE exec app composer install

echo "Generating application key..."
docker compose -f $COMPOSE_FILE exec app php artisan key:generate

echo "Running database migrations..."
docker compose -f $COMPOSE_FILE exec app php artisan migrate

echo "Seeding the database..."
docker compose -f $COMPOSE_FILE exec app php artisan db:seed

echo "Application started successfully!"
echo "You can access the application at http://localhost:8000"
echo "Mailpit: http://localhost:8025"

