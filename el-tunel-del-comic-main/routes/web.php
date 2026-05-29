<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Admin\ComicController as AdminComicController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

// Language Switch
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalog
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{comic}', [CatalogController::class, 'show'])->name('catalog.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{comic}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{comic}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{comic}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/failure/{order}', [CheckoutController::class, 'failure'])->name('checkout.failure');
Route::get('/checkout/pending/{order}', [CheckoutController::class, 'pending'])->name('checkout.pending');
Route::get('/checkout/paypal/success', [CheckoutController::class, 'paypalSuccess'])->name('checkout.paypal.success');
Route::get('/checkout/paypal/cancel', [CheckoutController::class, 'paypalCancel'])->name('checkout.paypal.cancel');

// Webhooks (no CSRF)
Route::post('/webhooks/mercadopago', [WebhookController::class, 'mercadopago'])
    ->name('webhooks.mercadopago')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Admin - Protected
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('comics', AdminComicController::class);
    Route::post('comics/bulk-action', [AdminComicController::class, 'bulkAction'])->name('comics.bulk-action');
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});

// Breeze Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
