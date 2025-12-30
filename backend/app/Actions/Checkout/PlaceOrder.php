<?php

namespace App\Actions\Checkout;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Throwable;

class PlaceOrder
{
    /**
     * @throws Throwable
     */
    public function handle(User $user): Order
    {
        $cart = $user->getOrCreateCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            throw new Exception('Your cart is empty.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                throw new Exception("Insufficient stock for {$item->product->name}. Available: {$item->product->stock_quantity}");
            }
        }

        return DB::transaction(function () use ($cart, $user): Order {
            $total = $cart->items->sum(fn ($item): int|float => $item->quantity * $item->product->price);

            $order = Order::query()->create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'completed',
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $product = $item->product;
                $product->decrement('stock_quantity', $item->quantity);
            }

            $cart->items()->delete();

            return $order;
        });
    }
}

