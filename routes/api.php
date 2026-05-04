<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\RestaurantController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);

    // ============================================================
    // RESTAURANT ROUTES — Owner: Hanaa
    // ============================================================
    Route::apiResource('/v1/restaurants', RestaurantController::class);
    Route::apiResource('/v1/menu-items', MenuItemController::class);
    Route::patch('/v1/menu-items/{id}/toggle-availability', [MenuItemController::class, 'toggleAvailability']);
});

Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'profile']);

Route::middleware(['auth:sanctum', 'role:customer'])->prefix('customer')->name('api.customer.')->group(function () {
    Route::get('/home', fn () => response()->json(['message' => 'Customer Home Data']));
    Route::get('/restaurants/{slug}', fn ($slug) => response()->json(['message' => 'Restaurant Data', 'slug' => $slug]));
    Route::get('/cart', fn () => response()->json(['message' => 'Cart Data']));
    Route::post('/checkout', fn () => response()->json(['message' => 'Checkout Processing']));
    
    Route::prefix('orders')->group(function () {
        Route::get('/', fn () => response()->json(['message' => 'Customer Orders List']));
        Route::get('/{id}/track', fn ($id) => response()->json(['message' => 'Tracking Order', 'order_id' => $id]));
        Route::post('/{id}/review', fn ($id) => response()->json(['message' => 'Submit Review', 'order_id' => $id]));
    });
});

Route::middleware(['auth:sanctum', 'role:rider'])->prefix('rider')->name('api.rider.')->group(function () {
    Route::get('/dashboard', fn () => response()->json(['message' => 'Rider Dashboard Data']));
    Route::get('/delivery/{id}', fn ($id) => response()->json(['message' => 'Delivery Details', 'delivery_id' => $id]));
    Route::get('/earnings', fn () => response()->json(['message' => 'Rider Earnings Data']));
});

Route::middleware(['auth:sanctum', 'role:restaurant_owner'])->prefix('restaurant')->name('api.restaurant.')->group(function () {
    Route::get('/dashboard', fn () => response()->json(['message' => 'Restaurant Dashboard Data']));
    Route::get('/menu', fn () => response()->json(['message' => 'Restaurant Menu Data']));
    Route::get('/orders', fn () => response()->json(['message' => 'Restaurant Orders List']));
    Route::get('/reviews', fn () => response()->json(['message' => 'Restaurant Reviews']));
    Route::get('/payouts', fn () => response()->json(['message' => 'Restaurant Payouts Data']));
    Route::get('/settings', fn () => response()->json(['message' => 'Restaurant Settings Data']));
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->name('api.admin.')->group(function () {
    Route::get('/dashboard', fn () => response()->json(['message' => 'Admin Dashboard Data']));
    Route::get('/control-tower', fn () => response()->json(['message' => 'Admin Control Tower Data']));
    Route::get('/users', fn () => response()->json(['message' => 'Admin Users List']));
    Route::get('/restaurants', fn () => response()->json(['message' => 'Admin Restaurants List']));
    Route::get('/surge-pricing', fn () => response()->json(['message' => 'Admin Surge Pricing Data']));
});
