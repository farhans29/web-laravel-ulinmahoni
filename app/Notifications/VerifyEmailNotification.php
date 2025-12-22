<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;

class VerifyEmailNotification extends BaseVerifyEmail implements ShouldQueue
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
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        // Plain text version (kept for fallback)
        $message = "Dear Valued Customer,\n\nThank you for registering with Ulinmahoni. To complete your registration and verify your email address, please click on the following link:\n\n{$verificationUrl}\n\nThis link will expire in 24 hours for security purposes.\n\nIf you did not create an account with Ulinmahoni, please disregard this email.\n\nBest regards,\nThe Ulinmahoni Team";

        // Render the Blade template to HTML
        $htmlContent = view('emails.verify-email', [
            'user' => $notifiable,
            'verificationUrl' => $verificationUrl
        ])->render();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.mailtrap.api_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(env('MAIL_HOST'), [
            'from' => [
                'email' => 'noreply@ulinmahoni.com',
                'name' => 'Ulinmahoni - Account Registration'
            ],
            'to' => [
                ['email' => $notifiable->getEmailForVerification()]
            ],
            'subject' => 'Ulinmahoni - Account Registration',
            'html' => $htmlContent,
            // 'text' => $message,
            'category' => 'Ulinmahoni - Account Registration'
        ]);

        if ($response->failed()) {
            Log::error('Failed to send verification email', [
                'user_id' => $notifiable->getKey(),
                'email' => $notifiable->getEmailForVerification(),
                'error' => $response->json()
            ]);
        }

        // Return a dummy MailMessage to satisfy the interface
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->line('Please verify your email address by clicking the button below.')
            ->action('Verify Email Address', $verificationUrl);
    }
}
