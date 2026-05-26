@extends('layouts.app')
@section('title', 'Open Student Data')
@section('page-title', '🗂 Open Student Data Browser')

@section('sidebar-nav')
<a href="{{ route('government.dashboard') }}" class="nav-item"><span class="icon">📊</span> Dashboard</a>
<a href="{{ route('government.data') }}" class="nav-item active"><span class="icon">🗂</span> Student Data</a>
<a href="{{ route('government.universities') }}" class="nav-item"><span class="icon">🏛️</span> Universities</a>
<a href="{{ route('government.export') }}" class="nav-item"><span class="icon">📥</span> Export CSV</a>
@endsection

@section('content')
<form method="GET" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px">
    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="🔍 Name / Roll / Course">
    <select name="state" class="form-control">
        <option value="">All States</option>
        @foreach($states as $st)<option value="{{ $st }}" {{ $state===$st?'selected':'' }}>{{ $st }}</option>@endforeach
    </select>
    <select name="university" class="form-control">
        <option value="">All Universities</option>
        @foreach($universities as $u)<option value="{{ $u->id }}" {{ $university==$u->id?'selected':'' }}>{{ $u->name }}</option>@endforeach
    </select>
    <input type="text" name="course" class="form-control" value="{{ $course }}" placeholder="Course">
    <select name="status" class="form-control">
        <option value="">All Status</option>
        @foreach(['active'=>'Active','graduated'=>'Graduated','dropout'=>'Dropout'] as $v=>$l)
        <option value="{{ $v }}" {{ $status===$v?'selected':'' }}>{{ $l }}</option>
        @endforeach
    </select>
    <div style="display:flex;gap:8px">
        <button type="submit" class="btn btn-amber" style="flex:1">Filter</button>
        <a href="{{ route('government.data') }}" class="btn" style="border:1px solid var(--border);color:var(--text2)">Clear</a>
    </div>
</form>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
    <span style="font-size:14px;color:var(--text2)">{{ $students->total() }} records found</span>
    <a href="{{ route('government.export') }}?state={{ $state }}&course={{ $course }}&status={{ $status }}" class="btn btn-amber">📥 Export CSV</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr><th>Name</th><th>Roll No</th><th>Course</th><th>University</th><th>State</th><th>Year</th><th>CGPA</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse($students as $s)
            <tr>
                <td style="font-weight:600">{{ $s->name }}</td>
                <td style="font-family:monospace;font-size:13px">{{ $s->roll_no }}</td>
                <td>
                    <div>{{ $s->course }}</div>
                    <div style="font-size:12px;color:var(--text2)">{{ $s->department }}</div>
                </td>
                <td style="font-size:13px">{{ $s->university->name }}</td>
                <td>{{ $s->university->state }}</td>
                <td>{{ $s->year }}</td>
                <td>{{ $s->cgpa ?? '—' }}</td>
                <td><span class="badge badge-{{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><div class="icon">🗂</div><h3>No records match your filters</h3></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $students->links() }}</div>
@endsection
