<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantPageController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [RestaurantPageController::class, 'landing'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::get('/register', fn () => view('auth.register'))->name('register');
});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('browse')->name('customer.')->group(function () {
    Route::get('/', [RestaurantPageController::class, 'browse'])->name('home');
    Route::get('/restaurant/{slug}', [RestaurantPageController::class, 'show'])->name('restaurant');
    Route::get('/cart', fn () => view('customer.cart'))->name('cart');
    Route::get('/checkout', fn () => view('customer.checkout'))->name('checkout');
});

// For now, these views don't enforce auth in web.php since auth is handled by the API.
// The frontend Javascript will be responsible for sending the API tokens and protecting these pages.
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
Route::prefix('restaurant')->name('restaurant.')->group(function () {
    Route::get('/dashboard', function () {
        $mock = app(\App\Services\MockDataService::class);
        return view('restaurant.dashboard', [
            'pendingOrders' => array_values($mock->pendingOrders()),
            'weeklyBars'    => [40, 65, 55, 80, 70, 90, 60],
        ]);
    })->name('dashboard');
    Route::get('/menu', [RestaurantPageController::class, 'menu'])->name('menu');
    Route::get('/orders', function () {
        $mock = app(\App\Services\MockDataService::class);
        return view('restaurant.orders', [
            'activeOrders' => array_values($mock->activeOrders()),
        ]);
    })->name('orders');
    Route::get('/reviews', function () {
        $mock = app(\App\Services\MockDataService::class);
        return view('restaurant.reviews', [
            'reviews' => $mock->orders(), // use orders that are delivered as reviewable
        ]);
    })->name('reviews');
    Route::get('/payouts', function () {
        $mock = app(\App\Services\MockDataService::class);
        $delivered = array_filter($mock->orders(), fn($o) => $o['status'] === 'delivered');
        return view('restaurant.payouts', [
            'payoutOrders' => array_values($delivered),
        ]);
    })->name('payouts');
    Route::get('/settings', fn () => view('restaurant.settings'))->name('settings');
});

/*
|--------------------------------------------------------------------------
| Rider Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('rider')->name('rider.')->group(function () {
    Route::get('/dashboard', fn () => view('rider.dashboard'))->name('dashboard');
    Route::get('/delivery/{id}', fn ($id) => view('rider.delivery', ['id' => $id]))->name('delivery');
    Route::get('/earnings', fn () => view('rider.earnings'))->name('earnings');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
    Route::get('/control-tower', fn () => view('admin.control-tower'))->name('control-tower');
    Route::get('/users', fn () => view('admin.users'))->name('users');
    Route::get('/restaurants', fn () => view('admin.restaurants'))->name('restaurants');
    Route::get('/surge-pricing', fn () => view('admin.surge-pricing'))->name('surge-pricing');
});
