<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);
});

Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'profile']);

Route::middleware(['auth:sanctum','role:customer'])->group(function () {
    // Customer routes go here
});

Route::middleware(['auth:sanctum','role:rider'])->group(function () {
    // Rider routes go here
});

Route::middleware(['auth:sanctum','role:restaurant_owner'])->group(function () {
    // Restaurant Owner routes go here
});

Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    // Admin routes go here
});
