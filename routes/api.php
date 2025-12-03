<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\Auth\SocialAuthController;
use App\Http\Controllers\Api\HealthCheckController;
use App\Http\Controllers\Api\DokuServiceController as DokuController;

use App\Http\Middleware\VerifyApiKey;

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

// Public routes (no API key required)
Route::get('health-check', [HealthCheckController::class, 'check']);


// API routes that require a valid API key in the X-API-KEY header
// Protected routes that require both API key and authentication



// ===== V1 API ROUTES =====
// Version 1 API routes (require API key but not necessarily authentication)

Route::prefix('v1')->group(function () {
    
    // AUTH ROUTES WITHOUT PREFIX AUTH
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('resend-verification', [AuthController::class, 'resendVerification']);
    
    // AUTH API ROUTES
    Route::prefix('auth')->group(function () {
        // GOOGLE ( UNUSED, COMMENTED FOR REVIEW )
        // Route::get('/google', [SocialAuthController::class, 'redirectToGoogle']);
        // Route::post('/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
        // Route::middleware('auth:sanctum')->post('/logout', [SocialAuthController::class, 'logout']);

        // Public authentication endpoints
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('resend-verification', [AuthController::class, 'resendVerification']);
    });

    // ROUTES THAT REQUIRE API KEY (MIDDLEWARE)
    Route::middleware([VerifyApiKey::class])->group(function () {

        // BANNER API ROUTES
        Route::prefix('banner')->group(function () {
            Route::get('/', [BannerController::class, 'index']);
            Route::get('/{id}', [BannerController::class, 'show']);
            Route::post('/', [BannerController::class, 'store']);
            Route::put('/{id}', [BannerController::class, 'update']);
            Route::delete('/{id}', [BannerController::class, 'destroy']);
        });

        // PROPERTY API ROUTES
        Route::prefix('property')->group(function () {
            Route::get('/', [PropertyController::class, 'index']);
            Route::get('/{id}', [PropertyController::class, 'show']);
            Route::post('/', [PropertyController::class, 'store']);
            Route::put('/{id}', [PropertyController::class, 'update']);
            Route::delete('/{id}', [PropertyController::class, 'destroy']);
        });

        // BOOKING API ROUTES
        Route::prefix('booking')->group(function () {
            Route::get('/', [BookingController::class, 'index']);
            Route::get('/{id}', [BookingController::class, 'show']);
            Route::get('/order/{order_id}', [BookingController::class, 'showByOrderId']);
            Route::get('/userId/{user_id}', [BookingController::class, 'showByUserId']);
            Route::post('/', [BookingController::class, 'store']);
            Route::post('/check-availability', [BookingController::class, 'checkAvailability'])->name('api.booking.check-availability');
            Route::put('/{id}', [BookingController::class, 'update']);
            Route::post('/{id}/upload', [BookingController::class, 'uploadAttachment']);
            Route::put('/{id}/update-attachment', [BookingController::class, 'updateAttachment']);
            Route::put('/{id}/payment-method', [BookingController::class, 'updatePaymentMethod']);
            // Removed duplicate check-availability route
        });

        // USER API ROUTES
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('/', [UserController::class, 'store']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
            Route::post('/{id}/deactivate', [UserController::class, 'deactivate']);
        });

        // ROOM API ROUTES
        Route::prefix('rooms')->group(function () {
            Route::get('/', [RoomController::class, 'index']);
            Route::get('/propertyId/{property_id}', [RoomController::class, 'byPropertyId']);
            Route::get('/{id}', [RoomController::class, 'show']);
            Route::post('/', [RoomController::class, 'store']);
            Route::put('/{id}', [RoomController::class, 'update']);
            Route::delete('/{id}', [RoomController::class, 'destroy']);
        });

        // PROFILE API ROUTES
        Route::prefix('profile')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/', [AuthController::class, 'profile']);
            Route::put('/{id}', [AuthController::class, 'updateProfile']);
            Route::put('/{id}/update-password', [AuthController::class, 'updatePassword']);
            Route::post('/{id}/profile-picture', [AuthController::class, 'updateProfilePicture']);
        });

        // COMMENTED FOR REVIEW 
        // NOTIFICATIONS API ROUTES
        // Route::prefix('notifications')->group(function () {
        //     Route::get('/', [NotificationController::class, 'getNotifications']);
        //     Route::post('/payment', [NotificationController::class, 'paymentNotifications']);
        //     Route::post('/mark-as-read', [NotificationController::class, 'markNotificationsAsRead']);
        // });

        // COMMENTED FOR REVIEW
        // DOKU API ROUTES
        // Route::prefix('doku')->withoutMiddleware(['api.key'])->middleware('App\Http\Middleware\DokuHeaderMiddleware')->group(function () {
        //     Route::post('/transfer-va/payment', [NotificationController::class, 'dokuPaymentNotification']);
        //     Route::post('/qr-mpm/payment', [NotificationController::class, 'dokuQRPaymentNotification']); 
        // });        
    });
}); 
// END OF V1 API ROUTES
// END OF VerifyApiKey middleware group`

