@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Vendor Project Assignment List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title d-flex justify-content-between align-items-center">
                            <span>Vendor Project Assignment List</span>
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
                                    <th>Client Project ID</th>
                                    <th>Client Project name</th>
                                    <th>Client Project Code</th>
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
                                    <th>Survey Quota</th>
                                    <th>Client Live Click Quota</th>
                                    <th>Client Test Click Quota</th>
                                    <th>Survey Start Date</th>
                                    <th>Survey End Date</th>
                                    <th>Client Live Link</th>
                                    <th>Client Test Link</th>
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
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $vendor_project->project_vendor_id }}</td>
                                    <td>{{ $vendor_project->vendor?->user->name }}</td>
                                    <td>{{ $survey->survey_code }}</td>
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
                                    <td>{{ $survey->cpi }}</td>
                                    <td>{{ $survey->quota }}</td>
                                    <td>{{ $survey->live_click_quota }}</td>
                                    <td>{{ $survey->test_click_quota }}</td>
                                    <td>{{ $survey->project_start_date }}</td>
                                    <td>{{ $survey->project_end_date }}</td>
                                    <td>{{ $survey->live_url }}</td>
                                    <td>{{ $survey->test_url }}</td>
                                    <td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->vendor?->vendor_id}}&3ASPID={{$survey->survey_code}}&country={{$survey->country?->name }}&ccode=[encrypted12digit]&link=test</td>
                                    <td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->vendor?->vendor_id}}&3ASPID={{ $survey->survey_code}}&country={{$survey->country?->name }}&ccode=[encrypted12digit]&link=live&phash=[newhash]</td>
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


