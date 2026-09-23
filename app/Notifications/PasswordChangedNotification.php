<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $userName,
        public ?string $securityUrl = null,
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $supportEmail = config('mail.from.address', 'support@example.com');
        $securityUrl = $this->securityUrl ?? url('/profile');

        $subject = 'Password Changed';
        $message = "Your password was changed successfully.\n\nSecurity link: {$securityUrl}\n\nIf you did not make this change, contact support immediately at {$supportEmail}.";
        $headers = 'From: no-reply@3asresearchsolutions.in. ';
        $headers .= 'Reply-To: support@3asresearchsolutions.in. ';
        $headers .= 'Content-Type: text/plain; charset=UTF-8. ';

        try {
            mail($notifiable->email, $subject, $message, $headers);
        } catch (\Throwable $e) {
            \Log::error('Password changed mail failed', [
                'email' => $notifiable->email,
                'error' => $e->getMessage(),
            ]);
        }

        return (new MailMessage)
            ->subject('Password Changed')
            ->line('Your password was changed successfully.');
    }
}
