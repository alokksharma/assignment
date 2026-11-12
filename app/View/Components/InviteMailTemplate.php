<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class InviteMailTemplate extends Component
{
    public function __construct(
        public string $userName,
        public string $userEmail,
        public string $userRole,
        public string $loginLink,
        public string $portalUrl,
        public string $supportEmail,
        public string $companyName,
        public string $companyWebsite,
    ) {}

    public function render(): View
    {
        return view('components.invite-mail-template');
    }
}
