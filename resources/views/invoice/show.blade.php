@extends('layouts.main')

@section('content')

<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Invoice Preview</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                            <div class="card-title d-flex justify-content-between align-items-center">
                            <!--Invoice--> &nbsp;
                                <div>
                                    <a href="{{ route('invoices.export', $invoice->id) }}" style="background-color:#007bff" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Download Excel</a>
                                    <a href="{{ route('invoice.pdf', $invoice->id) }}" style="background-color:#007bff" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Download PDF</a>

                               {{--  <button type="button" class="btn btn-warning" onclick="window.print()">
                                    🖨️ Print Invoice
                                </button> --}}
                                 </div>
                        </div>
                    </div>
                     <div class="table-responsive">
                        @include('invoice.excel', ['invoice' => $invoice])
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
