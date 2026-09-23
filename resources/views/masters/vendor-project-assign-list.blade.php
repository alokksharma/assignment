@extends('layouts.main')
@section('content')
@php
    $isVendor = auth()->user()->hasRole('Vendor');
    $isSuperAdmin = auth()->user()->hasRole('SuperAdmin');
@endphp
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Vendor Project Assignment List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                         <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <form id="searchForm" action="{{ route('vendor.project.assign.list') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2 ml-auto">
                                <div class="d-flex align-items-center mr-4">
                                    <label for="search_vendor_project" class="mb-0 mr-2 text-nowrap">Search</label>
                                    <input id="search_vendor_project" type="text" name="q" class="form-control" placeholder="Search vendor project..." value="{{ request('q') }}">
                                </div>
                                @if($isSuperAdmin)
                                <div class="d-flex align-items-center mr-4">
                                    <label for="supplier_filter" class="mb-0 mr-2 text-nowrap">Supplier</label>
                                    <select id="supplier_filter" name="supplier_id" class="form-control">
                                        <option value="">All Suppliers</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }} - {{ $supplier->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
								@endif

                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>

                                @if(request('q') || request('supplier_id'))
                                    <a href="{{ route('vendor.project.assign.list') }}" class="btn btn-secondary btn-sm">
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
                                    @unless($isVendor)
                                        <th>Vendor Project ID</th>
                                        <th>Vendor Name</th>
                                    @endunless
                                        <th>3AS Project ID</th>
                                    @if($isSuperAdmin)
                                        <th>Client Project ID</th>
                                        <th>Client Project name</th>
                                        <th>Client Project Code</th>
                                    @endif
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
                                    <th>Survey CPI($)</th>
                                    <th>Vendor CPI</th>
                                    <th>Survey Quota</th>
                                    <th>Client Live Click Quota</th>
                                    <th>Client Test Click Quota</th>
                                    <th>Survey Start Date</th>
                                    <th>Survey End Date</th>
                                    @if($isSuperAdmin)
                                        <th>Client Live URL</th>
                                        <th>Client Test URL</th>
                                    @endif
                                    <th>Vendor Test URL</th>
                                    <th>Vendor Live URL</th>
                                    <th>Survey Status</th>
                                    <th>Device Type</th>
                                    <th>Survey Qualification</th>
                                    <th>Survey Target Spec</th>
                                    @can('delete-vendor-assign-projects')
                                    <th>Action</th>
                                    @endcan

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
                                        'B2C' => 0.5,
                                        'B2B' => 0.4,
                                        'HC'  => 0.1,
                                        default => 0,
                                    };

                                    $surveyCpi = $survey->cpi * $multiplier;
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                     @unless($isVendor)
                                    <td>{{ $vendor_project->project_vendor_id }}</td>
                                    <td>{{ $vendor_project->vendor?->user->name }}</td>
                                    @endunless
                                    <td>{{ $survey->survey_code }}</td>
                                    @if($isSuperAdmin)
                                    <td>{{ $survey->project_id }}</td>
                                    <td>{{ $survey->project_name }}</td>
                                    <td>{{ $survey->project_code }}</td>
                                    @endif
                                    <td>{{ $survey->country?->name }}</td>
                                    <td>{{ $survey->language?->name }}</td>
                                    <td>{{ $survey->category }}</td>
                                    <td>{{ $survey->currency }}</td>
                                    <td>{{ $survey->audience_type }}</td>
                                    <td>{{ $survey->pii_collection }}</td>
                                   <td>{{ $survey->application_download ?? 'No' }}</td>
                                    <td>{{ $survey->facial_coding ?? 'No' }}</td>
                                    <td>{{ $survey->project_ir }}</td>
                                    <td>{{ $survey->project_loi }}</td>
                                    <td>{{$surveyCpi }}</td>
                                    <td>{{ optional($survey->vendorProjectAssign()->latest()->first())->temp_cpi}}</td>
                                    <td>{{ $survey->quota }}</td>
                                    <td>{{ $survey->live_click_quota }}</td>
                                    <td>{{ $survey->test_click_quota }}</td>
                                    <td>{{ $survey->project_start_date }}</td>
                                    <td>{{ $survey->project_end_date }}</td>
                                     @if($isSuperAdmin)
                                        <td>{{ $survey->live_url }}</td>
                                        <td>{{ $survey->test_url }}</td>
                                    @endif
                                   <!-- <td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->vendor?->vendor_id}}&3ASPID={{$survey->survey_code}}&country={{$survey->country?->name }}&ccode=[encrypted12digit]&link=test</td>
                                    <td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->vendor?->vendor_id}}&3ASPID={{ $survey->survey_code}}&country={{$survey->country?->name }}&ccode=[encrypted12digit]&link=live&phash=[newhash]</td>-->
									<td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->project_vendor_id}}&3ASPID={{$survey->survey_code}}&country={{$survey->country?->name }}&link=test&ccode=[PanelistID]</td>
                                    <td>{{ config('app.url') }}/s2c?VendorID={{ $vendor_project->project_vendor_id}}&3ASPID={{ $survey->survey_code}}&country={{$survey->country?->name }}&link=live&ccode=[PanelistID]</td>
                                    <td>{{ $survey->status }}</td>
                                    <td>{{ !empty($survey->device_type) ? $survey->device_type : 'all' }}</td>
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
                                    @can('delete-vendor-assign-projects')
                                    <th>
                                        <form action="{{ route('vendor.project.assign.destroy', $vendor_project) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="la la-trash"></i> Delete
                                            </button>
                                        </form>
                                    </th>
                                    @endcan
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

