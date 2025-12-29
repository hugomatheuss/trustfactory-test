<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function index(): Response|RedirectResponse
    {
        $cart = auth()->user()->getOrCreateCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return to_route('cart.index')->with('error', 'Your cart is empty.');
        }

        return inertia('checkout/index', [
            'cart' => $cart,
            'total' => $cart->items->sum(fn ($item): int|float => $item->quantity * $item->product->price),
        ]);
    }

    public function store(): RedirectResponse
    {
        $cart = auth()->user()->getOrCreateCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return to_route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                return back()->with('error', "Insufficient stock for {$item->product->name}. Available: {$item->product->stock_quantity}");
            }
        }

        return DB::transaction(function () use ($cart): RedirectResponse {
            $total = $cart->items->sum(fn ($item): int|float => $item->quantity * $item->product->price);

            $order = Order::query()->create([
                'user_id' => auth()->id(),
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
                $product->update([
                    'stock_quantity' => $product->stock_quantity - $item->quantity,
                ]);
            }

            $cart->items()->delete();

            return to_route('orders.show', $order)->with('success', 'Order placed successfully!');
        });
    }
}
