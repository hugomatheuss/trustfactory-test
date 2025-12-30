<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateItem
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, CartItem $cartItem, int $quantity): CartItem
    {
        $cart = $user->getOrCreateCart();

        if ($cartItem->cart_id !== $cart->id) {
            throw new \RuntimeException('Unauthorized cart item.');
        }

        return DB::transaction(function () use ($cartItem, $quantity) {
            $lockedItem = CartItem::where('id', $cartItem->id)->lockForUpdate()->first();

            if (! $lockedItem) {
                throw new \RuntimeException('Cart item not found.');
            }

            $lockedItem->load('product');

            if ($quantity <= 0) {
                throw new \RuntimeException('Quantity must be at least 1.');
            }

            if ($quantity > $lockedItem->product->stock_quantity) {
                throw new \RuntimeException('Requested quantity exceeds available stock.');
            }

            $lockedItem->quantity = $quantity;
            $lockedItem->save();

            return $lockedItem->refresh();
        });
    }
}

