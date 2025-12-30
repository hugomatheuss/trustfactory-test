<?php

namespace App\Http\Controllers;

use App\Actions\Checkout\PlaceOrder;
use Illuminate\Http\RedirectResponse;
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

    public function store(PlaceOrder $placeOrder): RedirectResponse
    {
        try {
            $order = $placeOrder->handle(auth()->user());

            return to_route('orders.show', $order)->with('success', 'Order placed successfully!');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
