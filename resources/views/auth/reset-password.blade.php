@extends('layouts.auth')
@section('title', 'Reset Password')
@section('content')
<div class="auth-logo">
    <span class="icon">🔑</span>
    <h1>Reset Password</h1>
    <p>Enter the OTP and your new password</p>
</div>

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="{{ $email }}" disabled style="opacity:.6;">
    </div>
    <div class="form-group">
        <label class="form-label">OTP Code</label>
        <input type="text" name="otp" class="form-control" placeholder="6-digit OTP" maxlength="6"
            style="text-align:center;font-size:20px;letter-spacing:6px;font-weight:700;" required>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Min 8 chars" required>
        </div>
        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat" required>
        </div>
    </div>
    <button type="submit" class="btn-submit">Reset Password →</button>
</form>

<div class="auth-links">
    <a href="{{ route('login') }}">← Back to Login</a>
</div>
@endsection
