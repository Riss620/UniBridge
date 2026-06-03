@extends('layouts.app')
@section('title', 'Student Dashboard')
@section('page-title', '🎓 My Academic Record')

@section('sidebar-nav')
<div class="nav-section">My Account</div>
<a href="{{ route('student.dashboard') }}" class="nav-item active"><span class="icon">🎓</span> My Record</a>
@endsection

@section('content')
<div style="max-width:600px">
    @if($studentRecord && $studentRecord->status === 'pending')
    <div class="glass-card" style="text-align:center;padding:60px 20px;margin-bottom:20px">
        <div style="font-size:48px;margin-bottom:16px">⏳</div>
        <h2 style="font-size:22px;font-weight:700;margin-bottom:8px">Approval Pending</h2>
        <p style="color:var(--text2);font-size:14px">
            Your registration is pending approval by your university: <strong>{{ $studentRecord->university->name }}</strong>.<br>
            Please wait for them to verify and approve your record.
        </p>
    </div>
    @elseif($studentRecord)
    <div class="glass-card" style="margin-bottom:20px">
        <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px">
            <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#f43f5e,#ec4899);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#fff">
                {{ strtoupper(substr($studentRecord->name,0,1)) }}
            </div>
            <div>
                <div style="font-size:22px;font-weight:800">{{ $studentRecord->name }}</div>
                <div style="color:var(--text2);font-size:14px">{{ $studentRecord->roll_no }}</div>
                <span class="badge badge-{{ $studentRecord->status }}" style="margin-top:4px">{{ ucfirst($studentRecord->status) }}</span>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            @foreach([
                ['📚','Course',$studentRecord->course],
                ['🏛','Department',$studentRecord->department],
                ['📅','Year',('Year '.$studentRecord->year)],
                ['⭐','CGPA',($studentRecord->cgpa ?? 'N/A')],
                ['🏫','University',$studentRecord->university->name],
                ['📌','State',$studentRecord->university->state],
                ['📅','Admission Year',$studentRecord->admission_year],
                ['🎓','Passout Year',($studentRecord->passout_year ?? 'Ongoing')],
            ] as [$icon,$label,$value])
            <div style="background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:12px;padding:14px">
                <div style="font-size:11px;color:var(--text2);margin-bottom:4px">{{ $icon }} {{ $label }}</div>
                <div style="font-weight:600;font-size:15px">{{ $value }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="glass-card" style="text-align:center;padding:60px 20px">
        <div style="font-size:48px;margin-bottom:16px">🎓</div>
        <h2 style="font-size:22px;font-weight:700;margin-bottom:8px">No Record Found</h2>
        <p style="color:var(--text2);font-size:14px">
            Your academic record hasn't been linked yet.<br>
            Contact your university to add you with email: <strong style="color:#818cf8">{{ $user->email }}</strong>
        </p>
    </div>
    @endif
</div>
@endsection
