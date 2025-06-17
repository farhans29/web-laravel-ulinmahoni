<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // Create a new user
                // Extract username from email (everything before @)
                $email = $googleUser->getEmail();
                $username = strstr($email, '@', true) ?: $email;
                
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'username' => $username,
                    'email' => $email,
                    'password' => bcrypt(Str::random(16)), // Random password
                    // 'email_verified_at' => now(), // Mark email as verified
                ]);
            }
            
            // Log the user in
            Auth::login($user, true);
            
            return redirect()->intended('/');
            
        } catch (\Exception $e) {
            // Log the full error to the Laravel log
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            \Log::error('Error Trace: ' . $e->getTraceAsString());
            
            // Also log the request details that might be helpful
            if (isset($googleUser)) {
                \Log::info('Google User Data: ' . json_encode($googleUser));
            }
            
            // For debugging in development, you can also dd() the error
            if (config('app.debug')) {
                dd([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            return redirect()->route('login')
                ->withErrors(['error' => 'Unable to login using Google. Please try again.']);
        }
    }
}
