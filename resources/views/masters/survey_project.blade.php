@extends('layouts.main')
@section('content')
@php
    $isVendor = auth()->user()->hasRole('Vendor');
    $isSuperAdmin = auth()->user()->hasRole('SuperAdmin');
@endphp
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Survey List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h5 class="mb-0">3AS Survey List</h5>
                            <form id="searchForm"  action="{{ route('survey.project') }}" method="GET" class="form-inline">
                                <div class="form-group mr-2">
                                     <label for="search" class="pr-3 font-weight-bold">Search</label>
                                    <input type="text" name="q" class="form-control me-2" placeholder="Search survey..." value="{{ request('q') }}">
                                </div>
                                 <div class="form-group mr-2">
                                    <label for="vpi" class="pr-3 font-weight-bold">Project Status</label>
                                    <select name="survey_status" id="vpi" class="form-control vendor_list select2">
                                        <option value="" {{ blank(request('survey_status')) ? 'selected' : '' }}>All</option>
                                        <option value="Live" {{ request('survey_status') == 'Live' ? 'selected' : '' }}>Live</option>
                                        <option value="Pause" {{ request('survey_status') == 'Pause' ? 'selected' : '' }}>Pause</option>
                                        <option value="Test" {{ request('survey_status') == 'Test' ? 'selected' : '' }}>Test</option>
                                    </select>
                                </div>
								@if($isSuperAdmin)
                                <div class="form-group mr-2">
                                    <label for="supplier_id" class="pr-3 font-weight-bold">Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control vendor_list select2">
                                        <option value="">All Suppliers</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }} - {{ $supplier->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
								@endif
                                <button type="submit" class="btn btn-light btn-sm mr-2">
                                    <i class="fa fa-search">Filter</i>
                                </button>
                                @if(request('q'))
                                    <a href="{{ route('survey.project') }}" class="btn btn-secondary ms-2">
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
                                    <th>3AS Project ID</th>
                                     @if($isSuperAdmin)
                                    <th>Client Project ID</th>
                                    <th>Client Project name</th>
                                    <th>Client Project Code</th>
                                    @endif
                                    <th>Survey Country</th>
                                    <th>Survey Language</th>
                                    <th>Survey Category</th>
                                    <th>Survay Currency</th>
                                    <th>Audience Type</th>
                                    <th>PII Collection</th>
                                    <th>Application Download</th>
                                    <th>Facial Coding</th>
                                    <th>Survey IR</th>
                                    <th>Survey LOI</th>
                                    <th>Survey CPI ($)</th>
                                     @unless($isVendor)
                                    <th>Vendor CPI</th>
                                    @endunless
                                    <th>Survey Quota</th>
                                    <th>Client Live Click Quota</th>
                                    <th>Client Test Click Quota</th>
                                    <th>Survey Start Date</th>
                                    <th>Survey End Date</th>
                                     @if($isSuperAdmin)
                                        <th>Client Live URL</th>
                                        <th>Client Test URL</th>
                                    @endif
                                    <th>3AS Live URL</th>
                                    <th>3AS Test URL </th>
                                    @unless($isVendor)
									    <th>3AS Redirects </th>
                                    @endunless
                                    <th>Survey Status</th>
                                    <th>Device Type</th>
                                    <th>Survey Qualification</th>
                                    <th>Survey Target Spec</th>
                                     @can('create-vendor-assign-projects')
                                    <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse($surveys as $survey)
                                    @php
                                        $i++;
                                        $colspan = $isVendor ? 26 : 30; // Adjust if you add/remove columns
                                        $multiplier = match ($survey->audience_type) {
                                            'B2C' => 0.5,
                                            'B2B' => 0.4,
                                            'HC'  => 0.1,
                                            default => 0,
                                        };

                                        $surveyCpi = $survey->cpi * $multiplier;
                                        $alreadyAssignedToSurvey = $isVendor && $survey->vendorProjectAssign()
                                            ->where('vendor_id', auth()->user()->vendor?->id)
                                            ->exists();
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $survey->survey_code }}</td>
                                    @if($isSuperAdmin)
                                        <td>{{ ($survey->supplier?->code).''.$survey->project_id }}</td>
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
                                    <td> {{$surveyCpi }}</td>
                                     @unless($isVendor)
                                    <td>{{ optional($survey->vendorProjectAssign()->latest()->first())->temp_cpi}}</td>
                                    @endunless
                                    <td>{{ $survey->quota }}</td>
                                    <td>{{ $survey->live_click_quota }}</td>
                                    <td>{{ $survey->test_click_quota }}</td>
                                    <td>{{ $survey->project_start_date }}</td>
                                    <td>{{ $survey->project_end_date }}</td>
                                   @if($isSuperAdmin)
                                        <td>{{ $survey->live_url }}</td>
                                        <td>{{ $survey->test_url }}</td>
                                    @endif
                                    <td>{{ config('app.url') }}/s2c?3ASPID={{$survey->survey_code }}&country={{$survey->country?->name }}&link=test&ccode=[PanelistID]</td>
                                    <td>{{ config('app.url') }}/s2c?3ASPID={{$survey->survey_code }}&country={{$survey->country?->name }}&link=live&ccode=[PanelistID]</td>
									 @unless($isVendor)
                                    <td>
										<ul>
											<li>Complete:-  {{ config('app.url') }}/c2s?ccode=[encrypted12digit]&status=1</li>
											<li>Terminate:- {{ config('app.url') }}/c2s?ccode=[encrypted12digit]&status=2</li>
										    <li>Quotafull:- {{ config('app.url') }}/c2s?ccode=[encrypted12digit]&status=3</li>
								            <li>Quality:-   {{ config('app.url') }}/c2s?ccode=[encrypted12digit]&status=4</li>
										</ul>
									</td>
                                    @endunless
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
                                    @can('create-vendor-assign-projects')
                                    <th>
                                        @if(!$alreadyAssignedToSurvey)
                                            <a href="{{ route('vendor.project.assign', $survey->id)}}" class="btn btn-sm btn-warning"><i class="la la-plus"></i></a>
                                        @else
                                            <span class="badge badge-secondary">Assigned</span>
                                        @endif
                                    </th>
                                    @endcan
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="32" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                                    {{-- pagination --}}
                                    @if ($surveys->hasPages())
                                            <tr>
                                        <td colspan="32" class="text-left">
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


