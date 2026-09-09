<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorLoginDetailsNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $vendorName,
        public string $vendorCompanyName,
        public string $vendorId,
        public string $loginEmail,
        public string $username,
        public string $portalUrl,
        public string $loginLink,
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
            ->subject('Vendor Portal Login Details – 3AS Research')
            ->view('emails.vendor-login-details', [
                'vendor_name' => $this->vendorName,
                'vendor_company_name' => $this->vendorCompanyName,
                'vendor_id' => $this->vendorId,
                'login_email' => $this->loginEmail,
                'username' => $this->username,
                'vendor_portal_url' => $this->portalUrl,
                'vendor_login_link' => $this->loginLink,
                'support_email' => $supportEmail,
                'company_website' => $companyWebsite,
                'company_name' => config('app.name', '3AS Research'),
            ]);
    }
}
