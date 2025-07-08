<?php

use App\Http\Controllers\Api\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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

// Booking API
Route::get('booking', [BookingController::class, 'index']);
Route::get('booking/{id}', [BookingController::class, 'show']);
Route::get('booking/order/{order_id}', [BookingController::class, 'showByOrderId']);
Route::get('booking/userId/{user_id}', [BookingController::class, 'showByUserId']);
Route::post('booking', [BookingController::class, 'store']);
Route::get('booking/check-availability', [BookingController::class, 'checkAvailability']);
Route::put('booking/{id}', [BookingController::class, 'update']);
Route::post('booking/{id}/upload', [BookingController::class, 'uploadAttachment']);
Route::put('booking/{id}/update-attachment', [BookingController::class, 'updateAttachment']);
Route::put('booking/{id}/payment-method', [BookingController::class, 'updatePaymentMethod']);
Route::post('booking/check-availability', [BookingController::class, 'checkAvailability']);

// User API
Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);

// Room API
Route::get('rooms', [RoomController::class, 'index']);
Route::get('rooms/propertyId/{property_id}', [RoomController::class, 'byPropertyId']);
Route::get('rooms/{id}', [RoomController::class, 'show']);
Route::post('rooms', [RoomController::class, 'store']);
Route::put('rooms/{id}', [RoomController::class, 'update']);
Route::delete('rooms/{id}', [RoomController::class, 'destroy']);

// Profile API
Route::post('logout', [AuthController::class, 'logout']);
Route::get('profile', [AuthController::class, 'profile']);
Route::put('profile/{id}', [AuthController::class, 'updateProfile']);
Route::put('profile/{id}/update-password', [AuthController::class, 'updatePassword']);
Route::post('profile/{id}/profile-picture', [AuthController::class, 'updateProfilePicture']);