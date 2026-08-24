<!DOCTYPE html>
<html class="avada-html-layout-wide avada-html-header-position-top avada-is-100-percent-template" lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} - Yayasan Natural Kapital Indonesia</title>
    <meta name="description" content="{{ Str::limit(strip_tags($article->summary ?? $article->content), 160) }}">
    <link rel="icon" href="/wp-content/uploads/2026/05/favicon.webp" type="image/webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@600;700;800&family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/ynki-responsive-system.css">

    <style>
      :root {
        --awb-color1: #ffffff;
        --awb-color2: #f9f9fb;
        --awb-color3: #f2f3f5;
        --awb-color4: #65bd7d;
        --awb-color5: #198fd9;
        --awb-color6: #434549;
        --awb-color7: #212326;
        --awb-color8: #141617;
        --ynki-forest: #0F5132;
        --ynki-leaf: #117710;
        --ynki-accent: #65bd7d;
      }

      * { box-sizing: border-box; }
      body {
        margin: 0;
        font-family: 'Inter', Arial, Helvetica, sans-serif;
        color: #212326;
        background-color: #ffffff;
        line-height: 1.7;
        overflow-x: hidden;
      }

      /* Header & Navbar */
      .fusion-tb-header {
        background: transparent !important;
        position: relative;
        z-index: 1000;
      }
      .ynki-header-inner {
        max-width: 1216px;
        margin: 0 auto;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .ynki-logo-wrap img {
        height: 52px;
        width: auto;
        object-fit: contain;
      }

      .ynki-nav-menu {
        display: flex;
        align-items: center;
        gap: 32px;
        list-style: none;
        margin: 0;
        padding: 0;
      }
      .ynki-nav-item {
        position: relative;
      }
      .ynki-nav-item > a {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #141617;
        text-decoration: none;
        padding: 10px 0;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s ease;
      }
      .ynki-nav-item > a:hover {
        color: var(--ynki-leaf);
      }
      .ynki-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        background: #ffffff;
        min-width: 260px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        border-radius: 8px;
        padding: 10px 0;
        list-style: none;
        margin: 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.25s ease;
        border-top: 3px solid var(--ynki-leaf);
        z-index: 9999;
      }
      .ynki-nav-item:hover .ynki-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
      }
      .ynki-dropdown li a {
        display: block;
        padding: 9px 20px;
        font-size: 13.5px;
        font-weight: 600;
        color: #333333;
        text-decoration: none;
        transition: all 0.2s ease;
      }
      .ynki-dropdown li a:hover {
        background: #f4faf5;
        color: var(--ynki-leaf);
        padding-left: 24px;
      }

      /* Page Title Banner */
      .article-hero-banner {
        background: linear-gradient(155deg, rgba(15,81,50,0.92) 0%, rgba(17,119,16,0.85) 60%, rgba(8,45,27,0.95) 100%), url('/assets/images/homepage/hero-bg.png');
        background-size: cover;
        background-position: center;
        padding: 85px 24px 75px;
        color: #ffffff;
        text-align: center;
        position: relative;
      }
      .article-hero-inner {
        max-width: 960px;
        margin: 0 auto;
      }
      .article-hero-date {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #a3e0a2;
        margin-bottom: 14px;
      }
      .article-hero-title {
        font-family: 'Montserrat', 'Inter', sans-serif;
        font-size: clamp(26px, 4vw, 42px);
        font-weight: 800;
        line-height: 1.3;
        color: #ffffff;
        margin: 0 0 20px;
      }
      .article-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 5px 16px;
        border-radius: 50px;
        background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.3);
        color: #ffffff;
      }

      /* Main Article Layout */
      .article-main-wrap {
        max-width: 1200px;
        margin: 50px auto 80px;
        padding: 0 24px;
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 48px;
        align-items: flex-start;
      }

      /* Sidebar Meta */
      .article-sidebar {
        background: #f8faf9;
        border: 1px solid #dce8e1;
        border-radius: 16px;
        padding: 26px;
        position: sticky;
        top: 30px;
      }
      .sidebar-section-title {
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: #7a9485;
        margin: 0 0 12px;
      }
      .sidebar-meta-item {
        margin-bottom: 16px;
        font-size: 13.5px;
        color: #2d4236;
      }
      .sidebar-meta-item strong {
        display: block;
        color: #0e241b;
        font-size: 14.5px;
        margin-top: 2px;
      }

      .btn-pdf-download-sidebar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #117710;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13px;
        padding: 12px 18px;
        border-radius: 8px;
        text-decoration: none;
        margin-top: 20px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(17,119,16,0.2);
        width: 100%;
        text-align: center;
      }
      .btn-pdf-download-sidebar:hover {
        background: #0c500b;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(17,119,16,0.3);
      }

      .btn-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 700;
        color: #117710;
        text-decoration: none;
        margin-bottom: 20px;
      }
      .btn-back-link:hover {
        text-decoration: underline;
      }

      /* Article Content */
      .article-content-body {
        font-size: 16.5px;
        line-height: 1.85;
        color: #212326;
      }
      .article-featured-img {
        width: 100%;
        max-height: 520px;
        object-fit: cover;
        border-radius: 16px;
        margin-bottom: 35px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      }

      .article-summary-box {
        background: #f4faf5;
        border-left: 4px solid #117710;
        border-radius: 0 12px 12px 0;
        padding: 20px 24px;
        font-size: 16px;
        font-weight: 500;
        font-style: italic;
        color: #0e241b;
        margin-bottom: 30px;
        line-height: 1.7;
      }

      .article-pdf-banner {
        background: #ffffff;
        border: 1.5px solid #c2e2ce;
        border-radius: 14px;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin: 40px 0;
        box-shadow: 0 4px 16px rgba(17,119,16,0.06);
      }
      .article-pdf-banner h4 {
        margin: 0 0 6px;
        font-size: 16px;
        font-weight: 800;
        color: #0F5132;
      }
      .article-pdf-banner p {
        margin: 0;
        font-size: 13.5px;
        color: #5a7364;
      }

      /* Related Articles */
      .related-section {
        margin-top: 60px;
        padding-top: 40px;
        border-top: 1.5px solid #e5ece8;
      }
      .related-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: #0e241b;
        margin: 0 0 24px;
      }
      .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
      }
      .related-card {
        background: #ffffff;
        border: 1px solid #dce8e1;
        border-radius: 14px;
        padding: 22px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
      }
      .related-card:hover {
        transform: translateY(-4px);
        border-color: #117710;
        box-shadow: 0 10px 24px rgba(17,119,16,0.08);
      }
      .related-card h5 {
        font-family: 'Montserrat', sans-serif;
        font-size: 15.5px;
        font-weight: 700;
        margin: 10px 0;
        line-height: 1.4;
      }
      .related-card h5 a {
        color: #0e241b;
        text-decoration: none;
      }
      .related-card h5 a:hover {
        color: #117710;
      }

      /* Footer */
      .fusion-tb-footer {
        background: #ffffff;
        margin-top: 60px;
      }
      .fusion-fullwidth-footer {
        background-color: #ffffff;
        padding: 40px 0 20px;
      }
      .footer-inner-grid {
        max-width: 1216px;
        margin: 0 auto;
        padding: 0 24px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
      }
      .footer-card {
        background: #117710;
        border-radius: 16px;
        padding: 36px 32px;
        color: #ffffff;
      }
      .footer-card h4 {
        font-family: 'Montserrat', sans-serif;
        font-size: 18px;
        font-weight: 800;
        margin: 0 0 12px;
        color: #ffffff;
      }
      .footer-card p {
        font-size: 14px;
        color: rgba(255,255,255,0.9);
        margin: 0 0 10px;
        line-height: 1.6;
      }
      .footer-card ul {
        list-style: none;
        padding: 0;
        margin: 0 0 16px;
      }
      .footer-card ul li {
        font-size: 14px;
        margin-bottom: 8px;
      }
      .footer-card ul li a {
        color: rgba(255,255,255,0.9);
        text-decoration: none;
        transition: color 0.2s;
      }
      .footer-card ul li a:hover {
        color: #ffffff;
        text-decoration: underline;
      }
      .ynki-footer-social {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        margin-top: 24px !important;
        flex-wrap: wrap !important;
      }
      .ynki-social-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 42px !important;
        height: 42px !important;
        background: rgba(255, 255, 255, 0.14) !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        border-radius: 10px !important;
        color: #ffffff !important;
        text-decoration: none !important;
        transition: all 0.25s ease !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1) !important;
      }
      .ynki-social-btn:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2) !important;
      }
      .ynki-social-btn svg {
        width: 18px !important;
        height: 18px !important;
        fill: #ffffff !important;
      }

      @media (max-width: 900px) {
        .article-main-wrap { grid-template-columns: 1fr; gap: 30px; }
        .article-sidebar { position: static; }
        .footer-inner-grid { grid-template-columns: 1fr; }
        .ynki-nav-menu { display: none; }
      }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <header class="fusion-tb-header">
        <div class="ynki-header-inner">
            <div class="ynki-logo-wrap">
                <a href="/">
                    <img src="/wp-content/uploads/2026/05/logo-ynki-80.webp" alt="Yayasan Natural Kapital Indonesia">
                </a>
            </div>

            <nav>
                <ul class="ynki-nav-menu">
                    <!-- Tentang Kami -->
                    <li class="ynki-nav-item">
                        <a href="/sejarah-visi-misi/">Tentang Kami <span style="font-size:10px;">▾</span></a>
                        <ul class="ynki-dropdown">
                            <li><a href="/sejarah-visi-misi/">Sejarah, Visi &amp; Misi</a></li>
                            <li><a href="/tim/">Tim &amp; Pengurus YNKI</a></li>
                            <li><a href="/lgos/">LGOS: Sistem Operasi Organisasi</a></li>
                            <li><a href="/portofolio/">Portfolio</a></li>
                            <li><a href="/transparansi/">Transparansi &amp; Laporan Mitra</a></li>
                        </ul>
                    </li>

                    <!-- Program Kami -->
                    <li class="ynki-nav-item">
                        <a href="/#">Program Kami <span style="font-size:10px;">▾</span></a>
                        <ul class="ynki-dropdown">
                            <li><a href="/landscape-governance/">Landscape Governance</a></li>
                            <li><a href="/natural-capital/">Natural Capital &amp; Restoration</a></li>
                            <li><a href="/sustainable-commodity/">Sustainable Commodity System</a></li>
                            <li><a href="/landscape-intelligence/">Landscape Intelligence &amp; Innovation</a></li>
                            <li><a href="/institutional-partnership/">Institutional Sustainability &amp; Partnership</a></li>
                        </ul>
                    </li>

                    <!-- Dampak & Pembelajaran -->
                    <li class="ynki-nav-item">
                        <a href="/#">Dampak &amp; Pembelajaran <span style="font-size:10px;">▾</span></a>
                        <ul class="ynki-dropdown">
                            <li><a href="/dampak/">Dampak</a></li>
                            <li><a href="/kisah-perubahan/">Kisah Perubahan</a></li>
                            <li><a href="/liputan-media/">Liputan Media</a></li>
                        </ul>
                    </li>

                    <!-- Pustaka & Pengetahuan -->
                    <li class="ynki-nav-item">
                        <a href="/#">Pustaka &amp; Pengetahuan <span style="font-size:10px;">▾</span></a>
                        <ul class="ynki-dropdown">
                            <li><a href="/news-features/">News &amp; Features</a></li>
                            <li><a href="/penelitian-laporan/">Penelitian &amp; Laporan</a></li>
                            <li><a href="/analisis-kebijakan/">Analisis &amp; Kebijakan</a></li>
                            <li><a href="/perspektif-budaya/">Perspektif Budaya</a></li>
                            <li><a href="/data-spasial-dan-gis/">Data Spasial dan GIS</a></li>
                            <li><a href="/story-foto-video/">Story Foto &amp; Video</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Page Title Hero Banner -->
    <section class="article-hero-banner">
        <div class="article-hero-inner">
            <div class="article-hero-date">
                {{ $article->published_at ? $article->published_at->translatedFormat('l, d F Y') : 'Publikasi Resmi' }}
            </div>
            <h1 class="article-hero-title">{{ $article->title }}</h1>
            <div>
                <span class="article-hero-badge">
                    {{ $article->category->category_name ?? 'Publikasi' }}
                </span>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <main class="article-main-wrap">
        <!-- Sidebar Meta -->
        <aside class="article-sidebar">
            <a href="javascript:history.back()" class="btn-back-link">
                &larr; Kembali ke Halaman Sebelumnya
            </a>

            <div style="margin-top: 14px; border-top: 1px solid #dce8e1; padding-top: 18px;">
                <div class="sidebar-section-title">Informasi Publikasi</div>
                
                <div class="sidebar-meta-item">
                    Kategori:
                    <strong>{{ $article->category->category_name ?? 'Publikasi' }}</strong>
                </div>

                <div class="sidebar-meta-item">
                    Penulis:
                    <strong>{{ $article->author->name ?? 'Tim YNKI' }}</strong>
                </div>

                <div class="sidebar-meta-item">
                    Tanggal Terbit:
                    <strong>{{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : '-' }}</strong>
                </div>

                <div class="sidebar-meta-item">
                    Dilihat:
                    <strong>{{ $article->views_count }} Kali</strong>
                </div>

                @if ($article->attachment_pdf_path)
                    <a href="/storage/{{ $article->attachment_pdf_path }}" download target="_blank" class="btn-pdf-download-sidebar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Unduh Naskah PDF
                    </a>
                @endif
            </div>
        </aside>

        <!-- Article Body -->
        <article class="article-content-body">
            @if ($article->featured_image_path)
                <img src="/storage/{{ $article->featured_image_path }}" alt="{{ $article->title }}" class="article-featured-img">
            @endif

            @if ($article->summary)
                <div class="article-summary-box">
                    {{ $article->summary }}
                </div>
            @endif

            @if ($article->attachment_pdf_path)
                <div class="article-pdf-banner">
                    <div>
                        <h4>Dokumen Lengkap Tersedia (PDF)</h4>
                        <p>Naskah lengkap kebijakan, riset, atau laporan factsheet dapat diunduh untuk bahan telaah dan referensi.</p>
                    </div>
                    <a href="/storage/{{ $article->attachment_pdf_path }}" download target="_blank" style="background: #117710; color: #ffffff; padding: 11px 22px; border-radius: 8px; font-weight: 700; font-size: 13.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Unduh PDF
                    </a>
                </div>
            @endif

            <div style="margin-bottom: 40px;">
                {!! nl2br(e($article->content)) !!}
            </div>

            @if ($relatedArticles->count() > 0)
                <section class="related-section">
                    <h3 class="related-title">Publikasi Terkait Lainnya</h3>
                    <div class="related-grid">
                        @foreach ($relatedArticles as $rel)
                            <div class="related-card">
                                <div>
                                    <span style="font-size: 11px; font-weight: 800; color: #117710; background: #e8f5e8; padding: 3px 10px; border-radius: 50px; text-transform: uppercase;">
                                        {{ $rel->category->category_name ?? 'Publikasi' }}
                                    </span>
                                    <h5>
                                        <a href="{{ route('public.article.show', $rel->slug) }}">
                                            {{ $rel->title }}
                                        </a>
                                    </h5>
                                </div>
                                <div style="font-size: 12px; color: #7a9485; border-top: 1px solid #f0f4f1; padding-top: 10px; margin-top: 12px;">
                                    {{ $rel->published_at ? $rel->published_at->format('d M Y') : '-' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>
    </main>

    <!-- Footer Identik Website Asli -->
    <footer class="fusion-tb-footer">
        <div class="fusion-fullwidth-footer">
            <div class="footer-inner-grid">
                <!-- Kolom 1: YNKI & Social Media -->
                <div class="footer-card">
                    <h4>Yayasan Natural Kapital Indonesia</h4>
                    <p>Nature for Life</p>
                    <p>Pontianak, Kalimantan Barat, Indonesia</p>
                    <p style="margin-top: 14px;">
                        📧 <a href="mailto:sekretariat@naturalkapital.or.id" style="color: #ffffff; font-weight: 600; text-decoration: none;">sekretariat@naturalkapital.or.id</a>
                    </p>
                    <div class="ynki-footer-social">
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
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://id.linkedin.com/company/natural-kapital-foundation" target="_blank" rel="noopener" class="ynki-social-btn ynki-social-linkedin" title="LinkedIn" aria-label="LinkedIn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Tautan Cepat -->
                <div class="footer-card">
                    <h4>Tautan Cepat</h4>
                    <ul>
                        <li><a href="/sejarah-visi-misi/">Sejarah, Visi &amp; Misi</a></li>
                        <li><a href="/landscape-governance/">Landscape Governance</a></li>
                        <li><a href="/dampak/">Dampak</a></li>
                        <li><a href="/news-features/">News &amp; Features</a></li>
                        <li><a href="/analisis-kebijakan/">Analisis &amp; Kebijakan</a></li>
                    </ul>
                    <p style="margin-top: 20px; font-size: 13px; color: rgba(255,255,255,0.75);">
                        &copy; 2026 Yayasan Natural Kapital Indonesia
                    </p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
