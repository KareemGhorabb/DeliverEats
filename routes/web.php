<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('landing'))->name('home');
Route::get('/login', fn () => view('auth.login'))->name('login');
Route::get('/register', fn () => view('auth.register'))->name('register');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('browse')->name('customer.')->group(function () {
    Route::get('/', fn () => view('customer.home'))->name('home');
    Route::get('/restaurant/{slug}', fn ($slug) => view('customer.restaurant', ['slug' => $slug]))->name('restaurant');
    Route::get('/cart', fn () => view('customer.cart'))->name('cart');
    Route::get('/checkout', fn () => view('customer.checkout'))->name('checkout');
});

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
    Route::get('/dashboard', fn () => view('restaurant.dashboard'))->name('dashboard');
    Route::get('/menu', fn () => view('restaurant.menu'))->name('menu');
    Route::get('/orders', fn () => view('restaurant.orders'))->name('orders');
    Route::get('/reviews', fn () => view('restaurant.reviews'))->name('reviews');
    Route::get('/payouts', fn () => view('restaurant.payouts'))->name('payouts');
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
