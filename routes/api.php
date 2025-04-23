<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PropertyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Health check endpoint (no version prefix)
Route::get('health-check', function () {
    return response()->json(['status' => 'ok']);
});

// Version 1 API routes
Route::prefix('v1')->group(function () {
    // Banner routes - using resource for cleaner structure
    Route::apiResource('banners', BannerController::class);
    
    // If you need custom banner routes
    Route::get('banners/active', [BannerController::class, 'getActive']);
    Route::put('banners/{banner}/status', [BannerController::class, 'updateStatus']);
    
    // Future resource endpoints would go here
    // Route::apiResource('products', ProductController::class);
    // Route::apiResource('categories', CategoryController::class);
});

// Legacy API routes (if needed for backward compatibility)
// These will be accessible without the v1 prefix
Route::get('banner', [BannerController::class, 'index']);
Route::get('banner/{id}', [BannerController::class, 'show']);
Route::post('banner', [BannerController::class, 'store']);
Route::put('banner/{id}', [BannerController::class, 'update']);
Route::delete('banner/{id}', [BannerController::class, 'destroy']);

Route::get('property', [PropertyController::class, 'index']);
Route::get('property/{id}', [PropertyController::class, 'show']);
Route::post('property', [PropertyController::class, 'store']);
Route::put('property/{id}', [PropertyController::class, 'update']);
Route::delete('property/{id}', [PropertyController::class, 'destroy']);