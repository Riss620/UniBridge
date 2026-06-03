@extends('layouts.app')
@section('title', 'Students - ' . $university->name)
@section('page-title', '🎓 Student Management')

@section('sidebar-nav')
<a href="{{ route('university.dashboard') }}" class="nav-item"><span class="icon">📊</span> Dashboard</a>
<a href="{{ route('university.students') }}" class="nav-item active"><span class="icon">🎓</span> All Students</a>
<a href="{{ route('university.students.create') }}" class="nav-item"><span class="icon">➕</span> Add Student</a>
<a href="{{ route('university.profile') }}" class="nav-item"><span class="icon">⚙️</span> Profile</a>
@endsection

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <form method="GET" style="display:flex;gap:10px">
        <input type="text" name="search" class="form-control" style="width:260px" value="{{ $search }}" placeholder="🔍 Search students...">
        <button type="submit" class="btn btn-teal">Search</button>
        @if($search)<a href="{{ route('university.students') }}" class="btn" style="border:1px solid var(--border);color:var(--text2)">Clear</a>@endif
    </form>
    <a href="{{ route('university.students.create') }}" class="btn btn-teal">➕ Add Student</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr><th>Name</th><th>Roll No</th><th>Course / Dept</th><th>Year</th><th>CGPA</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse($students as $s)
            <tr>
                <td>
                    <div style="font-weight:600">{{ $s->name }}</div>
                    <div style="font-size:12px;color:var(--text2)">{{ $s->email }}</div>
                </td>
                <td style="font-family:monospace;font-size:13px">{{ $s->roll_no }}</td>
                <td>
                    <div>{{ $s->course }}</div>
                    <div style="font-size:12px;color:var(--text2)">{{ $s->department }}</div>
                </td>
                <td>Year {{ $s->year }}</td>
                <td>{{ $s->cgpa ?? '—' }}</td>
                <td><span class="badge badge-{{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
                <td style="display:flex;gap:6px;align-items:center;">
                    @if($s->status === 'pending')
                    <form action="{{ route('university.students.approve', $s) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">✓ Approve</button>
                    </form>
                    @endif
                    <a href="{{ route('university.students.edit', $s) }}" class="btn btn-warning btn-sm">✏ Edit</a>
                    <form action="{{ route('university.students.delete', $s) }}" method="POST" onsubmit="return confirm('Delete this student?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7">
                <div class="empty-state">
                    <div class="icon">🎓</div>
                    <h3>No students found</h3>
                    <a href="{{ route('university.students.create') }}" class="btn btn-teal" style="margin-top:12px">Add First Student</a>
                </div>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $students->links() }}</div>
@endsection
