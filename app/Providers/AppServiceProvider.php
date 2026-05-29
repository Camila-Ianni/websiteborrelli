<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cart = session('cart', ['items' => []]);
            $items = $cart['items'] ?? [];
            $count = collect($items)->sum('quantity');
            $total = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);

            $view->with('cartCount', $count);
            $view->with('cartTotal', $total);
        });
    }
}
