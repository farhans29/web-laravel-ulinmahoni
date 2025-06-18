<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            // Check for error in the OAuth response
            if ($request->has('error')) {
                throw new \Exception($request->error);
            }

            // Get the user from Google
            $googleUser = Socialite::driver('google')->user();
            
            if (!$googleUser->getEmail()) {
                throw new \Exception('No email returned from Google');
            }
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // Create a new user
                $email = $googleUser->getEmail();
                $username = strstr($email, '@', true) ?: $email;
                
                $user = User::create([
                    'name' => $googleUser->getName() ?: $username,
                    'username' => $username,
                    'email' => $email,
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                ]);
            }
            
            // Log the user in
            Auth::login($user, true);
            
            return redirect()->intended('/');
            
        } catch (\Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage());
            Log::error('Error Trace: ' . $e->getTraceAsString());
            
            $errorMessage = 'Unable to login using Google. ';
            
            // Add more specific error messages based on the exception
            if (str_contains($e->getMessage(), 'Invalid state')) {
                $errorMessage .= 'Session expired. Please try again.';
            } elseif (str_contains($e->getMessage(), 'Invalid verification code')) {
                $errorMessage .= 'Invalid verification code. Please try again.';
            } else {
                $errorMessage .= $e->getMessage();
            }
            
            return redirect()->route('login')
                ->withErrors(['error' => $errorMessage]);
        }
    }
}
