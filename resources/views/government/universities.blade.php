@extends('layouts.app')
@section('title', 'Universities')
@section('page-title', '🏛️ Approved Universities')

@section('sidebar-nav')
<a href="{{ route('government.dashboard') }}" class="nav-item"><span class="icon">📊</span> Dashboard</a>
<a href="{{ route('government.data') }}" class="nav-item"><span class="icon">🗂</span> Student Data</a>
<a href="{{ route('government.universities') }}" class="nav-item active"><span class="icon">🏛️</span> Universities</a>
<a href="{{ route('government.export') }}" class="nav-item"><span class="icon">📥</span> Export CSV</a>
@endsection

@section('content')
<div class="table-wrap">
    <table>
        <thead>
            <tr><th>University Name</th><th>Affiliation No.</th><th>Type</th><th>City</th><th>State</th><th>Contact</th></tr>
        </thead>
        <tbody>
            @forelse($universities as $u)
            <tr>
                <td style="font-weight:600">{{ $u->name }}</td>
                <td style="font-family:monospace">{{ $u->affiliation_no }}</td>
                <td><span class="badge badge-active" style="text-transform:capitalize">{{ $u->type }}</span></td>
                <td>{{ $u->city }}</td>
                <td>{{ $u->state }}</td>
                <td>{{ $u->contact_phone }}</td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><div class="icon">🏛️</div><h3>No approved universities</h3></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $universities->links() }}</div>
@endsection
