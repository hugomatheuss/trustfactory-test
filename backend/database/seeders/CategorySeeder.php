<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Footwear', 'slug' => 'footwear'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
            ['name' => 'Home & Decor', 'slug' => 'home-decor'],
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Books', 'slug' => 'books'],
            ['name' => 'Beauty', 'slug' => 'beauty'],
            ['name' => 'Toys', 'slug' => 'toys'],
            ['name' => 'Food & Beverages', 'slug' => 'food-beverages'],
        ];

        foreach ($categories as $category) {
            Category::query()->create($category);
        }
    }
}
