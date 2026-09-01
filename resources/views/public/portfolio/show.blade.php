<!doctype html>
<html class="avada-html-layout-wide avada-html-header-position-top avada-is-100-percent-template" lang="id" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $project['title'] }} - Portfolio YNKI</title>
  <meta name="description" content="{{ Str::limit($project['summary'] ?? $project['description'], 160) }}" />
  <link rel="canonical" href="{{ url('/portofolio/' . $project['slug']) }}" />

  <link rel="stylesheet" href="/wp-content/themes/Avada/assets/css/style.min.css" />
  <link rel="stylesheet" href="/wp-content/uploads/fusion-styles/fusion-5283.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root {
      --ynki-primary: #117710;
      --ynki-primary-dark: #0b5e0a;
      --ynki-primary-light: #eaf6ea;
      --ynki-text-dark: #0e241b;
      --ynki-text-muted: #4a6356;
      --ynki-bg-card: #ffffff;
      --ynki-border: #e2ece5;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--ynki-text-dark);
      background-color: #f7faf8;
      margin: 0;
      padding: 0;
      -webkit-font-smoothing: antialiased;
    }

    /* Universal Header / Navbar Reset */
    header, .fusion-tb-header, .fusion-header, .fusion-header-wrapper {
      background: transparent !important;
      border-bottom: none !important;
      box-shadow: none !important;
    }
    .awb-menu__main-li > a, .awb-menu__main-a {
      color: #12291e !important;
      font-weight: 700 !important;
      font-size: 13.5px !important;
      text-decoration: none !important;
    }
    .awb-menu__main-li > a:hover { color: #117710 !important; }

    /* Breadcrumbs Bar */
    .porto-breadcrumb-bar {
      background: #ffffff;
      border-bottom: 1px solid #eef4f0;
      padding: 16px 24px;
    }
    .porto-breadcrumb-container {
      max-width: 1140px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      color: #5d7568;
      flex-wrap: wrap;
    }
    .porto-breadcrumb-container a {
      color: #117710;
      text-decoration: none;
      font-weight: 600;
    }
    .porto-breadcrumb-container a:hover {
      text-decoration: underline;
    }
    .porto-breadcrumb-container span.separator {
      color: #b5c7bd;
    }

    /* Article Hero */
    .article-hero {
      background: linear-gradient(135deg, #0e241b 0%, #153c2c 100%);
      color: #ffffff;
      padding: 60px 24px 70px;
      position: relative;
      overflow: hidden;
    }
    .article-hero::before {
      content: "";
      position: absolute;
      top: -50%;
      right: -20%;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(17, 119, 16, 0.25) 0%, rgba(17, 119, 16, 0) 70%);
      pointer-events: none;
    }
    .article-hero-inner {
      max-width: 1040px;
      margin: 0 auto;
      position: relative;
      z-index: 2;
    }
    .article-tags {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .tag-cat {
      font-size: 11.5px;
      font-weight: 800;
      padding: 5px 12px;
      border-radius: 50px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .tag-cat.restorasi { background: #eaf6ea; color: #117710; }
    .tag-cat.pemetaan { background: #e6f7fa; color: #0a7b8e; }
    .tag-cat.komoditas { background: #e6f4fb; color: #1e70a6; }
    .tag-cat.kapasitas { background: #fdf5e6; color: #b86e00; }
    .tag-cat.kebijakan { background: #fde8ea; color: #b22231; }

    .tag-status {
      font-size: 11.5px;
      font-weight: 700;
      padding: 5px 14px;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .tag-status.selesai {
      background: rgba(255, 255, 255, 0.15);
      color: #e2ede5;
      border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .tag-status.berjalan {
      background: rgba(16, 185, 129, 0.2);
      color: #a7f3d0;
      border: 1px solid #10b981;
      font-weight: 800;
    }
    .tag-status.berjalan::before {
      content: "";
      display: inline-block;
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #10b981;
      box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.4);
      animation: pulse-dot 1.8s infinite;
    }
    @keyframes pulse-dot {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
      70% { transform: scale(1.05); box-shadow: 0 0 0 5px rgba(16, 185, 129, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .article-hero-title {
      font-family: 'Montserrat', 'Inter', sans-serif;
      font-size: clamp(26px, 3.5vw, 40px);
      font-weight: 800;
      line-height: 1.3;
      margin: 0 0 20px;
      color: #ffffff;
      letter-spacing: -0.02em;
    }
    .article-hero-summary {
      font-size: clamp(15px, 1.8vw, 17px);
      line-height: 1.7;
      color: #c9ded3;
      max-width: 860px;
      margin: 0;
    }

    /* Main Article Container */
    .article-layout {
      max-width: 1040px;
      margin: -35px auto 80px;
      padding: 0 24px;
      position: relative;
      z-index: 3;
    }
    .article-card-main {
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 35px rgba(14, 36, 27, 0.08);
      border: 1px solid #e5ede7;
      overflow: hidden;
    }

    /* Meta Details Grid */
    .article-meta-box {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      background: #f8fbf9;
      border-bottom: 1px solid #e8f0ea;
      padding: 24px 32px;
    }
    .meta-item-label {
      font-size: 11.5px;
      font-weight: 800;
      text-transform: uppercase;
      color: #117710;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .meta-item-value {
      font-size: 14.5px;
      font-weight: 700;
      color: #0e241b;
      line-height: 1.4;
    }

    /* Content Body */
    .article-body-content {
      padding: 40px 48px;
      font-size: 16.5px;
      line-height: 1.85;
      color: #2c4438;
    }
    @media (max-width: 768px) {
      .article-meta-box { padding: 20px; }
      .article-body-content { padding: 28px 20px; font-size: 15.5px; }
    }
    .article-body-content h3 {
      font-family: 'Montserrat', 'Inter', sans-serif;
      font-size: 22px;
      font-weight: 800;
      color: #0e241b;
      margin: 36px 0 16px;
      border-left: 4px solid #117710;
      padding-left: 14px;
    }
    .article-body-content h3:first-child {
      margin-top: 0;
    }
    .article-body-content p {
      margin: 0 0 20px;
    }
    .article-quote-box {
      background: #f3f9f4;
      border-left: 4px solid #117710;
      padding: 20px 24px;
      border-radius: 0 12px 12px 0;
      margin: 28px 0;
      font-style: italic;
      color: #1b4d35;
      font-size: 16px;
    }

    /* Action Footer */
    .article-actions-bar {
      border-top: 1px solid #e8f0ea;
      padding: 24px 48px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
      background: #fafcfa;
    }
    .btn-back-porto {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #ffffff;
      color: #223e30;
      font-size: 14px;
      font-weight: 700;
      padding: 10px 20px;
      border-radius: 8px;
      border: 1px solid #d4e2d8;
      text-decoration: none;
      transition: all 0.25s ease;
    }
    .btn-back-porto:hover {
      background: #f0f6f2;
      border-color: #117710;
      color: #117710;
    }
    .btn-download-pdf {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #117710;
      color: #ffffff !important;
      font-size: 14px;
      font-weight: 700;
      padding: 10px 22px;
      border-radius: 8px;
      text-decoration: none;
      transition: all 0.25s ease;
      box-shadow: 0 4px 14px rgba(17, 119, 16, 0.25);
    }
    .btn-download-pdf:hover {
      background: #0b5e0a;
      transform: translateY(-1px);
    }

    /* Related Projects Section */
    .related-projects-section {
      max-width: 1040px;
      margin: 0 auto 80px;
      padding: 0 24px;
    }
    .related-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 24px;
      margin-top: 24px;
    }
    .related-card {
      background: #ffffff;
      border: 1px solid #e2ece5;
      border-radius: 12px;
      padding: 24px;
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
      transition: all 0.3s ease;
    }
    .related-card:hover {
      transform: translateY(-4px);
      border-color: #117710;
      box-shadow: 0 10px 25px rgba(17, 119, 16, 0.1);
    }
    .related-card h4 {
      font-family: 'Montserrat', 'Inter', sans-serif;
      font-size: 16px;
      font-weight: 700;
      color: #0e241b;
      margin: 12px 0 8px;
      line-height: 1.4;
    }
    .related-card p {
      font-size: 13.5px;
      color: #536b5f;
      line-height: 1.6;
      margin: 0 0 16px;
      flex: 1;
    }
    .related-link-text {
      font-size: 13px;
      font-weight: 800;
      color: #117710;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }
  </style>
</head>
<body>
  <!-- Header / Navigation Bar -->
  <div id="wrapper" class="fusion-wrapper">
    <div id="home" class="fusion-header-wrapper">
      <header class="fusion-header-3 fusion-custom-header fusion-header-v3 fusion-logo-left fusion-sticky-menu- fusion-mobile-menu-design-modern">
        <div class="fusion-header">
          <div class="fusion-row" style="display:flex;justify-content:space-between;align-items:center;padding:15px 24px;max-width:1140px;margin:0 auto;">
            <div class="fusion-logo" style="margin:0;">
              <a class="fusion-logo-link" href="/">
                <img src="/wp-content/uploads/2026/05/logo-naturalkapital-foundation-ynki-transparan.png" alt="Yayasan Natural Kapital Indonesia" style="height:48px;width:auto;" onerror="this.src='/wp-content/uploads/2026/05/logo-naturalkapital-foundation-ynki.webp'" />
              </a>
            </div>
            <nav class="fusion-main-menu">
              <ul class="fusion-menu" style="display:flex;gap:24px;list-style:none;margin:0;padding:0;align-items:center;">
                <li><a href="/" style="text-decoration:none;color:#0e241b;font-weight:700;font-size:14px;">Beranda</a></li>
                <li><a href="/portofolio" style="text-decoration:none;color:#117710;font-weight:800;font-size:14px;">Portofolio</a></li>
                <li><a href="/news-features" style="text-decoration:none;color:#0e241b;font-weight:700;font-size:14px;">Pustaka</a></li>
                <li><a href="/transparansi" style="text-decoration:none;color:#0e241b;font-weight:700;font-size:14px;">Transparansi</a></li>
                <li><a href="/kontak-kami" style="background:#117710;color:#ffffff;padding:8px 18px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13.5px;">Hubungi Kami</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </header>
    </div>

    <!-- Breadcrumbs -->
    <div class="porto-breadcrumb-bar">
      <div class="porto-breadcrumb-container">
        <a href="/">Beranda</a>
        <span class="separator">&rsaquo;</span>
        <a href="/portofolio">Portofolio Proyek</a>
        <span class="separator">&rsaquo;</span>
        <span>{{ $project['title'] }}</span>
      </div>
    </div>

    <!-- Article Hero Banner -->
    <section class="article-hero">
      <div class="article-hero-inner">
        <div class="article-tags">
          <span class="tag-cat {{ $project['category_slug'] ?? 'restorasi' }}">{{ $project['category'] }}</span>
          @if(strtolower($project['status']) === 'ongoing' || str_contains(strtolower($project['status']), 'berjalan'))
            <span class="tag-status berjalan">Sedang Berjalan (Ongoing)</span>
          @else
            <span class="tag-status selesai">Selesai (Completed)</span>
          @endif
        </div>
        <h1 class="article-hero-title">{{ $project['title'] }}</h1>
        <p class="article-hero-summary">{{ $project['summary'] ?? $project['description'] }}</p>
      </div>
    </section>

    <!-- Main Content Layout -->
    <main class="article-layout">
      <article class="article-card-main">
        <!-- Meta Details Box -->
        <div class="article-meta-box">
          <div>
            <div class="meta-item-label">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Lokasi Kegiatan
            </div>
            <div class="meta-item-value">{{ $project['location'] ?? 'Kalimantan Barat' }}</div>
          </div>
          <div>
            <div class="meta-item-label">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Periode Waktu
            </div>
            <div class="meta-item-value">{{ $project['period'] ?? '-' }}</div>
          </div>
          <div>
            <div class="meta-item-label">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
              Mitra &amp; Pendukung
            </div>
            <div class="meta-item-value">{{ $project['partner_donor'] ?? 'Yayasan Natural Kapital Indonesia' }}</div>
          </div>
        </div>

        <!-- Article Rich Body -->
        <div class="article-body-content">
          <h3>1. Latar Belakang &amp; Urgensi Proyek</h3>
          <p>{{ $project['description'] }}</p>

          <div class="article-quote-box">
            "Inisiatif ini dirancang untuk mewujudkan keseimbangan antara pelestarian ekosistem alam, penguatan tata kelola lanskap berkelanjutan, dan peningkatan taraf hidup masyarakat lokal secara inklusif."
          </div>

          <h3>2. Fokus &amp; Pendekatan Intervensi</h3>
          <p>Dalam pelaksanaannya di wilayah <strong>{{ $project['location'] ?? 'Kalimantan Barat' }}</strong>, inisiatif ini mengedepankan pendekatan kolaboratif berbasis sains dan kearifan lokal. Bersama mitra strategis <strong>{{ $project['partner_donor'] ?? 'YNKI' }}</strong>, berbagai instrumen teknis diimplementasikan, mulai dari pemetaan partisipatif, penguatan kapasitas kelompok swadaya, hingga advokasi kebijakan perencanaan ruang.</p>

          <h3>3. Capaian &amp; Dampak Positif</h3>
          <p>Program yang berlangsung pada periode <strong>{{ $project['period'] ?? '-' }}</strong> ini memberikan kontribusi nyata terhadap perlindungan keanekaragaman hayati, mitigasi degradasi lahan, dan ketahanan sosial-ekonomi masyarakat desa yang terlibat secara langsung.</p>
        </div>

        <!-- Action Footer -->
        <div class="article-actions-bar">
          <a href="/portofolio" class="btn-back-porto">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Portofolio
          </a>

          @if(!empty($project['document_pdf_path']))
            <a href="/storage/{{ $project['document_pdf_path'] }}" target="_blank" download class="btn-download-pdf">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
              Unduh Dokumen Factsheet (PDF)
            </a>
          @endif
        </div>
      </article>
    </main>

    <!-- Other Related Projects -->
    @if(!empty($relatedProjects) && count($relatedProjects) > 0)
      <section class="related-projects-section">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
          <h3 style="font-family:'Montserrat','Inter',sans-serif;font-size:20px;font-weight:800;color:#0e241b;margin:0;">Proyek Lainnya</h3>
          <a href="/portofolio" style="font-size:13px;font-weight:700;color:#117710;text-decoration:none;">Lihat Semua &rarr;</a>
        </div>
        <div class="related-grid">
          @foreach($relatedProjects as $rel)
            <a href="/portofolio/{{ $rel['slug'] }}" class="related-card">
              <div style="margin-bottom:8px;">
                <span class="tag-cat {{ $rel['category_slug'] ?? 'restorasi' }}" style="font-size:10px;padding:3px 8px;">{{ $rel['category'] }}</span>
              </div>
              <h4>{{ $rel['title'] }}</h4>
              <p>{{ Str::limit($rel['summary'] ?? $rel['description'], 110) }}</p>
              <div class="related-link-text">
                Baca Selengkapnya &rarr;
              </div>
            </a>
          @endforeach
        </div>
      </section>
    @endif

    <!-- Simple Footer -->
    <footer style="background:#0e241b;color:#a2bdb0;padding:40px 24px;text-align:center;font-size:13px;">
      <p style="margin:0 0 8px;">&copy; {{ date('Y') }} <strong>Yayasan Natural Kapital Indonesia (YNKI)</strong>. Hak Cipta Dilindungi.</p>
      <p style="margin:0;color:#6b8b7b;">For Sustainable &amp; Resilient Landscape in West Kalimantan</p>
    </footer>
  </div>
</body>
</html>
