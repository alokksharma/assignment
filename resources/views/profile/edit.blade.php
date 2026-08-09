@extends('layouts.main')
@section('content')

<div class="content">
    <div class="container-fluid">
			<h4 class="page-title"></h4>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Profile Information</h4>
                            <a href="" class="btn btn-light btn-sm">Back to List</a>
                        </div>
                        <div class="card-body">
                            <section>
                                <header class="mb-4">
                                    <p class="text-sm text-gray-600">
                                        {{ __("Update your account's profile information and email address.") }}
                                    </p>
                                </header>

                                <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                </form>

                                <form action="{{ route('profile.update') }}" method="POST" class="mt-4">
                                    @csrf
                                    @method('PATCH')

                                    <!-- Name -->
                                    <div class="form-group mb-3">
                                        <label for="name">
                                            {{ __('Name') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group mb-3">
                                        <label for="email">{{ __('Email') }} <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username"
                                        >
                                        @error('email')<span class="text-danger">{{ $message }}</span>
                                        @enderror

                                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                            <div class="mt-2">
                                                <small class="text-warning d-block">
                                                    {{ __('Your email address is unverified.') }}
                                                </small>

                                                <button type="submit" form="send-verification" class="btn btn-link p-0">
                                                    {{ __('Click here to re-send the verification email.') }}
                                                </button>

                                                @if (session('status') === 'verification-link-sent')
                                                    <div class="text-success mt-1">
                                                        {{ __('A new verification link has been sent to your email address.') }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>


                                    <!-- Submit -->
                                    <div class="d-flex align-items-center gap-3">
                                        <button type="submit" class="btn btn-primary">{{ __('Save') }}
                                        </button>

                                        @if (session('status') === 'profile-updated')<span class="text-success">{{ __('Saved.') }}</span>
                                        @endif
                                    </div>
                                </form>
                            </section>
                            <section>
                                <header class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ __('Update Password') }}
                                    </h4>

                                    <p class="text-sm text-gray-600">
                                        {{ __('Ensure your account is using a long, random password to stay secure.') }}
                                    </p>
                                </header>

                                <form action="{{ route('password.update') }}" method="POST" class="mt-4">
                                    @csrf
                                    @method('PUT')

                                    <!-- Current Password -->
                                    <div class="form-group mb-3">
                                        <label for="current_password">
                                            {{ __('Current Password') }} <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="password"
                                            name="current_password"
                                            id="current_password"
                                            class="form-control"
                                            autocomplete="current-password"
                                        >
                                        @error('current_password', 'updatePassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- New Password -->
                                    <div class="form-group mb-3">
                                        <label for="password">
                                            {{ __('New Password') }} <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control"
                                            autocomplete="new-password"
                                        >
                                        @error('password', 'updatePassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group mb-3">
                                        <label for="password_confirmation">
                                            {{ __('Confirm Password') }} <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            class="form-control"
                                            autocomplete="new-password"
                                        >
                                        @error('password_confirmation', 'updatePassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Submit -->
                                    <div class="d-flex align-items-center gap-3">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Save') }}
                                        </button>

                                        @if (session('status') === 'password-updated')
                                            <span class="text-success">
                                                {{ __('Saved.') }}
                                            </span>
                                        @endif
                                    </div>
                                </form>
                            </section>
                            {{-- <section class="mb-5">
                                <header class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ __('Delete Account') }}
                                    </h4>

                                    <p class="text-sm text-gray-600">
                                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                                    </p>
                                </header>
                                <!-- Delete Button -->
                                <button
                                    type="button"
                                    class="btn btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmUserDeletion"
                                >
                                    {{ __('Delete Account') }}
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div
                                    class="modal fade @if($errors->userDeletion->isNotEmpty()) show @endif"
                                    id="confirmUserDeletion"
                                    tabindex="-1"
                                    aria-labelledby="confirmUserDeletionLabel"
                                    aria-hidden="true"
                                    @if($errors->userDeletion->isNotEmpty()) style="display:block;" @endif
                                >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('profile.destroy') }}">
                                                @csrf
                                                @method('DELETE')

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmUserDeletionLabel">
                                                        {{ __('Are you sure you want to delete your account?') }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <p class="text-sm text-gray-600">
                                                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                                                    </p>

                                                    <div class="form-group mt-3">
                                                        <label for="password" class="form-label">
                                                            {{ __('Password') }} <span class="text-danger">*</span>
                                                        </label>
                                                        <input
                                                            type="password"
                                                            name="password"
                                                            id="password"
                                                            class="form-control"
                                                            placeholder="{{ __('Password') }}"
                                                        >

                                                        @error('password', 'userDeletion')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        {{ __('Cancel') }}
                                                    </button>

                                                    <button type="submit" class="btn btn-danger">
                                                        {{ __('Delete Account') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </section> --}}

                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@endsection
