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

                                        <div>
                                            <label>Customer</label>

                                            <select name="customer_id" required>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}">
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label>Invoice Date</label>
                                            <input
                                                type="date"
                                                name="invoice_date"
                                                value="{{ date('Y-m-d') }}"
                                                required
                                            >
                                        </div>

                                        <div>
                                            <label>Due Date</label>
                                            <input type="date" name="due_date">
                                        </div>

                                        <h4>Items</h4>

                                        <div id="items">
                                            <div class="item">
                                                <input
                                                    type="text"
                                                    name="items[0][description]"
                                                    placeholder="Description"
                                                    required
                                                >

                                                <input
                                                    type="number"
                                                    name="items[0][quantity]"
                                                    placeholder="Qty"
                                                    step="0.01"
                                                    required
                                                >

                                                <input
                                                    type="number"
                                                    name="items[0][unit_price]"
                                                    placeholder="Unit Price"
                                                    step="0.01"
                                                    required
                                                >
                                            </div>
                                        </div>

                                        <div>
                                            <label>Discount</label>
                                            <input
                                                type="number"
                                                name="discount"
                                                value="0"
                                                step="0.01"
                                            >
                                        </div>

                                        <div>
                                            <label>Tax</label>
                                            <input
                                                type="number"
                                                name="tax"
                                                value="0"
                                                step="0.01"
                                            >
                                        </div>

                                        <div>
                                            <label>Notes</label>
                                            <textarea name="notes"></textarea>
                                        </div>

                                        <button type="submit">
                                            Create Invoice
                                        </button>
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


</script>
@endpush

