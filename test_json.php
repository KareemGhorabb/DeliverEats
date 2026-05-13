<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('email', 'owner@delivereats.com')->first();
$restaurant = $user->restaurantsOwned()->first();
echo json_encode(['data' => $restaurant]);
