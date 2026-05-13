<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\User;
use App\Models\Restaurant;
use App\Services\PaymobPaymentGateway;
use Illuminate\Support\Facades\Log;

try {
    $gateway = new PaymobPaymentGateway();
    
    // Create a dummy order object in memory (don't save to DB just to test)
    $order = new Order();
    $order->id = 9999;
    $order->total = 100.50; // $100.50
    $order->user = User::first() ?? new User(['name' => 'Test', 'email' => 'test@example.com']);
    
    echo "Creating payment intent...\n";
    $intent = $gateway->createPaymentIntent($order);
    
    echo "Payment Intent ID: " . $intent['payment_intent_id'] . "\n";
    echo "Client Secret: " . substr($intent['client_secret'], 0, 20) . "...\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
