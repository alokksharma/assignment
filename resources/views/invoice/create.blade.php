@push('css')
<style>
    .invoice-card {
        max-width: 900px;
        margin: 30px auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .invoice-header {
        background: #1e3a5f;
        color: #fff;
        padding: 10px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .invoice-header h2 { margin: 0; }
    .invoice-header small { opacity: 0.8; }
    .section-title {
        background: #1e3a5f;
        color: #fff;
        padding: 8px 16px;
        margin: 25px 0 15px;
        font-weight: 600;
        border-radius: 4px;
    }
    .invoice-body { padding: 30px; }
    .form-group label { font-weight: 600; font-size: 13px; color: #374151; }
    .totals-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
    }
    .totals-box .row { margin-bottom: 8px; }
    .totals-box .total-row {
        font-weight: 700;
        font-size: 18px;
        color: #1e3a5f;
        border-top: 2px solid #1e3a5f;
        padding-top: 10px;
        margin-top: 10px;
    }
    .remove-row { color: #dc2626; cursor: pointer; border: none; background: none; font-size: 18px; }
    #addRow { margin-top: 10px; }
</style>
@endpush
@extends('layouts.main') {{-- adjust to your layout --}}
@section('content')
    <div class="content">
        <div class="container-fluid">
            <h4 class="page-title"></h4>
            <div class="row">
                <div class="col-md-12">
                        <div class="card">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>There were some problems with your submission:</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="invoice-header">
                                <div>
                                    <h4>3AS RESEARCH</h4>
                                    <small>Growing Through Insights</small>
                                </div>
                                <div class="text-right">
                                    <h5>INVOICE</h5>
                                </div>
                            </div>

                            <div class="invoice-body">

                                <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                                    @csrf

                                    <div class="section-title">Invoice Details</div>
                                    <div class="row">
                                        {{-- <div class="col-md-4 form-group">
                                            <label>Invoice No.</label>
                                            <input type="text" name="invoice_no" class="form-control" value="3AS-INV-2026-00001" required>
                                        </div> --}}
                                        <div class="col-md-4 form-group">
                                            <label>Invoice Date</label>
                                            <input type="date" id="invoice_date" name="invoice_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Due Date</label>
                                            <input type="date" id="due_date" disabled name="due_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>Currency</label>
                                            <select name="currency" class="form-control" required>
                                                <option value="USD">USD</option>
                                                <option value="INR">INR</option>
												<option value="GBP">GBP</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="Draft">Draft</option>
                                                <option value="Sent">Sent</option>
                                                <option value="Paid">Paid</option>
												<option value="Paid">Partially Paid</option>
												<option value="Paid">Overdue</option>
												<option value="Paid">Cancel</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Service Location</label>
                                            <select name="service_location" id="service_location" class="form-control" required>
                                                <option value="within_india_up">Within India (UP)</option>
                                                <option value="within_india_outside_up">Within India (Outside UP)</option>
                                                <option value="outside_india">Outside India</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="section-title"><center>Bill To</center></div>
                                    <div class="row">
                                        {{-- <div class="col-md-6 form-group">
                                            <label>Client Name</label>
                                            <input type="text" name="client_name" class="form-control" required>
                                        </div> --}}
                                        <div class="col-md-6 form-group">
                                            <label>Tax ID / GSTIN</label>
                                            <input type="text" name="tax_id" class="form-control">
                                        </div>
                                        {{-- <div class="col-md-6 form-group">
                                            <label>Billing Address</label>
                                            <textarea name="billing_address" class="form-control" rows="2"></textarea>
                                        </div> --}}
                                        <div class="col-md-6 form-group">
                                            <label>Project</label>
                                                <select name="supplier_id"  class="form-control">
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>

                                    <div class="section-title"><center>Services / API Billing</center></div>
                                    <table class="table table-bordered" id="itemsTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Description</th>
                                                <th>Billing Type</th>
                                                <th width="110">Quantity</th>
                                                <th width="130">Unit Price</th>
                                                <th width="120">Amount</th>
                                                <th width="40"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="item-row">
                                                <td><input type="text" class="form-control" name="items[0][description]" required></td>
                                                <td>
                                                    <select name="items[0][billing_type]" class="form-control">
                                                        <option value="Api Usages">Api Usages</option>
                                                        <option value="Custom">Custom</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" step="0.01" class="form-control qty" name="items[0][quantity]" required></td>
                                                <td><input type="number" step="0.01" class="form-control price" name="items[0][unit_price]" required></td>
                                                <td class="align-middle text-right row-amount">0.00</td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="remove-row">&times;</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" id="addRow" class="btn btn-sm btn-outline-primary">+ Add Row</button>

                                    <div class="row mt-4">
                                        <div class="col-md-6 form-group">
                                            <label>Notes</label>
                                            <textarea name="notes" class="form-control" rows="4"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="totals-box">
                                                <div class="row">
                                                    <div class="col-6">Subtotal</div>
                                                    <div class="col-6 text-right" id="subtotal">0.00</div>
                                                </div>
                                                <div class="row align-items-center">
                                                    <div class="col-6">
                                                        <label class="mb-0">Discount</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" name="discount" id="discount" class="form-control form-control-sm text-right" value="0">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">CGST</div>
                                                    <div class="col-6 text-right" id="cgst">0.00</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">SGST</div>
                                                    <div class="col-6 text-right" id="sgst">0.00</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">IGST</div>
                                                    <div class="col-6 text-right" id="igst">0.00</div>
                                                </div>
                                                <div class="row align-items-center">
                                                    <div class="col-6">
                                                        <label class="mb-0">Other Tax</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" name="other_tax" id="other_tax"
                                                            class="form-control form-control-sm text-right" value="0" disabled>
                                                    </div>
                                                </div>
                                                <div class="row total-row">
                                                    <div class="col-6">Total</div>
                                                    <div class="col-6 text-right" id="total">0.00</div>
                                                </div>
                                                <div class="row align-items-center mt-2">
                                                    <div class="col-6">
                                                        <label class="mb-0">Amount Paid</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="form-control form-control-sm text-right" value="0">
                                                    </div>
                                                </div>
                                                <div class="row total-row">
                                                    <div class="col-6">Balance Due</div>
                                                    <div class="col-6 text-right" id="balance_due">0.00</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">Save Invoice</button>
                                </form>

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

<script>
let rowIndex = 1;
  const billingOptionsData = @json($billingTypes ?? []);
  const billingOptions = billingOptionsData.map(b => `<option value="${b}">${b}</option>`).join('');

    $('#addRow').on('click', function () {
        const row = `
            <tr class="item-row">
                <td>
                    <input type="text"
                        class="form-control"
                        name="items[${rowIndex}][description]"
                        required>
                </td>

                <td>
                    <select name="items[${rowIndex}][billing_type]" class="form-control">
                        ${billingOptions}
                    </select>
                </td>

                <td>
                    <input type="number"
                        step="0.01"
                        class="form-control qty"
                        name="items[${rowIndex}][quantity]"
                        required>
                </td>

                <td>
                    <input type="number"
                        step="0.01"
                        class="form-control price"
                        name="items[${rowIndex}][unit_price]"
                        required>
                </td>

                <td class="align-middle text-right row-amount">
                    0.00
                </td>

                <td class="text-center align-middle">
                    <button type="button" class="remove-row">
                        &times;
                    </button>
                </td>
            </tr>
        `;
        $('#itemsTable tbody').append(row);

        rowIndex++;
    });

    // Remove row
    $('#itemsTable').on('click', '.remove-row', function () {

        $(this).closest('tr').remove();
        calculateTotals();
    });

    // Calculate when item fields change
    $('#itemsTable').on('input', '.qty, .price', function () {
        calculateTotals();
    });

    // Discount
    $('#discount').on('input', function () {
        calculateTotals();
    });

    // Amount paid
    $('#amount_paid').on('input', function () {
        calculateTotals();
    });

    // Other tax (manual entry, only active when outside India)
    $('#other_tax').on('input', function () {
        calculateTotals();
    });

    // Service location
    $('#service_location').on('change', function () {
        calculateTotals();
    });

    function toggleOtherTax() {
        const location = $('#service_location').val();
        const $otherTax = $('#other_tax');

        if (location === 'outside_india') {
            $otherTax.prop('disabled', false);
        } else {
            $otherTax.prop('disabled', true).val(0);
        }
    }

    function calculateTotals() {

        let subtotal = 0;

        $('.item-row').each(function () {

            const qty = parseFloat($(this).find('.qty').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) || 0;

            const amount = qty * price;

            $(this)
                .find('.row-amount')
                .text(amount.toFixed(2));

            subtotal += amount;
        });

        const discount = parseFloat($('#discount').val()) || 0;
        const taxable = subtotal - discount;
        const location = $('#service_location').val();

        let cgst = 0;
        let sgst = 0;
        let igst = 0;
        let otherTax = 0;

        if (location === 'within_india_up') {
            cgst = taxable * 0.09;
            sgst = taxable * 0.09;
        } else if (location === 'within_india_outside_up') {
            igst = taxable * 0.18;
        } else if (location === 'outside_india') {
            otherTax = parseFloat($('#other_tax').val()) || 0;
        }

        const total = taxable + cgst + sgst + igst + otherTax;
        const amountPaid = parseFloat($('#amount_paid').val()) || 0;
        const balanceDue = total - amountPaid;

        // Update totals
        $('#subtotal').text(subtotal.toFixed(2));
        $('#cgst').text(cgst.toFixed(2));
        $('#sgst').text(sgst.toFixed(2));
        $('#igst').text(igst.toFixed(2));
        $('#total').text(total.toFixed(2));
        $('#balance_due').text(balanceDue.toFixed(2));
    }

    $('#invoice_date').on('change', function () {

        setDueDate();
    });

    function setDueDate() {
        const invoiceDateVal = $('#invoice_date').val();
        if (!invoiceDateVal) return;

        const invoiceDate = new Date(invoiceDateVal);
        invoiceDate.setDate(invoiceDate.getDate() + 90);

        // Format back to YYYY-MM-DD for the date input
        const yyyy = invoiceDate.getFullYear();
        const mm = String(invoiceDate.getMonth() + 1).padStart(2, '0');
        const dd = String(invoiceDate.getDate()).padStart(2, '0');
        $('#due_date').val(`${yyyy}-${mm}-${dd}`);
    }
</script>
@endpush
