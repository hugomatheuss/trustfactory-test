<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productsByCategory = [
            'electronics' => [
                ['name' => 'Samsung Galaxy S24 Smartphone', 'description' => 'Smartphone with 6.2" AMOLED display, 128GB', 'price' => 999.99, 'stock_quantity' => 50],
                ['name' => 'Dell Inspiron 15 Laptop', 'description' => 'Laptop Intel Core i5, 8GB RAM, 256GB SSD', 'price' => 749.00, 'stock_quantity' => 30],
                ['name' => 'LG 55" Smart TV', 'description' => 'Smart TV 4K UHD, WebOS, HDR10', 'price' => 599.00, 'stock_quantity' => 20],
                ['name' => 'JBL Bluetooth Headphones', 'description' => 'Wireless headphones with noise cancellation', 'price' => 149.90, 'stock_quantity' => 100],
                ['name' => 'Apple iPad 10.2" Tablet', 'description' => 'Tablet with A13 Bionic chip, 64GB', 'price' => 449.00, 'stock_quantity' => 25],
            ],
            'clothing' => [
                ['name' => 'Basic Cotton T-Shirt', 'description' => '100% cotton t-shirt, various colors', 'price' => 19.90, 'stock_quantity' => 200],
                ['name' => 'Slim Fit Jeans', 'description' => 'Men\'s slim fit denim jeans', 'price' => 59.90, 'stock_quantity' => 80],
                ['name' => 'Floral Summer Dress', 'description' => 'Women\'s printed dress, lightweight fabric', 'price' => 49.90, 'stock_quantity' => 60],
                ['name' => 'Hooded Sweatshirt', 'description' => 'Unisex hoodie, soft fabric', 'price' => 69.90, 'stock_quantity' => 70],
                ['name' => 'Men\'s Formal Blazer', 'description' => 'Slim fit blazer for formal occasions', 'price' => 149.90, 'stock_quantity' => 40],
            ],
            'footwear' => [
                ['name' => 'Nike Air Max Sneakers', 'description' => 'Sports sneakers with Air cushioning', 'price' => 179.90, 'stock_quantity' => 45],
                ['name' => 'Classic Flip Flops', 'description' => 'Traditional comfortable sandals', 'price' => 14.90, 'stock_quantity' => 300],
                ['name' => 'Leather Dress Shoes', 'description' => 'Men\'s genuine leather shoes', 'price' => 129.90, 'stock_quantity' => 35],
                ['name' => 'Women\'s Knee-High Boots', 'description' => 'Synthetic leather boots with heel', 'price' => 99.90, 'stock_quantity' => 50],
                ['name' => 'Adidas Slide Sandals', 'description' => 'Comfortable slides for casual wear', 'price' => 39.90, 'stock_quantity' => 120],
            ],
            'accessories' => [
                ['name' => 'Xiaomi Smartwatch', 'description' => 'Smartwatch with heart rate monitor', 'price' => 149.90, 'stock_quantity' => 60],
                ['name' => 'Ray-Ban Aviator Sunglasses', 'description' => 'Classic aviator style sunglasses', 'price' => 179.90, 'stock_quantity' => 40],
                ['name' => 'Women\'s Leather Handbag', 'description' => 'Shoulder bag in synthetic leather', 'price' => 79.90, 'stock_quantity' => 55],
                ['name' => 'Men\'s Leather Wallet', 'description' => 'Leather wallet with card slots', 'price' => 39.90, 'stock_quantity' => 90],
                ['name' => 'Genuine Leather Belt', 'description' => 'Men\'s 100% leather belt', 'price' => 34.90, 'stock_quantity' => 100],
            ],
            'home-decor' => [
                ['name' => 'Queen Size Bedding Set', 'description' => '200 thread count bedding set, 4 pieces', 'price' => 79.90, 'stock_quantity' => 40],
                ['name' => 'LED Desk Lamp', 'description' => 'Lamp with adjustable brightness', 'price' => 49.90, 'stock_quantity' => 70],
                ['name' => 'Abstract Wall Art', 'description' => 'Canvas print 24x16 inches', 'price' => 39.90, 'stock_quantity' => 50],
                ['name' => 'Non-Stick Cookware Set', 'description' => '5-piece non-stick pan set', 'price' => 129.90, 'stock_quantity' => 30],
                ['name' => 'Shaggy Area Rug', 'description' => 'Plush rug 60x80 inches', 'price' => 99.90, 'stock_quantity' => 25],
            ],
            'sports' => [
                ['name' => 'Mountain Bike', 'description' => '29" wheel bike with 21 speeds', 'price' => 399.00, 'stock_quantity' => 15],
                ['name' => 'Adjustable Dumbbell Set 45lb', 'description' => 'Pair of adjustable dumbbells', 'price' => 129.90, 'stock_quantity' => 40],
                ['name' => 'Official Soccer Ball', 'description' => 'FIFA standard match ball', 'price' => 49.90, 'stock_quantity' => 80],
                ['name' => 'Electric Treadmill', 'description' => 'Foldable treadmill up to 7.5mph', 'price' => 599.00, 'stock_quantity' => 10],
                ['name' => 'Wilson Tennis Racket', 'description' => 'Professional strung racket', 'price' => 149.90, 'stock_quantity' => 20],
            ],
            'books' => [
                ['name' => 'The Lord of the Rings - Box Set', 'description' => 'Box set with all 3 books of the saga', 'price' => 49.90, 'stock_quantity' => 50],
                ['name' => 'Clean Code', 'description' => 'A Handbook of Agile Software Craftsmanship - Robert C. Martin', 'price' => 39.90, 'stock_quantity' => 60],
                ['name' => 'Harry Potter - Complete Collection', 'description' => 'Box set with all 7 books', 'price' => 99.90, 'stock_quantity' => 35],
                ['name' => 'Sapiens - A Brief History of Humankind', 'description' => 'Yuval Noah Harari', 'price' => 24.90, 'stock_quantity' => 70],
                ['name' => 'The Little Prince', 'description' => 'Special illustrated edition', 'price' => 14.90, 'stock_quantity' => 100],
            ],
            'beauty' => [
                ['name' => 'Imported Perfume 100ml', 'description' => 'Women\'s Eau de Parfum', 'price' => 89.90, 'stock_quantity' => 45],
                ['name' => 'Facial Skincare Kit', 'description' => '5-product skincare set', 'price' => 69.90, 'stock_quantity' => 55],
                ['name' => 'Professional Hair Dryer', 'description' => '2200W dryer with diffuser', 'price' => 79.90, 'stock_quantity' => 40],
                ['name' => 'Makeup Palette', 'description' => '18-color eyeshadow palette', 'price' => 34.90, 'stock_quantity' => 80],
                ['name' => 'Body Moisturizing Cream', 'description' => '13.5oz moisturizer for dry skin', 'price' => 19.90, 'stock_quantity' => 120],
            ],
            'toys' => [
                ['name' => 'LEGO Star Wars Millennium Falcon', 'description' => 'Set with 1351 pieces', 'price' => 169.90, 'stock_quantity' => 20],
                ['name' => 'Barbie Fashionista Doll', 'description' => 'Doll with fashion accessories', 'price' => 24.90, 'stock_quantity' => 60],
                ['name' => 'Hot Wheels 20-Pack', 'description' => 'Pack with 20 assorted cars', 'price' => 29.90, 'stock_quantity' => 50],
                ['name' => 'Monopoly Board Game', 'description' => 'Classic family board game', 'price' => 34.90, 'stock_quantity' => 40],
                ['name' => 'Giant Teddy Bear 40"', 'description' => 'Soft plush teddy bear', 'price' => 59.90, 'stock_quantity' => 30],
            ],
            'food-beverages' => [
                ['name' => 'Imported Chocolate Gift Box', 'description' => 'Box with 15 premium chocolates', 'price' => 49.90, 'stock_quantity' => 40],
                ['name' => 'Gourmet Coffee Kit', 'description' => 'Kit with 3 specialty coffees 8oz each', 'price' => 39.90, 'stock_quantity' => 60],
                ['name' => 'Portuguese Extra Virgin Olive Oil', 'description' => 'Premium olive oil 16.9oz', 'price' => 24.90, 'stock_quantity' => 80],
                ['name' => 'Red Wine Case', 'description' => 'Case with 6 selected wines', 'price' => 149.90, 'stock_quantity' => 25],
                ['name' => 'Specialty Tea Collection', 'description' => 'Box with 50 assorted tea bags', 'price' => 19.90, 'stock_quantity' => 70],
            ],
        ];

        $categories = Category::all()->keyBy('slug');

        foreach ($productsByCategory as $categorySlug => $products) {
            $category = $categories->get($categorySlug);

            if (! $category) {
                continue;
            }

            foreach ($products as $productData) {
                $product = Product::query()->create($productData);
                $product->categories()->attach($category->id);
            }
        }
    }
}
