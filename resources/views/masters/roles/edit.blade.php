@extends('layouts.main')

@section('content')

<div class="content">
    <div class="container-fluid">
        <h4 class="page-title"></h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    @if ($errors->any())
                        <div class="alert alert-danger m-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-header">
                        <div class="card-title">Edit Role</div>
                    </div>

                    <form method="POST" action="{{ route('roles.update', $role->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="card-body">

                            <div class="form-group">
                                <label for="role">Role</label>
                                <input
                                    type="text"
                                    id="role"
                                    name="role"
                                    class="form-control"
                                    value="{{ old('role', $role->name) }}"
                                    placeholder="Enter Role Name">

                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="display_name">Display Name</label>
                                <input
                                    type="text"
                                    id="display_name"
                                    name="display_name"
                                    class="form-control"
                                    value="{{ old('display_name', $role->display_name) }}"
                                    placeholder="Enter Display Name">

                                @error('display_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="permission">Select Permissions</label>

                                <select
                                    id="permission"
                                    class="select2 form-control"
                                    name="permission[]"
                                    multiple="multiple">

                                    @foreach ($permissions as $permission)
                                        <option
                                            value="{{ $permission->name }}"
                                            {{ $role->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                                            {{ $permission->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('permission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-success">
                                Update
                            </button>

                            <a href="{{ route('roles.index') }}" class="btn btn-danger">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select Permissions'
        });
    });
</script>
@endpush
