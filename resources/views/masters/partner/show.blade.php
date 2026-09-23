@extends('layouts.main')
@section('content')
@php
$baseUrl = rtrim(config('app.url', url('/')), '/');
$languageApiUrl = $baseUrl . '/api/languages';
$countriesApiUrl = $baseUrl . '/api/countries';
$questionsApiUrl = $baseUrl . '/api/questions';
$surveyApiUrl = $baseUrl . '/api/survey';
@endphp
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">API Partner Details</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>API Partner Information</div>
                        <div>
                            <a href="{{ route('partners.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('partners.index') }}" class="btn btn-secondary btn-sm">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Name</label>
                                    <div>{{ $user->name ?? '-' }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <div>{{ $user->email ?? '-' }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Address</label>
                                    <div>{{ $user->address ?? '-' }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Contact</label>
                                    <div>{{ $user->contact ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Partner Nick Name</label>
                                    <div>{{ optional($user->partner)->partner_nick_name ?? '-' }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Supplier Token</label>
                                    <div>{{ optional($user->partner)->supplier_token ?? '-' }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Supplier ID</label>
                                    <div>{{ optional($user->partner)->supplier_id ?? '-' }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <div>
                                        @if(optional($user->partner)->status === 'Active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif(optional($user->partner)->status === 'NonActive')
                                            <span class="badge badge-secondary">NonActive</span>
                                        @else
                                            <span class="badge badge-light">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Expired At</label>
                                    <div>{{ optional($user->partner)->expired_at ? \Carbon\Carbon::parse(optional($user->partner)->expired_at)->format('d M, Y') : '-' }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Complete Redirect</label>
                                    <div>{{ optional($user->partner)->complete_redirect ?? '-' }}</div>
                                </div>
                                 <div class="mb-3">
                                    <label class="form-label fw-bold">Partner API URL</label>
                                    <div>{{ optional($user->partner)->partner_api_url ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Terminate Redirect</label>
                                    <div>{{ optional($user->partner)->terminate_redirect ?? '-' }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Quotafull Redirect</label>
                                    <div>{{ optional($user->partner)->quotafull_redirect ?? '-' }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Quality Redirect</label>
                                    <div>{{ optional($user->partner)->quality_redirect ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        Partner Apis
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">API Languages URL</label>
                                    <div>{{ $languageApiUrl }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">API Countries URL</label>
                                    <div>{{ $countriesApiUrl }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">API Questions URL</label>
                                    <div>{{ $questionsApiUrl }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">API Survey URL</label>
                                    <div>{{ $surveyApiUrl }}</div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">3AS Project Id</label>
                                    @foreach ($user->partner->proofs as $proof)
                                        <div>{{ $proof->{'3ASProjectID'} }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
