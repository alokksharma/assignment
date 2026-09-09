<table style="width:100%; border-collapse:collapse; font-family:Arial, sans-serif; font-size:12px;">

    {{-- Header --}}
    <tr>
        <td colspan="4" style="background-color:#1F3864; color:#FFFFFF; font-style:italic; font-size:11px; padding:8px 15px; vertical-align:middle;">
            Billing From
        </td>
		
        <td colspan="4" style="background-color:#1F3864; color:#FFFFFF; font-size:22px; font-weight:bold; text-align:right; padding:8px 15px;">
            3AS RESEARCH
        </td>
    </tr>
    <tr>
        <td colspan="4" style="background-color:#1F3864; color:#FFFFFF; padding:0 15px 10px 15px;">Address<br> GSTIN: | Other:</td>
        <td colspan="4" style="background-color:#1F3864; color:#FFFFFF; font-style:italic; font-size:11px; text-align:right; padding:0 15px 10px 15px;">
            Growing Through Insights
        </td>
    </tr>

    {{-- Address block --}}
    <tr>
        <td colspan="8" style="background-color:#FFFFFF; color:#1F3864; font-weight:bold; padding:10px 15px 0 15px;">
         <!--   Address | Jaipur, Rajasthan, India-->
        </td>
    </tr>
    <tr>
        <td colspan="8" style="background-color:#FFFFFF; color:#1F3864; font-weight:bold; padding:0 15px 12px 15px;">
          <!--  GSTIN: {{ $invoice->tax ?? 'XXXXXX' }} | Other: --->
        </td>
    </tr>

    {{-- INVOICE title --}}
    <tr>
        <td colspan="8" style="background-color:#FFFFFF; text-align:center; padding:10px 0;">
            <span style="color:#1F3864; font-size:26px; font-weight:bold;">INVOICE</span>
        </td>
    </tr>

    <tr><td colspan="8" style="padding:4px;"></td></tr>

    {{-- Invoice Details grid --}}
    <tr>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Invoice No.</td>
        <td colspan="2" style="padding:4px 15px; border-bottom:1px solid #ccc;">{{ $invoice->invoice_number }}</td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Currency</td>
        <td colspan="2" style="padding:4px 15px; border-bottom:1px solid #ccc;">{{ $invoice->currency }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Invoice Date</td>
        <td colspan="2" style="padding:4px 15px; border-bottom:1px solid #ccc;">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Status</td>
        <td colspan="2" style="padding:4px 15px; border-bottom:1px solid #ccc;">{{ $invoice->status }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Due Date</td>
        <td colspan="2" style="padding:4px 15px; border-bottom:1px solid #ccc;">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d-M-Y') }}</td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Service</td>
        <td colspan="2" style="padding:4px 15px; border-bottom:1px solid #ccc;">
            {{ $invoice->service_location === 'outside_india' ? 'Outside India' : 'Within India' }}
        </td>
    </tr>

    <tr><td colspan="8" style="padding:6px;"></td></tr>

    {{-- BILL TO bar --}}
    <tr>
        <td colspan="8" style="background-color:#1F3864; color:#FFFFFF; font-weight:bold; text-align:center; padding:6px;">
            BILL TO
        </td>
    </tr>

    <tr>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Client Name</td>
        <td colspan="6" style="padding:4px 15px;">{{ $invoice->supplier?->client_name ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Billing Address</td>
        <td colspan="6" style="padding:4px 15px;">{{ $invoice->supplier?->billing_address ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Tax ID / GSTIN</td>
        <td colspan="6" style="padding:4px 15px;">{{ $invoice->tax   ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Project</td>
        <td colspan="6" style="padding:4px 15px;">{{ $invoice->supplier?->name ?? '' }}</td>
    </tr>

    <tr><td colspan="8" style="padding:6px;"></td></tr>

    {{-- SERVICES / API BILLING bar --}}
    <tr>
        <td colspan="8" style="background-color:#1F3864; color:#FFFFFF; font-weight:bold; padding:6px 15px;">
			<center>SERVICES / API BILLING</center>
        </td>
    </tr>

    {{-- Items header --}}
    <tr>
        <td style="font-weight:bold; padding:4px 15px; border-bottom:2px solid #1F3864;">SNo. #</td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px; border-bottom:2px solid #1F3864;">Description</td>
        <td style="font-weight:bold; padding:4px 15px; border-bottom:2px solid #1F3864;">Billing Type</td>
        <td style="font-weight:bold; padding:4px 15px; border-bottom:2px solid #1F3864;">Quantity</td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px; border-bottom:2px solid #1F3864;">Unit Price</td>
        <td style="font-weight:bold; padding:4px 15px; border-bottom:2px solid #1F3864; text-align:right;">Amount</td>
    </tr>

    {{-- Items --}}
    @foreach($invoice->items as $index => $item)
        <tr>
            <td style="padding:4px 15px;">{{ $index + 1 }}</td>
            <td colspan="2" style="padding:4px 15px;">{{ $item->description }}</td>
            <td style="padding:4px 15px;">{{ $item->billing_type ?? '' }}</td>
            <td style="padding:4px 15px;">{{ number_format($item->quantity, 2) }}</td>
            <td colspan="2" style="padding:4px 15px;">{{ number_format($item->unit_price, 2) }}</td>
            <td style="padding:4px 15px; text-align:right;">{{ number_format($item->amount, 2) }}</td>
        </tr>
    @endforeach

    <tr><td colspan="8" style="padding:6px;"></td></tr>

    {{-- Totals box, right aligned --}}
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Subtotal</td>
        <td style="padding:4px 15px; text-align:right;">{{ number_format($invoice->subtotal, 2) }}</td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Discount</td>
        <td style="padding:4px 15px; text-align:right;">{{ number_format($invoice->discount, 2) }}</td>
    </tr>
    @if ($invoice->service_location !== 'outside_india')
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">CGST</td>
        <td style="padding:4px 15px; text-align:right;">{{ number_format($invoice->cgst, 2) }}</td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">SGST</td>
        <td style="padding:4px 15px; text-align:right;">{{ number_format($invoice->sgst, 2) }}</td>
    </tr>
     @if ($invoice->service_location !== 'within_india_outside_up')
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">IGST</td>
        <td style="padding:4px 15px; text-align:right;">{{ number_format($invoice->igst, 2) }}</td>
    </tr>
    @endif

    @else
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Other Tax</td>
        <td style="padding:4px 15px; text-align:right;">{{ number_format($invoice->other_tax, 2) }}</td>
    </tr>
    @endif
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="background-color:#D9EAF7; font-weight:bold; padding:4px 15px;">TOTAL</td>
        <td style="background-color:#D9EAF7; font-weight:bold; padding:4px 15px; text-align:right;">{{ number_format($invoice->total, 2) }}</td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="font-weight:bold; padding:4px 15px;">Amount Paid</td>
        <td style="padding:4px 15px; text-align:right;">{{ number_format($invoice->amount_paid, 2) }}</td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="background-color:#D9EAF7; font-weight:bold; padding:4px 15px;">BALANCE DUE</td>
        <td style="background-color:#D9EAF7; font-weight:bold; padding:4px 15px; text-align:right;">{{ number_format($invoice->balance_due, 2) }}</td>
    </tr>

    <tr><td colspan="8" style="padding:8px;"></td></tr>

    {{-- Payment terms & notes --}}
    <tr>
        <td colspan="8" style="background-color:#1F3864; color:#FFFFFF; font-weight:bold; padding:6px 15px;">
            PAYMENT TERMS &amp; NOTES
        </td>
    </tr>
    <tr>
        <td colspan="8" style="padding:8px 15px;">
            {{ $invoice->notes }}
        </td>
    </tr>

    <tr><td colspan="8" style="padding:10px;"></td></tr>

    <tr>
        <td colspan="8" style="text-align:center; font-style:italic; font-size:11px; color:#6b7280; padding:6px;">
            This invoice is generated electronically by 3AS Research.
        </td>
    </tr>

</table>
