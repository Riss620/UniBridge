@extends('layouts.auth')
@section('title', 'Login')
@section('content')
<div class="auth-logo">
    <span class="icon">🌉</span>
    <h1>UniBridge</h1>
    <p>University Open Data Platform</p>
</div>

<div class="auth-tabs">
    <a href="{{ route('login') }}" class="auth-tab active">Sign In</a>
    <a href="{{ route('register') }}" class="auth-tab">Register</a>
</div>

<h2 class="form-heading">Welcome back</h2>
<p class="sub">Sign in to your UniBridge account</p>

<form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required>
    </div>
    <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
    </div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <label style="display:flex;gap:8px;font-size:13px;color:#8b90a0;cursor:pointer;">
            <input type="checkbox" name="remember"> Remember me
        </label>
        <a href="{{ route('password.request') }}" style="font-size:13px;color:#6366f1;font-weight:600;text-decoration:none;">Forgot password?</a>
    </div>
    <button type="submit" class="btn-submit">Sign In →</button>
</form>

<div class="auth-links">
    New to UniBridge? <a href="{{ route('register') }}">Create an account</a>
</div>

<div class="divider">Demo Credentials</div>
<div style="background:rgba(99,102,241,.06);border:1px solid rgba(99,102,241,.15);border-radius:10px;padding:12px;font-size:12px;color:#8b90a0;line-height:1.8;">
    <strong style="color:#818cf8;">Admin:</strong> admin@unibridge.in / Admin@1234<br>
    <strong style="color:#2dd4bf;">University:</strong> du@unibridge.in / Univ@1234<br>
    <strong style="color:#fbbf24;">Government:</strong> govt@unibridge.in / Govt@1234<br>
    <strong style="color:#fb7185;">Student:</strong> student1@du.ac.in / Student@1234
</div>
@endsection
