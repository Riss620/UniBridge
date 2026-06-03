@extends('layouts.auth')
@section('title', 'Register')
@section('extra-styles')
<style>
    .auth-card { max-width:520px; }

    /* Role Selector */
    .role-selector { display:grid; grid-template-columns:repeat(4,1fr); gap:6px; margin-bottom:16px; }
    .role-card-btn {
        display:flex; flex-direction:column; align-items:center; gap:5px;
        padding:10px 4px;
        border:2px solid rgba(255,255,255,.08);
        border-radius:12px; cursor:pointer;
        transition:all .2s;
        font-size:10px; font-weight:700;
        text-transform:uppercase; letter-spacing:.3px;
        color:#8b90a0; background:rgba(255,255,255,.03);
        user-select:none; width:100%;
        border-style: solid;
    }
    .role-card-btn span.emoji { font-size:20px; }
    .role-card-btn:hover { border-color:rgba(255,255,255,.2); color:#e8eaf0; background:rgba(255,255,255,.06); }
    .role-card-btn.active-university { border-color:#14b8a6; background:rgba(20,184,166,.12); color:#14b8a6; }
    .role-card-btn.active-government { border-color:#f59e0b; background:rgba(245,158,11,.12); color:#f59e0b; }
    .role-card-btn.active-student    { border-color:#f43f5e; background:rgba(244,63,94,.12);  color:#f43f5e; }
    .role-card-btn.active-admin      { border-color:#818cf8; background:rgba(129,140,248,.12); color:#818cf8; }

    /* Extra fields hidden by default via inline style — JS adds .show */
    .extra-fields { display:none; }
    .extra-fields.show { display:block; }
    .extra-fields .section-header {
        font-size:11px; font-weight:700; color:#8b90a0;
        text-transform:uppercase; letter-spacing:1px;
        margin:14px 0 10px; padding-top:12px;
        border-top:1px solid rgba(255,255,255,.07);
    }

    /* Tighter form */
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    h2.form-heading { font-size:18px; margin-bottom:4px; }
    .sub { font-size:13px; margin-bottom:14px; }
</style>
@endsection

@section('content')
<div class="auth-logo">
    <span class="icon">🌉</span>
    <h1>UniBridge</h1>
    <p>Create your account</p>
</div>

<div class="auth-tabs">
    <a href="{{ route('login') }}" class="auth-tab">Sign In</a>
    <a href="{{ route('register') }}" class="auth-tab active">Register</a>
</div>

<h2 class="form-heading">Join UniBridge</h2>
<p class="sub">Select your role, then fill in your details</p>

<form action="{{ route('register') }}" method="POST" id="reg-form">
    @csrf
    {{-- Hidden role field — only this gets submitted --}}
    <input type="hidden" name="role" id="role-input" value="{{ old('role', 'student') }}">

    {{-- Role Selector --}}
    <div class="role-selector">
        <button type="button" id="btn-student" class="role-card-btn" onclick="switchRole('student')">
            <span class="emoji">🎓</span> Student
        </button>
        <button type="button" id="btn-university" class="role-card-btn" onclick="switchRole('university')">
            <span class="emoji">🏛️</span> University
        </button>
        <button type="button" id="btn-government" class="role-card-btn" onclick="switchRole('government')">
            <span class="emoji">🏛</span> Government
        </button>
        <button type="button" id="btn-admin" class="role-card-btn" onclick="switchRole('admin')">
            <span class="emoji">👑</span> Admin
        </button>
    </div>

    {{-- Base Fields (all roles need these) --}}
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="John Doe" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
        </div>
    </div>

    {{-- ── University Extra Fields ───────────────────────── --}}
    <div class="extra-fields" id="university-fields" style="display:none">
        <div class="section-header">🏛️ University Details</div>
        <div class="form-group">
            <label class="form-label">University Name</label>
            <input type="text" name="university_name" class="form-control" value="{{ old('university_name') }}" placeholder="University of Delhi">
            @error('university_name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Affiliation No.</label>
                <input type="text" name="affiliation_no" class="form-control" value="{{ old('affiliation_no') }}" placeholder="DU-CENT-001">
                @error('affiliation_no')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">University Type</label>
                <select name="university_type" class="form-control">
                    <option value="">Select type</option>
                    @foreach(['central'=>'Central','state'=>'State','deemed'=>'Deemed','private'=>'Private'] as $val=>$lbl)
                        <option value="{{ $val }}" {{ old('university_type')===$val?'selected':'' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('university_type')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <input type="text" name="university_address" class="form-control" value="{{ old('university_address') }}" placeholder="Full address">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="New Delhi">
            </div>
            <div class="form-group">
                <label class="form-label">State</label>
                <input type="text" name="state" class="form-control" value="{{ old('state') }}" placeholder="Delhi">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Contact Phone</label>
            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}" placeholder="011-XXXXXXXX">
        </div>
    </div>

    {{-- ── Government Extra Fields ──────────────────────── --}}
    <div class="extra-fields" id="government-fields" style="display:none">
        <div class="section-header">🏛 Government Details</div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Department / Ministry</label>
                <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="Ministry of Education">
                @error('department')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Designation</label>
                <input type="text" name="designation" class="form-control" value="{{ old('designation') }}" placeholder="Director">
                @error('designation')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- ── Student Extra Fields ────────────────────────── --}}
    <div class="extra-fields" id="student-fields" style="display:none">
        <div class="section-header">🎓 Academic Details</div>
        <div class="form-group">
            <label class="form-label">Select University</label>
            <select name="university_id" class="form-control">
                <option value="">Select your university</option>
                @foreach($approvedUniversities as $univ)
                    <option value="{{ $univ->id }}" {{ old('university_id') == $univ->id ? 'selected' : '' }}>{{ $univ->name }}</option>
                @endforeach
            </select>
            @error('university_id')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Roll Number</label>
                <input type="text" name="roll_no" class="form-control" value="{{ old('roll_no') }}" placeholder="Roll No / Enroll No">
                @error('roll_no')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Course</label>
                <input type="text" name="course" class="form-control" value="{{ old('course') }}" placeholder="e.g. B.Tech / B.Sc / MBA">
                @error('course')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Department</label>
                <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="e.g. Computer Science">
                @error('department')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Current Year</label>
                <input type="number" name="year" class="form-control" value="{{ old('year') }}" placeholder="e.g. 1, 2, 3" min="1" max="7">
                @error('year')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Admission Year</label>
                <input type="number" name="admission_year" class="form-control" value="{{ old('admission_year') }}" placeholder="e.g. 2024">
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
        </div>
        <div class="form-group">
            <label class="form-label">State of Origin</label>
            <input type="text" name="state_of_origin" class="form-control" value="{{ old('state_of_origin') }}" placeholder="e.g. Delhi">
            @error('state_of_origin')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>

    <button type="submit" class="btn-submit" style="margin-top:14px">Create Account →</button>
</form>

<div class="auth-links">
    Already have an account? <a href="{{ route('login') }}">Sign in</a>
</div>

<script>
/**
 * switchRole(role)
 * - Updates the hidden `name="role"` field
 * - Highlights the clicked card button
 * - Hides ALL extra-fields, then shows only the one for this role
 */
function switchRole(role) {
    // 1. Update hidden field
    document.getElementById('role-input').value = role;

    // 2. Reset all buttons
    ['admin', 'university', 'government', 'student'].forEach(function(r) {
        document.getElementById('btn-' + r).className = 'role-card-btn';
    });

    // 3. Activate clicked button
    document.getElementById('btn-' + role).classList.add('active-' + role);

    // 4. Hide ALL extra-field sections (using style.display for certainty)
    document.querySelectorAll('.extra-fields').forEach(function(el) {
        el.style.display = 'none';
        el.classList.remove('show');
    });

    // 5. Show only the matching section (university / government — student has none)
    var target = document.getElementById(role + '-fields');
    if (target) {
        target.style.display = 'block';
        target.classList.add('show');
    }
}

// On page load — restore correct role (handles back-button / validation errors)
(function() {
    var savedRole = document.getElementById('role-input').value || 'student';
    switchRole(savedRole);
})();
</script>
@endsection
