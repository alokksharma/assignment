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

    @endpush
