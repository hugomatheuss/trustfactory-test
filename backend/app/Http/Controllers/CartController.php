<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
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

    public function addItem(AddCartItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = Product::query()->findOrFail($validated['product_id']);
        $cart = auth()->user()->getOrCreateCart();

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];

            if ($newQuantity > $product->stock_quantity) {
                return back()->withErrors(['quantity' => 'Requested quantity exceeds available stock.']);
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            if ($validated['quantity'] > $product->stock_quantity) {
                return back()->withErrors(['quantity' => 'Requested quantity exceeds available stock.']);
            }

            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function updateItem(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorize('update', $cartItem);

        $validated = $request->validated();

        if ($validated['quantity'] > $cartItem->product->stock_quantity) {
            return back()->withErrors(['quantity' => 'Requested quantity exceeds available stock.']);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', 'Cart updated!');
    }

    public function removeItem(CartItem $cartItem): RedirectResponse
    {
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        return back()->with('success', 'Product removed from cart!');
    }
}
