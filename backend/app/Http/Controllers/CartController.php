<?php

namespace App\Http\Controllers;

use App\Actions\Cart\AddItem;
use App\Actions\Cart\UpdateItem;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class CartController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $cart = auth()->user()->getOrCreateCart();
        $cart->load('items.product');

        return Inertia::render('cart/index', [
            'cart' => $cart,
            'total' => $cart->getTotal(),
        ]);
    }

    public function addItem(AddCartItemRequest $request, AddItem $addItem): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $addItem->handle(auth()->user(), $validated['product_id'], $validated['quantity']);
            return back()->with('success', 'Product added to cart!');
        } catch (\RuntimeException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()]);
        }
    }

    public function updateItem(UpdateCartItemRequest $request, CartItem $cartItem, UpdateItem $updateItem): RedirectResponse
    {
        $this->authorize('update', $cartItem);

        $validated = $request->validated();

        try {
            $updateItem->handle(auth()->user(), $cartItem, $validated['quantity']);

            return back()->with('success', 'Cart updated!');
        } catch (\RuntimeException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()]);
        }
    }

    public function removeItem(CartItem $cartItem): RedirectResponse
    {
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        return back()->with('success', 'Product removed from cart!');
    }
}
