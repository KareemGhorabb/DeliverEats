<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Http\Request;
use App\Models\User;

try {
    $owner = User::where('email', 'owner@delivereats.com')->first();
    
    // Simulate Request to /api/restaurant/settings
    $request = Request::create('/api/restaurant/settings', 'GET');
    $request->setUserResolver(fn() => $owner);
    
    // Create an instance of RoleMiddleware and pass the request
    $middleware = new \App\Http\Middleware\RoleMiddleware();
    
    $response = $middleware->handle($request, function ($req) {
        $restaurant = $req->user()->restaurantsOwned()->first();
        return response()->json(['success' => true, 'data' => $restaurant]);
    }, 'restaurant_owner');
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Content: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
