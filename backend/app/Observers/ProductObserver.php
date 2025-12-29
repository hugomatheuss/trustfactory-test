<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if (! $product->wasChanged('stock_quantity')) {
            return;
        }

        $oldStock = $product->getOriginal('stock_quantity');
        $newStock = $product->stock_quantity;
        $threshold = 5;

        if ($oldStock > $threshold && $newStock <= $threshold && $newStock > 0) {
            dispatch(new \App\Jobs\LowStockNotificationJob($product));
        }
    }
}
