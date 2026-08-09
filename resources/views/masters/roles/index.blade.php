@extends('layouts.main')

@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Survey List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Roles</h4>

                        @can('create-role')
                            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                <i class="la la-plus"></i> Add Role
                            </a>
                        @endcan
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Name</th>
                                        <th>Display Name</th>
                                        <th>Permissions</th>
                                        <th width="150" class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($roles as $role)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $role->name }}</td>

                                            <td>{{ $role->display_name ?? '-' }}</td>

                                            <td>
                                                @forelse($role->permissions as $permission)
                                                    <span class="badge badge-primary mb-1">
                                                        {{ $permission->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted">No Permissions</span>
                                                @endforelse
                                            </td>

                                            <td class="text-center">
                                                @can('edit-role')
                                                    <a href="{{ route('roles.edit', $role->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                        <i class="la la-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('destroy-role')
                                                    <form action="{{ route('roles.destroy', $role->id) }}"
                                                        method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-danger btn-sm">
                                                            <i class="la la-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                No roles found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
