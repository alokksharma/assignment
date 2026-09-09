@extends('layouts.main')
    @section('content')
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">Invoices Dashboard</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                     <div class="card-title d-flex justify-content-between align-items-center">
                                        <!--Invoices List-->&nbsp;
                                        <div>
                                            <a href="{{ route('invoices.create') }}" style="background-color:#007bff" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Invoice</a>
                                         </div>

                                    </div>

                                     @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice No</th>
                                                <th>Supplier</th>
                                                <th>Invoice Date</th>
                                                <th>Invoiced Amount</th>
                                                <th>Amount Received</th>
												<th>Amount Pending</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @forelse($invoices as $key => $invoice)
                                            @php
                                                $key = 1 + $key;
                                            @endphp
                                            <tr>
                                                <th scope="row">{{ $key }}</th>
                                                <td> {{ $invoice->invoice_number }} </td>
                                                <td> {{ $invoice->supplier?->name }} </td>
                                                <td> {{ $invoice->invoice_date }} </td>
                                                <td>{{ $invoice->total }}</td>
                                                <td>{{ $invoice->amount_paid }}</td>
												<td>{{ $invoice->total - $invoice->amount_paid }}</td>
                                                <td>
                                                    <a href="{{ route('invoices.show', $invoice->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        show
                                                    </a>
                                                    {{-- <a href="{{ route('invoices.edit', $invoice->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        Edit
                                                    </a> --}}

                                                    {{-- <form action="{{ route('suppliers.destroy', $invoice->id) }}"
                                                        method="POST"
                                                        style="display:inline-block;"
                                                        onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            Delete
                                                        </button>
                                                    </form> --}}
                                                </td>
                                            </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No record found.</td>
                                                </tr>
                                            @endforelse
                                                @if ($invoices->hasPages())
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <div class="d-flex justify-content-center">
                                                            {{ $invoices->appends(request()->query())->links('pagination::bootstrap-4') }}
                                                        </div>
                                                    </td>
                                                </tr>

                                                @endif
                                        </tbody>
                                    </table>
                                </div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
    @endsection
    @push('scripts')

    @endpush
