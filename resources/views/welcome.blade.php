<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniBridge — University Open Data Platform</title>
    <meta name="description" content="UniBridge bridges the gap between Indian Universities, Government, and Students through open data.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        :root { --bg:#0f1117; --text:#e8eaf0; --text2:#8b90a0; --border:rgba(255,255,255,.08); }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); overflow-x:hidden; }

        /* Nav */
        nav { position:fixed; top:0; left:0; right:0; z-index:100; padding:16px 40px; display:flex; align-items:center; justify-content:space-between; background:rgba(15,17,23,.8); backdrop-filter:blur(20px); border-bottom:1px solid var(--border); }
        .nav-logo { display:flex; align-items:center; gap:12px; text-decoration:none; }
        .logo-icon { width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg,#6366f1,#8b5cf6); display:flex; align-items:center; justify-content:center; font-size:18px; }
        .logo-text { font-size:18px; font-weight:800; color:var(--text); letter-spacing:-0.5px; }
        .nav-links { display:flex; gap:8px; align-items:center; }
        .nav-links a { text-decoration:none; padding:8px 16px; border-radius:8px; font-size:14px; font-weight:600; transition:all .2s; }
        .btn-ghost { color:var(--text2); }
        .btn-ghost:hover { color:var(--text); background:rgba(255,255,255,.05); }
        .btn-filled { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; }
        .btn-filled:hover { opacity:.9; transform:translateY(-1px); box-shadow:0 8px 24px rgba(99,102,241,.3); }

        /* Hero */
        .hero { min-height:100vh; display:flex; align-items:center; justify-content:center; text-align:center; padding:120px 20px 80px; position:relative; overflow:hidden; }
        .hero-bg { position:absolute; inset:0; background:radial-gradient(ellipse 1000px 800px at 50% 0%, rgba(99,102,241,.2) 0%, transparent 70%),radial-gradient(ellipse 600px 400px at 20% 80%, rgba(20,184,166,.1) 0%, transparent 70%); pointer-events:none; }
        .hero-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(99,102,241,.1); border:1px solid rgba(99,102,241,.2); color:#818cf8; padding:6px 16px; border-radius:20px; font-size:13px; font-weight:600; margin-bottom:24px; }
        .hero h1 { font-size:clamp(40px,7vw,80px); font-weight:900; line-height:1.05; letter-spacing:-2px; margin-bottom:24px; }
        .hero h1 span { background:linear-gradient(135deg,#818cf8,#38bdf8,#34d399); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .hero p { font-size:18px; color:var(--text2); max-width:600px; margin:0 auto 40px; line-height:1.7; }
        .hero-cta { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
        .cta-btn { display:inline-flex; align-items:center; gap:8px; padding:14px 28px; border-radius:12px; font-size:16px; font-weight:700; text-decoration:none; transition:all .2s; cursor:pointer; border:none; font-family:'Inter',sans-serif; }
        .cta-primary { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; box-shadow:0 8px 32px rgba(99,102,241,.3); }
        .cta-primary:hover { transform:translateY(-2px); box-shadow:0 12px 40px rgba(99,102,241,.4); }
        .cta-secondary { background:rgba(255,255,255,.05); border:1px solid var(--border); color:var(--text); }
        .cta-secondary:hover { background:rgba(255,255,255,.08); }

        /* Features */
        .section { padding:80px 40px; max-width:1200px; margin:0 auto; }
        .section-badge { display:inline-block; background:rgba(20,184,166,.1); border:1px solid rgba(20,184,166,.2); color:#2dd4bf; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px; }
        .section h2 { font-size:40px; font-weight:800; letter-spacing:-1px; margin-bottom:12px; }
        .section > p { color:var(--text2); font-size:16px; margin-bottom:48px; max-width:500px; }
        .features-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px; }
        .feature-card { background:rgba(255,255,255,.03); border:1px solid var(--border); border-radius:20px; padding:28px; transition:all .3s; position:relative; overflow:hidden; }
        .feature-card:hover { transform:translateY(-4px); border-color:rgba(255,255,255,.15); background:rgba(255,255,255,.05); }
        .feature-card::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:var(--accent-grad); opacity:0; transition:.3s; }
        .feature-card:hover::before { opacity:1; }
        .feature-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:16px; }
        .feature-card h3 { font-size:18px; font-weight:700; margin-bottom:8px; }
        .feature-card p { color:var(--text2); font-size:14px; line-height:1.6; }

        /* Roles */
        .roles-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
        .role-card { border-radius:20px; padding:28px; text-align:center; transition:transform .2s; }
        .role-card:hover { transform:translateY(-4px); }
        .role-card .emoji { font-size:36px; margin-bottom:12px; }
        .role-card h3 { font-size:18px; font-weight:700; margin-bottom:8px; }
        .role-card p { font-size:13px; line-height:1.6; }
        .role-card a { display:inline-block; margin-top:16px; padding:8px 20px; border-radius:8px; font-size:13px; font-weight:700; text-decoration:none; }

        /* Footer */
        footer { text-align:center; padding:40px 20px; border-top:1px solid var(--border); color:var(--text2); font-size:14px; }
        footer strong { color:var(--text); }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('home') }}" class="nav-logo">
        <div class="logo-icon">🌉</div>
        <span class="logo-text">UniBridge</span>
    </a>
    <div class="nav-links">
        @auth
        <a href="{{ route(auth()->user()->role.'.dashboard') }}" class="nav-links" style="text-decoration:none;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:600;background:rgba(255,255,255,.05);color:var(--text)">Dashboard →</a>
        @else
        <a href="{{ route('login') }}" class="nav-links btn-ghost">Sign In</a>
        <a href="{{ route('register') }}" class="nav-links btn-filled">Get Started</a>
        @endauth
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-bg"></div>
    <div style="position:relative;z-index:1">
        <div class="hero-badge">🇮🇳 India's University Open Data Platform</div>
        <h1>Bridge the Gap Between<br><span>Universities & Government</span></h1>
        <p>UniBridge creates transparency by connecting Indian universities, government bodies, and students through a unified open data ecosystem.</p>
        <div class="hero-cta">
            <a href="{{ route('register') }}" class="cta-btn cta-primary">🚀 Get Started Free</a>
            <a href="{{ route('login') }}" class="cta-btn cta-secondary">Sign In →</a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="section">
    <div class="section-badge">Features</div>
    <h2>Everything you need</h2>
    <p>A complete platform for managing and accessing university open data in India.</p>
    <div class="features-grid">
        @foreach([
            ['bg:rgba(99,102,241,.1)','linear-gradient(135deg,#6366f1,#8b5cf6)','🔐','Secure Authentication','Multi-role registration with OTP email verification and password reset.'],
            ['bg:rgba(245,158,11,.1)','linear-gradient(135deg,#f59e0b,#ef4444)','✅','Admin Approval Flow','Admins review and approve university registrations before granting data access.'],
            ['bg:rgba(20,184,166,.1)','linear-gradient(135deg,#14b8a6,#06b6d4)','🎓','Student Management','Universities submit and maintain complete academic records for all students.'],
            ['bg:rgba(34,197,94,.1)','linear-gradient(135deg,#22c55e,#16a34a)','🗂','Open Data Access','Government officials browse, filter, and export all approved university data as CSV.'],
            ['bg:rgba(244,63,94,.1)','linear-gradient(135deg,#f43f5e,#ec4899)','📊','Role Dashboards','Purpose-built dashboards for Admin, University, Government, and Student roles.'],
            ['bg:rgba(139,92,246,.1)','linear-gradient(135deg,#8b5cf6,#6366f1)','🌉','Data Transparency','Eliminate the information gap between universities and the Government of India.'],
        ] as [$bg,$grad,$icon,$title,$desc])
        <div class="feature-card" style="--accent-grad:{{ $grad }}">
            <div class="feature-icon" style="{{ $bg }}">{{ $icon }}</div>
            <h3>{{ $title }}</h3>
            <p>{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</section>

<!-- Roles -->
<section class="section">
    <div class="section-badge">Roles</div>
    <h2>Built for every stakeholder</h2>
    <p>Register as the role that fits you and get an immediately useful experience.</p>
    <div class="roles-grid">
        <div class="role-card" style="background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.2)">
            <div class="emoji">🛡️</div>
            <h3 style="color:#818cf8">Admin</h3>
            <p style="color:var(--text2)">Approve universities, manage all users, view platform-wide statistics.</p>
            <a href="{{ route('login') }}" style="background:rgba(99,102,241,.2);color:#818cf8">Admin Login →</a>
        </div>
        <div class="role-card" style="background:rgba(20,184,166,.08);border:1px solid rgba(20,184,166,.2)">
            <div class="emoji">🏛️</div>
            <h3 style="color:#2dd4bf">University</h3>
            <p style="color:var(--text2)">Submit your institution's data and manage complete student academic records.</p>
            <a href="{{ route('register') }}?role=university" style="background:rgba(20,184,166,.2);color:#2dd4bf">Register →</a>
        </div>
        <div class="role-card" style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2)">
            <div class="emoji">🏛</div>
            <h3 style="color:#fbbf24">Government</h3>
            <p style="color:var(--text2)">Access open education data, filter by state/course, and export reports.</p>
            <a href="{{ route('register') }}?role=government" style="background:rgba(245,158,11,.2);color:#fbbf24">Register →</a>
        </div>
        <div class="role-card" style="background:rgba(244,63,94,.08);border:1px solid rgba(244,63,94,.2)">
            <div class="emoji">🎓</div>
            <h3 style="color:#fb7185">Student</h3>
            <p style="color:var(--text2)">View your complete academic record submitted by your university.</p>
            <a href="{{ route('register') }}?role=student" style="background:rgba(244,63,94,.2);color:#fb7185">Register →</a>
        </div>
    </div>
</section>

<footer>
    <strong>UniBridge</strong> — University Open Data Platform<br>
    <span style="font-size:12px;margin-top:4px;display:block">Solving India's university data transparency gap | Built with Laravel</span>
</footer>
</body>
</html>
