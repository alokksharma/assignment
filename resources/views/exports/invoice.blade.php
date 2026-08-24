  <table class="table table-bordered">
    <tr>
        <td colspan="6"
            style="
                background-color:#4d7cfe;
                color:#FFFFFF;
                font-size:22px;
                font-weight:bold;
                text-align:center;
                height:40px;
            ">
            INVOICE
        </td>
    </tr>

     <tr>
        <td colspan="5"></td>

        <td
            style="
                background-color:#ffffff;
                font-weight:bold;
                color:#1F4E78;
            ">
            BILLING ADDRESS: <br> <br>
            Address 508, TRIMURTY VIJAI-CITY POINT,
            AHINSA CIRCLE,
            ASHOK MARG, C-SCHEME, C Scheme,
            <br> Jaipur-302001,
            Rajasthan,
            India
        </td>
    </tr>
    <tr>
        <td colspan="3"
            style="
                background-color:#D9EAF7;
                font-weight:bold;
            ">
            Invoice Details
        </td>

        <td colspan="3"
            style="
                background-color:#D9EAF7;
                font-weight:bold;
            ">
            Customer Details
        </td>
    </tr>

    <tr>
        <td>Invoice No.</td>
        <td colspan="2">
            {{ $invoice->invoice_number }}
        </td>

        <td>Customer</td>
        <td colspan="2">
            {{ $invoice->customer->name ?? '' }}
        </td>
    </tr>

    <tr>
        <td>Invoice Date</td>
        <td colspan="2">
            {{ $invoice->invoice_date }}
        </td>

        <td>Email</td>
        <td colspan="2">
            {{ $invoice->customer->email ?? '' }}
        </td>
    </tr>

    <tr>
        <td>Due Date</td>
        <td colspan="2">
            {{ $invoice->due_date }}
        </td>

        <td>Phone</td>
        <td colspan="2">
            {{ $invoice->customer->phone ?? '' }}
        </td>
    </tr>

    <tr>
        <td colspan="6"></td>
    </tr>

    {{-- Items Header --}}
    <tr>
        <td style="background-color:#4d7cfe;color:#FFFFFF;font-weight:bold;">
            #
        </td>

        <td colspan="2"
            style="background-color:#4d7cfe;color:#FFFFFF;font-weight:bold;">
            Description
        </td>

        <td style="background-color:#4d7cfe;color:#FFFFFF;font-weight:bold;">
            Qty
        </td>

        <td style="background-color:#4d7cfe;color:#FFFFFF;font-weight:bold;">
            Unit Price
        </td>

        <td style="background-color:#4d7cfe;color:#FFFFFF;font-weight:bold;">
            Total
        </td>
    </tr>

    {{-- Items --}}
    @foreach($invoice->items as $index => $item)
        <tr>
            <td>
                {{ $index + 1 }}
            </td>

            <td colspan="2">
                {{ $item->description }}
            </td>

            <td>
                {{ $item->quantity }}
            </td>

            <td>
                ₹{{ number_format($item->unit_price, 2) }}
            </td>

            <td>
                ₹{{ number_format($item->total, 2) }}
            </td>
        </tr>
    @endforeach

    <tr>
        <td colspan="4"></td>

        <td style="font-weight:bold;">
            Subtotal
        </td>

        <td style="font-weight:bold;">
            ₹{{ number_format($invoice->subtotal, 2) }}
        </td>
    </tr>

    <tr>
        <td colspan="4"></td>

        <td style="font-weight:bold;">
            Discount
        </td>

        <td>
            ₹{{ number_format($invoice->discount, 2) }}
        </td>
    </tr>

    <tr>
        <td colspan="4"></td>

        <td style="font-weight:bold;">
            Tax
        </td>

        <td>
            ₹{{ number_format($invoice->tax, 2) }}
        </td>
    </tr>

    <tr>
        <td colspan="4"></td>

        <td style="
            background-color:#4d7cfe;
            color:#FFFFFF;
            font-weight:bold;
        ">
            GRAND TOTAL
        </td>

        <td style="
            background-color:#4d7cfe;
            color:#FFFFFF;
            font-weight:bold;
        ">
            ₹{{ number_format($invoice->total, 2) }}
        </td>
    </tr>

    <tr>
        <td colspan="6"></td>
    </tr>

    <tr>
        <td colspan="6"
            style="
                background-color:#F2F2F2;
                font-weight:bold;
            ">
            Notes
        </td>
    </tr>

    <tr>
        <td colspan="6">
            {{ $invoice->notes }}
        </td>
    </tr>
</table>
