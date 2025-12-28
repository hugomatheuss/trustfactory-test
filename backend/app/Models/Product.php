<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock_quantity', 'image'];

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function isLowStock(int $threshold = 5): bool
    {
        return $this->stock_quantity <= $threshold;
    }
}
