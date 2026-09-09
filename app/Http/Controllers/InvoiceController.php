<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\InvoiceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices =  Invoice::with([
            'supplier',
            'items'
        ])->paginate(15);

         return view('invoice.index', compact('invoices'));
    }

    public function export($id)
    {
        $invoice = Invoice::with([
            'supplier',
            'items'
        ])->findOrFail($id);

        return Excel::download(
            new InvoiceExport($invoice),
            'invoice-' . $invoice->invoice_number . '.xlsx'
        );
    }

    public function create()
    {
        $suppliers = Project::where('status', 1)->get();
        $invoice = Invoice::latest()->first();
        $billingTypes = [
            'Api Usages',
            'Custom',
        ];

        return view('invoice.create', compact('suppliers', 'invoice', 'billingTypes'));
    }

    public function show($id)
    {
         $invoice = Invoice::with([
            'supplier',
            'items'
        ])->findOrFail($id);
        return view('invoice.show', [
            'invoice' => $invoice,
        ]);
    }

    public function downloadInvoice($id)
    {
         $invoice = Invoice::with([
            'supplier',
            'items'
        ])->findOrFail($id);
        $pdf = Pdf::loadView('invoice.excel',  ['invoice' => $invoice]);
        return $pdf->download('invoice.pdf');

    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'invoice_date' => ['required', 'date'],
    //         'due_date' => ['nullable', 'date'],

    //         'items' => ['required', 'array', 'min:1'],
    //         'items.*.description' => ['required', 'string'],
    //         'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
    //         'items.*.unit_price' => ['required', 'numeric', 'min:0'],

    //         'discount' => ['nullable', 'numeric', 'min:0'],
    //         'tax' => ['nullable', 'numeric', 'min:0'],
    //         'notes' => ['nullable', 'string'],
    //     ]);

    //     DB::transaction(function () use ($validated) {

    //         $subtotal = collect($validated['items'])->sum(function ($item) {
    //             return $item['quantity'] * $item['unit_price'];
    //         });

    //         $discount = $validated['discount'] ?? 0;
    //         $tax = $validated['tax'] ?? 0;

    //         $total = $subtotal - $discount + $tax;

    //         $invoice = Invoice::create([
    //             'invoice_number' => 'INV-' . date('YmdHis'),
    //             'invoice_date' => $validated['invoice_date'],
    //             'due_date' => $validated['due_date'] ?? null,

    //             'subtotal' => $subtotal,
    //             'discount' => $discount,
    //             'tax' => $tax,
    //             'total' => $total,

    //             'status' => 'pending',
    //             'notes' => $validated['notes'] ?? null,
    //         ]);

    //         foreach ($validated['items'] as $item) {
    //             $itemTotal = $item['quantity'] * $item['unit_price'];

    //             $invoice->items()->create([
    //                 'description' => $item['description'],
    //                 'quantity' => $item['quantity'],
    //                 'unit_price' => $item['unit_price'],
    //                 'tax' => 0,
    //                 'discount' => 0,
    //                 'total' => $itemTotal,
    //             ]);
    //         }
    //     });

    //     return redirect()
    //         ->route('invoices.show')
    //         ->with('success', 'Invoice created successfully.');
    // }

    public function store(Request $request)
    {
        if (empty($request->due_date) && $request->invoice_date) {
            $request->merge([
                'due_date' => \Carbon\Carbon::parse($request->invoice_date)->addDays(90)->format('Y-m-d'),
            ]);
        }
        $validated = $request->validate([
           // 'invoice_no'        => 'required|string|unique:invoices,invoice_number',
            'invoice_date'      => 'required|date',
            'due_date'          => 'required|date',
            'currency'          => 'required|string',
            'status'            => 'required|string',
            'service_location'  => 'required|in:within_india_up,within_india_outside_up,outside_india',
          //  'client_name'       => 'required|string',
      //      'billing_address'   => 'nullable|string',
            'tax_id'            => 'nullable|string',
            'supplier_id'           => 'nullable|string',
            'discount'          => 'nullable|numeric',
            'amount_paid'       => 'nullable|numeric',
            'notes'             => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.description'  => 'required|string',
            'items.*.billing_type' => 'nullable|string',
            'items.*.quantity'      => 'required|numeric',
            'items.*.unit_price'    => 'required|numeric',
        ]);

        DB::transaction(function () use ($validated) {
            $subtotal = collect($validated['items'])->sum(fn ($item) => $item['quantity'] * $item['unit_price']);
            $discount = $validated['discount'] ?? 0;
            $taxable  = $subtotal - $discount;

            $cgst = $sgst = $igst = $otherTax = 0;

            if ($validated['service_location'] === 'within_india_up') {
                $cgst = round($taxable * 0.09, 2);
                $sgst = round($taxable * 0.09, 2);
            } elseif ($validated['service_location'] === 'within_india_outside_up') {
                $igst = round($taxable * 0.18, 2);
            }
            // outside_india -> other_tax stays 0 unless you have a rule for it

            $total = $taxable + $cgst + $sgst + $igst + $otherTax;
            $amountPaid = $validated['amount_paid'] ?? 0;

            $invoice = Invoice::create([
               // 'invoice_number'       => $validated['invoice_no'],
                'invoice_date'     => $validated['invoice_date'],
                'due_date'         => $validated['due_date'],
                'currency'         => $validated['currency'],
                'status'           => $validated['status'],
                'service_location' => $validated['service_location'],
                'supplier_id' => $validated['supplier_id'],
            //    'client_name'      => $validated['client_name'],
           //     'billing_address'  => $validated['billing_address'] ?? null,
                'tax'           => $validated['tax_id'] ?? null,
             //   'project'          => $validated['project'] ?? null,
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'cgst'             => $cgst,
                'sgst'             => $sgst,
                'igst'             => $igst,
                'other_tax'        => $otherTax,
                'total'            => $total,
                'amount_paid'      => $amountPaid,
                'balance_due'      => $total - $amountPaid,
                'notes'            => $validated['notes'] ?? null,
            ]);
            $year = now()->format('Y');
            $invoice->update([
                'invoice_number' => sprintf('3AS-INV-%s-%05d', $year, $invoice->id),
            ]);
            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description'  => $item['description'],
                    'billing_type' => $item['billing_type'] ?? null,
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'amount'       => $item['quantity'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }
}
