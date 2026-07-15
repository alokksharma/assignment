@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Language List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title d-flex justify-content-between align-items-center">
                            <span>Language List</span>
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
                                    <th>Name</th>
                                    <th>Short name</th>
                                    <th>Modify At</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse($languages as $language)
                                    @php
                                    $i++;
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $language->name }}</td>
                                    <td>{{ $language->short_name }}</td>
                                    <td>{{ $language->updated_at }}</td>

                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                                    {{-- pagination --}}
                                    @if ($languages->hasPages())
                                            <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="d-flex justify-content-center">
                                                {{ $languages->appends(request()->query())->links('pagination::bootstrap-4') }}
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


