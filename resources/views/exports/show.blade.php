@extends('layouts.main')

@section('content')

<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">User List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                            <div class="card-title d-flex justify-content-between align-items-center">
                            Short Url List
                                @can('create short url')
                                <div>
                                <a href="{{ route('short_urls.create') }}" style="background-color:#007bff" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Short URL</a>
                                </div>

                            @endcan
                        </div>
                    </div>
                     <div class="table-responsive">
                        @include('exports.invoice', ['invoice' => $invoice])
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
