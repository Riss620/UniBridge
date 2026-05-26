@extends('layouts.app')
@section('title', 'Government Dashboard')
@section('page-title', '🏛 Government Dashboard')

@section('sidebar-nav')
<div class="nav-section">Overview</div>
<a href="{{ route('government.dashboard') }}" class="nav-item active"><span class="icon">📊</span> Dashboard</a>
<div class="nav-section">Open Data</div>
<a href="{{ route('government.data') }}" class="nav-item"><span class="icon">🗂</span> Student Data</a>
<a href="{{ route('government.universities') }}" class="nav-item"><span class="icon">🏛️</span> Universities</a>
<a href="{{ route('government.export') }}" class="nav-item"><span class="icon">📥</span> Export CSV</a>
@endsection

@section('content')
<div class="stat-grid">
    <div class="stat-card" style="border-left:3px solid #f59e0b">
        <div class="stat-icon">🏛️</div>
        <div class="stat-num" style="color:#fbbf24">{{ $stats['total_universities'] }}</div>
        <div class="stat-label">Approved Universities</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #14b8a6">
        <div class="stat-icon">🎓</div>
        <div class="stat-num" style="color:#2dd4bf">{{ $stats['total_students'] }}</div>
        <div class="stat-label">Total Students (Open)</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #6366f1">
        <div class="stat-icon">🗺</div>
        <div class="stat-num" style="color:#818cf8">{{ $stats['states'] }}</div>
        <div class="stat-label">States Covered</div>
    </div>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2 style="font-size:18px;font-weight:700">Approved Universities</h2>
    <div style="display:flex;gap:8px">
        <a href="{{ route('government.data') }}" class="btn btn-amber">🗂 Browse Data</a>
        <a href="{{ route('government.export') }}" class="btn btn-amber">📥 Export All CSV</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px">
    @foreach($universities as $u)
    <div class="glass-card" style="border-left:3px solid #f59e0b">
        <div style="font-weight:700;font-size:16px;margin-bottom:8px">{{ $u->name }}</div>
        <div style="font-size:13px;color:var(--text2);line-height:1.8">
            📍 {{ $u->city }}, {{ $u->state }}<br>
            🏷 {{ ucfirst($u->type) }} University<br>
            📋 {{ $u->affiliation_no }}
        </div>
    </div>
    @endforeach
</div>
@endsection
