<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;

try {
    $customer = User::where('email', 'customer@delivereats.com')->first();
    $owner = User::where('email', 'owner@delivereats.com')->first();
    $restaurant = Restaurant::first();

    echo "Customer placing order...\n";
    $request = Request::create('/api/v1/orders', 'POST', [
        'restaurant_id' => $restaurant->id,
        'delivery_address' => '123 Customer St',
        'latitude' => 30.0,
        'longitude' => 31.0,
        'payment_method' => 'cash',
        'items' => [
            [
                'menu_item_id' => $restaurant->menuCategories->first()->menuItems->first()->id,
                'quantity' => 2
            ]
        ]
    ]);
    $request->setUserResolver(fn() => $customer);
    $response = app(\App\Http\Controllers\Api\OrderController::class)->store($request);
    
    $result = json_decode($response->getContent(), true);
    if (!$result['success']) {
        echo "Order placement failed: " . json_encode($result) . "\n";
    } else {
        echo "Order placed successfully. ID: " . $result['data']['id'] . "\n";
    }

    echo "Owner fetching orders...\n";
    $request = Request::create('/api/v1/orders', 'GET');
    $request->setUserResolver(fn() => $owner);
    $response = app(\App\Http\Controllers\Api\OrderController::class)->index($request);
    
    $ordersResult = json_decode($response->getContent(), true);
    if (isset($ordersResult['data']['data'])) {
        echo "Found " . count($ordersResult['data']['data']) . " orders for owner.\n";
    } else {
        echo "Pagination structure issue: " . json_encode(array_keys($ordersResult['data'])) . "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
