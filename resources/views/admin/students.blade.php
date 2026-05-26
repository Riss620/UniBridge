@extends('layouts.app')
@section('title', 'Admin - All Students')
@section('page-title', '🎓 Student Records')

@section('sidebar-nav')
<a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="icon">📊</span> Dashboard</a>
<a href="{{ route('admin.universities') }}" class="nav-item"><span class="icon">🏛️</span> Universities</a>
<a href="{{ route('admin.students') }}" class="nav-item active"><span class="icon">🎓</span> Students</a>
<a href="{{ route('admin.users') }}" class="nav-item"><span class="icon">👥</span> Users</a>
@endsection

@section('content')
<form method="GET" style="display:flex;gap:12px;margin-bottom:20px;">
    <input type="text" name="search" class="form-control" style="max-width:300px" value="{{ $search }}" placeholder="🔍 Search by name, roll no, course...">
    <button type="submit" class="btn btn-primary">Search</button>
    @if($search)<a href="{{ route('admin.students') }}" class="btn" style="border:1px solid var(--border);color:var(--text2)">Clear</a>@endif
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr><th>Name</th><th>Roll No</th><th>Course / Dept</th><th>Year</th><th>CGPA</th><th>University</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse($students as $s)
            <tr>
                <td><div style="font-weight:600">{{ $s->name }}</div><div style="font-size:12px;color:var(--text2)">{{ $s->email }}</div></td>
                <td style="font-family:monospace">{{ $s->roll_no }}</td>
                <td><div>{{ $s->course }}</div><div style="font-size:12px;color:var(--text2)">{{ $s->department }}</div></td>
                <td>Year {{ $s->year }}</td>
                <td>{{ $s->cgpa ?? '—' }}</td>
                <td style="font-size:13px">{{ $s->university->name }}</td>
                <td><span class="badge badge-{{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><div class="icon">🎓</div><h3>No students found</h3></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $students->links() }}</div>
@endsection
