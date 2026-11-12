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
                            <form id="searchForm"  action="{{ route('surveys') }}" method="GET" class="form-inline">
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
                                <button type="submit" class="btn btn-light btn-sm mr-2">
                                    <i class="fa fa-search">Filter</i>
                                </button>
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

                        @if(session('error'))
                            <div class="alert alert-danger mt-3 mb-0">
                                {{ session('error') }}
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
                                    <th>Demographics</th>
                                    <th>Quota Qualifications</th>


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
                                    <td>{{ ($survey->supplier?->code ?? 'mx').''.$survey->project_id }}</td>
                                    <td>{{ $survey->project_name }}</td>
                                    <td>{{ $survey->project_code }}</td>
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
                                    <td>${{ $survey->cpi }}</td>
                                    <td>{{ $survey->quota }}</td>
                                    <td>{{ $survey->live_click_quota }}</td>
                                    <td>{{ $survey->test_click_quota }}</td>
                                    <td>{{ $survey->project_start_date }}</td>
                                    <td>{{ $survey->project_end_date }}</td>
                                    <td>@if ($survey->live_url)
                                        {{ $survey->live_url }}
                                        @elseif ($survey->supplier?->code == "ZAM")
                                            <a href="{{ route('api.zamp-link',['survey' => $survey->id, 'SurveyId' => $survey->project_id]) }}" class="btn btn-sm btn-primary">
                                                Generate link
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $survey->test_url }}</td>
                                    <td>{{ $survey->status }}</td>
                                    <td>{{ !empty($survey->device_type) ? $survey->device_type : 'all' }}</td>
                                    <td>
                                        @php
                                           $questions = $survey->surveyQuestionWithOptions($survey->api_project_id, $survey->surveyQuestions->pluck('question_id')->toArray());
                                        @endphp
                                        @forelse ($questions as $sq)
                                            @php
                                                $mappedOptions = $sq?->mapped_options ?? [];
                                                if (is_string($mappedOptions)) {
                                                    $mappedOptions = json_decode($mappedOptions, true) ?? [];
                                                }

                                                $mappedOptions = is_array($mappedOptions) ? collect($mappedOptions) : collect();
                                                $mappedOptionLabels = $mappedOptions
                                                    ->map(function ($option, $key) use ($sq, $survey) {
                                                        // Construct keys for both 1-based and 0-based index
                                                        $matchedSq = $survey->surveyQuestions->firstWhere('question_id', $sq?->question_id);
                                                        return $option['AnswerText']
                                                            ?? $option
                                                            ??'';
                                                    })
                                                    ->filter(fn ($value) => is_string($value) ? trim($value) !== '' : !empty($value))
                                                    ->values();
                                            @endphp
                                            {{ $sq?->question_id }} - {{ $sq?->question }}<br>
                                            {{ $mappedOptionLabels->isNotEmpty() ? $mappedOptionLabels->implode(', ') : (  optional($survey->surveyQuestions?->firstWhere('question_id', $sq?->question_id))->surveyOption?->pluck('option_text')->implode(', ') ?? 'N/A') }}</br>
                                        @empty
                                            <span class="text-muted">N/A</span> <br>
                                        @endforelse
                                    </td>

                                    {{-- <td>{!! $survey->target_spec !!}</td> --}}
                                    <th>
                                        @if ($survey->target_spec)
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-primary show-details"
                                                data-toggle="modal"
                                                data-target="#modalUpdate"
                                                data-id="{{ $survey->id }}"
                                                data-target-spec="{{ $survey->target_spec }}"
                                                data-name="{{ $survey->project_name }}"
                                            >
                                                <p><i class="la la-hand-pointer-o"></i> Show details</p>
                                            </button>
                                        @else
                                            <span class="text-muted">N/A</span>

                                        @endif
                                    </th>
                                     <th>
                                       @if ($survey->supplier?->code == "ZAM")
                                            <button type="button" class="btn btn-sm btn-primary show-details"
                                                data-toggle="modal" data-target="#modalUpdate"
                                                data-demo-url="{{ route('surveys.demographics', $survey) }}"
                                                data-name="{{ $survey->project_name }}">
                                                <p><i class="la la-hand-pointer-o"></i> Demographics</p>
                                            </button>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </th>
                                     <td>
                                        @php
                                            $quotaQualificationList = ($survey->surveyQuotaQualifications ?? collect())
                                                ->map(function ($quota) {
                                                    $questionId = $quota->question_id ?? null;
                                                    $answerText = trim((string) ($quota->answer_text ?? $quota->answer_code ?? ''));
                                                    $qualificationCount = (int) ($quota->total_qualification_count ?? 0);
                                                    $quotaCount = (int) ($quota->total_quota_count ?? 0);

                                                    $parts = [];
                                                    if ($questionId !== null && $questionId !== '') {
                                                        $parts[] = 'Q' . $questionId;
                                                    }
                                                    if ($answerText !== '') {
                                                        $parts[] = $answerText;
                                                    }
                                                    if ($qualificationCount > 0 || $quotaCount > 0) {
                                                        $parts[] = '(' . $qualificationCount . '/' . $quotaCount . ')';
                                                    }

                                                    if (empty($parts)) {
                                                        return null;
                                                    }

                                                    return e(implode(' - ', $parts));
                                                })
                                                ->filter()
                                                ->implode('<br>');
                                        @endphp

                                        {!! $quotaQualificationList !== '' ? $quotaQualificationList : 'N/A' !!}
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="27" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                                    {{-- pagination --}}
                                    @if ($surveys->hasPages())
                                            <tr>
                                        <td colspan="27" class="text-left">
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
        $(document).on('click', '.show-details', function () {
            let targetSpec = $(this).data('target-spec') || '';
            let $targetSpec = $('#targetSpec');

            if (!targetSpec) {
                $targetSpec.html('<div class="text-muted">No details available.</div>');
                return;
            }

            const pageSize = 10;
            const $temp = $('<div>').html(String(targetSpec));
            const $table = $temp.find('table').first();

            if ($table.length) {
                const $rows = $table.find('tbody tr');
                const totalRows = $rows.length;
                const totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
                let currentPage = 1;

                function renderPage() {
                    const start = (currentPage - 1) * pageSize;
                    const end = start + pageSize;
                    const $pageRows = $rows.slice(start, end).clone();

                    const $clone = $table.clone();
                    $clone.find('tbody').empty().append($pageRows);

                    const $pager = $(
                        '<div class="d-flex justify-content-between align-items-center mt-3">' +
                        '<button type="button" class="btn btn-sm btn-outline-secondary prev-page" ' + (currentPage === 1 ? 'disabled' : '') + '>Previous</button>' +
                        '<span class="text-muted small">Page <strong>' + currentPage + '</strong> of ' + totalPages + '</span>' +
                        '<button type="button" class="btn btn-sm btn-outline-secondary next-page" ' + (currentPage === totalPages ? 'disabled' : '') + '>Next</button>' +
                        '</div>'
                    );

                    $targetSpec.html($clone.prop('outerHTML') + $pager.prop('outerHTML'));

                    $targetSpec.find('.prev-page').off('click').on('click', function () {
                        if (currentPage > 1) {
                            currentPage -= 1;
                            renderPage();
                        }
                    });

                    $targetSpec.find('.next-page').off('click').on('click', function () {
                        if (currentPage < totalPages) {
                            currentPage += 1;
                            renderPage();
                        }
                    });
                }

                if (totalRows === 0) {
                    $targetSpec.html('<div class="text-muted">No details available.</div>');
                    return;
                }

                renderPage();
                return;
            }

            $targetSpec.html('<div class="table-responsive">' + targetSpec + '</div>');


        });
    </script>
    <script>
        let demoUrl = null;

        function loadDemographics(page) {
            $('#targetSpec').html('<div class="text-muted">Loading...</div>');
            $.get(demoUrl, { page: page })
                .done(html => $('#targetSpec').html(html))
                .fail(() => $('#targetSpec').html('<div class="text-danger">Unable to load demographics.</div>'));
        }

        $(document).on('click', '.demo-page:not([disabled])', function () {
            loadDemographics($(this).data('page'));
        });

        $(document).on('click', '.show-details', function () {
            // Demographics button: load page 1 from the server
            if ($(this).data('demo-url')) {
                demoUrl = $(this).data('demo-url');
                loadDemographics(1);
                return;
            }

            // Existing target_spec behaviour (client-side paging)
            let targetSpec = $(this).data('target-spec') || '';
            let $targetSpec = $('#targetSpec');

            if (!targetSpec) {
                $targetSpec.html('<div class="text-muted">No details available.</div>');
                return;
            }

            const pageSize = 10;
            const $temp = $('<div>').html(String(targetSpec));
            const $table = $temp.find('table').first();

            if ($table.length) {
                const $rows = $table.find('tbody tr');
                const totalRows = $rows.length;
                const totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
                let currentPage = 1;

                function renderPage() {
                    const start = (currentPage - 1) * pageSize;
                    const $pageRows = $rows.slice(start, start + pageSize).clone();
                    const $clone = $table.clone();
                    $clone.find('tbody').empty().append($pageRows);

                    const pager =
                        '<div class="d-flex justify-content-between align-items-center mt-3">' +
                        '<button type="button" class="btn btn-sm btn-outline-secondary prev-page" ' + (currentPage === 1 ? 'disabled' : '') + '>Previous</button>' +
                        '<span class="text-muted small">Page <strong>' + currentPage + '</strong> of ' + totalPages + '</span>' +
                        '<button type="button" class="btn btn-sm btn-outline-secondary next-page" ' + (currentPage === totalPages ? 'disabled' : '') + '>Next</button>' +
                        '</div>';

                    $targetSpec.html($clone.prop('outerHTML') + pager);

                    $targetSpec.find('.prev-page').off('click').on('click', function () {
                        if (currentPage > 1) { currentPage--; renderPage(); }
                    });
                    $targetSpec.find('.next-page').off('click').on('click', function () {
                        if (currentPage < totalPages) { currentPage++; renderPage(); }
                    });
                }

                if (totalRows === 0) {
                    $targetSpec.html('<div class="text-muted">No details available.</div>');
                    return;
                }
                renderPage();
                return;
            }

            $targetSpec.html('<div class="table-responsive">' + targetSpec + '</div>');
        });
    </script>
@endpush


