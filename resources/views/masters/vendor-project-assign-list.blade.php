@extends('layouts.main')
@section('content')
@php
    $isVendor = auth()->user()->hasRole('Vendor');

@endphp
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Vendor Project Assignment List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                         <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h5 class="mb-0">Vendor Project Assignment List</h5>
                            <form id="searchForm"  action="{{ route('vendor.project.assign.list') }}" method="GET" class="d-flex">
                                <input type="text" name="q" class="form-control me-2" placeholder="Search vendor project..." value="{{ request('q') }}">
                                @if(request('q'))
                                    <a href="{{ route('vendor.project.assign.list') }}" class="btn btn-secondary ms-2">
                                        Reset
                                    </a>
                                @endif
                            </form>
                        </div>

                            @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vendor Project ID</th>
									<th>Vendor Name</th>
                                    <th>3AS Project ID</th>
                                    @unless($isVendor)
                                        <th>Client Project ID</th>
                                        <th>Client Project name</th>
                                        <th>Client Project Code</th>
                                    @endunless
                                    <th>Survey Country</th>
                                    <th>Survey Language</th>
                                    <th>Survey Category</th>
                                    <th>Survey Currency</th>
                                    <th>Audience Type</th>
                                    <th>Survey PII Collection</th>
                                    <th>Application Download</th>
                                    <th>Facial Coding</th>
                                    <th>Survey IR</th>
                                    <th>Survey LOI</th>
                                    <th>Survey CPI</th>
                                    <th>Vendor CPI</th>
                                    <th>Survey Quota</th>
                                    <th>Client Live Click Quota</th>
                                    <th>Client Test Click Quota</th>
                                    <th>Survey Start Date</th>
                                    <th>Survey End Date</th>
                                    @unless($isVendor)
                                        <th>Client Live URL</th>
                                        <th>Client Test URL</th>
                                    @endunless
                                    <th>Vendor Test URL</th>
                                    <th>Vendor Live URL</th>
                                    <th>Survey Status</th>
                                    <th>Device Type</th>
                                    <th>Survey Qualification</th>
                                    <th>Survey Target Spec</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse($vendor_projects as $vendor_project)
                                    @php
                                    $i++;
                                    $survey = $vendor_project->survey;
                                    $colspan = $isVendor ? 26 : 30; // Adjust if you add/remove columns
                                    $multiplier = match ($survey->audience_type) {
                                        'B2C' => 0.06,
                                        'B2B' => 0.04,
                                        'HC'  => 0.01,
                                        default => 0,
                                    };

                                    $surveyCpi = $survey->cpi * $multiplier;
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $vendor_project->project_vendor_id }}</td>
                                    <td>{{ $vendor_project->vendor?->user->name }}</td>
                                    <td>{{ $survey->survey_code }}</td>
                                    @unless($isVendor)
                                        <td>{{ $survey->project_id }}</td>
                                        <td>{{ $survey->project_name }}</td>
                                        <td>{{ $survey->project_code }}</td>
                                    @endunless
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
                                    <td>$ {{$surveyCpi }}</td>
                                    <td>{{ optional($survey->vendorProjectAssign()->latest()->first())->temp_cpi}}</td>
                                    <td>{{ $survey->quota }}</td>
                                    <td>{{ $survey->live_click_quota }}</td>
                                    <td>{{ $survey->test_click_quota }}</td>
                                    <td>{{ $survey->project_start_date }}</td>
                                    <td>{{ $survey->project_end_date }}</td>
                                     @unless($isVendor)
                                        <td>{{ $survey->live_url }}</td>
                                        <td>{{ $survey->test_url }}</td>
                                    @endunless
                                   <!-- <td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->vendor?->vendor_id}}&3ASPID={{$survey->survey_code}}&country={{$survey->country?->name }}&ccode=[encrypted12digit]&link=test</td>
                                    <td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->vendor?->vendor_id}}&3ASPID={{ $survey->survey_code}}&country={{$survey->country?->name }}&ccode=[encrypted12digit]&link=live&phash=[newhash]</td>-->
									<td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->project_vendor_id}}&3ASPID={{$survey->survey_code}}&country={{$survey->country?->name }}&link=test&ccode=[PanelistID]</td>
                                    <td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->project_vendor_id}}&3ASPID={{ $survey->survey_code}}&country={{$survey->country?->name }}&link=live&ccode=[PanelistID]</td>
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
                                        <td colspan="30" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                                    {{-- pagination --}}
                                    @if ($vendor_projects->hasPages())
                                            <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="d-flex justify-content-center">
                                                {{ $vendor_projects->appends(request()->query())->links('pagination::bootstrap-4') }}
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


