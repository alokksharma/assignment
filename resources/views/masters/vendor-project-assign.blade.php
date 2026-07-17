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
                                    <div class="card-title">Vendor Project Assignment</div>
                                </div>
                                <form method="POST" id="validateSubmitForm" action="{{ route('vendors-project-assign.store', $id) }}">
                                        @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vendor_list">Vendor List <span>*</span></label>
                                                    <select name="vendor_id" id="vendor_list" class="form-control">
                                                        <option value="">Select Vendor</option>
                                                    </select>
                                                    @error('vendor_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="temp_complete">Vendor Temp Complete Redirect <span>*</span></label>
                                                    <input type="text" name="temp_complete" class="form-control" id="temp_complete" placeholder="Enter Vendor Temp Complete Redirecct" value="{{ old('temp_complete') }}">
                                                    @error('temp_complete')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="temp_terminate">Vendor Temp Terminate Redirect <span>*</span></label>
                                                    <input type="text" name="temp_terminate" class="form-control" id="temp_terminate" placeholder="Enter Vendor Temp Terminate Redirect" value="{{ old('temp_terminate') }}">
                                                    @error('temp_terminate')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="temp_quotafull">Vendor Temp Quotafull  <span>*</span></label>
                                                    <input type="text" name="temp_quotafull" class="form-control" id="temp_quotafull" placeholder="Enter Vendor Temp Quotafull" value="{{ old('temp_quotafull') }}">
                                                    @error('temp_quotafull')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="temp_speedster">Vendor Temp Quality  <span>*</span></label>
                                                    <input type="text" name="temp_speedster" class="form-control" id="temp_speedster" placeholder="Enter Vendor Temp Speedster" value="{{ old('temp_speedster') }}">
                                                    @error('temp_speedster')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="temp_cpi">Vendor Temp CPI  <span>*</span></label>
                                                    <input type="text" name="temp_cpi" class="form-control" id="temp_cpi" placeholder="Enter Vendor Temp CPI" value="{{ old('temp_cpi') }}">
                                                    @error('temp_cpi')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
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
    @endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset("js/validate.js") }}"></script>
<script>

    $(document).ready(function () {

        $('#vendor_list').select2({
            placeholder: 'Select Vendor',
            allowClear: true,
            width: '100%',

            ajax: {
                url: "{{ route('vendors.search') }}",
                type: "GET",
                dataType: 'json',
                delay: 250,

                data: function (params) {
                    return {
                        search: params.term || '',
                        survey_id: "{{ $id }}",
                        page: params.page || 1
                    };
                },

                processResults: function (data, params) {

                    params.page = params.page || 1;

                    return {
                        results: data.results,

                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },

                cache: true
            }
        });

    });


    $.validator.addMethod("checkIfEmpty", function(value, element) {
        return $(element).find("option:selected").length > 0;
    }, "Please select at least one option.");

    $.validator.addMethod("decimalNumber", function(value, element) {
        return this.optional(element) || /^\d+(\.\d{1,2})?$/.test(value);
    }, "Please enter a valid decimal number with up to 2 decimal places.");

     $("#validateSubmitForm").validate({
        rules: {
            vendor_id: {
                required: true,
            },
            temp_complete: {
                required: true,
                maxlength: 255,
            },
            temp_terminate: {
                required: true,
                maxlength: 255,
            },
            temp_quotafull: {
                required: true,
                maxlength: 255,
            },
            temp_speedster: {
                required: true,
                maxlength: 255,
            },
            temp_cpi: {
                required: true,
                decimalNumber:true,
                maxlength: 6,
            },


        },
        messages: {
            temp_complete: {
                required: "This field is required",
                maxlength: "Complete must be less than 255 characters"
            },
            vendor_id: {
                required: "This field is required",
            },
            temp_terminate: {
                required: "This field is required",
                maxlength: "Terminate must be less than 255 characters"
            },
            temp_quotafull: {
                required: "This field is required",
            },
            temp_speedster: {
                required: "This field is required",
            },
            temp_cpi: {
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


