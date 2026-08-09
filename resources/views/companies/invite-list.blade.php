@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">User List </h4>
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
                            <span>User List</span>
                            <form id="searchForm"  action="{{ route('companies.invite-list') }}" method="GET" class="form-inline">
                                <div class="form-group mr-2">
                                     <label for="search" class="pr-3 font-weight-bold">Search</label>
                                    <input type="text" name="q" class="form-control me-2" placeholder="Search users..." value="{{ request('q') }}">
                                </div>
                                 <div class="form-group mr-2">
                                    <label for="vpi" class="pr-3 font-weight-bold">Role</label>
                                    <select name="role" id="vpi" class="form-control vendor_list select2">
                                        @foreach ($roles as $role)
                                            <option value="{{$role->name}}" {{ request('role') == $role->name ? 'selected' : '' }}>{{$role->display_name ?? $role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-light btn-sm mr-2">
                                    <i class="fa fa-search">Filter</i>
                                </button>
                                @if(request('q'))
                                    <a href="{{ route('companies.invite-list') }}" class="btn btn-secondary ms-2">
                                        Reset
                                    </a>
                                @endif

                            </form>
                            @can('create-user')
                            <div>
                                <a href="{{ route('companies.invite-admin') }}" style="background-color:#007bff" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create User</a>
                            </div>
                            @endcan
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    @canany(['edit-user', 'destroy-user'])
                                    <th>Action</th>
                                    @endcanany
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
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                                   @canany(['edit-user', 'destroy-user'])
                                        <td>
                                            @can('edit-user')
                                                <a href="{{ route('companies.invite-admin.edit', $user->id) }}"
                                                class="btn btn-warning btn-sm">
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('destroy-user')
                                                <form action="{{ route('users.destroy', $user->id) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No users found.</td>
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


