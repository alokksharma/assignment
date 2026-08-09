@extends('layouts.main')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <h4 class="page-title"></h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Vendor</div>
                        </div>
                          <form method="POST" id="validateSubmitForm" action="{{ route('vendors.update', $user->id) }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vendor_name">Vendor Name <span>*</span></label>
                                                <input type="text" name="name" class="form-control" id="admin_name" placeholder="Enter Name" value="{{ old('name', $user->name) }}">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Vendor Email Address <span>*</span></label>
                                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" value="{{ old('email', $user->email) }}">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Vendor Address </label>
                                                <input type="text" name="address" class="form-control" id="address" placeholder="Enter Vendor Address" value="{{ old('address', $user->address) }}">
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact">Vendor Contact </label>
                                                <input type="text" name="contact" class="form-control" id="contact" placeholder="Enter Vendor Contact" value="{{ old('contact', $user->contact) }}">
                                                @error('contact')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="currency">Currency <span>*</span></label>
                                                <select name="currency" id="currency" class="form-control">
                                                    <option value="1" {{ old('currency', $user->vendor->currency) == 1 ? 'selected' : '' }}> INR </option>
                                                    <option value="2" {{ old('currency', $user->vendor->currency) == 2 ? 'selected' : '' }}> USD </option>
                                                    <option value="3" {{ old('currency', $user->vendor->currency) == 3 ? 'selected' : '' }}> GBP </option>
                                                </select>
                                                @error('currency')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="complete">Vendor Complete </label>
                                                <input type="text" name="complete" class="form-control" id="complete" placeholder="Enter Vendor Complete" value="{{ old('complete', $user->vendor->complete) }}">
                                                @error('complete')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="terminate">Vendor Terminate </label>
                                                <input type="text" name="terminate" class="form-control" id="terminate" placeholder="Enter Vendor Terminate" value="{{ old('terminate', $user->vendor->terminate) }}">
                                                @error('terminate')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="quotafull">Vendor Quotafull </label>
                                                <input type="text" name="quotafull" class="form-control" id="quotafull" placeholder="Enter Vendor Quotafull" value="{{ old('quotafull', $user->vendor->quotafull) }}">
                                                @error('quotafull')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="speedster">Vendor Speedster </label>
                                                <input type="text" name="speedster" class="form-control" id="speedster" placeholder="Enter Vendor Speedster" value="{{ old('speedster', $user->vendor->speedster) }}">
                                                @error('speedster')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expired_at">Expired At <span>*</span></label>
                                                <input type="date" name="expired_at" class="form-control" id="expired_at" placeholder="Enter Vendor Expired Date" value="{{ old('expired_at', $user->vendor->expired_at->format('Y-m-d')) }}">
                                                @error('expired_at')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <button class="btn btn-success">Submit</button>
                                    <a href="{{ route('vendors.index') }}" class="btn btn-danger">Cancel</a>
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
            $.validator.addMethod("extension", function(value, element, param) {
                var extension = value.split('.').pop().toLowerCase();
                return this.optional(element) || $.inArray(extension, param.split(',')) !== -1;
            }, "Invalid file type.");

            $.validator.addMethod("alphabets", function(value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            }, "Please enter only letters.");

            $.validator.addMethod("customEmail", function(value, element) {
                // Custom regex for email validation
                return this.optional(element) || /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value);
            }, "Please enter a valid email address.");

            $.validator.addMethod("checkIfEmpty", function(value, element) {
                return $(element).find("option:selected").length > 0;
            }, "Please select at least one option.");


     $("#validateSubmitForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 30,
                alphabets: true
            },
            email: {
                required: true,
                customEmail: true
            },
            expired_at: {
                required: true,
                date: true
            },
            contact: {
                required: false,
                minlength: 8,
                maxlength: 16,
            },
            // vendor_id: {
            //     required: true,
            //     alphaNumeric: true,
            //     rangelength: [6, 6]
            // },
            currency: {
                required: true,
            },
            address: {
                maxlength: 255,
            },
            complete: {
                maxlength: 255,
            },
            terminate: {
                maxlength: 255,
            },
            quotafull: {
                maxlength: 255,
            },
            speedster: {
                maxlength: 255,
            },


        },
        messages: {
            name: {
                required: "This field is required",
                minlength: "Name must be at least 2 characters long",
                maxlength: "Name must be less than 50 characters"
            },
            email: {
                required: "This field is required",
                email: "Please enter a valid email address"
            },

            company_name: {
                required: "This field is required",
                minlength: "Company Name must be at least 2 characters long",
                maxlength: "Company Name must be less than 50 characters"
            },
            currency: {
                required: "This field is required",
            },
            address: {
                maxlength: "Address must be less than 255 characters"
            },
            complete: {
                maxlength: "Completee must be less than 255 characters"
            },
            terminate: {
                maxlength: "terminate must be less than 255 characters"
            },
            quotafull: {
                maxlength: "Quotafull must be less than 255 characters"
            },
            speedster: {
                maxlength: "Speedster Name must be less than 255 characters"
            },
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
