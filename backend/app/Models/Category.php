<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name', 'slug'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category): void {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }

        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
