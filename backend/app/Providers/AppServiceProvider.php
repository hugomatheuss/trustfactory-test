<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Observers\ProductObserver;
use App\Policies\CartItemPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(CartItem::class, CartItemPolicy::class);

        Gate::define('accessDashboard', fn (User $user): bool => $user->is_admin);

        Product::observe(ProductObserver::class);
    }
}
