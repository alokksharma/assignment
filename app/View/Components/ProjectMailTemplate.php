<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ProjectMailTemplate extends Component
{
    public function __construct(
        public string $projectName,
        public string $clientName,
        public string $projectCode,
        public string $projectUrl,
        public string $supplierId,
        public string $supplierToken,
        public string $status,
        public string $supportEmail,
        public string $companyName,
        public string $companyWebsite,
    ) {}

    public function render(): View
    {
        return view('components.project-mail-template');
    }
}
