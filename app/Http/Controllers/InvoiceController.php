<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\InvoiceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function export($id)
    {
        $invoice = Invoice::with([
            'customer',
            'items'
        ])->findOrFail($id);

        return Excel::download(
            new InvoiceExport($invoice),
            'invoice-' . $invoice->invoice_number . '.xlsx'
        );
    }

    public function create()
    {
        $customers = User::get();
        return view('exports.invoice_create', compact('customers'));
    }

    public function show($id)
    {
         $invoice = Invoice::with([
            'customer',
            'items'
        ])->findOrFail($id);
        return view('exports.show', [
            'invoice' => $invoice,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],

            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {

            $subtotal = collect($validated['items'])->sum(function ($item) {
                return $item['quantity'] * $item['unit_price'];
            });

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;

            $total = $subtotal - $discount + $tax;

            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . date('YmdHis'),
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'] ?? null,

                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,

                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];

                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax' => 0,
                    'discount' => 0,
                    'total' => $itemTotal,
                ]);
            }
        });

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }
}
