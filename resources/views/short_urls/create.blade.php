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
										<div class="card-title">User</div>
									</div>

                                    <form method="POST" id="validateSubmitForm" action="{{ route('short_urls.store') }}">
                                            @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="admin_name">Short Url<span>*</span></label>
                                                <input type="text" name="original_url" class="form-control" id="admin_name" placeholder="Enter short Url" value="{{ old('original_url') }}">
                                                @error('original_url')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
										<div class="card-action">
											<button class="btn btn-success">Submit</button>
											<a href="" class="btn btn-danger">Cancel</a>
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

     $("#validateSubmitForm").validate({
        rules: {
            original_url: {
                required: true,
            },

        },
        messages: {
            original_url: {
                required: "This field is required",
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

