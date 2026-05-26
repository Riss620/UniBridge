@extends('layouts.auth')
@section('title', 'Forgot Password')
@section('content')
<div class="auth-logo">
    <span class="icon">🔐</span>
    <h1>Forgot Password</h1>
    <p>We'll send an OTP to reset your password</p>
</div>

<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <div class="form-group">
        <label class="form-label">Registered Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required>
    </div>
    <button type="submit" class="btn-submit">Send Reset OTP →</button>
</form>

<div class="auth-links">
    <a href="{{ route('login') }}">← Back to Login</a>
</div>
@endsection
