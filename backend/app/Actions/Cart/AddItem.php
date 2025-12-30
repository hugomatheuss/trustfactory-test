<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class AddItem
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, int $productId, int $quantity): CartItem
    {
        $product = Product::query()->findOrFail($productId);

        return DB::transaction(function () use ($user, $product, $quantity) {
            $cart = $user->getOrCreateCart();
            $cart->load('items.product');

            $cartItem = $cart->items()->where('product_id', $product->id)->lockForUpdate()->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;

                if ($newQuantity > $product->stock_quantity) {
                    throw new \RuntimeException('Requested quantity exceeds available stock.');
                }

                $cartItem->update(['quantity' => $newQuantity]);

                return $cartItem->refresh();
            }

            if ($quantity > $product->stock_quantity) {
                throw new \RuntimeException('Requested quantity exceeds available stock.');
            }

            return $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        });
    }
}

