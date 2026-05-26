@extends('layouts.app')
@section('title', 'Admin - Users')
@section('page-title', '👥 User Management')

@section('sidebar-nav')
<a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="icon">📊</span> Dashboard</a>
<a href="{{ route('admin.universities') }}" class="nav-item"><span class="icon">🏛️</span> Universities</a>
<a href="{{ route('admin.students') }}" class="nav-item"><span class="icon">🎓</span> Students</a>
<a href="{{ route('admin.users') }}" class="nav-item active"><span class="icon">👥</span> Users</a>
@endsection

@section('content')
<div class="table-wrap">
    <table>
        <thead>
            <tr><th>Name</th><th>Email</th><th>Role</th><th>Verified</th><th>Status</th><th>Joined</th><th>Action</th></tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td style="font-weight:600">{{ $u->name }}</td>
                <td style="font-size:13px;color:var(--text2)">{{ $u->email }}</td>
                <td><span class="badge badge-{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
                <td>{{ $u->isVerified() ? '✅ Yes' : '❌ No' }}</td>
                <td><span class="badge {{ $u->is_active ? 'badge-approved' : 'badge-rejected' }}">{{ $u->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td style="font-size:12px;color:var(--text2)">{{ $u->created_at->format('d M Y') }}</td>
                <td>
                    @if($u->id !== auth()->id())
                    <form action="{{ route('admin.users.toggle', $u) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-danger' : 'btn-success' }}">
                            {{ $u->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    @else
                    <span style="color:var(--text2);font-size:12px">You</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="pagination">{{ $users->links() }}</div>
@endsection
