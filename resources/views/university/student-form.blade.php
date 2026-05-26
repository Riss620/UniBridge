@extends('layouts.app')
@section('title', $student ? 'Edit Student' : 'Add Student')
@section('page-title', $student ? '✏ Edit Student' : '➕ Add Student')

@section('sidebar-nav')
<a href="{{ route('university.dashboard') }}" class="nav-item"><span class="icon">📊</span> Dashboard</a>
<a href="{{ route('university.students') }}" class="nav-item"><span class="icon">🎓</span> All Students</a>
<a href="{{ route('university.students.create') }}" class="nav-item active"><span class="icon">➕</span> Add Student</a>
<a href="{{ route('university.profile') }}" class="nav-item"><span class="icon">⚙️</span> Profile</a>
@endsection

@section('content')
<div class="glass-card" style="max-width:700px">
    <form action="{{ $student ? route('university.students.update', $student) : route('university.students.store') }}" method="POST">
        @csrf
        @if($student) @method('PUT') @endif

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $student?->name) }}" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Roll Number *</label>
                <input type="text" name="roll_no" class="form-control" value="{{ old('roll_no', $student?->roll_no) }}" required>
                @error('roll_no')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $student?->email) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Course *</label>
                <input type="text" name="course" class="form-control" value="{{ old('course', $student?->course) }}" placeholder="B.Tech, M.Sc..." required>
            </div>
            <div class="form-group">
                <label class="form-label">Department *</label>
                <input type="text" name="department" class="form-control" value="{{ old('department', $student?->department) }}" placeholder="Computer Science..." required>
            </div>
            <div class="form-group">
                <label class="form-label">Year *</label>
                <select name="year" class="form-control" required>
                    @for($i=1;$i<=7;$i++)
                    <option value="{{ $i }}" {{ old('year',$student?->year)==$i?'selected':'' }}>Year {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">CGPA (out of 10)</label>
                <input type="number" name="cgpa" class="form-control" step="0.1" min="0" max="10" value="{{ old('cgpa', $student?->cgpa) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="status" class="form-control" required>
                    @foreach(['active'=>'Active','graduated'=>'Graduated','dropout'=>'Dropout'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('status',$student?->status??'active')===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Admission Year *</label>
                <input type="number" name="admission_year" class="form-control" min="2000" max="{{ date('Y') }}" value="{{ old('admission_year', $student?->admission_year) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Passout Year</label>
                <input type="number" name="passout_year" class="form-control" min="2000" max="{{ date('Y')+6 }}" value="{{ old('passout_year', $student?->passout_year) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-control">
                    <option value="">— Select —</option>
                    @foreach(['male'=>'Male','female'=>'Female','other'=>'Other'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('gender',$student?->gender)===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">State of Origin</label>
                <input type="text" name="state_of_origin" class="form-control" value="{{ old('state_of_origin', $student?->state_of_origin) }}" placeholder="Delhi, UP...">
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px">
            <button type="submit" class="btn btn-teal">{{ $student ? '💾 Save Changes' : '➕ Add Student' }}</button>
            <a href="{{ route('university.students') }}" class="btn" style="border:1px solid var(--border);color:var(--text2)">Cancel</a>
        </div>
    </form>
</div>
@endsection
