@extends('layouts.main')
@section('content')
@php
    $isVendor = auth()->user()->hasRole('Vendor');
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
                            <form id="searchForm"  action="{{ route('survey.project') }}" method="GET" class="d-flex">
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
                                    <th>3AS Project ID</th>
                                     @unless($isVendor)
                                    <th>Client Project ID</th>
                                    <th>Client Project name</th>
                                    <th>Client Project Code</th>
                                    @endunless
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
                                    <th>3AS Live URL</th>
                                    <th>3AS Test URL </th>
                                    @unless($isVendor)
									    <th>3AS Redirects </th>
                                    @endunless
                                    <th>Survey Status</th>
                                    <th>Device Type</th>
                                    <th>Survey Qualification</th>
                                    <th>Survey Target Spec</th>
                                    <th>Action</th>
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
                                            'B2C' => 0.06,
                                            'B2B' => 0.04,
                                            'HC'  => 0.01,
                                            default => 0,
                                        };

                                        $surveyCpi = $survey->cpi * $multiplier;
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
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
                                    <th><a href="{{ route('vendor.project.assign', $survey->id)}}" class="btn btn-sm btn-warning"><i class="la la-plus"></i></a></th>
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