// DOKU API ROUTES ( DO NOT COMMENT THIS PART OF CODE )
// DOKU SNAP API
Route::prefix('')->middleware('App\Http\Middleware\DokuHeaderMiddleware')->group(function () {
    Route::post('v1/transfer-va/payment', [DokuController::class, 'dokuPaymentNotification']);
});

// DOKU NON SNAP API
Route::prefix('')->middleware('App\Http\Middleware\DokuNonSnapHeaderMiddleware')->group(function () {
    Route::post('v1/qr-mpm/payment', [DokuController::class, 'dokuQRPaymentNotification']); 
});
// DOKU B2B API
Route::prefix('')->middleware('App\Http\Middleware\DokuB2BHeaderMiddleware')->group(function () {
    Route::post('/authorization/v1/access-token/b2b', [DokuController::class, 'dokuGetTokenB2B']);
});
// END OF DOKU API ROUTES

// ===== LEGACY API ROUTES =====
// Legacy API routes (if needed for backward compatibility)
// These will be accessible without the v1 prefix

// SOME OF THE CODES BELOWS ARE COMMENTED FOR REVIEW REASONS
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::post('resend-verification', [AuthController::class, 'resendVerification']);

// AUTH API ROUTES 
// Route::prefix('auth')->group(function () {
//     // GOOGLE ( UNUSED, COMMENTED FOR REVIEW )
//     // Route::get('/google', [SocialAuthController::class, 'redirectToGoogle']);
//     // Route::post('/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
//     // Route::middleware('auth:sanctum')->post('/logout', [SocialAuthController::class, 'logout']);
    
//     // Public authentication endpoints
//     Route::post('register', [AuthController::class, 'register']);
//     Route::post('login', [AuthController::class, 'login']);
//     Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
//     Route::post('reset-password', [AuthController::class, 'resetPassword']);
// });

// BANNER API ROUTES
// Route::get('banner', [BannerController::class, 'index']);
// Route::get('banner/{id}', [BannerController::class, 'show']);
// Route::post('banner', [BannerController::class, 'store']);
// Route::put('banner/{id}', [BannerController::class, 'update']);
// Route::delete('banner/{id}', [BannerController::class, 'destroy']);

// // Property API - these routes will be accessible at /api/your-api-key/property
// Route::prefix('property')->group(function () {
//     Route::get('/', [PropertyController::class, 'index']);
//     Route::get('/{id}', [PropertyController::class, 'show']);
//     Route::post('/', [PropertyController::class, 'store']);
//     Route::put('/{id}', [PropertyController::class, 'update']);
//     Route::delete('/{id}', [PropertyController::class, 'destroy']);
// });

// // Booking API routes
// Route::prefix('booking')->group(function () {
//     Route::get('/', [BookingController::class, 'index']);
//     Route::get('/{id}', [BookingController::class, 'show']);
//     Route::get('/order/{order_id}', [BookingController::class, 'showByOrderId']);
//     Route::get('/userId/{user_id}', [BookingController::class, 'showByUserId']);
//     Route::post('/', [BookingController::class, 'store']);
//     Route::post('/check-availability', [BookingController::class, 'checkAvailability'])->name('api.booking.check-availability');
//     Route::put('/{id}', [BookingController::class, 'update']);
//     Route::post('/{id}/upload', [BookingController::class, 'uploadAttachment']);
//     Route::put('/{id}/update-attachment', [BookingController::class, 'updateAttachment']);
//     Route::put('/{id}/payment-method', [BookingController::class, 'updatePaymentMethod']);
//     // Removed duplicate check-availability route
// });

