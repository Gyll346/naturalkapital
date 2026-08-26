<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Yayasan Natural Kapital Indonesia</title>
    
    <!-- Favicon -->
    <link rel="icon" href="/wp-content/uploads/2026/05/favicon.webp" type="image/webp">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0F5132;
            --primary-dark: #082d1b;
            --primary-light: #198754;
            --accent: #65bd7d;
            --sidebar-bg: #072214;
            --sidebar-hover: #0e3b24;
            --bg-light: #f4f7f5;
            --card-bg: #ffffff;
            --text-dark: #141617;
            --text-muted: #5a7364;
            --border: #dbe7e0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand-img {
            max-height: 38px;
            width: auto;
        }

        .sidebar-brand-text h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 14.5px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }

        .sidebar-brand-text p {
            font-size: 11px;
            color: var(--accent);
            font-weight: 600;
        }

        .sidebar-menu {
            list-style: none;
            padding: 16px 14px;
            flex-grow: 1;
        }

        .menu-category {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.4);
            padding: 14px 10px 6px;
        }

        .menu-item {
            margin-bottom: 4px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .menu-link svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
            flex-shrink: 0;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.2s ease;
        }

        .menu-link:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
        }

        .menu-link:hover svg {
            color: #ffffff;
        }

        .menu-link.active {
            background: var(--primary);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        }

        .menu-link.active svg {
            color: var(--accent);
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.15);
            margin-top: auto;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            background: var(--primary);
            color: #ffffff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .profile-info h4 {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            line-height: 1.2;
        }

        .profile-info p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.55);
        }

        .btn-logout {
            background: none;
            border: none;
            color: #ff7b72;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 6px;
            border-radius: 6px;
            transition: background 0.2s ease;
        }

        .btn-logout:hover {
            background: rgba(255, 123, 114, 0.12);
        }

        /* Main Content */
        .admin-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            min-height: 100vh;
        }

        .admin-topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .admin-content {
            padding: 32px;
            flex-grow: 1;
        }

        /* Cards and Components */
        .grid-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-green { background: #eaf5ee; color: var(--primary); }
        .icon-amber { background: #fef7e6; color: #b45309; }
        .icon-blue { background: #eff6ff; color: #1d4ed8; }
        .icon-purple { background: #f5f3ff; color: #6d28d9; }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .card-table {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-header h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 16.5px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        table.admin-table th {
            background: #f8faf9;
            color: var(--text-muted);
            font-weight: 600;
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
        }

        table.admin-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        table.admin-table tr:hover td {
            background: #fbfdfc;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .badge-success { background: #dafbe1; color: #1a7f37; }
        .badge-warning { background: #fff8c5; color: #9a6700; }
        .badge-danger { background: #ffebe9; color: #cf222e; }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .btn-primary { background: var(--primary); color: #ffffff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-outline { border-color: var(--border); background: #ffffff; color: var(--text-dark); }
        .btn-outline:hover { background: #f0f4f2; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 13.5px;
            font-family: inherit;
            background: #ffffff;
            color: var(--text-dark);
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.12);
        }

        .form-group {
            margin-bottom: 18px;
        }

        /* Mobile Hamburger & Overlay */
        .btn-hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--primary-dark);
            cursor: pointer;
            padding: 6px;
            margin-right: 12px;
            border-radius: 6px;
        }
        .btn-hamburger:hover {
            background: #eaf5ee;
        }
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }

        /* =======================================================
           RESPONSIVE MOBILE BREAKPOINTS (<= 992px & <= 640px)
           ======================================================= */
        @media (max-width: 992px) {
            .btn-hamburger {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .admin-sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: -290px;
                z-index: 1050;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 4px 0 24px rgba(0,0,0,0.25);
            }
            .admin-sidebar.show {
                left: 0;
            }
            .sidebar-backdrop.show {
                display: block;
            }
            .admin-topbar {
                padding: 12px 16px;
            }
            .topbar-title {
                font-size: 16px;
            }
            .admin-content {
                padding: 18px 14px;
            }
            .grid-stats {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 14px;
                margin-bottom: 20px;
            }
            .stat-card {
                padding: 16px;
            }
            .stat-value {
                font-size: 20px;
            }
            .card-table {
                padding: 16px 14px;
            }
        }

        @media (max-width: 640px) {
            .topbar-actions .btn-action span {
                display: none;
            }
            .grid-stats {
                grid-template-columns: 1fr;
            }
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <img src="/wp-content/uploads/2026/05/logo-ynki-80.webp" alt="YNKI" class="sidebar-brand-img" onerror="this.style.display='none'">
            <div class="sidebar-brand-text">
                <h2>YNKI Admin</h2>
                <p>Internal CMS Panel</p>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-category">Ringkasan</li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>
                    Dashboard
                </a>
            </li>

            <li class="menu-category">Tentang Kami</li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}" href="{{ route('admin.teams.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Tim & Pengurus YNKI
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.lgos.*') ? 'active' : '' }}" href="{{ route('admin.lgos.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    Komponen LGOS
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.portfolios.*') ? 'active' : '' }}" href="{{ route('admin.portfolios.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                    Portfolio Proyek
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.transparency.*') ? 'active' : '' }}" href="{{ route('admin.transparency.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Transparansi Laporan
                </a>
            </li>

            <li class="menu-category">Pustaka & Pengetahuan</li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.articles.*') && !request()->has('category') ? 'active' : '' }}" href="{{ route('admin.articles.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    Semua Artikel & Riset
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->get('category') === 'news-features' ? 'active' : '' }}" href="{{ route('admin.articles.index', ['category' => 'news-features']) }}" style="padding-left: 24px; font-size: 13px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path></svg>
                    News & Features
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->get('category') === 'penelitian-laporan' ? 'active' : '' }}" href="{{ route('admin.articles.index', ['category' => 'penelitian-laporan']) }}" style="padding-left: 24px; font-size: 13px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    Penelitian & Laporan (PDF)
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->get('category') === 'analisis-kebijakan' ? 'active' : '' }}" href="{{ route('admin.articles.index', ['category' => 'analisis-kebijakan']) }}" style="padding-left: 24px; font-size: 13px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                    Analisis & Kebijakan (PDF)
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->get('category') === 'perspektif-budaya' ? 'active' : '' }}" href="{{ route('admin.articles.index', ['category' => 'perspektif-budaya']) }}" style="padding-left: 24px; font-size: 13px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    Perspektif Budaya
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->get('category') === 'data-spasial-dan-gis' ? 'active' : '' }}" href="{{ route('admin.articles.index', ['category' => 'data-spasial-dan-gis']) }}" style="padding-left: 24px; font-size: 13px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                    Data Spasial & GIS (PDF)
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.media-stories.*') ? 'active' : '' }}" href="{{ route('admin.media-stories.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    Story Foto & Video
                </a>
            </li>

            <li class="menu-category">Donasi & Keuangan</li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}" href="{{ route('admin.donations.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 6v12"></path></svg>
                    Transaksi Donasi
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}" href="{{ route('admin.accounts.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    Rekening & QRIS
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.donations.excel') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Ekspor Laporan
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="admin-profile">
                <div class="avatar-circle">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
                <div class="profile-info">
                    <h4>{{ auth()->user()->name ?? 'Administrator' }}</h4>
                    <p>{{ ucfirst(auth()->user()->role ?? 'Superadmin') }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout" title="Keluar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Overlay Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Main Section -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div style="display: flex; align-items: center;">
                <button class="btn-hamburger" id="sidebarToggle" aria-label="Toggle Sidebar" title="Buka Menu">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <h1 class="topbar-title">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('public.home') }}" target="_blank" class="btn-action btn-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    <span>Lihat Website</span>
                </a>
            </div>
        </header>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('sidebarToggle');
            var sidebar = document.querySelector('.admin-sidebar');
            var backdrop = document.getElementById('sidebarBackdrop');

            if (toggleBtn && sidebar && backdrop) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });

                // Tutup sidebar saat link di mobile diklik
                var menuLinks = sidebar.querySelectorAll('.menu-link');
                menuLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 992) {
                            sidebar.classList.remove('show');
                            backdrop.classList.remove('show');
                        }
                    });
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

