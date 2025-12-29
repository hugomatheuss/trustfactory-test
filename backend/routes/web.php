<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn (): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse => to_route('products.index'))->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('dashboard', fn () => Inertia::render('dashboard'))->name('dashboard');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'updateItem'])->name('cart.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

require __DIR__.'/settings.php';