// // User API routes
// Route::prefix('users')->group(function () {
//     Route::get('/', [UserController::class, 'index']);
//     Route::get('/{id}', [UserController::class, 'show']);
//     Route::post('/', [UserController::class, 'store']);
//     Route::put('/{id}', [UserController::class, 'update']);
//     Route::delete('/{id}', [UserController::class, 'destroy']);
//     Route::post('/{id}/deactivate', [UserController::class, 'deactivate']);
// });

// // Room API routes
// Route::prefix('rooms')->group(function () {
//     Route::get('/', [RoomController::class, 'index']);
//     Route::get('/propertyId/{property_id}', [RoomController::class, 'byPropertyId']);
//     Route::get('/{id}', [RoomController::class, 'show']);
//     Route::post('/', [RoomController::class, 'store']);
//     Route::put('/{id}', [RoomController::class, 'update']);
//     Route::delete('/{id}', [RoomController::class, 'destroy']);
// });

// // Profile API routes
// Route::prefix('profile')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/', [AuthController::class, 'profile']);
//     Route::put('/{id}', [AuthController::class, 'updateProfile']);
//     Route::put('/{id}/update-password', [AuthController::class, 'updatePassword']);
//     Route::post('/{id}/profile-picture', [AuthController::class, 'updateProfilePicture']);
// });

// Route::prefix('notifications')->group(function () {
//     Route::get('/', [NotificationController::class, 'getNotifications']);
//     Route::post('/payment', [NotificationController::class, 'paymentNotifications']);
//     Route::post('/mark-as-read', [NotificationController::class, 'markNotificationsAsRead']);
// });
// Route::prefix('doku')->withoutMiddleware(['api.key'])->middleware('App\Http\Middleware\DokuHeaderMiddleware')->group(function () {
//     Route::post('/transfer-va/payment', [NotificationController::class, 'dokuPaymentNotification']);
//     Route::post('/qr-mpm/payment', [NotificationController::class, 'dokuQRPaymentNotification']); 
// });

// END OF LEGACY API ROUTES

// Route::middleware('auth:sanctum')->group(function () {
//     // User info endpoint
//     Route::get('/user', function (Request $request) {
//         return response()->json($request->user());
//     });
    
//     // Profile routes
//     Route::prefix('profile')->group(function () {
//         Route::get('/', [AuthController::class, 'profile']);
//         Route::put('/', [AuthController::class, 'updateProfile']);
//         Route::put('/password', [AuthController::class, 'updatePassword']);
//         Route::post('/profile-picture', [AuthController::class, 'updateProfilePicture']);
//     });
    
//     // Update profile picture
//     Route::post('/user/{id}/profile-picture', [AuthController::class, 'updateProfilePicture']);
    
//     // Booking API routes
//     Route::prefix('booking')->group(function () {
//         Route::get('/', [BookingController::class, 'index']);
//         Route::get('/{id}', [BookingController::class, 'show']);
//         Route::get('/order/{order_id}', [BookingController::class, 'showByOrderId']);
//         Route::get('/userId/{user_id}', [BookingController::class, 'showByUserId']);
//         Route::post('/', [BookingController::class, 'store']);
//         Route::post('/check-availability', [BookingController::class, 'checkAvailability'])->name('api.booking.check-availability');
//         Route::put('/{id}', [BookingController::class, 'update']);
//         Route::post('/{id}/upload', [BookingController::class, 'uploadAttachment']);
//         Route::put('/{id}/update-attachment', [BookingController::class, 'updateAttachment']);
//         Route::put('/{id}/payment-method', [BookingController::class, 'updatePaymentMethod']);
//     });
// }); // End of auth:sanctum middleware group