@extends('layouts.main')
@section('content')
        <div class="content">
            <div class="container-fluid">
                <h4 class="page-title"></h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                                {{-- @error('survey_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror --}}
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            <div class="card-header">
                                <div class="card-title">Data Reconcile</div>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#manual">
                                        Manual Reconcile
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#import">
                                        Import Excel
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content border p-3">
                                <div class="tab-pane fade show active" id="manual">
                                    <form method="POST" id="validateSubmitForm" action="{{ route('data.store') }}">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="vendor_list">Vendor Project Assign <span>*</span></label>
                                                <select name="vendor_id" id="vendor_list" class="form-control select2">
                                                    @foreach($threeAsProjects as $project)
                                                        <option value="{{ $project->getAttribute('3ASProjectID') }}">
                                                            {{ $project->getAttribute('3ASProjectID') }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('vendor_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="vendor_list">Vendor Project Names</label>
                                                <textarea
                                                    name="asr_hash_short"
                                                    class="form-control"
                                                    rows="2"
                                                    placeholder="Enter Project Name"></textarea>
                                                @error('asr_hash_short')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Redirect Status <span>*</span></label>

                                                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                                    <label class="btn btn-outline-success flex-fill active">
                                                        <input type="radio" name="redirect_status" value="1" checked  autocomplete="off"> Complete
                                                    </label>

                                                    <label class="btn btn-outline-danger flex-fill">
                                                        <input type="radio" name="redirect_status" value="2" autocomplete="off"> Terminate
                                                    </label>

                                                    <label class="btn btn-outline-warning flex-fill">
                                                        <input type="radio" name="redirect_status" value="3" autocomplete="off"> Quota Full
                                                    </label>

                                                    <label class="btn btn-outline-info flex-fill">
                                                        <input type="radio" name="redirect_status" value="4" autocomplete="off"> Quality
                                                    </label>
                                                </div>

                                                @error('redirect_status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="card-action">
                                                <button class="btn btn-success">Submit</button>
                                                {{-- <a href="{{ route('companies.invite-list') }}" class="btn btn-danger">Cancel</a> --}}
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="import">
                                    {{-- Import form --}}
                                    <p>Upload an Excel file (.xlsx) to reconcile multiple records.</p>

                                    <form id="importForm" enctype="multipart/form-data" style="display:inline-block;">
                                            @csrf
                                        <label for="import_file" class="btn btn-success btn-sm mb-0" style="cursor:pointer;">
                                            Import Excel
                                            <input type="file" name="import_file" id="import_file" accept=".xlsx,.xls" style="display:none;">
                                        </label>
                                    </form>
                                    <div class="progress mt-2" style="height: 20px; display:none;" id="importProgressWrapper">
                                        <div id="importProgressBar" class="progress-bar" role="progressbar" style="width: 0%">0%</div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset("js/validate.js") }}"></script>
    <script>
    //select 2 with multi select
    $(document).ready(function () {
        $('#vendor_list').select2({
            placeholder: 'Select Vendors',
            allowClear: true,
            width: '100%'
        });
    });

    //validation for field
    $("#validateSubmitForm").validate({
        rules: {
            vendor_id: {
                required: true
            },
            redirect_status: {
                required: true
            },
            asr_hash_short: {
                required: true
            }
        },
        messages: {
            vendor_id: {
                required: "Please select at least one vendor."
            },
            redirect_status: {
                required: "Please select a redirect status."
            }
        },
        submitHandler: function(form) {
            var redirectStatus = $('input[name="redirect_status"]:checked')
                            .closest('label')
                            .text()
                            .trim();

            if (confirm(
                "Are you sure you want to save this record?\n\n" +
                "Redirect Status: " + redirectStatus
            )) {
                form.submit();
            }
        }
    });

    //remote button active
    $('.btn-group label').on('click', function () {
        $('.btn-group label').removeClass('active');
        $(this).addClass('active');
    });

     $('#import_file').on('change', function () {

        let formData = new FormData($('#importForm')[0]);

        $('#importProgressWrapper').show();
        $('#importProgressBar')
            .css('width', '0%')
            .text('0%')
            .removeClass('bg-danger');

        $.ajax({
            url: "{{ route('data.reconcile.import') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },

            xhr: function () {
                let xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener("progress", function (e) {
                    if (e.lengthComputable) {
                        let percent = Math.round((e.loaded / e.total) * 100);
                        $('#importProgressBar')
                            .css('width', percent + '%')
                            .text(percent + '%');
                    }
                });

                return xhr;
            },

            success: function (response) {
                $('#importProgressBar')
                    .css('width', '100%')
                    .text('100%');

                alert(response.message);

                setTimeout(function () {
                    $('#importProgressWrapper').hide();
                }, 1000);
            },

            error: function (xhr) {
                let message = "Import failed.";
                if (xhr.responseJSON) {

                    if (xhr.responseJSON.errors) {
                        message = xhr.responseJSON.errors
                            .flat()
                            .join("\n");
                    }
                    else if (xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    $('#importProgressBar')
                    .addClass('bg-danger')
                    .text(message);

                }

                alert(message);
            }
        });

    });


    </script>

@endpush
