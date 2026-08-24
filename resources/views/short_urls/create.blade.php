<!-- resources/views/short_urls/create.blade.php -->
@extends('layouts.main')
    @section('content')
				<div class="content">
					<div class="container-fluid">
						<h4 class="page-title"></h4>
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">User</div>
									</div>

                                   <form action="{{ route('invoices.store') }}" method="POST">
    @csrf

    {{-- Invoice Information --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fa fa-file-invoice"></i> Invoice Information
            </h5>
        </div>

        <div class="card-body">
            <div class="row">

                {{-- Customer --}}
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="customer_id" class="form-label">
                            Customer <span class="text-danger">*</span>
                        </label>

                        <select
                            name="customer_id"
                            id="customer_id"
                            class="form-control"
                            required
                        >
                            <option value="">Select Customer</option>

                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('customer_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Invoice Date --}}
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label for="invoice_date" class="form-label">
                            Invoice Date <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="invoice_date"
                            id="invoice_date"
                            class="form-control"
                            value="{{ old('invoice_date', date('Y-m-d')) }}"
                            required
                        >

                        @error('invoice_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Due Date --}}
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label for="due_date" class="form-label">
                            Due Date
                        </label>

                        <input
                            type="date"
                            name="due_date"
                            id="due_date"
                            class="form-control"
                            value="{{ old('due_date') }}"
                        >

                        @error('due_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- Invoice Items --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa fa-shopping-cart"></i> Invoice Items
            </h5>

            <button
                type="button"
                class="btn btn-success btn-sm"
                id="addItem"
            >
                <i class="fa fa-plus"></i> Add Item
            </button>
        </div>

        <div class="card-body">

            <div id="items">

                <div class="row item mb-3">

                    {{-- Description --}}
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">
                                Description
                            </label>

                            <input
                                type="text"
                                name="items[0][description]"
                                class="form-control"
                                placeholder="Enter product/service"
                                required
                            >
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">
                                Quantity
                            </label>

                            <input
                                type="number"
                                name="items[0][quantity]"
                                class="form-control quantity"
                                placeholder="Qty"
                                step="0.01"
                                min="0.01"
                                value="1"
                                required
                            >
                        </div>
                    </div>

                    {{-- Unit Price --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">
                                Unit Price
                            </label>

                            <input
                                type="number"
                                name="items[0][unit_price]"
                                class="form-control unit-price"
                                placeholder="Unit Price"
                                step="0.01"
                                min="0"
                                required
                            >
                        </div>
                    </div>

                    {{-- Remove --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label d-block">
                                Action
                            </label>

                            <button
                                type="button"
                                class="btn btn-danger remove-item"
                                disabled
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>


    {{-- Amount Details --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
                <i class="fa fa-calculator"></i> Amount Details
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- Discount --}}
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="discount" class="form-label">
                            Discount
                        </label>

                        <input
                            type="number"
                            name="discount"
                            id="discount"
                            class="form-control"
                            value="{{ old('discount', 0) }}"
                            step="0.01"
                            min="0"
                        >
                    </div>
                </div>

                {{-- Tax --}}
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="tax" class="form-label">
                            Tax
                        </label>

                        <input
                            type="number"
                            name="tax"
                            id="tax"
                            class="form-control"
                            value="{{ old('tax', 0) }}"
                            step="0.01"
                            min="0"
                        >
                    </div>
                </div>

                {{-- Notes --}}
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="notes" class="form-label">
                            Notes
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            class="form-control"
                            rows="3"
                            placeholder="Enter invoice notes..."
                        >{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- Submit --}}
    <div class="text-end">
        <a
            href="{{ route('invoices.index') }}"
            class="btn btn-secondary"
        >
            <i class="fa fa-times"></i> Cancel
        </a>

        <button
            type="submit"
            class="btn btn-primary"
        >
            <i class="fa fa-save"></i> Create Invoice
        </button>
    </div>

</form>



									</div>
								</div>
							</div>
						</div>
				</div>


			</div>
		</div>
	</div>
    @endsection
@push('scripts')
    <script src="{{asset("js/validate.js") }}"></script>
<script>

     $("#validateSubmitForm").validate({
        rules: {
            original_url: {
                required: true,
            },

        },
        messages: {
            original_url: {
                required: "This field is required",
            },
        },
        highlight: function(element) {
            // Prevent adding the error class to input and select elements
        },
        unhighlight: function(element) {
            // Prevent removing the error class from input and select elements
        },
        submitHandler: function(form) {
        	form.submit();
        }

    });

</script>
@endpush

