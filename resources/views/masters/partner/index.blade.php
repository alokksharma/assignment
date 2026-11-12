@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">API Partners</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>API Dashboard List</div>
                        <a href="{{ route('partners.create') }}" class="btn btn-primary">Create API Partner</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $i => $user)
                                <tr>
                                    <td>{{ $i + $users->firstItem() }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->contact }}</td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-info copy-mail-template mb-1"
                                            data-target="partner-mail-template-{{ $user->id }}"
                                        >
                                            Copy Mail Template
                                        </button>

                                        <div id="partner-mail-template-{{ $user->id }}" class="d-none">
                                            <x-partner-mail-template
                                                :partner-name="$user->name"
                                                :partner-company-name="$user->partner?->partner_nick_name ?? $user->name"
                                                :partner-id="$user->partner?->supplier_id ?? 'N/A'"
                                                :api-username="$user->partner?->partner_nick_name ?? $user->name"
                                                :login-email="$user->email"
                                                :account-status="$user->partner?->status ?? 'Active'"
                                                :activation-date="$user->partner?->expired_at ? \Carbon\Carbon::parse($user->partner->expired_at)->format('d M, Y') : now()->format('d M, Y')"
                                                :partner-portal-url="url('/')"
                                                :partner-login-link="route('login')"
                                                :api-base-url="rtrim(config('app.url', url('/')), '/')"
                                                :api-documentation-url="url('/')"
                                                :authentication-method="'API Key'"
                                                :client-id="$user->partner?->supplier_id ?? 'N/A'"
                                                :client-secret-or-secure-link="$user->partner?->supplier_token ?? 'N/A'"
                                                :production-or-sandbox="'Production'"
                                                :api-version="'v1'"
                                                :postback-url="$user->partner?->complete_redirect ?? url('/')"
                                                :account-security-link="url('/profile')"
                                                :support-email="config('mail.from.address', 'support@example.com')"
                                                :technical-support-email="config('mail.from.address', 'support@example.com')"
                                                :company-name="config('app.name', '3AS Research')"
                                                :company-website="rtrim(config('app.url', 'https://3asresearch.com'))"
                                            />
                                        </div>

                                        <a href="{{ route('partners.show', $user->id) }}" class="btn btn-sm btn-info">API Details</a>
                                        <a href="{{ route('partners.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('partners.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No partners found.</td>
                                </tr>
                                @endforelse

                                @if($users->hasPages())
                                    <tr>
                                        <td colspan="5" class="text-center">{{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}</td>
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
