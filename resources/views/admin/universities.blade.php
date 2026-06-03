@extends('layouts.app')
@section('title', 'Manage Universities')
@section('page-title', '🏛️ Universities')

@section('sidebar-nav')
<div class="nav-section">Overview</div>
<a href="{{ route('admin.dashboard') }}" class="nav-item">
    <span class="icon">📊</span> Dashboard
</a>
<div class="nav-section">Management</div>
<a href="{{ route('admin.universities') }}" class="nav-item active">
    <span class="icon">🏛️</span> Universities
</a>
<a href="{{ route('admin.students') }}" class="nav-item">
    <span class="icon">🎓</span> Students
</a>
<a href="{{ route('admin.users') }}" class="nav-item">
    <span class="icon">👥</span> Users
</a>
@endsection

@section('content')
<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;">
    @foreach(['all'=>'All','pending'=>'⏳ Pending','approved'=>'✅ Approved','rejected'=>'❌ Rejected'] as $key=>$label)
    <a href="{{ route('admin.universities') }}?status={{ $key }}"
       class="btn {{ $filter===$key ? 'btn-primary' : '' }}"
       style="{{ $filter!==$key ? 'border:1px solid var(--border);color:var(--text2)' : '' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>University</th>
                <th>Location</th>
                <th>Affiliation No.</th>
                <th>Type</th>
                <th>Status</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            @forelse($universities as $u)
            <tr>
                <td style="color:var(--text2)">{{ $u->id }}</td>
                <td>
                    <div style="font-weight:600">{{ $u->name }}</div>
                    <div style="font-size:12px;color:var(--text2)">{{ $u->user->email }}</div>
                </td>
                <td>{{ $u->city }}, {{ $u->state }}</td>
                <td style="font-family:monospace;font-size:13px">{{ $u->affiliation_no }}</td>
                <td><span class="badge badge-active" style="text-transform:capitalize">{{ $u->type }}</span></td>
                <td><span class="badge badge-{{ $u->status }}">{{ ucfirst($u->status) }}</span></td>
                <td style="font-size:12px;color:var(--text2)">{{ $u->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="8">
                <div class="empty-state">
                    <div class="icon">🏛️</div>
                    <h3>No Universities Found</h3>
                    <p>No universities match this filter.</p>
                </div>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $universities->links() }}</div>
@endsection
