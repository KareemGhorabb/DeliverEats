<?php

use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| OAuth Social Login Routes (Google, GitHub)
|--------------------------------------------------------------------------
| These routes must live in web.php because Socialite performs browser-based
| redirects. The callback handler returns a JSON Sanctum token for the frontend.
*/
Route::middleware('guest')->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])
         ->where('provider', 'google|github')
         ->name('auth.social.redirect');

    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
         ->where('provider', 'google|github')
         ->name('auth.social.callback');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('landing'))->name('home');

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
    Route::get('/', fn () => view('customer.home'))->name('home');
    Route::get('/restaurant/{slug}', fn ($slug) => view('customer.restaurant', ['slug' => $slug]))->name('restaurant');
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
