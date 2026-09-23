<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PartnerApiCredentialsNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $partnerName,
        public string $partnerCompanyName,
        public string $partnerId,
        public string $apiUsername,
        public string $loginEmail,
        public string $accountStatus,
        public string $activationDate,
        public string $partnerPortalUrl,
        public string $partnerLoginLink,
        public string $apiBaseUrl,
        public string $apiDocumentationUrl,
        public string $authenticationMethod,
        public string $clientId,
        public string $clientSecret,
        public string $environment,
        public string $apiVersion,
        public string $postbackUrl,
        public string $securityUrl,
        public string $supportEmail,
        public string $technicalSupportEmail,
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $companyWebsite = rtrim(config('app.url', 'https://3asresearch.com'), '/');

        return (new MailMessage)
            ->subject('Partner API Login & Integration Details – 3AS Research')
            ->view('emails.partner-api-credentials', [
                'partner_name' => $this->partnerName,
                'partner_company_name' => $this->partnerCompanyName,
                'partner_id' => $this->partnerId,
                'api_username' => $this->apiUsername,
                'login_email' => $this->loginEmail,
                'account_status' => $this->accountStatus,
                'activation_date' => $this->activationDate,
                'partner_portal_url' => $this->partnerPortalUrl,
                'partner_login_link' => $this->partnerLoginLink,
                'api_base_url' => $this->apiBaseUrl,
                'api_documentation_url' => $this->apiDocumentationUrl,
                'authentication_method' => $this->authenticationMethod,
                'client_id' => $this->clientId,
                'client_secret_or_secure_link' => $this->clientSecret,
                'production_or_sandbox' => $this->environment,
                'api_version' => $this->apiVersion,
                'postback_url' => $this->postbackUrl,
                'account_security_link' => $this->securityUrl,
                'support_email' => $this->supportEmail,
                'technical_support_email' => $this->technicalSupportEmail,
                'company_name' => config('app.name', '3AS Research'),
                'company_website' => $companyWebsite,
            ]);
    }
}
