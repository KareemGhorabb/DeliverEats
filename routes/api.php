<?php

use App\Http\Controllers\Api\PaymobWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PayoutController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\RiderController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Public surge check (for browse page dynamic badge)
Route::get('/v1/surge/{restaurantId}', function (int $restaurantId) {
    $multiplier = app(\App\Services\SurgeService::class)->getCurrentMultiplier($restaurantId);
    return response()->json(['success' => true, 'data' => ['multiplier' => $multiplier]]);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
// External Webhooks
Route::post('/v1/webhooks/paymob', [PaymobWebhookController::class, 'handleCallback']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/user', fn (Request $request) => $request->user());

    // ──────────────────────────────────────────────
    // RESTAURANT & MENU MANAGEMENT
    // ──────────────────────────────────────────────
    Route::apiResource('/v1/restaurants', RestaurantController::class);
    Route::apiResource('/v1/menu-items', MenuItemController::class);
    Route::patch('/v1/menu-items/{id}/toggle-availability', [MenuItemController::class, 'toggleAvailability']);

    // ──────────────────────────────────────────────
    // USERS (Admin)
    // ──────────────────────────────────────────────
    Route::apiResource('/v1/users', UserController::class);

    // ──────────────────────────────────────────────
    // ORDERS (all roles, role-filtered in controller)
    // ──────────────────────────────────────────────
    Route::prefix('v1/orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::patch('/{id}/status', [OrderController::class, 'updateStatus']);
        Route::post('/{id}/cancel', [OrderController::class, 'cancel']);
        Route::get('/{id}/history', [OrderController::class, 'history']);
    });

    // ──────────────────────────────────────────────
    // REVIEWS
    // ──────────────────────────────────────────────
    Route::post('/v1/orders/{orderId}/review', [ReviewController::class, 'store']);
    Route::get('/v1/restaurants/{restaurantId}/reviews', [ReviewController::class, 'restaurantReviews']);

    // ──────────────────────────────────────────────
    // PAYOUTS (role-aware in controller)
    // ──────────────────────────────────────────────
    Route::get('/v1/payouts', [PayoutController::class, 'index']);

    // ──────────────────────────────────────────────
    // CUSTOMER ROUTES
    // ──────────────────────────────────────────────
    Route::middleware('role:customer')->prefix('customer')->name('api.customer.')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
    });

    // ──────────────────────────────────────────────
    // RIDER ROUTES
    // ──────────────────────────────────────────────
    Route::middleware('role:rider')->prefix('rider')->name('api.rider.')->group(function () {
        Route::get('/dashboard', [RiderController::class, 'dashboard']);
        Route::post('/location', [RiderController::class, 'updateLocation']);
        Route::get('/earnings', [RiderController::class, 'earnings']);
        Route::get('/delivery/{id}', [RiderController::class, 'delivery']);
        
        // Order lifecycle endpoints
        Route::get('/orders/available', [RiderController::class, 'availableOrders']);
        Route::post('/orders/{id}/accept', [RiderController::class, 'acceptOrder']);
        Route::patch('/orders/{id}/pickup', [RiderController::class, 'pickupOrder']);
        Route::patch('/orders/{id}/deliver', [RiderController::class, 'deliverOrder']);
        Route::get('/orders/history', [RiderController::class, 'history']);
    });

    // ──────────────────────────────────────────────
    // RESTAURANT OWNER ROUTES
    // ──────────────────────────────────────────────
    Route::middleware('role:restaurant_owner')->prefix('restaurant')->name('api.restaurant.')->group(function () {
        Route::get('/dashboard', function (Request $request) {
            $restaurant = $request->user()->restaurantsOwned()->first();
            if (! $restaurant) {
                return response()->json(['success' => false, 'message' => 'No restaurant found.'], 404);
            }
            $activeOrders = \App\Models\Order::where('restaurant_id', $restaurant->id)
                ->active()->with(['user', 'items.menuItem'])->latest()->get();
            $todayOrders = \App\Models\Order::where('restaurant_id', $restaurant->id)
                ->whereDate('created_at', today())->count();
            $earnings = app(\App\Services\PayoutService::class)->getRestaurantEarnings($restaurant->id);
            
            // Re-fetch restaurant to get latest avg_rating
            $restaurant->refresh();

            return response()->json([
                'success' => true,
                'data' => compact('restaurant', 'activeOrders', 'todayOrders', 'earnings'),
            ]);
        });
        
        // Debugging Helper: Create a test order for this restaurant
        Route::post('/test-order', function (Request $request) {
            $user = $request->user();
            $restaurant = $user->restaurantsOwned()->first();
            if (!$restaurant) return response()->json(['success' => false, 'message' => 'No restaurant found.']);
            
            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'restaurant_id' => $restaurant->id,
                'status' => \App\Enums\OrderStatus::Pending,
                'subtotal' => 100,
                'delivery_fee' => 15,
                'surge_multiplier' => 1.0,
                'tax' => 14,
                'total' => 129,
                'delivery_address' => 'Test Street, Cairo',
            ]);
            
            // Create a dummy item
            $menuItem = $restaurant->menuItems()->first();
            if ($menuItem) {
                $order->items()->create([
                    'menu_item_id' => $menuItem->id,
                    'quantity' => 1,
                    'unit_price' => $menuItem->price,
                    'total_price' => $menuItem->price,
                ]);
            }
            
            return response()->json(['success' => true, 'order_id' => $order->id, 'status' => $order->status]);
        });

        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/reviews', function (Request $request) {
            $restaurant = $request->user()->restaurantsOwned()->first();
            return app(ReviewController::class)->restaurantReviews($restaurant?->id ?? 0);
        });
        Route::get('/payouts', function (Request $request) {
            $restaurant = $request->user()->restaurantsOwned()->first();
            $earnings = app(\App\Services\PayoutService::class)->getRestaurantEarnings($restaurant?->id ?? 0);
            return response()->json(['success' => true, 'data' => $earnings]);
        });
        Route::get('/settings', function (Request $request) {
            $restaurant = $request->user()->restaurantsOwned()->first();
            return response()->json(['success' => true, 'data' => $restaurant]);
        });
    });

    // ──────────────────────────────────────────────
    // ADMIN ROUTES
    // ──────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('api.admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/control-tower', [AdminController::class, 'controlTower']);
        Route::get('/restaurants', [AdminController::class, 'restaurants']);
        Route::get('/surge-pricing', [AdminController::class, 'surgePricing']);
        Route::post('/surge-pricing/override', [AdminController::class, 'setSurgeOverride']);
        Route::get('/reviews', [AdminController::class, 'reviews']);
        Route::post('/payouts/{id}/mark-paid', [PayoutController::class, 'markPaid']);
    });
});
