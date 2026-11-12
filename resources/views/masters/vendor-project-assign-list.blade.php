
@extends('layouts.main')

@section('content')

@php
    $isVendor = auth()->user()->hasRole('Vendor');
    $isSuperAdmin = auth()->user()->hasAnyRole(['SuperAdmin', 'Member']);
@endphp

<div class="content">
    <div class="container-fluid">

        <h4 class="page-title">Vendor Project Assignment List</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    {{-- Search Filters --}}
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                            <form id="searchForm"
                                  action="{{ route('vendor.project.assign.list') }}"
                                  method="GET"
                                  class="d-flex flex-wrap align-items-center gap-2 ml-auto">

                                <div class="d-flex align-items-center mr-4">
                                    <label for="search_vendor_project" class="mb-0 mr-2 text-nowrap">
                                        Search
                                    </label>

                                    <input id="search_vendor_project"
                                           type="text"
                                           name="q"
                                           class="form-control"
                                           placeholder="Search vendor project..."
                                           value="{{ request('q') }}">
                                </div>

                                <div class="d-flex align-items-center mr-4">
                                    <label for="survey_status_filter" class="mb-0 mr-2 text-nowrap">
                                        Project Status
                                    </label>

                                    <select id="survey_status_filter"
                                            name="survey_status"
                                            class="form-control">
                                        <option value="">All Statuses</option>

                                        <option value="Live"
                                            {{ request('survey_status') == 'Live' ? 'selected' : '' }}>
                                            Live
                                        </option>

                                        <option value="Pause"
                                            {{ request('survey_status') == 'Pause' ? 'selected' : '' }}>
                                            Pause
                                        </option>

                                        <option value="Test"
                                            {{ request('survey_status') == 'Test' ? 'selected' : '' }}>
                                            Test
                                        </option>
                                    </select>
                                </div>

                                @if($isSuperAdmin)
                                    <div class="d-flex align-items-center mr-4">
                                        <label for="supplier_filter" class="mb-0 mr-2 text-nowrap">
                                            Supplier
                                        </label>

                                        <select id="supplier_filter"
                                                name="supplier_id"
                                                class="form-control">
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

                                <button type="submit" class="btn btn-primary btn-sm">
                                    Filter
                                </button>

                                @if(request('q') || request('survey_status') || request('supplier_id'))
                                    <a href="{{ route('vendor.project.assign.list') }}"
                                       class="btn btn-secondary btn-sm">
                                        Reset
                                    </a>
                                @endif

                            </form>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>

                    {{-- Project Assignment Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered" id="vendorProjectTable">

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
                                        <th>Client Project Name</th>
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

                                    @unless($isVendor)
                                        <th>Survey CPI ($)</th>
                                    @endunless

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
                                        <th class="no-copy">Action</th>
                                    @endcan

                                    @if($isVendor || $isSuperAdmin)
                                        <th class="no-copy">Project Template [Email/Word/Excel]</th>
                                    @endif
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

                                        $multiplier = match ($survey->audience_type) {
                                            'B2C' => 0.8,
                                            'B2B' => 0.7,
                                            'HC'  => 0.1,
                                            default => 0,
                                        };

                                        $surveyCpi = $survey->cpi * $multiplier;
                                        $countryName = $survey->country?->name ?? '';

                                        $vendorTestUrl = config('app.url')
                                            . '/s2c?VendorID='
                                            . $vendor_project->project_vendor_id
                                            . '&3ASPID='
                                            . $survey->survey_code
                                            . '&country='
                                            . rawurlencode($countryName)
                                            . '&link=test&ccode=[PanelistID]';

                                        $vendorLiveUrl = config('app.url')
                                            . '/s2c?VendorID='
                                            . $vendor_project->project_vendor_id
                                            . '&3ASPID='
                                            . $survey->survey_code
                                            . '&country='
                                            . rawurlencode($countryName)
                                            . '&link=live&ccode=[PanelistID]';

                                        $vendorCpi = optional(
                                            $survey->vendorProjectAssign()->latest()->first()
                                        )->temp_cpi;

                                        $qualification = $survey->questions
                                            ->pluck('question')
                                            ->implode(', ');
                                    @endphp

                                    <tr>
                                        <th scope="row">{{ $i }}</th>

                                        @unless($isVendor)
                                            <td>{{ $vendor_project->project_vendor_id }}</td>
                                            <td>{{ $vendor_project->vendor?->user?->name }}</td>
                                        @endunless

                                        <td>{{ $survey->survey_code }}</td>

                                        @if($isSuperAdmin)
                                            <td>{{ $survey->project_id }}</td>
                                            <td>{{ $survey->project_name }}</td>
                                            <td>{{ $survey->project_code }}</td>
                                        @endif

                                        <td>{{ $countryName }}</td>
                                        <td>{{ $survey->language?->name }}</td>
                                        <td>{{ $survey->category }}</td>
                                        <td>{{ $survey->currency }}</td>
                                        <td>{{ $survey->audience_type }}</td>
                                        <td>{{ $survey->pii_collection }}</td>
                                        <td>{{ $survey->application_download ?? 'No' }}</td>
                                        <td>{{ $survey->facial_coding ?? 'No' }}</td>
                                        <td>{{ $survey->project_ir }}</td>
                                        <td>{{ $survey->project_loi }}</td>

                                        @unless($isVendor)
                                            <td>{{ $surveyCpi }}</td>
                                        @endunless

                                        <td>{{ $vendorCpi }}</td>
                                        <td>{{ $survey->quota }}</td>
                                        <td>{{ $survey->live_click_quota }}</td>
                                        <td>{{ $survey->test_click_quota }}</td>
                                        <td>{{ $survey->project_start_date }}</td>
                                        <td>{{ $survey->project_end_date }}</td>

                                        @if($isSuperAdmin)
                                            <td>{{ $survey->live_url }}</td>
                                            <td>{{ $survey->test_url }}</td>
                                        @endif

                                        <td>{{ $vendorTestUrl }}</td>
                                        <td>{{ $vendorLiveUrl }}</td>
                                        <td>{{ $survey->status }}</td>
                                        <td>{{ !empty($survey->device_type) ? $survey->device_type : 'all' }}</td>
                                        <td>{{ $qualification }}</td>

                                        {{-- Target specification: keep actual text separately for copying --}}
                                        <td>
                                             <th>
                                        @if ($survey->target_spec)
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-primary show-details"
                                                data-toggle="modal"
                                                data-target="#modalUpdate"
                                                data-id="{{ $survey->id }}"
                                                data-target-spec="{!! $survey->target_spec !!}"
                                                data-name="{{ $survey->project_name }}"
                                            >
                                                <p><i class="la la-hand-pointer-o"></i> Show details</p>
                                            </button>
                                        @elseif ($survey->supplier?->code == "ZAM")
                                            <button type="button" class="btn btn-sm btn-primary show-details"
                                                data-toggle="modal" data-target="#modalUpdate"
                                                data-demo-url="{{ route('surveys.demographics', $survey) }}"
                                                data-name="{{ $survey->project_name }}">
                                                <p><i class="la la-hand-pointer-o"></i> Demographics</p>
                                            </button>
                                        @endif
                                    </th>
                                        </td>

                                        {{-- Delete Action --}}
                                        @can('delete-vendor-assign-projects')
                                            <td class="no-copy">
                                                <form
                                                    action="{{ route('vendor.project.assign.destroy', $vendor_project) }}"
                                                    method="POST"
                                                    style="display:inline-block;"
                                                    onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="la la-trash"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        @endcan

                                        {{-- Copy Buttons --}}
                                        @if($isVendor || $isSuperAdmin)
                                            <td class="no-copy">

                                                {{-- SuperAdmin sees both buttons --}}
                                                @if($isSuperAdmin)
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary copy-project-row mb-1"
                                                            data-copy-type="superadmin">
                                                        <i class="fa fa-copy"></i>
                                                        Copy SuperAdmin
                                                    </button>

                                                    <button type="button"
                                                            class="btn btn-sm btn-success copy-project-row mb-1"
                                                            data-copy-type="vendor">
                                                        <i class="fa fa-copy"></i>
                                                        Copy Vendor
                                                    </button>

                                                {{-- Vendor sees only Vendor button --}}
                                                @elseif($isVendor)
                                                    <button type="button"
                                                            class="btn btn-sm btn-success copy-project-row mb-1"
                                                            data-copy-type="vendor">
                                                        <i class="fa fa-copy"></i>
                                                        Copy Vendor
                                                    </button>
                                                @endif

                                            </td>
                                        @endif
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="40" class="text-center">
                                            No records found.
                                        </td>
                                    </tr>
                                @endforelse

                                {{-- Pagination --}}
                                @if($vendor_projects->hasPages())
                                    <tr>
                                        <td colspan="40" class="text-center">
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
$(document).ready(function () {

    /*
     * Show Details functionality.
     * This preserves the existing modal behavior.
     */
    $(document).on('click', '.show-details', function () {
        let targetSpec = $(this).attr('data-target-spec') || '';

        $('#targetSpec').html(
            '<div class="table-responsive">' + targetSpec + '</div>'
        );
    });


    /*
     * Escape text before inserting it into the clipboard HTML.
     */
    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    /*
     * Convert target specification HTML to readable text.
     * Removes markup but retains line breaks and list formatting.
     */
    function htmlToReadableText(html) {
        const container = document.createElement('div');
        container.innerHTML = String(html ?? '');

        container.querySelectorAll('script, style, iframe, object, embed')
            .forEach(function (element) {
                element.remove();
            });

        container.querySelectorAll('br')
            .forEach(function (element) {
                element.replaceWith(document.createTextNode('\n'));
            });

        container.querySelectorAll('p, div, tr, h1, h2, h3, h4, li')
            .forEach(function (element) {
                element.appendChild(document.createTextNode('\n'));
            });

        container.querySelectorAll('td, th')
            .forEach(function (element) {
                element.appendChild(document.createTextNode('\t'));
            });

        return (container.innerText || container.textContent || '')
            .replace(/\u00a0/g, ' ')
            .replace(/[ \t]+\n/g, '\n')
            .replace(/\n{3,}/g, '\n\n')
            .trim();
    }


    /*
     * Get actual text for a table cell.
     */
    function getProjectCellValue(cell, headerName) {

        // Target specification: use the stored specification, not the button.
        if (headerName.toLowerCase() === 'survey target spec') {
            const specElement = cell.querySelector('.target-spec-copy');

            if (specElement) {
                return htmlToReadableText(specElement.textContent);
            }

            const detailsButton = cell.querySelector('.show-details');

            if (detailsButton) {
                return htmlToReadableText(
                    detailsButton.getAttribute('data-target-spec') || ''
                );
            }
        }

        // Clone the cell to avoid changing the displayed table.
        const clone = cell.cloneNode(true);

        // Remove buttons, forms and controls from the copied value.
        clone.querySelectorAll(
            'button, input, select, textarea, form, .no-copy'
        ).forEach(function (element) {
            element.remove();
        });

        return (clone.innerText || clone.textContent || '')
            .replace(/\u00a0/g, ' ')
            .replace(/\r\n/g, '\n')
            .trim();
    }


    /*
     * Copy the selected project row into a Field | Value table.
     */
    $(document).on('click', '.copy-project-row', async function () {

        const button = this;
        const row = button.closest('tr');
        const table = row ? row.closest('table') : null;
        const copyType = button.getAttribute('data-copy-type');

        if (!row || !table) {
            alert('Unable to find the selected project row.');
            return;
        }

        const headerRow = table.querySelector('thead tr');

        if (!headerRow) {
            alert('Unable to find the table headers.');
            return;
        }

        const headers = Array.from(headerRow.children);
        const cells = Array.from(row.children);
        const fields = [];

        /*
         * Fields hidden from Vendor copies.
         */
        const vendorExcludedFields = [
            'client project id',
            'client project name',
            'client project code',
            'client live url',
            'client test url',
            'survey cpi',
            'survey cpi ($)'
        ];

        headers.forEach(function (header, index) {

            const fieldName = (
                header.innerText || header.textContent || ''
            ).replace(/\s+/g, ' ').trim();

            const normalizedName = fieldName.toLowerCase();

            // Never copy the Action or Copy buttons columns.
            if (
                header.classList.contains('no-copy') ||
                normalizedName === 'action' ||
                normalizedName === 'copy for email send'
            ) {
                return;
            }

            // Exclude client-only information and Survey CPI for Vendors.
            if (
                copyType === 'vendor' &&
                vendorExcludedFields.includes(normalizedName)
            ) {
                return;
            }

            const cell = cells[index];

            if (!cell || !fieldName || normalizedName === '#') {
                return;
            }

            fields.push({
                name: fieldName,
                value: getProjectCellValue(cell, fieldName)
            });
        });

        if (!fields.length) {
            alert('No project details found to copy.');
            return;
        }


        /*
         * Create HTML table for Microsoft Word and rich-text email.
         */
        let html = `
            <table border="1"
                   cellpadding="6"
                   cellspacing="0"
                   style="border-collapse:collapse;
                          width:100%;
                          font-family:Arial,sans-serif;
                          font-size:10pt;">
                <thead>
                    <tr>
                        <th style="text-align:left;
                                   background-color:#eaf0f6;
                                   border:1px solid #999;
                                   padding:6px;">
                            Field
                        </th>
                        <th style="text-align:left;
                                   background-color:#eaf0f6;
                                   border:1px solid #999;
                                   padding:6px;">
                            Value
                        </th>
                    </tr>
                </thead>
                <tbody>
        `;

        fields.forEach(function (field) {
            html += `
                <tr>
                    <td style="border:1px solid #999;
                               padding:6px;
                               font-weight:bold;
                               vertical-align:top;">
                        ${escapeHtml(field.name)}
                    </td>
                    <td style="border:1px solid #999;
                               padding:6px;
                               vertical-align:top;
                               white-space:pre-wrap;
                               overflow-wrap:anywhere;">
                        ${escapeHtml(field.value)}
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';


        /*
         * Plain-text fallback for applications that do not accept HTML.
         * Tabs separate the two columns.
         */
        const plainText = [
            ['Field', 'Value'],
            ...fields.map(function (field) {
                return [field.name, field.value];
            })
        ].map(function (columns) {
            return columns.map(function (value) {
                return String(value ?? '')
                    .replace(/\t/g, ' ')
                    .replace(/\r\n|\r|\n/g, ' ');
            }).join('\t');
        }).join('\r\n');


        /*
         * Copy HTML and plain text to the clipboard.
         */
        try {

            if (
                navigator.clipboard &&
                window.ClipboardItem &&
                window.isSecureContext
            ) {
                const clipboardItem = new ClipboardItem({
                    'text/html': new Blob(
                        [html],
                        { type: 'text/html' }
                    ),
                    'text/plain': new Blob(
                        [plainText],
                        { type: 'text/plain' }
                    )
                });

                await navigator.clipboard.write([clipboardItem]);

            } else {

                // Fallback for browsers without rich clipboard support.
                const textArea = document.createElement('textarea');

                textArea.value = plainText;
                textArea.setAttribute('readonly', '');
                textArea.style.position = 'fixed';
                textArea.style.left = '-9999px';
                textArea.style.top = '0';

                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                const copied = document.execCommand('copy');

                document.body.removeChild(textArea);

                if (!copied) {
                    throw new Error('Clipboard copy failed.');
                }
            }

            // Show temporary success feedback.
            const originalHtml = button.innerHTML;

            button.innerHTML =
                '<i class="fa fa-check"></i> Copied';

            setTimeout(function () {
                button.innerHTML = originalHtml;
            }, 1500);

        } catch (error) {

            console.error('Copy project row failed:', error);

            // Leave a selectable plain-text copy as a final fallback.
            let existingFallback = document.getElementById(
                'projectCopyFallback'
            );

            if (existingFallback) {
                existingFallback.remove();
            }

            const fallbackContainer = document.createElement('div');

            fallbackContainer.id = 'projectCopyFallback';
            fallbackContainer.style.position = 'fixed';
            fallbackContainer.style.left = '2%';
            fallbackContainer.style.top = '10%';
            fallbackContainer.style.width = '96%';
            fallbackContainer.style.zIndex = '99999';
            fallbackContainer.style.background = '#ffffff';
            fallbackContainer.style.padding = '15px';
            fallbackContainer.style.border = '1px solid #999';
            fallbackContainer.style.boxShadow = '0 0 10px #999';

            const message = document.createElement('p');
            message.textContent =
                'Automatic copying was blocked. Select the text below and press Ctrl+C.';

            const textArea = document.createElement('textarea');

            textArea.value = plainText;
            textArea.style.width = '100%';
            textArea.style.height = '60vh';

            const closeButton = document.createElement('button');

            closeButton.type = 'button';
            closeButton.className = 'btn btn-secondary mt-2';
            closeButton.textContent = 'Close';

            closeButton.addEventListener('click', function () {
                fallbackContainer.remove();
            });

            fallbackContainer.appendChild(message);
            fallbackContainer.appendChild(textArea);
            fallbackContainer.appendChild(closeButton);

            document.body.appendChild(fallbackContainer);

            textArea.focus();
            textArea.select();
        }
    });

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
