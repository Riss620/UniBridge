@extends('layouts.app')
@section('title', 'Create User')
@section('page-title', '👥 Create New User')

@section('sidebar-nav')
<a href="{{ route('admin.dashboard') }}" class="nav-item"><span class="icon">📊</span> Dashboard</a>
<a href="{{ route('admin.universities') }}" class="nav-item"><span class="icon">🏛️</span> Universities</a>
<a href="{{ route('admin.students') }}" class="nav-item"><span class="icon">🎓</span> Students</a>
<a href="{{ route('admin.users') }}" class="nav-item active"><span class="icon">👥</span> Users</a>
@endsection

@section('content')
<div class="glass-card" style="max-width:700px">
    <form action="{{ route('admin.users.store') }}" method="POST" id="user-form">
        @csrf

        <h3 style="margin-top:0;margin-bottom:16px;font-size:18px;font-weight:700;">Base User Account</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control" required placeholder="Min 8 characters">
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role" id="role-select" class="form-control" required onchange="toggleRoleFields(this.value)">
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="university" {{ old('role') === 'university' ? 'selected' : '' }}>University</option>
                    <option value="government" {{ old('role') === 'government' ? 'selected' : '' }}>Government</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── University Details ── --}}
        <div id="university-details" class="role-details-section" style="display:none;border-top:1px solid var(--border);padding-top:20px;margin-bottom:20px;">
            <h3 style="margin-top:0;margin-bottom:16px;font-size:18px;font-weight:700;color:var(--primary);">🏛️ University Details</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">University Name *</label>
                    <input type="text" name="university_name" class="form-control" value="{{ old('university_name') }}">
                    @error('university_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Affiliation No. *</label>
                    <input type="text" name="affiliation_no" class="form-control" value="{{ old('affiliation_no') }}">
                    @error('affiliation_no')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">University Type *</label>
                    <select name="university_type" class="form-control">
                        <option value="central" {{ old('university_type') === 'central' ? 'selected' : '' }}>Central</option>
                        <option value="state" {{ old('university_type') === 'state' ? 'selected' : '' }}>State</option>
                        <option value="deemed" {{ old('university_type') === 'deemed' ? 'selected' : '' }}>Deemed</option>
                        <option value="private" {{ old('university_type') === 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                    @error('university_type')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Full Address *</label>
                    <input type="text" name="university_address" class="form-control" value="{{ old('university_address') }}">
                    @error('university_address')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">City *</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                    @error('city')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">State *</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                    @error('state')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Contact Phone *</label>
                    <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}">
                    @error('contact_phone')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- ── Government Details ── --}}
        <div id="government-details" class="role-details-section" style="display:none;border-top:1px solid var(--border);padding-top:20px;margin-bottom:20px;">
            <h3 style="margin-top:0;margin-bottom:16px;font-size:18px;font-weight:700;color:var(--primary);">🏛 Government Details</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department') }}">
                    @error('department')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Designation *</label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation') }}">
                    @error('designation')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- ── Student Details ── --}}
        <div id="student-details" class="role-details-section" style="display:none;border-top:1px solid var(--border);padding-top:20px;margin-bottom:20px;">
            <h3 style="margin-top:0;margin-bottom:16px;font-size:18px;font-weight:700;color:var(--primary);">🎓 Student Academic Details</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Select University *</label>
                    <select name="university_id" class="form-control">
                        <option value="">Select University</option>
                        @foreach($approvedUniversities as $univ)
                            <option value="{{ $univ->id }}" {{ old('university_id') == $univ->id ? 'selected' : '' }}>{{ $univ->name }}</option>
                        @endforeach
                    </select>
                    @error('university_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Roll Number *</label>
                    <input type="text" name="roll_no" class="form-control" value="{{ old('roll_no') }}">
                    @error('roll_no')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Course *</label>
                    <input type="text" name="course" class="form-control" value="{{ old('course') }}" placeholder="e.g. B.Tech / B.Sc">
                    @error('course')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="e.g. Computer Science">
                    @error('department')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Current Year *</label>
                    <input type="number" name="year" class="form-control" value="{{ old('year') }}" min="1" max="7">
                    @error('year')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Admission Year *</label>
                    <input type="number" name="admission_year" class="form-control" value="{{ old('admission_year') }}">
                    @error('admission_year')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">State of Origin</label>
                    <input type="text" name="state_of_origin" class="form-control" value="{{ old('state_of_origin') }}">
                    @error('state_of_origin')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:20px;border-top:1px solid var(--border);padding-top:20px;">
            <button type="submit" class="btn btn-primary">➕ Create User</button>
            <a href="{{ route('admin.users') }}" class="btn" style="border:1px solid var(--border);color:var(--text2)">Cancel</a>
        </div>
    </form>
</div>

<script>
function toggleRoleFields(role) {
    // Hide all sections
    document.querySelectorAll('.role-details-section').forEach(function(el) {
        el.style.display = 'none';
        el.querySelectorAll('input, select').forEach(function(input) {
            input.removeAttribute('required');
        });
    });

    // Show selected role section
    if (role === 'university') {
        var section = document.getElementById('university-details');
        section.style.display = 'block';
        section.querySelectorAll('input:not([name="website"]), select').forEach(function(input) {
            input.setAttribute('required', 'required');
        });
    } else if (role === 'government') {
        var section = document.getElementById('government-details');
        section.style.display = 'block';
        section.querySelectorAll('input').forEach(function(input) {
            input.setAttribute('required', 'required');
        });
    } else if (role === 'student') {
        var section = document.getElementById('student-details');
        section.style.display = 'block';
        section.querySelectorAll('input:not([name="gender"]):not([name="state_of_origin"]), select:not([name="gender"])').forEach(function(input) {
            input.setAttribute('required', 'required');
        });
    }
}

// Set initial view on load
document.addEventListener('DOMContentLoaded', function() {
    var savedRole = document.getElementById('role-select').value || 'student';
    toggleRoleFields(savedRole);
});
</script>
@endsection
