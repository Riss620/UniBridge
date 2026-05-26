<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UniBridge') — University Open Data Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f1117;
            --bg2: #1a1d27;
            --bg3: #22263a;
            --card: rgba(255,255,255,0.04);
            --border: rgba(255,255,255,0.08);
            --text: #e8eaf0;
            --text2: #8b90a0;
            --admin: #6366f1;
            --university: #14b8a6;
            --government: #f59e0b;
            --student: #f43f5e;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; display:flex; }

        /* Sidebar */
        .sidebar { width:260px; background:var(--bg2); border-right:1px solid var(--border); display:flex; flex-direction:column; position:fixed; height:100vh; z-index:100; }
        .sidebar-logo { padding:24px 20px; border-bottom:1px solid var(--border); }
        .sidebar-logo a { text-decoration:none; display:flex; align-items:center; gap:12px; }
        .logo-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; background:linear-gradient(135deg,#6366f1,#8b5cf6); }
        .logo-text { font-size:18px; font-weight:700; color:var(--text); letter-spacing:-0.5px; }
        .logo-sub { font-size:10px; color:var(--text2); }
        .sidebar-nav { flex:1; padding:16px 0; overflow-y:auto; }
        .nav-section { padding:8px 20px; font-size:10px; font-weight:600; color:var(--text2); text-transform:uppercase; letter-spacing:1.5px; margin-top:8px; }
        .nav-item { display:flex; align-items:center; gap:12px; padding:10px 20px; color:var(--text2); text-decoration:none; font-size:14px; font-weight:500; transition:all .2s; border-left:3px solid transparent; }
        .nav-item:hover, .nav-item.active { background:var(--card); color:var(--text); border-left-color:var(--accent,#6366f1); }
        .nav-item .icon { font-size:18px; width:20px; text-align:center; }
        .sidebar-footer { padding:16px 20px; border-top:1px solid var(--border); }
        .user-card { display:flex; align-items:center; gap:12px; }
        .avatar { width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; color:#fff; flex-shrink:0; }
        .user-info .name { font-size:14px; font-weight:600; }
        .user-info .role-badge { font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600; text-transform:uppercase; }
        .logout-btn { width:100%; margin-top:12px; padding:8px; background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); color:#ef4444; border-radius:8px; cursor:pointer; font-size:13px; font-weight:500; display:flex; align-items:center; justify-content:center; gap:8px; text-decoration:none; transition:all .2s; }
        .logout-btn:hover { background:rgba(239,68,68,.2); }

        /* Main */
        .main { margin-left:260px; flex:1; display:flex; flex-direction:column; min-height:100vh; }
        .topbar { background:var(--bg2); border-bottom:1px solid var(--border); padding:16px 32px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; }
        .page-title { font-size:20px; font-weight:700; }
        .topbar-right { display:flex; align-items:center; gap:12px; }
        .content { padding:32px; flex:1; }

        /* Cards */
        .glass-card { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:24px; backdrop-filter:blur(10px); }
        .stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:32px; }
        .stat-card { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:20px; position:relative; overflow:hidden; transition:transform .2s; }
        .stat-card:hover { transform:translateY(-2px); }
        .stat-card::before { content:''; position:absolute; top:0; right:0; width:80px; height:80px; border-radius:50%; opacity:.1; transform:translate(20px,-20px); }
        .stat-num { font-size:32px; font-weight:800; margin-top:8px; }
        .stat-label { font-size:12px; color:var(--text2); font-weight:500; text-transform:uppercase; letter-spacing:.5px; }
        .stat-icon { font-size:20px; }

        /* Tables */
        .table-wrap { background:var(--card); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        th { background:var(--bg3); padding:12px 16px; text-align:left; font-size:11px; color:var(--text2); text-transform:uppercase; letter-spacing:.8px; font-weight:600; }
        td { padding:12px 16px; border-top:1px solid var(--border); font-size:14px; }
        tr:hover td { background:rgba(255,255,255,.02); }

        /* Badges */
        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .badge-pending    { background:rgba(245,158,11,.15); color:#f59e0b; }
        .badge-approved   { background:rgba(34,197,94,.15); color:#22c55e; }
        .badge-rejected   { background:rgba(239,68,68,.15); color:#ef4444; }
        .badge-active     { background:rgba(20,184,166,.15); color:#14b8a6; }
        .badge-graduated  { background:rgba(99,102,241,.15); color:#6366f1; }
        .badge-dropout    { background:rgba(239,68,68,.15); color:#ef4444; }
        .badge-admin      { background:rgba(99,102,241,.2); color:#818cf8; }
        .badge-university { background:rgba(20,184,166,.2); color:#2dd4bf; }
        .badge-government { background:rgba(245,158,11,.2); color:#fbbf24; }
        .badge-student    { background:rgba(244,63,94,.2); color:#fb7185; }

        /* Buttons */
        .btn { display:inline-flex; align-items:center; gap:8px; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:all .2s; }
        .btn-primary   { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; }
        .btn-primary:hover { opacity:.9; transform:translateY(-1px); }
        .btn-success   { background:rgba(34,197,94,.15); color:#22c55e; border:1px solid rgba(34,197,94,.3); }
        .btn-success:hover { background:rgba(34,197,94,.25); }
        .btn-danger    { background:rgba(239,68,68,.15); color:#ef4444; border:1px solid rgba(239,68,68,.3); }
        .btn-danger:hover  { background:rgba(239,68,68,.25); }
        .btn-warning   { background:rgba(245,158,11,.15); color:#f59e0b; border:1px solid rgba(245,158,11,.3); }
        .btn-sm { padding:5px 10px; font-size:12px; }
        .btn-teal { background:linear-gradient(135deg,#14b8a6,#06b6d4); color:#fff; }
        .btn-amber { background:linear-gradient(135deg,#f59e0b,#ef4444); color:#fff; }

        /* Alerts */
        .alert { padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:14px; display:flex; align-items:center; gap:10px; }
        .alert-success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.2); color:#22c55e; }
        .alert-error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); color:#ef4444; }
        .alert-warning { background:rgba(245,158,11,.1); border:1px solid rgba(245,158,11,.2); color:#f59e0b; }
        .alert-info    { background:rgba(99,102,241,.1); border:1px solid rgba(99,102,241,.2); color:#818cf8; }

        /* Forms */
        .form-group { margin-bottom:20px; }
        .form-label { display:block; font-size:13px; font-weight:500; color:var(--text2); margin-bottom:6px; }
        .form-control { width:100%; padding:10px 14px; background:var(--bg3); border:1px solid var(--border); border-radius:10px; color:var(--text); font-size:14px; font-family:'Inter',sans-serif; transition:border-color .2s; }
        .form-control:focus { outline:none; border-color:var(--accent,#6366f1); }
        .form-control option { background:var(--bg2); }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .form-error { color:#ef4444; font-size:12px; margin-top:4px; }

        /* Pagination */
        .pagination { display:flex; gap:6px; margin-top:16px; flex-wrap:wrap; }
        .pagination a, .pagination span { padding:6px 12px; border-radius:8px; border:1px solid var(--border); font-size:13px; text-decoration:none; color:var(--text2); background:var(--card); }
        .pagination .active span { background:#6366f1; color:#fff; border-color:#6366f1; }

        /* Empty state */
        .empty-state { text-align:center; padding:60px 20px; color:var(--text2); }
        .empty-state .icon { font-size:48px; margin-bottom:16px; }
        .empty-state h3 { font-size:18px; font-weight:600; margin-bottom:8px; color:var(--text); }

        /* Responsive */
        @media (max-width:768px) {
            .sidebar { width:0; overflow:hidden; }
            .main { margin-left:0; }
            .form-grid { grid-template-columns:1fr; }
            .stat-grid { grid-template-columns:1fr 1fr; }
        }
        @yield('extra-styles')
    </style>
    @yield('head')
</head>
<body>
@php $user = auth()->user(); @endphp
@php
    $accentMap = ['admin'=>'#6366f1','university'=>'#14b8a6','government'=>'#f59e0b','student'=>'#f43f5e'];
    $accent = $accentMap[$user->role] ?? '#6366f1';
    $avatarColors = ['admin'=>'#6366f1','university'=>'#14b8a6','government'=>'#f59e0b','student'=>'#f43f5e'];
    $avatarBg = $avatarColors[$user->role] ?? '#6366f1';
@endphp
<style>:root { --accent: {{ $accent }}; }</style>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('home') }}">
            <div class="logo-icon">🌉</div>
            <div>
                <div class="logo-text">UniBridge</div>
                <div class="logo-sub">Open Data Platform</div>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">
        @yield('sidebar-nav')
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="avatar" style="background:{{ $avatarBg }}">{{ strtoupper(substr($user->name,0,1)) }}</div>
            <div class="user-info">
                <div class="name">{{ Str::limit($user->name, 18) }}</div>
                <span class="role-badge badge badge-{{ $user->role }}">{{ $user->role }}</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">🚪 Logout</button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="main">
    <div class="topbar">
        <div class="page-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-right">
            <span style="font-size:12px;color:var(--text2)">{{ now()->format('d M Y') }}</span>
        </div>
    </div>
    <div class="content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">⚠️ {{ session('warning') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">ℹ️ {{ session('info') }}</div>
        @endif

        @yield('content')
    </div>
</div>
</body>
</html>
