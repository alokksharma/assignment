@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-4">
                   <div class="card-header text-white d-flex justify-content-between align-items-center">


                        <form  method="GET" class="form-inline">

                            <div class="form-group mr-2">
                                <label for="vendor_list">3AS Project ID</label>
                               <select name="three_as_project_id" id="vendor_list" class="form-control vendor_list select2">
                                    <option value="">3AS Project ID</option>

                                    @foreach($threeAsProjects as $project)
                                        <option
                                            value="{{ $project->getAttribute('3ASProjectID') }}"
                                            {{ $selectedProjectId == $project->getAttribute('3ASProjectID') ? 'selected' : '' }}>
                                            {{ $project->getAttribute('3ASProjectID') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mr-2">
                                <label for="vpi">Vendor Project ID</label>
                                 <select name="vendor_project_id" id="vpi" class="form-control vendor_list select2">
                                    <option value="">Vendor Project ID</option>
                                    @foreach($vendorProjects as $vendor)
                                        <option value="{{ $vendor->project_vendor_id }}"
                                            {{ request('vendor_project_id') == $vendor->project_vendor_id ? 'selected' : '' }}>
                                            {{ $vendor->project_vendor_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mr-2">
                                <label for="from_date" class="pr-3 font-weight-bold">From Date</label>
                                <input type="date"
                                    id="from_date"
                                    name="from_date"
                                    class="form-control"
                                    value="{{ request('from_date') }}">
                            </div>
                            <div class="form-group mr-2">
                               <label for="to_date" class="pr-3 font-weight-bold">To Date</label>
                                </label>
                                <input type="date"
                                    name="to_date"
                                    class="form-control"
                                    value="{{ request('to_date') }}">
                            </div>

                            <button type="submit" class="btn btn-light btn-sm mr-2">
                                <i class="fa fa-search">Data Filter</i>
                            </button>

                            <a href="{{ route('vendor.project.report') }}" class="btn btn-warning btn-sm">
                                Reset
                            </a>x
                        </form>
                        @can('report-export')
                            <a href="{{ route('proof.export', request()->all()) }}"
                            class="btn btn-success">
                                Export Panel Data
                            </a>
                        @endcan
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Vendor Project ID</th>
                                        <th>Complete</th>
                                        <th>Terminate</th>
                                        <th>Quatofull</th>
                                        <th>Quality</th>
                                        <th>IR</th>
                                        <th>LOI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($reports as $index => $report)
                                <tr class="project-row bg-primary text-white">
                                    <td colspan="8" class="p-0">
                                        <a class="project-header bg-primary text-white" data-target="project-{{ $index }}" style="cursor:pointer">
                                            <div>
                                                <i class="fa fa-chevron-right mr-2 toggle-icon"></i>
                                                <strong>{{ $report['project_id'] }}</strong>
                                                <span class="badge badge-light ml-2">
                                                    {{ count($report['vendors']) }} Vendors
                                                </span>

                                                <strong>Complete:</strong> {{ $report['complete'] }}
                                                &nbsp; | &nbsp;
                                                <strong>IR:</strong> {{ $report['ir'] }}%
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                                <tr class="project-{{ $index }}">
                                    <td scope="row">Total</td>
                                    <td>{{ $report['complete'] }}</td>
                                    <td>{{ $report['terminate'] }}</td>
                                    <td>{{ $report['quotafull'] }}</td>
                                    <td>{{ $report['quality'] }}</td>
                                    <td>{{ $report['ir'] }}%</td>
                                    <td> <p>average: {{ ( $report['complete'] == 0) ? "00:00:00 min" : $report['average'] }}</p>
                                        <p> median: {{  ( $report['complete'] == 0) ? "00:00:00 min" : $report['median'] }}</p>
                                    </td>
                                </tr>
                                @foreach ($report['vendors'] as $item)

                                    <tr class="project-{{ $index }}">
                                         <td>{{$item['vendor_project_id']}}</td>
                                         <td>{{$item['complete']}}</td>
                                         <td>{{$item['terminate']}}</td>
                                         <td>{{$item['quotafull']}}</td>
                                         <td>{{$item['quality']}}</td>
                                         <td>{{$item['ir']}}%</td>
                                          <td> <p>average: {{ $report['average'] }}</p>
                                                <p> median: {{ $report['median'] }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                                </span>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
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

<script>
      $(document).ready(function () {
        $('.vendor_list').select2({
            placeholder: 'Select Project',
            allowClear: true,
            width: '100%'
        });
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

   $(function () {

    $('.project-header').on('click', function () {

        let target = $(this).data('target');

        $('.' + target).toggleClass('d-none');

        $(this).find('.toggle-icon')
            .toggleClass('fa-chevron-right fa-chevron-down');
    });

});

</script>
@endpush
