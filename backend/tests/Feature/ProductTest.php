<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_products_list(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('products/index')
            ->has('products.data', 3)
        );
    }

    public function test_products_list_is_paginated(): void
    {
        Product::factory()->count(20)->create();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('products.data')
            ->has('products.links')
        );
    }

    public function test_anyone_can_view_single_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 99.99,
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('products/show')
            ->has('product')
            ->where('product.name', 'Test Product')
        );
    }

    public function test_product_shows_low_stock_indicator(): void
    {
        $product = Product::factory()->create([
            'stock_quantity' => 3,
        ]);

        $this->assertTrue($product->isLowStock(5));
    }

    public function test_product_does_not_show_low_stock_when_above_threshold(): void
    {
        $product = Product::factory()->create([
            'stock_quantity' => 10,
        ]);

        $this->assertFalse($product->isLowStock(5));
    }

    public function test_authenticated_user_can_add_product_to_cart_from_product_page(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $response = $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }
}
