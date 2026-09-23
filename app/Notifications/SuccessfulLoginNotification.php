<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuccessfulLoginNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $userName,
        public string $loginDate,
        public string $loginTime,
        public string $timeZone,
        public string $ipAddress,
        public string $location,
        public string $deviceBrowser,
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
        $companyWebsite = rtrim(config('app.url', 'https://3asresearch.com'), '/');

        return (new MailMessage)
            ->subject('New Login to Your 3AS Research Account')
            ->view('emails.successful-login', [
                'user_name' => $this->userName ?: ($notifiable->name ?? 'User'),
                'login_date' => $this->loginDate,
                'login_time' => $this->loginTime,
                'time_zone' => $this->timeZone,
                'ip_address' => $this->ipAddress,
                'location' => $this->location,
                'device_browser' => $this->deviceBrowser,
                'account_security_link' => $this->securityUrl ?? url('/profile'),
                'support_email' => $supportEmail,
                'company_website' => $companyWebsite,
                'company_name' => config('app.name', '3AS Research'),
            ]);
    }
}
