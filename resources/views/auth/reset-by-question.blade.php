@extends('layouts.auth')

@section('content')
    <h4 class="mb-3">Reset Password</h4>
    <p class="text-muted">Create a new password for your account.</p>

    <form method="POST" action="{{ route('password.reset.by_question') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label for="password">New Password</label>
            <input id="password" name="password" type="password" class="form-control" required autofocus>
            @error('password')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mt-2">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <a href="{{ route('password.request.by_question') }}" class="btn btn-outline-secondary">Back</a>
            <button class="btn btn-primary">Reset Password</button>
        </div>
    </form>
@endsection
