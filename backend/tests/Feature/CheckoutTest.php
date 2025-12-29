<?php

namespace Tests\Feature;

use App\Jobs\LowStockNotificationJob;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_checkout(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_checkout_with_empty_cart(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
    }

    public function test_user_can_view_checkout_page_with_items_in_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 50.00]);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('checkout/index')
            ->has('cart')
            ->where('total', 100)
        );
    }

    public function test_user_can_complete_checkout(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 50.00,
            'stock_quantity' => 10,
        ]);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 100.00,
            'status' => 'completed',
        ]);

        $this->assertDatabaseCount('cart_items', 0);

        $product->refresh();
        $this->assertEquals(8, $product->stock_quantity);
    }

    public function test_checkout_creates_order_items(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 25.00,
            'stock_quantity' => 10,
        ]);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->actingAs($user)->post(route('checkout.store'));

        $order = Order::query()->first();
        $this->assertNotNull($order);
        $this->assertDatabaseHas('orders_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'price' => 25.00,
        ]);
    }

    public function test_checkout_triggers_low_stock_notification_when_stock_crosses_threshold(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 10.00,
            'stock_quantity' => 8,
        ]);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 4,
        ]);

        $this->actingAs($user)->post(route('checkout.store'));

        Queue::assertPushed(LowStockNotificationJob::class);
    }

    public function test_checkout_does_not_trigger_low_stock_when_stock_stays_above_threshold(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 10.00,
            'stock_quantity' => 20,
        ]);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)->post(route('checkout.store'));

        Queue::assertNotPushed(LowStockNotificationJob::class);
    }
}
