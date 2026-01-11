<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Send the notification via Mailtrap API
     *
     * @param mixed $notifiable
     * @return void
     */
    public function toMail($notifiable)
    {
        try {
            // Generate reset URL (use frontend_url if set, otherwise use app.url)
            $baseUrl = config('app.frontend_url', config('app.url'));
            $resetUrl = $baseUrl . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

            // Render the Blade template to HTML
            $htmlContent = view('emails.password-reset', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
                'token' => $this->token
            ])->render();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.mailtrap.api_key'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://send.api.mailtrap.io/api/send', [
                'from' => [
                    'email' => 'noreply@ulinmahoni.com',
                    'name' => 'Ulinmahoni - Password Reset'
                ],
                'to' => [
                    ['email' => $notifiable->email]
                ],
                'subject' => 'Reset Your Password - Ulinmahoni',
                'html' => $htmlContent,
                'category' => 'Password Reset'
            ]);

            if ($response->failed()) {
                Log::error('Failed to send password reset email', [
                    'user_id' => $notifiable->id,
                    'email' => $notifiable->email,
                    'error' => $response->json()
                ]);
                throw new \RuntimeException('Failed to send password reset email');
            }

            Log::info('Password reset email sent successfully', [
                'user_id' => $notifiable->id,
                'email' => $notifiable->email
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending password reset email', [
                'user_id' => $notifiable->id,
                'email' => $notifiable->email,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }

        // Return a dummy MailMessage to satisfy the interface
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $resetUrl)
            ->line('If you did not request a password reset, no further action is required.');
    }
}
