@extends('layouts.auth')

@section('content')
    <h4 class="mb-3">Forgot Password</h4>
    <p class="text-muted">Enter your email and the recovery code to continue.</p>

    <form method="POST" action="{{ route('password.email.by_question') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required autofocus>
            @error('email')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mt-3">
            <label for="recovery_code">Recovery Code</label>
            <input id="recovery_code" name="recovery_code" type="text" class="form-control" value="{{ old('recovery_code') }}" required placeholder="Example: JO3210">
            @error('recovery_code')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <a href="{{ route('login') }}" class="btn btn-outline-secondary">Back to login</a>
            <button class="btn btn-primary">Continue</button>
        </div>
    </form>
@endsection
