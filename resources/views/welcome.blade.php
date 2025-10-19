<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Welcome') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Boxicons for modern icons -->
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

        <style>
            :root {
                --bg: #FDFDFC;
                --fg: #1b1b18;
                --muted: #706f6c;
                --card: #ffffff;
                --ring: rgba(0,0,0,.08);
                --accent: #111114;
                --accent-2: #FF4433;
                --border: rgba(25,20,0,.21);
            }
            @media (prefers-color-scheme: dark) {
                :root {
                    --bg: #0a0a0a;
                    --fg: #EDEDEC;
                    --muted: #A1A09A;
                    --card: #161615;
                    --ring: rgba(255,255,255,.12);
                    --accent: #ffffff;
                    --accent-2: #FF750F;
                    --border: #3E3E3A;
                }
            }
            * { box-sizing: border-box; }
            html { scroll-behavior: smooth; }
            html, body { margin: 0; padding: 0; }
            body {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Apple Color Emoji', 'Segoe UI Emoji';
                color: var(--fg);
                background: var(--bg);
            }
            .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
            .btn {
                appearance:none;
                border:2px solid;
                cursor:pointer;
                font-weight:700;
                border-radius:8px;
                padding:12px 24px;
                font-size:14px;
                text-decoration:none;
                display:inline-flex;
                align-items:center;
                justify-content:center;
                transition: all 0.3s ease;
                text-transform:uppercase;
                letter-spacing:0.5px;
                min-width:100px;
                height:44px;
                position: relative;
                overflow: hidden;
                transform-style: preserve-3d;
                perspective: 1000px;
                box-shadow:
                    0 4px 8px rgba(0,0,0,0.2),
                    0 6px 12px rgba(0,0,0,0.15),
                    inset 0 1px 0 rgba(255,255,255,0.3);
            }
            .btn:hover {
                transform: translateY(-3px) translateZ(10px);
                box-shadow:
                    0 8px 16px rgba(0,0,0,0.3),
                    0 12px 24px rgba(0,0,0,0.2),
                    inset 0 1px 0 rgba(255,255,255,0.4);
            }
            .btn:active {
                transform: translateY(1px) translateZ(5px);
                box-shadow:
                    0 2px 4px rgba(0,0,0,0.2),
                    0 4px 8px rgba(0,0,0,0.15),
                    inset 0 1px 0 rgba(255,255,255,0.2);
            }
            .btn-primary {
                background: linear-gradient(135deg, #FF4433, #FF750F);
                color:#fff;
                border-color: #CC3700;
                box-shadow:
                    0 4px 8px rgba(255, 68, 51, 0.4),
                    0 6px 12px rgba(255, 68, 51, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
                position: relative;
                overflow: hidden;
                transform-style: preserve-3d;
                perspective: 1000px;
            }
            .btn-primary:hover {
                transform: translateY(-4px) translateZ(15px);
                box-shadow:
                    0 8px 16px rgba(255, 68, 51, 0.5),
                    0 12px 24px rgba(255, 68, 51, 0.4),
                    inset 0 1px 0 rgba(255, 255, 255, 0.4);
                background: linear-gradient(135deg, #FF750F, #FF4433);
            }
            .btn-primary:active {
                transform: translateY(1px) translateZ(5px);
                box-shadow:
                    0 2px 4px rgba(255, 68, 51, 0.4),
                    0 4px 8px rgba(255, 68, 51, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
            .btn-outline {
                background: rgba(255,255,255,0.1);
                color:#fff;
                border: 2px solid rgba(255,255,255,0.9);
                box-shadow:
                    0 4px 8px rgba(255,255,255,0.2),
                    0 6px 12px rgba(255,255,255,0.15),
                    inset 0 1px 0 rgba(255,255,255,0.3);
                position: relative;
                backdrop-filter: blur(10px);
                transform-style: preserve-3d;
                perspective: 1000px;
            }
            .btn-outline:hover {
                background: rgba(255,255,255,0.2);
                border-color: #fff;
                transform: translateY(-4px) translateZ(15px);
                box-shadow:
                    0 8px 16px rgba(255,255,255,0.3),
                    0 12px 24px rgba(255,255,255,0.2),
                    inset 0 1px 0 rgba(255,255,255,0.4);
            }
            .btn-outline:active {
                transform: translateY(1px) translateZ(5px);
                box-shadow:
                    0 2px 4px rgba(255,255,255,0.2),
                    0 4px 8px rgba(255,255,255,0.15),
                    inset 0 1px 0 rgba(255,255,255,0.2);
            }

            /* 3D Icon Styles */
            .icon-3d {
                text-shadow: 0 1px 2px rgba(0,0,0,0.2);
                filter: drop-shadow(0 2px 3px rgba(0,0,0,0.1));
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .btn:hover .icon-3d {
                transform: scale(1.1) rotate(5deg);
                filter: drop-shadow(0 3px 5px rgba(0,0,0,0.2));
            }

            /* Advanced 3D Effects */
            .card-3d {
                transform-style: preserve-3d;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                perspective: 1000px;
            }
            .card-3d:hover {
                transform: translateY(-8px) rotateX(5deg) rotateY(5deg);
                box-shadow:
                    0 20px 40px rgba(0,0,0,0.15),
                    0 0 0 1px rgba(255,255,255,0.1),
                    inset 0 1px 0 rgba(255,255,255,0.2);
            }

            /* Floating Animation */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            .floating {
                animation: float 3s ease-in-out infinite;
            }

            /* Glow Effect */
            .glow {
                position: relative;
                overflow: hidden;
            }
            .glow::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s;
            }
            .glow:hover::before {
                left: 100%;
            }

            /* Pulse Animation */
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
            }
            .pulse {
                animation: pulse 2s ease-in-out infinite;
            }

            /* Gradient Text */
            .gradient-text {
                background: linear-gradient(135deg, #FF4433, #FF750F, #FFD700);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                animation: gradient-shift 3s ease-in-out infinite;
            }
            @keyframes gradient-shift {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }

            /* Header */
            .header { position:absolute; inset:0 auto auto 0; top:0; width:100%; z-index:20; }
            .nav { display:flex; align-items:center; justify-content:space-between; padding:18px 0; }
            .nav-center { position:absolute; left:50%; transform:translateX(-50%); display:none; gap:28px; color:#fff; font-size:14px; opacity:.95; }
            .nav-center a { color:inherit; text-decoration:none; }
            @media (min-width: 960px) { .nav-center { display:flex; } }

            /* Auth buttons */
            .nav-auth {
                display:flex;
                gap:12px;
                align-items:center;
                z-index:25;
                position:relative;
            }
            .nav-auth .btn {
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-radius: 8px;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 100px;
                height: 44px;
                padding: 12px 24px;
                font-size: 14px;
            }
            @media (max-width: 959px) {
                .nav-auth { gap:10px; }
                .nav-auth .btn { padding:10px 18px; font-size:13px; min-width:90px; height:40px; }
            }
            @media (max-width: 480px) {
                .nav-auth { gap:8px; }
                .nav-auth .btn { padding:8px 16px; font-size:12px; min-width:80px; height:36px; }
            }

            /* Hero */
            .hero { position:relative; height:82vh; min-height:560px; background:url('/images/kilang.png') center/cover no-repeat; }
            .hero::after { content:''; position:absolute; inset:0; background: linear-gradient(180deg, rgba(0,0,0,.55), rgba(0,0,0,.62)); }
            .hero-inner { position:relative; z-index:10; display:flex; align-items:center; justify-content:center; height:100%; text-align:center; color:#fff; padding:0 16px; }
            .kicker { letter-spacing:.35em; font-size:12px; opacity:.85; }
            .headline { margin-top:16px; font-weight:700; letter-spacing:.18em; font-size:44px; }
            @media (min-width: 960px) { .headline { font-size:64px; } }
            .sub { margin-top:12px; font-size:14px; opacity:.85; }
            .cta { margin-top:24px; display:flex; justify-content:center; gap:12px; }

            /* Cards */
            .section { padding:56px 0; }
            .grid { display:grid; gap:20px; }
            @media (min-width: 960px) { .grid-3 { grid-template-columns: repeat(3, minmax(0,1fr)); } }
            .card { background: var(--card); border-radius:16px; padding:24px; box-shadow:0 0 0 1px var(--ring); transition: all .2s ease; text-decoration:none; color:inherit; display:block; }
            .card:hover { box-shadow:0 0 0 1px var(--ring), 0 8px 26px rgba(0,0,0,.08); transform: translateY(-2px); }
            .card h4 { margin:8px 0 6px; font-weight:600; }
            .muted { color: var(--muted); font-size:14px; }

            /* Stats */
            .stats { display:grid; gap:20px; grid-template-columns: repeat(2, minmax(0,1fr)); }
            @media (min-width: 960px) { .stats { grid-template-columns: repeat(4, minmax(0,1fr)); } }
            .stat { background: var(--card); border-radius:14px; padding:22px; box-shadow:0 0 0 1px var(--ring); text-align:center; }
            .stat .num { font-size:28px; font-weight:700; }

            /* About */
            .about { display:grid; gap:24px; }
            @media (min-width: 960px) { .about { grid-template-columns: 1.1fr .9fr; align-items:center; } }
            .img-box { border-radius:16px; overflow:hidden; box-shadow:0 0 0 1px var(--ring); }
            .img-box img { width:100%; height:100%; object-fit:cover; }

            /* Footer CTA */
            .cta-band { background: var(--card); box-shadow: 0 0 0 1px var(--ring); border-radius:16px; padding:24px; display:flex; gap:16px; align-items:center; justify-content:space-between; flex-wrap:wrap; }

            /* Footer */
            body { padding-bottom: 56px; }
            .site-footer { position: fixed; left: 0; right: 0; bottom: 0; z-index: 40; background: var(--card); color: var(--muted); text-align: center; padding: 8px 0; box-shadow: 0 -1px 0 var(--ring); font-weight: normal; font-size: 0.75rem; }

            .overlay-3d { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; z-index: 100; }
            .overlay-3d.active { display: flex; }
            .overlay-3d::before { content: ''; position: absolute; inset: 0; backdrop-filter: blur(4px); background: radial-gradient(1200px 600px at 50% -10%, rgba(255,255,255,.08), transparent 60%), rgba(0,0,0,.45); }
            .overlay-card { position: relative; width: min(900px, 92vw); background: var(--card); color: var(--fg); border-radius: 16px; padding: 24px; box-shadow:
                0 1px 0 var(--ring),
                0 6px 18px rgba(0,0,0,.18),
                inset 0 1px 0 rgba(255,255,255,.06);
                transform: perspective(1000px) rotateX(4deg) translateY(-4px);
                transition: transform .3s ease, box-shadow .3s ease;
            }
            .overlay-card:hover { transform: perspective(1000px) rotateX(0deg) translateY(0); box-shadow:
                0 1px 0 var(--ring),
                0 12px 32px rgba(0,0,0,.22),
                inset 0 1px 0 rgba(255,255,255,.06); }
            .overlay-close { position: absolute; right: 14px; top: 12px; border: 0; background: transparent; color: var(--muted); font-weight: 700; cursor: pointer; }
            .overlay-actions { display: flex; gap: 10px; margin-top: 18px; }
            .btn-3d {
                border: 2px solid #CC3700;
                padding: 12px 24px;
                border-radius: 8px;
                background: linear-gradient(135deg, #FF4433, #FF750F);
                color: #fff;
                box-shadow:
                    0 6px 12px rgba(255, 68, 51, 0.4),
                    0 8px 16px rgba(255, 68, 51, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
                cursor: pointer;
                font-weight: 700;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-size: 14px;
                min-width: 100px;
                height: 44px;
                position: relative;
                overflow: hidden;
                transform-style: preserve-3d;
                perspective: 1000px;
            }
            .btn-3d:hover {
                transform: translateY(-4px) translateZ(15px);
                box-shadow:
                    0 10px 20px rgba(255, 68, 51, 0.5),
                    0 14px 28px rgba(255, 68, 51, 0.4),
                    inset 0 1px 0 rgba(255, 255, 255, 0.4);
                background: linear-gradient(135deg, #FF750F, #FF4433);
            }
            .btn-3d:active {
                transform: translateY(1px) translateZ(5px);
                box-shadow:
                    0 3px 6px rgba(255, 68, 51, 0.4),
                    0 5px 10px rgba(255, 68, 51, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
            .btn-ghost {
                border: 2px solid var(--ring);
                padding: 10px 16px;
                border-radius: 12px;
                background: rgba(255,255,255,.06);
                color: var(--fg);
                box-shadow:
                    0 4px 8px rgba(0,0,0,0.1),
                    0 6px 12px rgba(0,0,0,0.08),
                    inset 0 1px 0 rgba(255,255,255,0.1);
                cursor: pointer;
                font-weight: 700;
                position: relative;
                overflow: hidden;
                transform-style: preserve-3d;
                perspective: 1000px;
            }
            .btn-ghost:hover {
                transform: translateY(-3px) translateZ(10px);
                box-shadow:
                    0 6px 12px rgba(0,0,0,0.15),
                    0 8px 16px rgba(0,0,0,0.12),
                    inset 0 1px 0 rgba(255,255,255,0.2);
                background: rgba(255,255,255,.1);
            }
            .btn-ghost:active {
                transform: translateY(1px) translateZ(5px);
                box-shadow:
                    0 2px 4px rgba(0,0,0,0.1),
                    0 4px 8px rgba(0,0,0,0.08),
                    inset 0 1px 0 rgba(255,255,255,0.05);
            }

            /* Features Responsive */
            .kilang-image {
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .kilang-image:hover {
                transform: scale(1.02);
                box-shadow: 0 12px 40px rgba(0,0,0,0.4) !important;
            }
            .feature-item {
                transition: all 0.2s ease;
                padding: 4px 0;
            }
            .feature-item:hover {
                transform: translateX(8px);
            }
            .tech-section {
                transition: all 0.3s ease;
            }
            .tech-section:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(255,68,51,0.2);
            }
            /* About Us Styling */
            .about-image {
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .about-image:hover {
                transform: scale(1.02);
                box-shadow: 0 12px 40px rgba(0,0,0,0.4) !important;
            }
            .timeline-item {
                transition: all 0.2s ease;
            }
            .timeline-item:hover {
                transform: translateX(8px);
            }
            .timeline-item:hover .timeline-dot {
                transform: scale(1.2);
                box-shadow: 0 0 0 4px rgba(255,68,51,0.2);
            }
            .download-section {
                transition: all 0.3s ease;
            }
            .download-section:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(255,68,51,0.2);
            }
            .history-section {
                transition: all 0.3s ease;
            }
            .history-section:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            }

            @media (max-width: 768px) {
                .features-grid { grid-template-columns: 1fr !important; gap: 20px !important; }
                .kilang-image { height: 250px !important; }
                .features-list { gap: 8px !important; }
                .feature-item { gap: 8px !important; }
                .feature-item span { font-size: 14px; }

                .about-grid { grid-template-columns: 1fr !important; gap: 15px !important; }
                .about-image { height: 220px !important; }
                .timeline-item { padding-left: 16px !important; margin-bottom: 8px !important; }
                .timeline-dot { width: 6px !important; height: 6px !important; }

                .nav img { height: 100px !important; }
            }
        </style>
    </head>
    <body>
        <div>
        <header class="header">
            <div class="container nav">
                <a href="/" style="display:inline-flex;align-items:center;gap:12px;" class="floating">
                    <!-- <img src="/images/pertamina-font-putih.png" alt="Pertamina" style="height:25px;filter: drop-shadow(0 1px 0 rgba(0,0,0,.25));" class="icon-3d"> -->
                </a>
                <nav class="nav-center">
                    <a href="#" id="open-home" data-overlay="home" class="glow">Home</a>
                    <a href="#" id="open-features" data-overlay="features" class="glow">Features</a>
                    <a href="#" id="open-about" data-overlay="about" class="glow">About Us</a>
                </nav>
                <nav class="nav-auth">
                    @guest
                    <a href="/login" class="btn btn-outline glow">
                            <i class="bx bxs-log-in mr-2 icon-3d"></i> Login
                    </a>
                    <a href="/register" class="btn btn-primary glow">
                            <i class="bx bxs-user-plus mr-2 icon-3d"></i> Register
                    </a>
                    @endguest
                    <!-- For authenticated users, no buttons are shown in the header -->
                </nav>
            </div>
        </header>

         <section id="home" class="hero">
            <div class="hero-inner container">
                <div>
                    <div class="kicker">SELAMAT DATANG</div>
                    <h1 class="headline gradient-text floating" style="color:inherit; text-decoration:none;">CCTV MONITORING SYSTEM</h1>
                    <div class="sub">PT KILANG PERTAMINA INTERNASIONAL REFINERY UNIT VI BALONGAN</div>
                        <div class="cta">
                    @auth
                    <a href="/dashboard" class="btn btn-primary glow">
                        <i class="bx bxs-grid-alt mr-2 icon-3d"></i> Access Dashboard
                    </a>
                    @endauth
                 </div>
                </div>
            </div>
        </section>

        <section id="features" class="section" style="background:var(--card)">
            <div class="container">
                <div class="grid grid-3">
                        <a href="/dashboard" class="card card-3d glow bg-gradient-to-br from-blue-500/10 to-blue-600/20 hover:from-blue-500/20 hover:to-blue-600/30 border-2 border-blue-500/30 hover:border-blue-500/50 shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer">
                            <div style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(59, 130, 246, 0.2); border-radius: 12px; margin-bottom: 16px;" class="pulse">
                                <i class="bx bxs-home-alt" style="color: #3B82F6; font-size: 24px;"></i>
                        </div>
                            <h4 class="text-blue-100">RTSP → HLS Otomatis</h4>
                            <p class="muted text-blue-200">FFmpeg mengonversi RTSP ke HLS, diputar langsung di browser.</p>
                        </a>
                        <a href="/maps" class="card card-3d glow bg-gradient-to-br from-green-500/10 to-green-600/20 hover:from-green-500/20 hover:to-green-600/30 border-2 border-green-500/30 hover:border-green-500/50 shadow-lg hover:shadow-green-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer">
                            <div style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(16, 185, 129, 0.2); border-radius: 12px; margin-bottom: 16px;" class="pulse">
                                <i class="bx bxs-map" style="color: #10B981; font-size: 24px;"></i>
                        </div>
                            <h4 class="text-green-100">Peta Interaktif</h4>
                            <p class="muted text-green-200">Leaflet + OSM/Satellite, filter status, cari gedung, buka live dari marker.</p>
                        </a>
                        <a href="/notifications" class="card card-3d glow bg-gradient-to-br from-purple-500/10 to-purple-600/20 hover:from-purple-500/20 hover:to-purple-600/30 border-2 border-purple-500/30 hover:border-purple-500/50 shadow-lg hover:shadow-purple-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer">
                            <div style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(139, 92, 246, 0.2); border-radius: 12px; margin-bottom: 16px;" class="pulse">
                                <i class="bx bxs-bell" style="color: #8B5CF6; font-size: 24px;"></i>
                        </div>
                            <h4 class="text-purple-100">Notifikasi & Pesan</h4>
                            <p class="muted text-purple-200">Login & pesan realtime sederhana Admin ↔ User untuk koordinasi cepat.</p>
                    </a>
                </div>

                <div class="section" style="padding-top:28px;">
                    @php($buildings = \App\Models\Building::count())
                    @php($rooms = \App\Models\Room::count())
                    @php($cctvs = \App\Models\Cctv::count())
                    @php($contacts = \App\Models\Contact::count())
                    <div class="stats">
                            <a href="/locations" class="stat card-3d glow bg-gradient-to-br from-blue-500/10 to-blue-600/20 hover:from-blue-500/20 hover:to-blue-600/30 border-2 border-blue-500/30 hover:border-blue-500/50 shadow-lg hover:shadow-blue-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer">
                                <div style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(59, 130, 246, 0.2); border-radius: 12px; margin: 0 auto 12px;" class="pulse">
                                    <i class="bx bxs-building" style="color: #3B82F6; font-size: 24px;"></i>
                            </div>
                                <div class="muted text-blue-200">BUILDINGS</div>
                                <div class="num text-blue-100" data-count="{{ $buildings }}">0</div>
                            </a>
                            <a href="/locations" class="stat card-3d glow bg-gradient-to-br from-green-500/10 to-green-600/20 hover:from-green-500/20 hover:to-green-600/30 border-2 border-green-500/30 hover:border-green-500/50 shadow-lg hover:shadow-green-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer">
                                <div style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(16, 185, 129, 0.2); border-radius: 12px; margin: 0 auto 12px;" class="pulse">
                                    <i class="bx bxs-door-open" style="color: #10B981; font-size: 24px;"></i>
                            </div>
                                <div class="muted text-green-200">ROOMS</div>
                                <div class="num text-green-100" data-count="{{ $rooms }}">0</div>
                            </a>
                            <a href="/locations" class="stat card-3d glow bg-gradient-to-br from-red-500/10 to-red-600/20 hover:from-red-500/20 hover:to-red-600/30 border-2 border-red-500/30 hover:border-red-500/50 shadow-lg hover:shadow-red-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer">
                                <div style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(239, 68, 68, 0.2); border-radius: 12px; margin: 0 auto 12px;" class="pulse">
                                    <i class="bx bxs-video" style="color: #EF4444; font-size: 24px;"></i>
                            </div>
                                <div class="muted text-red-200">CCTVS</div>
                                <div class="num text-red-100" data-count="{{ $cctvs }}">0</div>
                            </a>
                            <a href="/contact" class="stat card-3d glow bg-gradient-to-br from-yellow-500/10 to-yellow-600/20 hover:from-yellow-500/20 hover:to-yellow-600/30 border-2 border-yellow-500/30 hover:border-yellow-500/50 shadow-lg hover:shadow-yellow-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer">
                                <div style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(245, 158, 11, 0.2); border-radius: 12px; margin: 0 auto 12px;" class="pulse">
                                    <i class="bx bxs-user-circle" style="color: #F59E0B; font-size: 24px;"></i>
                            </div>
                                <div class="muted text-yellow-200">CONTACTS</div>
                                <div class="num text-yellow-100" data-count="{{ $contacts }}">0</div>
                        </a>
                        </div>
                </div>
            </div>
        </section>

        <section id="about" class="section" style="background:var(--bg)">
            <div class="container about">
                <div>
                    <h3 style="margin:0 0 10px; font-weight:700;">Tentang Sistem</h3>
                    <p class="muted">Platform pemantauan CCTV untuk KILANG PERTAMINA INTERNASIONAL REFINERY UNIT VI BALONGAN dengan branding korporat, kontrol akses berbasis peran, ekspor data, dan UI modern.</p>
                    <ul style="margin:16px 0 0; padding-left:18px; line-height:1.8;">
                        <li>Streaming HLS otomatis (FFmpeg)</li>
                        <li>Peta Leaflet (OSM + Satellite)</li>
                        <li>Notifikasi & pesan realtime</li>
                        <li>Ekspor data ke XLSX</li>
                    </ul>
                    <div style="margin-top: 20px;">
    @guest
    <a href="/register" class="btn btn-primary glow">
        <i class="bx bxs-user-plus mr-2 icon-3d"></i> Daftar Sekarang
    </a>
    @endguest
</div>
                </div>
                <a href="/maps" class="img-box" style="display:block;">
                    <img src="/images/kilang1.jpg" alt="Refinery Unit VI Balongan">
                </a>
        </div>
        </section>

        <footer class="site-footer">
            &copy; {{ date('Y') }} PT. Kilang Pertamina Internasional - Refinery Unit VI Balongan
        </footer>

            <!-- Real-time Status Indicator -->
            <div id="status-indicator" style="position: fixed; top: 20px; right: 20px; z-index: 1000; background: rgba(0,0,0,0.8); color: white; padding: 8px 12px; border-radius: 8px; font-size: 12px; display: none;">
                <i class="bx bxs-wifi" style="margin-right: 4px;"></i>
                <span id="status-text">Connected</span>
            </div>

        <div id="overlay-features" class="overlay-3d" aria-hidden="true">
            <div class="overlay-card">
                <button class="overlay-close" data-close>&times;</button>
                <h3 style="margin:0 0 20px; font-weight:800; letter-spacing:.02em; text-align:center;">CCTV Monitoring System</h3>

                <div class="features-grid" style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; align-items:center; margin-bottom:30px;">
                    <div>
                        <div class="kilang-image" style="background:url('/images/kilang1.jpg') center/cover; height:350px; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,0.3);"></div>
                    </div>
                    <div>
                        <h4 style="margin:0 0 15px; font-weight:700; color:var(--accent);">Fitur Utama</h4>
                        <div class="features-list" style="display:grid; gap:12px;">
                            <div class="feature-item" style="display:flex; align-items:center; gap:12px;">
                                <div style="width:8px; height:8px; background:var(--accent); border-radius:50%;"></div>
                                <span class="muted">Live Streaming CCTV Real-time</span>
                            </div>
                            <div class="feature-item" style="display:flex; align-items:center; gap:12px;">
                                <div style="width:8px; height:8px; background:var(--accent); border-radius:50%;"></div>
                                <span class="muted">Interactive Maps dengan Leaflet</span>
                            </div>
                            <div class="feature-item" style="display:flex; align-items:center; gap:12px;">
                                <div style="width:8px; height:8px; background:var(--accent); border-radius:50%;"></div>
                                <span class="muted">Status Monitoring Online/Offline</span>
                            </div>
                            <div class="feature-item" style="display:flex; align-items:center; gap:12px;">
                                <div style="width:8px; height:8px; background:var(--accent); border-radius:50%;"></div>
                                <span class="muted">Export Data Excel (.xlsx)</span>
                            </div>
                            <div class="feature-item" style="display:flex; align-items:center; gap:12px;">
                                <div style="width:8px; height:8px; background:var(--accent); border-radius:50%;"></div>
                                <span class="muted">Notifikasi Real-time</span>
                    </div>
                            <div class="feature-item" style="display:flex; align-items:center; gap:12px;">
                                <div style="width:8px; height:8px; background:var(--accent); border-radius:50%;"></div>
                                <span class="muted">Responsive Mobile Design</span>
                    </div>
                    </div>
                    </div>
                </div>

                <div class="tech-section" style="background:linear-gradient(135deg, rgba(255,68,51,0.1), rgba(255,117,15,0.1)); padding:20px; border-radius:12px; margin-bottom:20px; border:1px solid rgba(255,68,51,0.2);">
                    <h4 style="margin:0 0 10px; font-weight:700; color:var(--accent);">Teknologi Modern</h4>
                    <p class="muted" style="margin:0; line-height:1.6;">
                        Sistem CCTV Monitoring untuk <strong>KILANG PERTAMINA INTERNASIONAL REFINERY UNIT VI BALONGAN</strong>
                        menggunakan teknologi terdepan dengan <strong>FFmpeg</strong> untuk streaming real-time. Dilengkapi dengan <strong>Leaflet.js</strong> untuk pemetaan interaktif
                        dan <strong>Redis</strong> untuk notifikasi real-time.
                    </p>
                </div>

                <div class="overlay-actions">
                    <a class="btn-3d" href="/maps">Buka Maps</a>
                    <a class="btn-ghost" href="#" data-close>Tutup</a>
                </div>
            </div>
        </div>

        <div id="overlay-about" class="overlay-3d" aria-hidden="true">
                <div class="overlay-card" style="width: min(650px, 92vw); padding: 20px;">
                <button class="overlay-close" data-close>&times;</button>
                    <h3 style="margin:0 0 15px; font-weight:800; letter-spacing:.02em; text-align:center; font-size:18px;">About Us: PT Kilang Pertamina Internasional</h3>

                    <div class="about-grid" style="display:grid; grid-template-columns: 1fr; gap:15px; margin-bottom:15px;">
                    <div>
                            <div class="about-image" style="background:url('/images/about-us.png') center/cover; height:180px; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.3);"></div>
                    </div>
                    <div>
                            <h4 style="margin:0 0 8px; font-weight:700; color:var(--accent); font-size:16px;">Tentang Perusahaan</h4>
                            <p class="muted" style="margin:0 0 8px; line-height:1.4; font-size:12px;">
                                <strong>PT Kilang Pertamina Internasional</strong> adalah anak perusahaan holding strategis Pertamina yang bergerak di bidang
                                pengolahan minyak mentah menjadi produk bernilai tinggi seperti bahan bakar, pelumas, dan petrokimia.
                                <br><br>
                                <strong>Refinery Unit VI Balongan</strong> adalah salah satu kilang terpenting yang dikelola PT KPI,
                                dengan kapasitas pemrosesan yang besar dan teknologi mutakhir untuk menghasilkan produk berkualitas tinggi.
                            </p>

                            <div class="download-section" style="background:linear-gradient(135deg, rgba(255,68,51,0.1), rgba(255,117,15,0.1)); padding:8px; border-radius:8px; border:1px solid rgba(255,68,51,0.2);">
                                <h5 style="margin:0 0 3px; font-weight:700; color:var(--accent); font-size:11px;">Informasi Lanjutan</h5>
                                <p class="muted" style="margin:0; font-size:10px; line-height:1.3;">
                                    Sistem pemantauan CCTV dibangun untuk memastikan operasional kilang berjalan aman dan terkendali dengan teknologi monitoring terdepan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="history-section" style="background:var(--card); padding:10px; border-radius:10px; margin-bottom:15px; border:1px solid var(--ring);">
                        <h4 style="margin:0 0 6px; font-weight:700; color:var(--accent); font-size:14px;">Sejarah Singkat</h4>

                        <div class="timeline" style="position:relative;">
                            <div class="timeline-item" style="margin-bottom:4px; padding-left:14px; position:relative;">
                                <div class="timeline-dot" style="position:absolute; left:0; top:2px; width:5px; height:5px; background:var(--accent); border-radius:50%; transition: all 0.3s ease;"></div>
                            <div class="timeline-content">
                                    <h5 style="margin:0 0 1px; font-weight:700; color:var(--fg); font-size:11px;">2017 - Pendirian</h5>
                                    <p class="muted" style="margin:0; line-height:1.2; font-size:10px;">
                                        PT Kilang Pertamina Internasional didirikan sebagai perusahaan holding strategis untuk mengelola
                                        investasi dan bisnis pengolahan minyak Pertamina.
                                </p>
                            </div>
                        </div>

                            <div class="timeline-item" style="margin-bottom:4px; padding-left:14px; position:relative;">
                                <div class="timeline-dot" style="position:absolute; left:0; top:2px; width:5px; height:5px; background:var(--accent); border-radius:50%; transition: all 0.3s ease;"></div>
                            <div class="timeline-content">
                                    <h5 style="margin:0 0 1px; font-weight:700; color:var(--fg); font-size:11px;">2020 - Pengelolaan RU VI</h5>
                                    <p class="muted" style="margin:0; line-height:1.2; font-size:10px;">
                                        PT KPI dipercaya mengelola Refinery Unit VI Balongan dengan kapasitas pengolahan terbesar
                                        dan teknologi mutakhir untuk produksi bahan bakar berkualitas tinggi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overlay-actions">
                    <a class="btn-3d" href="/dashboard">Buka Dashboard</a>
                    <a class="btn-ghost" href="#" data-close>Tutup</a>
                </div>
            </div>
        </div>

        <script>
                // Simple counter animation
            document.querySelectorAll('.num[data-count]').forEach(el => {
                const target = parseInt(el.getAttribute('data-count') || '0', 10);
                const duration = 900;
                const start = performance.now();
                function tick(ts){
                    const p = Math.min(1, (ts - start)/duration);
                    el.textContent = Math.floor(target * (0.2 + 0.8*p)).toLocaleString();
                    if (p < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            });

                // Real-time status updates
                @auth
                const statusIndicator = document.getElementById('status-indicator');
                const statusText = document.getElementById('status-text');

                // Show status indicator when authenticated
                if (statusIndicator) {
                    statusIndicator.style.display = 'block';

                    // Simulate real-time connection status
                    function updateStatus() {
                        statusIndicator.style.background = 'rgba(16, 185, 129, 0.9)';
                        statusText.innerHTML = 'Dashboard Connected';
                    }

                    updateStatus();
                    setInterval(updateStatus, 5000); // Check every 5 seconds
                }
                @endauth

            // overlay open/close handlers
            const openers = document.querySelectorAll('[data-overlay]');
            const overlays = {
                features: document.getElementById('overlay-features'),
                about: document.getElementById('overlay-about'),
            };
            openers.forEach(el => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    const key = el.getAttribute('data-overlay');
                    const overlay = overlays[key];
                    if (overlay) overlay.classList.add('active');
                });
            });
            document.querySelectorAll('[data-close]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    btn.closest('.overlay-3d')?.classList.remove('active');
                });
            });
            document.querySelectorAll('.overlay-3d').forEach(layer => {
                layer.addEventListener('click', (e) => {
                    if (e.target === layer) layer.classList.remove('active');
                });
            });
        </script>
        </div>
    </body>
</html>
