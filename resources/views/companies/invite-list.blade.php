@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">User List </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title d-flex justify-content-between align-items-center">
                            <span>User List</span>
                            <div>
                                <a href="{{ route('companies.invite-admin') }}" style="background-color:#007bff" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create User</a>
                            </div>
                            <div>
                                {{-- <button onclick="startExport()">Export Users</button> --}}
                             <button id="exportUsersBtn" class="btn btn-success">
                                    Export Users
                                </button>
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
                                    <th>Email</th>
                                    <th>Roles</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse($users as $user)
                                    @php
                                    $i++;
                                    @endphp
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->pluck('name')->join(', ') }}</td>

                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                                    {{-- pagination --}}
                                    @if ($users->hasPages())
                                            <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="d-flex justify-content-center">
                                                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
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
    async function startExport() {
        const res = await fetch('/api/export/users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer YOUR_TOKEN'
            }
        });

        const data = await res.json();
        console.log(data.message);
    }
    // Listen for export completion event
    Echo.channel(`users`)
        .listen('.export.completed', (e) => {
            alert('Export completed: ' + e.fileName);
            console.log('Export ready:', e.download_url);

            window.location.href = e.download_url;
        });
</script>


<script>
    $(document).ready(function () {

        $('#exportUsersBtn').on('click', function () {

            const button = $(this);

            button.prop('disabled', true);
            button.text('Exporting...');

            $.ajax({
                url: "{{ route('exports.create') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },

                success: function (response) {

                    alert('Export started');

                    const exportId = response.data.id;

                    let interval = setInterval(function () {

                        $.ajax({
                            url: '/temporary-storage/' + exportId,
                            type: 'GET',

                            success: function (exportData) {

                                console.log(exportData);

                                if (exportData.data.status === 'completed') {

                                    clearInterval(interval);

                                    button.prop('disabled', false);
                                    button.text('Export Users');

                                    if (exportData.data.url) {

                                        const link = document.createElement('a');

                                        link.href = exportData.data.url;
                                        link.download = '';

                                        document.body.appendChild(link);

                                        link.click();

                                        document.body.removeChild(link);

                                        alert('Export downloaded');

                                    } else {

                                        alert('Download URL missing');

                                    }
                                }

                                if (exportData.status === 'failed') {

                                    clearInterval(interval);

                                    button.prop('disabled', false);
                                    button.text('Export Users');

                                    alert('Export failed');
                                }
                            },

                            error: function () {

                                clearInterval(interval);

                                button.prop('disabled', false);
                                button.text('Export Users');

                                alert('Unable to check export status');
                            }
                        });

                    }, 3000);
                },

                error: function (xhr) {

                    button.prop('disabled', false);
                    button.text('Export Users');

                    console.log(xhr);

                    alert('Something went wrong');
                }
            });

        });

    });
</script>
@endpush

