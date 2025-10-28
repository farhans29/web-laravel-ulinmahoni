<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Send the email verification notification.
     *
     * @param  mixed  $notifiable
     * @return void
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.mailtrap.api_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://send.api.mailtrap.io/api/send', [
            'from' => [
                'email' => 'noreply@ulinmahoni.com',
                'name' => 'Ulinmahoni - Account Registration'
            ],
            'to' => [
                ['email' => $notifiable->getEmailForVerification()]
            ],
            'subject' => 'Ulinmahoni - Account Registration',
            'text' => "Please click the following link to verify your email: {$verificationUrl}",
            'category' => 'Ulinmahoni - Account Registration'
        ]);

        if ($response->failed()) {
            Log::error('Failed to send verification email', [
                'user_id' => $notifiable->getKey(),
                'email' => $notifiable->getEmailForVerification(),
                'error' => $response->json()
            ]);
        }
        
        return null; // Return null since we're handling the email send directly
    }
}
