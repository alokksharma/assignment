@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Country List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card-header">
                        <div class="card-title d-flex justify-content-between align-items-center">
                            <span>Country List</span>
                        </div>
                        <form id="searchForm"  action="{{ route('countries') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                    <label for="search" class="pr-3 font-weight-bold">Search</label>
                                <input type="text" name="q" class="form-control me-2" placeholder="Search users..." value="{{ request('q') }}">
                            </div>
                            <button type="submit" class="btn btn-light btn-sm mr-2">
                                <i class="fa fa-search">Filter</i>
                            </button>
                            @if(request('q'))
                                <a href="{{ route('countries') }}" class="btn btn-secondary ms-2">
                                    Reset
                                </a>
                            @endif

                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Short name</th>
                                    <th>ISO Code</th>
                                    <th>Country Code</th>
                                    <th>Language</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse($countries as $country)
                                    @php
                                    $i++;
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $country->name }}</td>
                                    <td>{{ $country->short_name }}</td>
                                    <td>{{ $country->iso_code }}</td>
                                    <td>{{ $country->code }}</td>
                                    <td>{{ $country->languages->pluck('name')->implode(', ') }}</td>

                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                                    {{-- pagination --}}
                                    @if ($countries->hasPages())
                                            <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="d-flex justify-content-center">
                                                {{ $countries->appends(request()->query())->links('pagination::bootstrap-4') }}
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


