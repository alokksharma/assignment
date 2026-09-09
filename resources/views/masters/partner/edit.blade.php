@extends('layouts.main')
@section('content')
<div class="content">
    <div class="container-fluid">
        <h4 class="page-title">Edit Partner</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('partners.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contact</label>
                                        <input type="text" name="contact" class="form-control" value="{{ old('contact', $user->contact) }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <h5>Partner Details</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Partner Nick Name</label>
                                        <input type="text" name="partner_nick_name" class="form-control" value="{{ old('partner_nick_name', optional($user->partner)->partner_nick_name) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Partner API URL</label>
                                        <input type="url" name="partner_api_url" class="form-control" value="{{ old('partner_api_url', optional($user->partner)->partner_api_url) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Supplier ID</label>
                                        <input type="text" name="supplier_id" class="form-control" value="{{ old('supplier_id', optional($user->partner)->supplier_id) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Token</label>
                                        <input type="text" name="supplier_token" class="form-control" value="{{ old('supplier_token', optional($user->partner)->supplier_token) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="Active" {{ (old('status', optional($user->partner)->status) == 'Active') ? 'selected' : '' }}>Active</option>
                                            <option value="NonActive" {{ (old('status', optional($user->partner)->status) == 'NonActive') ? 'selected' : '' }}>NonActive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Expired At</label>
                                        <input type="date" name="expired_at" class="form-control" value="{{ old('expired_at', optional($user->partner)->expired_at ? (\Carbon\Carbon::parse(optional($user->partner)->expired_at)->format('Y-m-d')) : '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Complete Redirect</label>
                                        <input type="url" name="complete_redirect" class="form-control" value="{{ old('complete_redirect', optional($user->partner)->complete_redirect) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Terminate Redirect</label>
                                        <input type="url" name="terminate_redirect" class="form-control" value="{{ old('terminate_redirect', optional($user->partner)->terminate_redirect) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Quotafull Redirect</label>
                                        <input type="url" name="quotafull_redirect" class="form-control" value="{{ old('quotafull_redirect', optional($user->partner)->quotafull_redirect) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Quality Redirect</label>
                                        <input type="url" name="quality_redirect" class="form-control" value="{{ old('quality_redirect', optional($user->partner)->quality_redirect) }}">
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary">Update</button>
                            <a href="{{ route('partners.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
