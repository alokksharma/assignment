@extends('layouts.main')
    @section('content')
				<div class="content">
					<div class="container-fluid">
						<h4 class="page-title"></h4>
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">User</div>
									</div>

                                    <form method="POST" id="validateSubmitForm" action="{{ route('companies.invite-admin.store', $company) }}">
                                            @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="admin_name">User Name <span>*</span></label>
                                                <input type="text" name="name" class="form-control" id="admin_name" placeholder="Enter Name" value="{{ old('name') }}">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email Address <span>*</span></label>
                                                <input type="text" name="email" class="form-control" id="email" placeholder="Enter Email" value="{{ old('email') }}">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            @if (auth()->user()->hasRole('SuperAdmin'))
                                                <div class="form-group">
                                                    <label for="company_name">Company Name <span>*</span></label>
                                                    <input type="text" name="company_name" class="form-control" id="company_name" placeholder="Enter Company Name" value="{{ old('company_name') }}">
                                                    @error('company_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif
                                            @if (auth()->user()->hasRole('Admin'))
                                            <div class="form-group">
                                                <label for="role">Role <span>*</span></label>
                                                <select name="role" id="role" class="form-control">
                                                    @foreach($roles as $roleValue => $roleLabel)
                                                        <option value="{{ $roleValue }}" @selected(old('role') == $roleValue)>{{ $roleLabel }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            @endif
                                            {{-- <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" id="password" placeholder="Password">
                                            </div> --}}


                                        </div>
										<div class="card-action">
											<button class="btn btn-success">Submit</button>
											<a href="{{ route('companies.invite-list') }}" class="btn btn-danger">Cancel</a>
										</div>

                                    </form>
									</div>
								</div>
							</div>
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
            company_name: {
                required: true,
                minlength: 2,
                maxlength: 50,
            },
            role: {
                required: true,
            }


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
            role: {
                required: "This field is required",
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


