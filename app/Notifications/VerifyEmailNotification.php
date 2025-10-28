<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
        
        // Create a request instance to check the signature
        $request = \Illuminate\Http\Request::create($url);
        
        \Log::info('Generated verification URL', [
            'user_id' => $notifiable->getKey(),
            'email' => $notifiable->getEmailForVerification(),
            'url' => $url,
            'signed' => URL::hasValidSignature($request)
        ]);
        
        return $url;
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify your email address - Ulin Mahoni')
            ->view('emails.verify-email', [
                'verificationUrl' => $verificationUrl,
                'user' => $notifiable
            ]);
    }
}
