<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Yayasan Natural Kapital Indonesia') - Nature for Life</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- YNKI System CSS -->
    <link rel="stylesheet" href="/assets/css/ynki-responsive-system.css">
    <style>
        :root {
            --ynki-primary: #0F5132;
            --ynki-primary-dark: #082d1b;
            --ynki-accent: #20c997;
            --ynki-gold: #d4a373;
            --ynki-bg: #f8faf9;
        }

        body {
            font-family: 'Inter', 'Candara', sans-serif;
            background-color: var(--ynki-bg);
            color: #1a2e22;
            margin: 0;
            padding: 0;
        }

        /* Top Navbar */
        .ynki-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e1ebe5;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }

        .ynki-nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
        }

        .ynki-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .ynki-brand h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: var(--ynki-primary);
            margin: 0;
            line-height: 1.2;
        }

        .ynki-brand span {
            font-size: 11px;
            color: #627b6d;
            font-weight: 600;
            display: block;
        }

        .ynki-nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .ynki-nav-links a {
            text-decoration: none;
            color: #2b4235;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .ynki-nav-links a:hover, .ynki-nav-links a.active {
            color: var(--ynki-primary);
        }

        .btn-donate-nav {
            background: linear-gradient(135deg, #0F5132 0%, #198754 100%);
            color: #ffffff !important;
            padding: 9px 20px;
            border-radius: 50px;
            font-weight: 700 !important;
            font-size: 13px !important;
            box-shadow: 0 4px 12px rgba(15, 81, 50, 0.25);
            transition: all 0.2s ease !important;
        }

        .btn-donate-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(15, 81, 50, 0.35);
        }

        /* Footer */
        .ynki-footer-container {
            background: #082d1b;
            color: #ffffff;
            padding: 60px 24px 30px;
            margin-top: 60px;
        }

        .ynki-footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h4 {
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 16px;
        }

        .footer-col p, .footer-col li {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-col a:hover {
            color: var(--ynki-accent);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 24px;
            text-align: center;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
        }

        @media (max-width: 768px) {
            .ynki-footer-grid { grid-template-columns: 1fr; gap: 30px; }
            .ynki-nav-links { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="ynki-navbar">
        <div class="ynki-nav-container">
            <a href="{{ route('public.home') }}" class="ynki-brand">
                <div>
                    <h1>Yayasan Natural Kapital Indonesia</h1>
                    <span>Nature for Life • Kalimantan</span>
                </div>
            </a>

            <ul class="ynki-nav-links">
                <li><a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('public.team') }}" class="{{ request()->routeIs('public.team') ? 'active' : '' }}">Tim & Pengurus</a></li>
                <li><a href="{{ route('public.article.index') }}" class="{{ request()->routeIs('public.article.*') ? 'active' : '' }}">Pustaka & Publikasi</a></li>
                <li><a href="{{ route('public.donation') }}" class="btn-donate-nav">🌱 Donasi Sekarang</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Dynamic Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="ynki-footer-container">
        <div class="ynki-footer-grid">
            <div class="footer-col">
                <h4>Yayasan Natural Kapital Indonesia</h4>
                <p>Nature for Life<br>Pontianak, Kalimantan Barat, Indonesia</p>
                <p>📧 <a href="mailto:sekretariat@naturalkapital.or.id">sekretariat@naturalkapital.or.id</a></p>
                <div class="ynki-footer-social" style="margin-top: 16px;">
                    <a href="https://www.youtube.com/@naturalkapital123" target="_blank" rel="noopener" class="ynki-social-btn ynki-social-youtube" title="YouTube" aria-label="YouTube">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="https://web.facebook.com/naturalkapital?_rdc=1&_rdr#" target="_blank" rel="noopener" class="ynki-social-btn ynki-social-facebook" title="Facebook" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/yayasannaturalkapital" target="_blank" rel="noopener" class="ynki-social-btn ynki-social-instagram" title="Instagram" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="https://x.com/kapital_natural" target="_blank" rel="noopener" class="ynki-social-btn ynki-social-xtwitter" title="X (Twitter)" aria-label="X (Twitter)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://id.linkedin.com/company/natural-kapital-foundation" target="_blank" rel="noopener" class="ynki-social-btn ynki-social-linkedin" title="LinkedIn" aria-label="LinkedIn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="{{ route('public.home') }}">Beranda</a></li>
                    <li><a href="{{ route('public.team') }}">Tim & Pengurus</a></li>
                    <li><a href="{{ route('public.article.index') }}">Pustaka & Publikasi</a></li>
                    <li><a href="{{ route('public.donation') }}">Kemitraan & Donasi</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Aksi Lanskap</h4>
                <ul>
                    <li>Restorasi Gambut</li>
                    <li>Pemberdayaan Masyarakat</li>
                    <li>Landscape Governance</li>
                    <li>Riset & Data Spasial GIS</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            &copy; {{ date('Y') }} Yayasan Natural Kapital Indonesia. All rights reserved.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
