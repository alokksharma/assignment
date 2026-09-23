<!-- resources/views/short_urls/create.blade.php -->
@extends('layouts.main')
    @section('content')
        <div class="content">
            <div class="container-fluid">
                <h4 class="page-title"></h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Client</div>
                            </div>

                            <form method="POST" id="validateSubmitForm" action="{{ route('suppliers.store') }}">
                                    @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="url">Short Url<span>*</span></label>
                                                <input type="text" name="url" class="form-control" id="url"
                                                    placeholder="Enter short Url" value="{{ old('url') }}">
                                                @error('url')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Project Name<span>*</span></label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                    placeholder="Enter Name" value="{{ old('name') }}">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                         <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="client_name">Client Name<span>*</span></label>
                                                <input type="text" name="client_name" class="form-control" id="client_name" placeholder="Enter client name" value="{{ old('client_name') }}">
                                                @error('client_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="code">Short Code<span>*</span></label>
                                                <input type="text" name="code" class="form-control" id="code"
                                                    placeholder="Enter short Code" value="{{ old('code') }}">
                                                @error('code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="supplier_token">Token<span>*</span></label>
                                                <input type="text" name="supplier_token" class="form-control" id="supplier_token"
                                                    placeholder="Enter Token" value="{{ old('supplier_token') }}">
                                                @error('supplier_token')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="supplier_id">Supplier Id<span>*</span></label>
                                                <input type="text" name="supplier_id" class="form-control" id="supplier_id"
                                                    placeholder="Enter Supplier Id" value="{{ old('supplier_id') }}">
                                                @error('supplier_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="billing_address">Billing Address<span>*</span></label>
                                                <input type="text" name="billing_address" class="form-control" id="billing_address" placeholder="Enter billing address" value="{{ old('billing_address') }}">
                                                @error('billing_address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status<span>*</span></label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>
                                                        Active
                                                    </option>
                                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                                        Inactive
                                                    </option>
                                                </select>

                                                @error('status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea name="description" id="description" class="form-control"
                                                    rows="4" placeholder="Enter Description">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="card-action">
                                    <button class="btn btn-success">Submit</button>
                                    <a href="{{route('suppliers.index')}}" class="btn btn-danger">Cancel</a>
                                </div>

                            </form>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    @endsection
@push('scripts')
    <script src="{{asset("js/validate.js") }}"></script>
<script>

   $("#validateSubmitForm").validate({
    rules: {
        url: {
            required: true,
            maxlength: 255
        },
        name: {
            required: true,
            maxlength: 255
        },
        client_name: {
            required: true,
            maxlength: 255
        },
        code: {
            required: true,
            maxlength: 255
        },
        supplier_token: {
            required: true,
            maxlength: 255
        },
        supplier_id: {
            required: true,
            maxlength: 255
        },
        status: {
            required: true
        },
        billing_address: {
            required: true,
            maxlength: 1000
        },
        description: {
            maxlength: 1000
        }
    },

    messages: {
        url: {
            required: "Short URL is required",
            maxlength: "Short URL cannot exceed 255 characters"
        },
        name: {
            required: "Name is required",
            maxlength: "Name cannot exceed 255 characters"
        },
        client_name: {
            required: "Client Name is required",
            maxlength: "Client Name cannot exceed 255 characters"
        },
        code: {
            required: "Short Code is required",
            maxlength: "Short Code cannot exceed 255 characters"
        },
        supplier_token: {
            required: "Supplier Token is required",
            maxlength: "Supplier Token cannot exceed 255 characters"
        },
        supplier_id: {
            required: "Supplier ID is required",
            maxlength: "Supplier ID cannot exceed 255 characters"
        },
        status: {
            required: "Please select a status"
        },
        description: {
            maxlength: "Description cannot exceed 1000 characters"
        }
    },

    highlight: function(element) {
        // Prevent adding the error class to input and select elements
    },

    unhighlight: function(element) {
        // Prevent removing the error class from input and select elements
    },

    submitHandler: function(form) {
        form.submit();
    }
});


</script>
@endpush

