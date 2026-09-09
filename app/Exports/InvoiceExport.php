<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoiceExport implements FromView
{
    public function __construct(
        private Invoice $invoice
    ) {}

    public function view(): View
    {
        return view('invoice.excel', [
            'invoice' => $this->invoice,
        ]);
    }
}
