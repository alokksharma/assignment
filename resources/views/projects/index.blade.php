@extends('layouts.main')
    @section('content')
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">Client Api List </h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                     <div class="card-title d-flex justify-content-between align-items-center">
                                         Create Client
                                        <div>
                                            <a href="{{ route('suppliers.create') }}" style="background-color:#007bff" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Client</a>
                                         </div>

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
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Url</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @forelse($projects as $key => $project)
                                            @php
                                                $key = 1 + $key;
                                            @endphp
                                            <tr>
                                                <th scope="row">{{ $key }}</th>
                                                <td> {{ $project->name }} </td>
                                                <td> {{ $project->code }} </td>
                                                <td> {{ $project->url }} </td>
                                                <td>{{ $project->status }}</td>
                                                <td>{{ $project->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-info copy-mail-template mb-1"
                                                        data-target="project-mail-template-{{ $project->id }}"
                                                    >
                                                        Copy Mail Template
                                                    </button>

                                                    <div id="project-mail-template-{{ $project->id }}" class="d-none">
                                                        <x-project-mail-template
                                                            :project-name="$project->name"
                                                            :client-name="$project->client_name ?? 'Client'"
                                                            :project-code="$project->code"
                                                            :project-url="$project->url"
                                                            :supplier-id="$project->supplier_id ?? 'N/A'"
                                                            :supplier-token="$project->supplier_token ?? 'N/A'"
                                                            :status="$project->status == 1 ? 'Active' : 'Inactive'"
                                                            :support-email="config('mail.from.address', 'support@example.com')"
                                                            :company-name="config('app.name', '3AS Research')"
                                                            :company-website="rtrim(config('app.url', 'https://3asresearch.com'))"
                                                        />
                                                    </div>

                                                    <a href="{{ route('suppliers.edit', $project->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        Edit
                                                    </a>

                                                    {{-- <form action="{{ route('suppliers.destroy', $project->id) }}"
                                                        method="POST"
                                                        style="display:inline-block;"
                                                        onsubmit="return confirm('Are you sure you want to delete this project?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            Delete
                                                        </button>
                                                    </form> --}}
                                                </td>
                                            </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No record found.</td>
                                                </tr>
                                            @endforelse
                                                @if ($projects->hasPages())
                                                 <tr>
                                                    <td colspan="5" class="text-center">
                                                        <div class="d-flex justify-content-center">
                                                            {{ $projects->appends(request()->query())->links('pagination::bootstrap-4') }}
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
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.copy-mail-template').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const container = document.getElementById(btn.dataset.target);
                    if (!container) return;

                    const html = container.innerHTML;
                    const text = container.innerText;

                    if (navigator.clipboard && window.ClipboardItem) {
                        const item = new ClipboardItem({
                            'text/html': new Blob([html], { type: 'text/html' }),
                            'text/plain': new Blob([text], { type: 'text/plain' }),
                        });

                        navigator.clipboard.write([item]).then(function () {
                            alert('Mail template copied to clipboard.');
                        }).catch(function () {
                            navigator.clipboard.writeText(text);
                            alert('Mail template copied to clipboard.');
                        });
                        return;
                    }

                    navigator.clipboard.writeText(text).then(function () {
                        alert('Mail template copied to clipboard.');
                    }).catch(function () {
                        const tempInput = document.createElement('textarea');
                        tempInput.value = text;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempInput);
                        alert('Mail template copied to clipboard.');
                    });
                });
            });
        });
    </script>
    @endpush
