@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Survey List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h5 class="mb-0">Client Survey List</h5>
                            <form id="searchForm"  action="{{ route('surveys') }}" method="GET" class="d-flex">
                                <input type="text" name="q" class="form-control me-2" placeholder="Search survey..." value="{{ request('q') }}">
                                @if(request('q'))
                                    <a href="{{ route('surveys') }}" class="btn btn-secondary ms-2">
                                        Reset
                                    </a>
                                @endif
                            </form>
                        </div>
                        @if(session('success'))
                            <div class="alert alert-success mt-3 mb-0">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project Id</th>
                                    <th>Project name</th>
                                    <th>Project Code</th>
                                    <th>Country</th>
                                    <th>Language</th>
                                    <th>Category</th>
                                    <th>Currency</th>
                                    <th>Audience Type</th>
                                    <th>Pii Collection</th>
                                    <th>Application Download</th>
                                    <th>Facial Coding</th>
                                    <th>Project Ir</th>
                                    <th>Project loi</th>
                                    <th>CPI</th>
                                    <th>Quota</th>
                                    <th>Live Click Quota</th>
                                    <th>Test Click Quota</th>
                                    <th>Project Start Date</th>
                                    <th>Project End Date</th>
                                    <th>Live Url</th>
                                    <th>Test Url</th>
                                    <th>Status</th>
                                    <th>Device Type</th>
                                     <th>Qualification</th>
                                    <th>Target Spec</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse($surveys as $survey)
                                    @php
                                    $i++;
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $survey->project_id }}</td>
                                    <td>{{ $survey->project_name }}</td>
                                    <td>{{ $survey->project_code }}</td>
                                    <td>{{ $survey->country?->name }}</td>
                                    <td>{{ $survey->language?->name }}</td>
                                    <td>{{ $survey->category }}</td>
                                    <td>{{ $survey->currency }}</td>
                                    <td>{{ $survey->audience_type }}</td>
                                    <td>{{ $survey->pii_collection }}</td>
                                    <td>{{ $survey->application_download }}</td>
                                    <td>{{ $survey->facial_coding }}</td>
                                    <td>{{ $survey->project_ir }}</td>
                                    <td>{{ $survey->project_loi }}</td>
                                    <td>${{ $survey->cpi }}</td>
                                    <td>{{ $survey->quota }}</td>
                                    <td>{{ $survey->live_click_quota }}</td>
                                    <td>{{ $survey->test_click_quota }}</td>
                                    <td>{{ $survey->project_start_date }}</td>
                                    <td>{{ $survey->project_end_date }}</td>
                                    <td>{{ $survey->live_url }}</td>
                                    <td>{{ $survey->test_url }}</td>
                                    <td>{{ $survey->status }}</td>
                                    <td>{{ $survey->device_type }}</td>
                                    <td>{{ $survey->questions->pluck('question')->implode(', ') }}</td>
                                    {{-- <td>{!! $survey->target_spec !!}</td> --}}
                                    <th><button
                                            type="button"
                                            class="btn btn-sm btn-primary show-details"
                                            data-toggle="modal"
                                            data-target="#modalUpdate"
                                            data-id="{{ $survey->id }}"
                                            data-target-spec="{{ $survey->target_spec }}"
                                            data-name="{{ $survey->project_name }}"
                                        >
                                            <p>
                                            <i class="la la-hand-pointer-o"></i>
                                            Show details</p>
                                        </button>
                                    </th>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                                    {{-- pagination --}}
                                    @if ($surveys->hasPages())
                                            <tr>
                                        <td colspan="26" class="text-left">
                                            <div class="d-flex justify-content-left">
                                                {{ $surveys->appends(request()->query())->links('pagination::bootstrap-4') }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $('.show-details').click(function () {

            let targetSpec = $(this).data('target-spec');

            $('#targetSpec').html(
                '<div class="table-responsive">' + targetSpec + '</div>'
            );

        });

    </script>
@endpush


