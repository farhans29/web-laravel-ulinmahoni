<?php

use App\Http\Controllers\Api\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\Auth\SocialAuthController;
use App\Http\Controllers\Api\HealthCheckController; 

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

// Social Authentication Routes
Route::prefix('auth')->group(function () {
    // Google OAuth
    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle']);
    Route::post('/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
    
    // Logout
    Route::post('/logout', [SocialAuthController::class, 'logout'])
        ->middleware('auth:sanctum');
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User info endpoint
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
    
    // Update profile picture
    Route::post('/user/{id}/profile-picture', [AuthController::class, 'updateProfilePicture']);
});

// Health check endpoint (public, no API key required)
Route::get('health-check', [HealthCheckController::class, 'check'])
    ->withoutMiddleware(['api.key']);   

// All routes below require API key in the URL
// Format: /api/your-api-key/route

// Public authentication routes
Route::middleware('guest:sanctum')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// Protected authentication routes
Route::middleware('auth:sanctum')->group(function () {
    // Route::post('logout', [AuthController::class, 'logout']);
    // Route::get('profile', [AuthController::class, 'profile']);
    // Route::put('profile', [AuthController::class, 'updateProfile']);
    // Route::put('profile/password', [AuthController::class, 'updatePassword']);
});

// Version 1 API routes
Route::prefix('v1')->group(function () {
    // Booking availability check - public endpoint
    Route::post('check-availability', [BookingController::class, 'checkAvailability']);
    
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
// Banner API
Route::get('banner', [BannerController::class, 'index']);
Route::get('banner/{id}', [BannerController::class, 'show']);
Route::post('banner', [BannerController::class, 'store']);
Route::put('banner/{id}', [BannerController::class, 'update']);
Route::delete('banner/{id}', [BannerController::class, 'destroy']);

// Property API - these routes will be accessible at /api/your-api-key/property
Route::prefix('property')->group(function () {
    Route::get('/', [PropertyController::class, 'index']);
    Route::get('/{id}', [PropertyController::class, 'show']);
    Route::post('/', [PropertyController::class, 'store']);
    Route::put('/{id}', [PropertyController::class, 'update']);
    Route::delete('/{id}', [PropertyController::class, 'destroy']);
});

// Booking API routes
Route::prefix('booking')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::get('/{id}', [BookingController::class, 'show']);
    Route::get('/order/{order_id}', [BookingController::class, 'showByOrderId']);
    Route::get('/userId/{user_id}', [BookingController::class, 'showByUserId']);
    Route::post('/', [BookingController::class, 'store']);
    Route::post('/check-availability', [BookingController::class, 'checkAvailability']);
    Route::put('/{id}', [BookingController::class, 'update']);
    Route::post('/{id}/upload', [BookingController::class, 'uploadAttachment']);
    Route::put('/{id}/update-attachment', [BookingController::class, 'updateAttachment']);
    Route::put('/{id}/payment-method', [BookingController::class, 'updatePaymentMethod']);
    // Removed duplicate check-availability route
});

// User API routes
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

// Room API routes
Route::prefix('rooms')->group(function () {
    Route::get('/', [RoomController::class, 'index']);
    Route::get('/propertyId/{property_id}', [RoomController::class, 'byPropertyId']);
    Route::get('/{id}', [RoomController::class, 'show']);
    Route::post('/', [RoomController::class, 'store']);
    Route::put('/{id}', [RoomController::class, 'update']);
    Route::delete('/{id}', [RoomController::class, 'destroy']);
});

// Profile API routes
Route::prefix('profile')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/', [AuthController::class, 'profile']);
    Route::put('/{id}', [AuthController::class, 'updateProfile']);
    Route::put('/{id}/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/{id}/profile-picture', [AuthController::class, 'updateProfilePicture']);
});