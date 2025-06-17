<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth URL
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function redirectToGoogle()
    {
        return response()->json([
            'url' => Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl()
        ]);
    }

    /**
     * Handle Google OAuth callback
     * 
     * @param  \App\Http\Requests\Api\GoogleCallbackRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleGoogleCallback(GoogleCallbackRequest $request)
    {

        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // Extract username from email (everything before @)
                $email = $googleUser->getEmail();
                $username = strstr($email, '@', true) ?: $email;
                
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'username' => $username,
                    'email' => $email,
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                ]);
            }
            
            // Create API token
            $tokenResult = $user->createToken('auth-token');
            $token = $tokenResult->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged in with Google',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'profile_photo_url' => $user->profile_photo_url,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_at' => $tokenResult->accessToken->expires_at->toDateTimeString(),
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Google OAuth API Error: ' . $e->getMessage());
            Log::error('Error Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate with Google',
                'error' => config('app.debug') ? $e->getMessage() : 'Authentication failed'
            ], 401);
        }
    }

    /**
     * Logout the user (Revoke the token)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }
}
