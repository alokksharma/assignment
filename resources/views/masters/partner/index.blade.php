@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">API Partners</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>API Dashboard List</div>
                        <a href="{{ route('partners.create') }}" class="btn btn-primary">Create API Partner</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $i => $user)
                                <tr>
                                    <td>{{ $i + $users->firstItem() }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->contact }}</td>
                                    <td>
                                        <a href="{{ route('partners.show', $user->id) }}" class="btn btn-sm btn-info">API Details</a>
                                        <a href="{{ route('partners.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('partners.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No partners found.</td>
                                </tr>
                                @endforelse

                                @if($users->hasPages())
                                    <tr>
                                        <td colspan="5" class="text-center">{{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}</td>
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
