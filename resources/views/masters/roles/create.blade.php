@extends('layouts.main')

@section('content')

<div class="content">
    <div class="container-fluid">
        <h4 class="page-title"></h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                        @error('survey_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    <div class="card-header">
                        <div class="card-title">Role</div>
                    </div>
                    <form method="POST" action="{{route('roles.store')}}">
                        @csrf
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" name="role" class="form-control" placeholder="super-admin">
                        </div>
                        <div class="form-group">
                            <label>Display Name</label>
                            <input type="text" name="display_name" class="form-control" placeholder="super-admin">
                        </div>
                        <div class="form-group">
                            <lable>Select Permissions</lable>
                            <select class="select2 form-select form-control" name="permission[]" multiple="multiple">
                                @foreach ($permissions as $permission)
                                    <option value="{{$permission->name}}">{{$permission->name}}</option>
                                @endforeach
                            </select>
                        </div>
                       <div class="card-action">
                            <button class="btn btn-success">Submit</button>
                            {{-- <a href="{{ route('vendor.project.report') }}" class="btn btn-danger">Cancel</a> --}}

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
    <script src="{{asset("js/validate.js") }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

@endpush
