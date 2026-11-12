<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class VendorMailTemplate extends Component
{
    public function __construct(
        public string $vendorName,
        public string $vendorCompanyName,
        public string|int $vendorId,
        public string $loginEmail,
        public string $username,
        public string $loginLink,
        public string $portalUrl,
        public string $supportEmail,
        public string $companyName,
        public string $companyWebsite,
    ) {}

    public function render(): View
    {
        return view('components.vendor-mail-template');
    }
}
