@extends('layouts.app')
@section('title', 'Manage Universities')
@section('page-title', '🏛️ Universities')

@section('sidebar-nav')
<a href="{{ route('government.dashboard') }}" class="nav-item"><span class="icon">📊</span> Dashboard</a>
<a href="{{ route('government.data') }}" class="nav-item"><span class="icon">🗂</span> Student Data</a>
<a href="{{ route('government.universities') }}" class="nav-item active"><span class="icon">🏛️</span> Universities</a>
<a href="{{ route('government.export') }}" class="nav-item"><span class="icon">📥</span> Export CSV</a>
@endsection

@section('content')
<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;">
    @foreach(['all'=>'All','pending'=>'⏳ Pending','approved'=>'✅ Approved','rejected'=>'❌ Rejected'] as $key=>$label)
    <a href="{{ route('government.universities') }}?status={{ $key }}"
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
                <th>Actions</th>
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
                <td>
                    @if($u->status === 'pending')
                    <form action="{{ route('government.universities.approve', $u) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">✓ Approve</button>
                    </form>
                    <form action="{{ route('government.universities.reject', $u) }}" method="POST" style="display:inline" onsubmit="return confirm('Reject this university?')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">✗ Reject</button>
                    </form>
                    @elseif($u->status === 'approved')
                    <form action="{{ route('government.universities.reject', $u) }}" method="POST" style="display:inline" onsubmit="return confirm('Revoke approval?')">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">Revoke</button>
                    </form>
                    @elseif($u->status === 'rejected')
                    <form action="{{ route('government.universities.approve', $u) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Re-approve</button>
                    </form>
                    @endif
                </td>
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
