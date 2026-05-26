@extends('layouts.app')
@section('title', 'University Profile')
@section('page-title', '⚙️ University Profile')

@section('sidebar-nav')
<a href="{{ route('university.dashboard') }}" class="nav-item"><span class="icon">📊</span> Dashboard</a>
<a href="{{ route('university.students') }}" class="nav-item"><span class="icon">🎓</span> All Students</a>
<a href="{{ route('university.students.create') }}" class="nav-item"><span class="icon">➕</span> Add Student</a>
<a href="{{ route('university.profile') }}" class="nav-item active"><span class="icon">⚙️</span> Profile</a>
@endsection

@section('content')
<div class="glass-card" style="max-width:600px">
    @if($university)
    <div style="display:grid;gap:16px">
        @foreach([
            '🏛️ University Name' => $university->name,
            '📍 Address' => $university->address,
            '🏙 City' => $university->city,
            '📌 State' => $university->state,
            '📋 Affiliation No.' => $university->affiliation_no,
            '📞 Contact' => $university->contact_phone,
            '🏷 Type' => ucfirst($university->type),
        ] as $label => $value)
        <div style="display:flex;gap:16px;padding-bottom:12px;border-bottom:1px solid var(--border)">
            <div style="min-width:180px;font-size:13px;color:var(--text2)">{{ $label }}</div>
            <div style="font-weight:600">{{ $value }}</div>
        </div>
        @endforeach
        <div style="display:flex;gap:16px;padding-bottom:12px;border-bottom:1px solid var(--border)">
            <div style="min-width:180px;font-size:13px;color:var(--text2)">📊 Status</div>
            <div><span class="badge badge-{{ $university->status }}">{{ ucfirst($university->status) }}</span></div>
        </div>
        @if($university->rejection_reason)
        <div class="alert alert-error">Rejection Reason: {{ $university->rejection_reason }}</div>
        @endif
    </div>
    @else
    <div class="empty-state"><div class="icon">⚙️</div><h3>No profile found</h3></div>
    @endif
</div>
@endsection
