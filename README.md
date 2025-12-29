# Simple E-commerce Shopping Cart

A simple e-commerce shopping cart system built with Laravel 12, React, Inertia.js, and Tailwind CSS v4.

## Features

- 🛒 **Shopping Cart** - Add, update quantities, and remove items
- 📦 **Product Catalog** - Browse products with categories
- 🔐 **User Authentication** - Secure login/register with Laravel Fortify
- 💳 **Checkout System** - Complete orders with stock validation
- 📧 **Low Stock Alerts** - Automatic email notifications when stock ≤ 5 units
- 📊 **Daily Sales Report** - Scheduled job sends daily sales summary at 23:00
- ✅ **Fully Tested** - 73+ automated tests

## Tech Stack

- **Backend:** Laravel 12, PHP 8.4
- **Frontend:** React 19, Inertia.js v2, TypeScript
- **Styling:** Tailwind CSS v4
- **Database:** MySQL 8.0
- **Queue:** Database driver
- **Email Testing:** Mailpit
- **Containerization:** Docker & Docker Compose

## Requirements

- Docker & Docker Compose
- Git

## Getting Started

### 1. Clone the repository

```bash
git clone git@github.com:hugomatheuss/trustfactory-test.git
cd trustfactory-test
```

### 2. Start the project

```bash
chmod +x start.sh
./start.sh
```

This script will automatically:
- Copy .env.example → .env
- Build and start Docker containers
- Install PHP dependencies
- Generate APP_KEY
- Run database migrations

### 3. Access the application

- **Application:** http://localhost:8000
- **Mailpit (Email UI):** http://localhost:8025

## Default Users

| Role | Email | Password  |
|------|-------|-----------|
| Admin | admin@example.com | batman123 |

> Regular users can be created via the registration page.

## Usage

### Browsing Products

1. Visit http://localhost:8000 (redirects to `/products`)
2. Browse the product catalog
3. Click on a product to view details

### Shopping Cart

1. Log in or register an account
2. Add products to cart from product pages
3. View cart at `/cart`
4. Update quantities or remove items
5. Proceed to checkout

### Checkout

1. Review your order at `/checkout`
2. Click "Complete Order"
3. View order confirmation

### Admin Dashboard

1. Log in as admin (admin@example.com)
2. Access dashboard at `/dashboard`

## Email Notifications

### Low Stock Alert
- Triggered when product stock drops to ≤ 5 units
- Sent to admin email
- View in Mailpit: http://localhost:8025

### Daily Sales Report
- Runs automatically at 23:00
- Summarizes all completed orders for the day
- Sent to admin email

### Testing Emails Manually

```bash
docker exec -it laravel_app php artisan tinker

# Test Low Stock Alert
$product = \App\Models\Product::first();
dispatch_sync(new \App\Jobs\LowStockNotificationJob($product));

# Test Daily Sales Report
dispatch_sync(new \App\Jobs\SendDailySalesReportJob());
```

Check emails at http://localhost:8025

## Running Tests

```bash
# Run all tests
docker exec laravel_app php artisan test

# Run specific test file
docker exec laravel_app php artisan test tests/Feature/CartTest.php

# Run with filter
docker exec laravel_app php artisan test --filter=checkout
```

## Queue Worker

The queue worker runs automatically via Docker. To run manually:

```bash
docker exec laravel_app php artisan queue:work
```

## Scheduler

The scheduler runs the daily sales report. To run manually:

```bash
# List scheduled tasks
docker exec laravel_app php artisan schedule:list

# Run scheduler
docker exec laravel_app php artisan schedule:run
```

## Project Structure

```
backend/
├── app/
│   ├── Http/Controllers/    # API Controllers
│   ├── Jobs/                # Queue Jobs
│   ├── Mail/                # Mailable Classes
│   ├── Models/              # Eloquent Models
│   ├── Observers/           # Model Observers
│   └── Policies/            # Authorization Policies
├── database/
│   ├── factories/           # Model Factories
│   ├── migrations/          # Database Migrations
│   └── seeders/             # Database Seeders
├── resources/js/
│   ├── components/          # React Components
│   ├── layouts/             # Page Layouts
│   └── pages/               # Inertia Pages
├── routes/
│   └── web.php              # Web Routes
└── tests/
    └── Feature/             # Feature Tests
```

## API Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/products` | List all products |
| GET | `/products/{id}` | View product details |
| GET | `/cart` | View shopping cart |
| POST | `/cart/add` | Add item to cart |
| PATCH | `/cart/items/{id}` | Update cart item quantity |
| DELETE | `/cart/items/{id}` | Remove item from cart |
| GET | `/checkout` | View checkout page |
| POST | `/checkout` | Complete order |
| GET | `/orders/{id}` | View order confirmation |

## Environment Variables

Key environment variables (configured in `.env`):

```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=laravel_mysql
QUEUE_CONNECTION=database
MAIL_HOST=mailpit
MAIL_PORT=1025
ADMIN_EMAIL=admin@example.com
```

## Troubleshooting

### Frontend changes not showing
```bash
npm run build
# Or for development with hot reload:
npm run dev
```

### Clear application cache
```bash
docker exec laravel_app php artisan config:clear
docker exec laravel_app php artisan cache:clear
docker exec laravel_app php artisan view:clear
```

### Database reset
```bash
docker exec laravel_app php artisan migrate:fresh --seed
```

### Check queue status
```bash
docker exec laravel_app php artisan queue:failed
```

