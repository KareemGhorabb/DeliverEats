<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantPageController;
use App\Http\Controllers\Api\PaymobWebhookController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [RestaurantPageController::class, 'landing'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::get('/register', fn () => view('auth.register'))->name('register');
    
    // Auth endpoints with session support
    Route::post('/api/login', [AuthController::class, 'login']);
    Route::post('/api/register', [AuthController::class, 'register']);
    
    // Social Auth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

// Logout (accessible to all, but only does something if logged in)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Payment Redirect Fallbacks
Route::get('/checkout/processed', [PaymobWebhookController::class, 'checkoutProcessed'])->name('payment.processed');
Route::get('/checkout/stripe/success/{order}', [\App\Http\Controllers\Api\StripeWebhookController::class, 'handleSuccess'])->name('stripe.success');

/*
|--------------------------------------------------------------------------
| Authenticated Web Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    Route::prefix('browse')->name('customer.')->group(function () {
        Route::get('/', [RestaurantPageController::class, 'browse'])->name('home');
        Route::get('/restaurant/{slug}', [RestaurantPageController::class, 'show'])->name('restaurant');
        Route::get('/cart', fn () => view('customer.cart'))->name('cart');
        Route::get('/checkout', fn () => view('customer.checkout'))->name('checkout');
    });

    Route::view('/discussion', 'admin.eng-haitham')->name('discussion');

    Route::prefix('orders')->name('customer.orders.')->group(function () {
        Route::get('/', fn () => view('customer.orders'))->name('index');
        Route::get('/{id}/track', fn ($id) => view('customer.track-order', ['id' => $id]))->name('track');
        Route::get('/{id}/review', fn ($id) => view('customer.review', ['id' => $id]))->name('review');
    });

    Route::get('/profile', fn () => view('customer.profile'))->name('customer.profile');

    /*
    |--------------------------------------------------------------------------
    | Restaurant Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('restaurant')->name('restaurant.')->middleware('role:restaurant_owner')->group(function () {
        Route::get('/dashboard', fn () => view('restaurant.dashboard', [
            'pendingOrders' => [],
            'weeklyBars' => [40, 70, 45, 90, 65, 85, 30]
        ]))->name('dashboard');
        Route::get('/menu', [RestaurantPageController::class, 'menu'])->name('menu');
        Route::get('/orders', fn () => view('restaurant.orders', [
            'activeOrders' => []
        ]))->name('orders');
        Route::get('/reviews', fn () => view('restaurant.reviews', [
            'reviews' => []
        ]))->name('reviews');
        Route::get('/payouts', fn () => view('restaurant.payouts', [
            'payoutOrders' => []
        ]))->name('payouts');
        Route::get('/settings', fn () => view('restaurant.settings'))->name('settings');
    });

    /*
    |--------------------------------------------------------------------------
    | Rider Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('rider')->name('rider.')->middleware('role:rider')->group(function () {
        Route::get('/dashboard', fn () => view('rider.dashboard'))->name('dashboard');
        Route::get('/delivery/{id}', fn ($id) => view('rider.delivery', ['id' => $id]))->name('delivery');
        Route::get('/earnings', fn () => view('rider.earnings'))->name('earnings');
        Route::get('/settings', fn () => view('rider.settings'))->name('settings');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
        Route::get('/control-tower', fn () => view('admin.control-tower'))->name('control-tower');
        Route::get('/users', fn () => view('admin.users'))->name('users');
        Route::get('/restaurants', fn () => view('admin.restaurants'))->name('restaurants');
        Route::get('/surge-pricing', fn () => view('admin.surge-pricing'))->name('surge-pricing');
        Route::get('/orders', fn () => view('admin.orders'))->name('orders');
        Route::get('/riders', fn () => view('admin.riders'))->name('riders');
        Route::get('/reviews', fn () => view('admin.reviews'))->name('reviews');
        Route::get('/payouts', fn () => view('admin.payouts'))->name('payouts');
        Route::get('/settings', fn () => view('admin.settings'))->name('settings');
    });
});
