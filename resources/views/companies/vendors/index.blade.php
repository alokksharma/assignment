@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Vendor List </h4>
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
                            <span>Vendor List</span>
                            <div>
                                <a href="{{ route('vendors.create') }}" style="background-color:#007bff" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Vendor</a>

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vendor Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Complete</th>
                                    <th>Terminate</th>
                                    <th>Quotafull</th>
                                    <th>Speedster</th>
                                    <th>Expired At</th>
                                    <th>Modify At</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse($users as $user)
                                    @php
                                    $i++;
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $user->vendor?->vendor_id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->vendor?->complete }}</td>
                                    <td>{{ $user->vendor?->terminate }}</td>
                                    <td>{{ $user->vendor?->quotafull }}</td>
                                    <td>{{ $user->vendor?->speedster }}</td>
                                    <td>{{ $user->vendor?->expired_at }}</td>
                                    <td>{{ $user->updated_at }}</td>

                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                                    {{-- pagination --}}
                                    @if ($users->hasPages())
                                            <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="d-flex justify-content-center">
                                                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
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


