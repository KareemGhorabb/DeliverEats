<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This application is acting as an API. Please use the /api endpoints.
|
*/

Route::get('/', function () {
    return response()->json([
        'name' => 'DeliverEats API',
        'status' => 'operational',
        'version' => '1.0.0',
        'message' => 'Please use the /api endpoints for application interaction.'
    ]);
});
