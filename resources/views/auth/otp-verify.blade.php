@extends('layouts.auth')
@section('title', 'Verify OTP')
@section('extra-styles')
<style>
.otp-inputs { display:flex; gap:10px; justify-content:center; margin:20px 0; }
.otp-inputs input { width:50px; height:56px; text-align:center; font-size:22px; font-weight:700; border-radius:12px; background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1); color:#e8eaf0; font-family:'Inter',sans-serif; transition:border-color .2s; }
.otp-inputs input:focus { outline:none; border-color:#6366f1; background:rgba(99,102,241,.08); }
.timer { text-align:center; font-size:13px; color:#8b90a0; margin-bottom:16px; }
</style>
@endsection
@section('content')
<div class="auth-logo">
    <span class="icon">📧</span>
    <h1>Verify Email</h1>
    <p>We sent a 6-digit OTP to <strong style="color:#818cf8">{{ auth()->user()->email }}</strong></p>
</div>

<p class="sub" style="text-align:center">Enter the OTP to verify your account. Check <code>storage/logs/laravel.log</code> for the OTP in dev mode.</p>

<form action="{{ route('otp.verify.post') }}" method="POST">
    @csrf
    <div class="form-group">
        <label class="form-label">6-Digit OTP</label>
        <input type="text" name="otp" class="form-control" maxlength="6" placeholder="Enter 6-digit OTP"
            style="text-align:center;font-size:22px;font-weight:700;letter-spacing:8px;" required autofocus>
    </div>
    <button type="submit" class="btn-submit">Verify OTP →</button>
</form>

<div class="auth-links" style="margin-top:16px;">
    Didn't receive it?
    <form action="{{ route('otp.resend') }}" method="POST" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;color:#6366f1;font-weight:600;cursor:pointer;font-size:13px;font-family:'Inter',sans-serif;">Resend OTP</button>
    </form>
</div>
<div class="auth-links">
    Wrong account?
    <form action="{{ route('logout') }}" method="POST" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;color:#ef4444;font-weight:600;cursor:pointer;font-size:13px;font-family:'Inter',sans-serif;">Logout</button>
    </form>
</div>
@endsection
