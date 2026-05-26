<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UniBridge') — University Open Data Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #6366f1; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Inter',sans-serif;
            min-height:100vh;
            background: #0f1117;
            display:flex;
            align-items:flex-start;
            justify-content:center;
            position:relative;
            overflow-y:auto;
            padding:24px 16px;
        }
        body::before {
            content:'';
            position:fixed; inset:0;
            background:
                radial-gradient(ellipse 800px 600px at 20% 10%, rgba(99,102,241,.15) 0%, transparent 70%),
                radial-gradient(ellipse 600px 400px at 80% 80%, rgba(20,184,166,.1) 0%, transparent 70%);
            pointer-events:none;
        }
        .auth-card {
            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.08);
            border-radius:20px;
            padding:28px 32px;
            width:100%;
            max-width:460px;
            backdrop-filter:blur(20px);
            position:relative;
            z-index:1;
            animation:slideUp .4s ease;
            margin:auto;
        }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .auth-logo { text-align:center; margin-bottom:20px; }
        .auth-logo .icon { font-size:34px; display:block; margin-bottom:6px; }
        .auth-logo h1 { font-size:24px; font-weight:800; color:#e8eaf0; letter-spacing:-1px; }
        .auth-logo p { font-size:13px; color:#8b90a0; margin-top:2px; }
        .auth-tabs { display:flex; gap:4px; background:rgba(255,255,255,.04); border-radius:12px; padding:4px; margin-bottom:24px; }
        .auth-tab { flex:1; padding:8px; text-align:center; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; border:none; background:none; color:#8b90a0; text-decoration:none; transition:all .2s; }
        .auth-tab.active, .auth-tab:hover { background:#6366f1; color:#fff; }
        h2.form-heading { font-size:20px; font-weight:700; color:#e8eaf0; margin-bottom:6px; }
        .sub { font-size:14px; color:#8b90a0; margin-bottom:24px; }
        .form-group { margin-bottom:12px; }
        .form-label { display:block; font-size:13px; font-weight:500; color:#8b90a0; margin-bottom:6px; }
        .form-control {
            width:100%; padding:11px 14px;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.1);
            border-radius:10px; color:#e8eaf0;
            font-size:14px; font-family:'Inter',sans-serif;
            transition:border-color .2s;
        }
        .form-control:focus { outline:none; border-color:var(--accent); background:rgba(255,255,255,.08); }
        .form-control option { background:#1a1d27; }
        select.form-control { cursor:pointer; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .form-error { color:#ef4444; font-size:12px; margin-top:4px; }
        .btn-submit {
            width:100%; padding:12px;
            background:linear-gradient(135deg, var(--accent), #8b5cf6);
            border:none; border-radius:12px;
            color:#fff; font-size:15px; font-weight:700;
            cursor:pointer; margin-top:8px;
            transition:all .2s; letter-spacing:.3px;
        }
        .btn-submit:hover { opacity:.9; transform:translateY(-1px); box-shadow:0 8px 24px rgba(99,102,241,.3); }
        .auth-links { text-align:center; margin-top:14px; font-size:13px; color:#8b90a0; }
        .auth-links a { color:var(--accent); text-decoration:none; font-weight:600; }
        .alert { padding:12px 14px; border-radius:10px; margin-bottom:16px; font-size:13px; display:flex; gap:8px; align-items:flex-start; }
        .alert-success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.2); color:#22c55e; }
        .alert-error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); color:#ef4444; }
        .divider { display:flex; align-items:center; gap:12px; margin:20px 0; color:#8b90a0; font-size:12px; }
        .divider::before, .divider::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.08); }
        .role-selector { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:20px; }
        .role-option { position:relative; }
        .role-option input { position:absolute; opacity:0; }
        .role-option label {
            display:flex; flex-direction:column; align-items:center; gap:6px;
            padding:14px 10px; border:2px solid rgba(255,255,255,.08);
            border-radius:12px; cursor:pointer; transition:all .2s; font-size:12px;
            color:#8b90a0; font-weight:600; text-transform:uppercase; letter-spacing:.5px;
        }
        .role-option label span:first-child { font-size:24px; }
        .role-option input:checked + label { border-color:var(--role-color); background:rgba(var(--role-rgb),0.08); color:var(--role-color); }
        .section-header { font-size:13px; font-weight:700; color:#8b90a0; text-transform:uppercase; letter-spacing:1px; margin:20px 0 12px; padding-top:16px; border-top:1px solid rgba(255,255,255,.06); }
    </style>
    @yield('extra-styles')
</head>
<body>
<div class="auth-card">
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            <span>❌</span>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif
    @yield('content')
</div>
</body>
</html>
