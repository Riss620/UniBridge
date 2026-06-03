@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', '📊 Admin Dashboard')

@section('sidebar-nav')
<div class="nav-section">Overview</div>
<a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active':'' }}">
    <span class="icon">📊</span> Dashboard
</a>
<div class="nav-section">Management</div>
<a href="{{ route('admin.universities') }}" class="nav-item {{ request()->routeIs('admin.universities') ? 'active':'' }}">
    <span class="icon">🏛️</span> Universities
</a>
<a href="{{ route('admin.students') }}" class="nav-item {{ request()->routeIs('admin.students') ? 'active':'' }}">
    <span class="icon">🎓</span> Students
</a>
<a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active':'' }}">
    <span class="icon">👥</span> Users
</a>
@endsection

@section('content')
<div class="stat-grid">
    <div class="stat-card" style="border-left:3px solid #6366f1">
        <div class="stat-icon">🏛️</div>
        <div class="stat-num" style="color:#818cf8">{{ $stats['total_universities'] }}</div>
        <div class="stat-label">Total Universities</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #f59e0b">
        <div class="stat-icon">⏳</div>
        <div class="stat-num" style="color:#fbbf24">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending Approvals</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #22c55e">
        <div class="stat-icon">✅</div>
        <div class="stat-num" style="color:#22c55e">{{ $stats['approved'] }}</div>
        <div class="stat-label">Approved</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #14b8a6">
        <div class="stat-icon">🎓</div>
        <div class="stat-num" style="color:#2dd4bf">{{ $stats['total_students'] }}</div>
        <div class="stat-label">Total Students</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #f43f5e">
        <div class="stat-icon">🏛</div>
        <div class="stat-num" style="color:#fb7185">{{ $stats['total_govt'] }}</div>
        <div class="stat-label">Govt. Users</div>
    </div>
    <div class="stat-card" style="border-left:3px solid #8b5cf6">
        <div class="stat-icon">👥</div>
        <div class="stat-num" style="color:#a78bfa">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Total Users</div>
    </div>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <h2 style="font-size:18px;font-weight:700;">Recent University Registrations</h2>
    <a href="{{ route('admin.universities') }}" class="btn btn-primary">View All</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>University</th>
                <th>State</th>
                <th>Type</th>
                <th>Registered By</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentUniversities as $u)
            <tr>
                <td>
                    <div style="font-weight:600">{{ $u->name }}</div>
                    <div style="font-size:12px;color:var(--text2)">{{ $u->affiliation_no }}</div>
                </td>
                <td>{{ $u->city }}, {{ $u->state }}</td>
                <td><span class="badge badge-active" style="text-transform:capitalize">{{ $u->type }}</span></td>
                <td>{{ $u->user->name }}</td>
                <td><span class="badge badge-{{ $u->status }}">{{ ucfirst($u->status) }}</span></td>
                <td>
                    <span style="color:var(--text2);font-size:13px">—</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:var(--text2);padding:30px">No universities registered yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
