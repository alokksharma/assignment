<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PartnerMailTemplate extends Component
{
    public function __construct(
        public string $partnerName,
        public string $partnerCompanyName,
        public string|int $partnerId,
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
        public string $clientSecretOrSecureLink,
        public string $productionOrSandbox,
        public string $apiVersion,
        public string $postbackUrl,
        public string $accountSecurityLink,
        public string $supportEmail,
        public string $technicalSupportEmail,
        public string $companyName,
        public string $companyWebsite,
    ) {}

    public function render(): View
    {
        return view('components.partner-mail-template');
    }
}
