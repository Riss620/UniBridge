@extends('layouts.app')
@section('title', 'University Dashboard')
@section('page-title', '🏛️ University Dashboard')

@section('sidebar-nav')
<div class="nav-section">Overview</div>
<a href="{{ route('university.dashboard') }}" class="nav-item active"><span class="icon">📊</span> Dashboard</a>
<div class="nav-section">Students</div>
<a href="{{ route('university.students') }}" class="nav-item"><span class="icon">🎓</span> All Students</a>
<a href="{{ route('university.students.create') }}" class="nav-item"><span class="icon">➕</span> Add Student</a>
<div class="nav-section">Settings</div>
<a href="{{ route('university.profile') }}" class="nav-item"><span class="icon">⚙️</span> Profile</a>
@endsection

@section('content')
@if(!$university)
<div class="alert alert-error">❌ University profile not found. Please contact admin.</div>
@elseif($university->status === 'pending')
<div class="glass-card" style="text-align:center;padding:40px">
    <div style="font-size:48px;margin-bottom:16px">⏳</div>
    <h2 style="font-size:22px;font-weight:700;margin-bottom:8px">Pending Approval</h2>
    <p style="color:var(--text2);margin-bottom:4px">Your university registration is under review by the admin.</p>
    <p style="color:var(--text2);font-size:14px">You'll be able to manage students once approved.</p>
</div>
@elseif($university->status === 'rejected')
<div class="glass-card" style="text-align:center;padding:40px">
    <div style="font-size:48px;margin-bottom:16px">❌</div>
    <h2 style="font-size:22px;font-weight:700;margin-bottom:8px">Registration Rejected</h2>
    @if($university->rejection_reason)
    <p style="color:#ef4444">Reason: {{ $university->rejection_reason }}</p>
    @endif
</div>
@else
<div class="stat-grid">
    <div class="stat-card" style="border-left:3px solid #14b8a6">
        <div class="stat-icon">🎓</div>
        <div class="stat-num" style="color:#2dd4bf">{{ $stats['total'] }}</div>
        <div class="stat-label">Total Students</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #22c55e">
        <div class="stat-icon">✅</div>
        <div class="stat-num" style="color:#22c55e">{{ $stats['active'] }}</div>
        <div class="stat-label">Active</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #6366f1">
        <div class="stat-icon">🏆</div>
        <div class="stat-num" style="color:#818cf8">{{ $stats['graduated'] }}</div>
        <div class="stat-label">Graduated</div>
    </div>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2 style="font-size:18px;font-weight:700">Recent Students</h2>
    <a href="{{ route('university.students.create') }}" class="btn btn-teal">➕ Add Student</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr><th>Name</th><th>Roll No</th><th>Course</th><th>Year</th><th>CGPA</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse($recentStudents as $s)
            <tr>
                <td style="font-weight:600">{{ $s->name }}</td>
                <td style="font-family:monospace;font-size:13px">{{ $s->roll_no }}</td>
                <td>{{ $s->course }}</td>
                <td>Year {{ $s->year }}</td>
                <td>{{ $s->cgpa ?? '—' }}</td>
                <td><span class="badge badge-{{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><div class="icon">🎓</div><h3>No students yet</h3><a href="{{ route('university.students.create') }}" class="btn btn-teal" style="margin-top:12px">Add First Student</a></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection
