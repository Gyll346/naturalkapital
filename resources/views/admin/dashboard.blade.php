@extends('layouts.admin')

@section('title', 'Dashboard Ringkasan & Pusat Pengelolaan File')

@push('styles')
    <style>
        .admin-main { min-width: 0; }
        .bar-chart-scroll {
            width: 100%;
            overflow-x: auto;
            padding-top: 24px;
        }
        .bar-chart-frame {
            display: grid;
            grid-template-columns: 42px 1fr;
            gap: 10px;
            min-width: 640px;
        }
        .bar-y-axis {
            height: 210px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-end;
            padding-right: 6px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-muted);
            font-variant-numeric: tabular-nums;
            line-height: 1;
        }
        .bar-plot-area {
            position: relative;
            height: 210px;
            border-bottom: 1.5px solid #b6cabf;
            background: repeating-linear-gradient(
                to bottom,
                #e6efe9 0,
                #e6efe9 1px,
                transparent 1px,
                transparent 25%
            );
            display: grid;
            grid-template-columns: repeat(var(--bar-count, 8), minmax(0, 1fr));
            gap: 10px;
            padding: 0 8px;
            align-items: end;
        }
        .bar-labels-row {
            grid-column: 2;
            display: grid;
            grid-template-columns: repeat(var(--bar-count, 8), minmax(0, 1fr));
            gap: 10px;
            padding: 8px 8px 0;
        }
        .bar-slot {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            text-decoration: none;
            cursor: pointer;
            border-radius: 6px 6px 0 0;
        }
        .bar-slot:focus-visible,
        .bar-label-link:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }
        .bar-val {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary-dark);
            font-variant-numeric: tabular-nums;
            margin-bottom: 4px;
            line-height: 1;
        }
        .bar-col {
            width: clamp(26px, 46%, 44px);
            flex-shrink: 0;
            background: var(--primary);
            border-radius: 6px 6px 0 0;
            transition: height 0.25s ease, background-color 0.2s ease;
        }
        .bar-col.is-zero {
            background: #cbdcd2;
        }
        .bar-slot:hover .bar-col:not(.is-zero) {
            background: var(--primary-light);
        }
        .bar-label-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            text-decoration: none;
            color: var(--text-dark);
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
            min-height: 44px;
            padding: 4px 2px;
            border-radius: 6px;
            cursor: pointer;
        }
        .bar-label-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        .bar-label-link small {
            margin-top: 2px;
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
        }
        @media (prefers-reduced-motion: reduce) {
            .bar-col { transition: none; }
        }
    </style>
@endpush

@section('content')
    <!-- Metrik Statistik Utama -->
    <div class="grid-stats">
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Pesan Kontak Masuk</span>
                <div class="stat-value">{{ $totalContactMessagesCount }} Pesan</div>
            </div>
            <div class="stat-icon icon-green">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Pendaftar Ikut Serta</span>
                <div class="stat-value" style="color: #117710;">{{ $totalParticipationsCount }} Pendaftar</div>
            </div>
            <div class="stat-icon icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Total Dokumen & Publikasi PDF</span>
                <div class="stat-value">{{ $totalPdfDocsCount }} File Dokumen</div>
            </div>
            <div class="stat-icon icon-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Pengurus & Tim Aktif</span>
                <div class="stat-value">{{ $totalTeamMembersCount }} Anggota</div>
            </div>
            <div class="stat-icon icon-purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
        </div>
    </div>

    @if (auth()->user()->isEditor())
    @php
        $totalArticleViews = $articleViewsByPage->sum('views');
        $rawMaxViews = (int) $articleViewsByPage->max('views');
        $axisMax = $rawMaxViews > 0 ? (int) (ceil($rawMaxViews / 4) * 4) : 4;
        $yTicks = [$axisMax, (int) ($axisMax * 0.75), (int) ($axisMax * 0.5), (int) ($axisMax * 0.25), 0];
    @endphp
    <div class="card-table" style="margin-bottom: 30px;">
        <div class="card-header">
            <div>
                <h3>Jumlah Views Artikel per Halaman Publik</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0;">
                    Total kunjungan pembaca artikel yang dikelompokkan per kategori halaman website.
                </p>
            </div>
            <div style="font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700; color: var(--primary-dark);">
                Total: {{ number_format($totalArticleViews, 0, ',', '.') }} Views
            </div>
        </div>

        @if ($articleViewsByPage->isEmpty())
            <p style="font-size: 13.5px; color: var(--text-muted); padding: 12px 0;">
                Belum ada kategori halaman artikel. Tambahkan artikel untuk mulai mencatat jumlah pembaca.
            </p>
        @else
            <div class="bar-chart-scroll" role="region" aria-label="Bar chart views artikel per halaman">
                <div class="bar-chart-frame" style="--bar-count: {{ $articleViewsByPage->count() }};">
                    <div class="bar-y-axis" aria-hidden="true">
                        @foreach ($yTicks as $tick)
                            <span>{{ number_format($tick, 0, ',', '.') }}</span>
                        @endforeach
                    </div>
                    <div class="bar-plot-area">
                        @foreach ($articleViewsByPage as $pageStat)
                            @php
                                $hasViews = $pageStat['views'] > 0;
                                $heightCss = $hasViews ? max(3, round(($pageStat['views'] / $axisMax) * 100, 1)) . '%' : '4px';
                            @endphp
                            <a href="{{ route('admin.articles.index', ['category' => $pageStat['slug']]) }}" class="bar-slot" aria-label="{{ $pageStat['name'] }}: {{ $pageStat['views'] }} views, {{ $pageStat['articles'] }} artikel">
                                <span class="bar-val">{{ number_format($pageStat['views'], 0, ',', '.') }}</span>
                                <span class="bar-col {{ $hasViews ? '' : 'is-zero' }}" style="height: {{ $heightCss }};"></span>
                            </a>
                        @endforeach
                    </div>
                    <div class="bar-labels-row">
                        @foreach ($articleViewsByPage as $pageStat)
                            <a href="{{ route('admin.articles.index', ['category' => $pageStat['slug']]) }}" class="bar-label-link">
                                <span>{{ $pageStat['name'] }}</span>
                                <small>{{ $pageStat['articles'] }} artikel</small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @if ($totalArticleViews === 0)
                <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 14px; border-top: 1px solid var(--border); padding-top: 12px;">
                    Belum ada views tercatat pada artikel publik. Batang grafik terisi otomatis setiap kali pengunjung membuka halaman detail artikel.
                </p>
            @endif
        @endif
    </div>
    @endif

    <!-- PUSAT PENGELOLAAN FILE & KONTEN SUBHALAMAN (CMS MANAGER) -->
    <div class="card-table" style="margin-bottom: 30px;">
        <div class="card-header">
            <div>
                <h3>Pusat Pengelolaan Konten & Dokumen File Website (10 Subhalaman)</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0;">
                    Kelola, unggah berkas PDF/Foto, edit, dan hapus konten yang terhubung ke database untuk setiap modul.
                </p>
            </div>
        </div>

        <!-- Grid 10 Modul Kelola File & Konten -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-top: 10px;">
            
            @if (auth()->user()->isEditor())
            <!-- 1. Tim & Pengurus -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eaf5ee; color: #0F5132; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">TENTANG KAMI</span>
                        <strong style="color: #117710; font-size: 13px;">{{ $totalTeamMembersCount }} Orang</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Tim & Pengurus YNKI</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Kelola foto profil WebP, dewan pengurus, jabatan kepemimpinan, dan bio.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.teams.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Tambah</a>
                    <a href="{{ route('admin.teams.index') }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola Data</a>
                </div>
            </div>

            <!-- 2. Komponen LGOS -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eaf5ee; color: #0F5132; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">TENTANG KAMI</span>
                        <strong style="color: #117710; font-size: 13px;">{{ $totalLgosCount }} Komponen</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Komponen Pendukung LGOS</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Upload dokumen SOP, pedoman operasional organisasi, dan file PDF.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.lgos.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Upload SOP</a>
                    <a href="{{ route('admin.lgos.index') }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola File</a>
                </div>
            </div>

            <!-- 3. Portfolio Proyek -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eaf5ee; color: #0F5132; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">TENTANG KAMI</span>
                        <strong style="color: #117710; font-size: 13px;">{{ $totalPortfolioCount }} Proyek</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Portfolio Program & Proyek</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Upload factsheet proyek PDF, foto sampul kegiatan, lokasi, dan mitra donor.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.portfolios.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Upload Proyek</a>
                    <a href="{{ route('admin.portfolios.index') }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola File</a>
                </div>
            </div>
            @endif

            @if (auth()->user()->isFinance())
            <!-- 4. Transparansi & Laporan -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eaf5ee; color: #0F5132; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">TENTANG KAMI</span>
                        <strong style="color: #117710; font-size: 13px;">{{ $totalTransparencyCount }} Laporan</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Transparansi & Laporan Mitra</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Upload Laporan Tahunan, hasil Audit Keuangan WTP, dan dokumen kebijakan.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.transparency.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Upload PDF</a>
                    <a href="{{ route('admin.transparency.index') }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola File</a>
                </div>
            </div>
            @endif

            @if (auth()->user()->isEditor())
            <!-- 5. News & Features -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">PUSTAKA</span>
                        <strong style="color: #1d4ed8; font-size: 13px;">{{ $newsCount }} Berita</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">News & Features</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Upload kabar berita terkini, artikel liputan program, dan foto kegiatan.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.articles.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Tulis Berita</a>
                    <a href="{{ route('admin.articles.index', ['category' => 'news-features']) }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola Berita</a>
                </div>
            </div>

            <!-- 6. Penelitian & Laporan -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">PUSTAKA</span>
                        <strong style="color: #1d4ed8; font-size: 13px;">{{ $researchCount }} Riset</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Penelitian & Laporan (PDF)</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Upload dokumen penelitian akademik, laporan studi lapangan, dan file PDF.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.articles.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Upload Riset</a>
                    <a href="{{ route('admin.articles.index', ['category' => 'penelitian-laporan']) }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola File PDF</a>
                </div>
            </div>

            <!-- 7. Analisis & Kebijakan -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">PUSTAKA</span>
                        <strong style="color: #1d4ed8; font-size: 13px;">{{ $policyCount }} Dokumen</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Analisis & Kebijakan (PDF)</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Upload policy brief, naskah advokasi kebijakan daerah, dan file PDF.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.articles.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Upload Policy</a>
                    <a href="{{ route('admin.articles.index', ['category' => 'analisis-kebijakan']) }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola File PDF</a>
                </div>
            </div>

            <!-- 8. Perspektif Budaya -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">PUSTAKA</span>
                        <strong style="color: #1d4ed8; font-size: 13px;">{{ $cultureCount }} Artikel</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Perspektif Budaya</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Upload esai filosofis, artikel tradisi lokal, narasi kearifan masyarakat adat.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.articles.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Tulis Esai</a>
                    <a href="{{ route('admin.articles.index', ['category' => 'perspektif-budaya']) }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola Artikel</a>
                </div>
            </div>

            <!-- 9. Data Spasial & GIS -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">PUSTAKA</span>
                        <strong style="color: #1d4ed8; font-size: 13px;">{{ $gisCount }} Peta & GIS</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Data Spasial dan GIS (PDF)</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Upload peta tematik tutupan lahan, analisis hotspot kebakaran, dan file geospasial.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.articles.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Upload Peta</a>
                    <a href="{{ route('admin.articles.index', ['category' => 'data-spasial-dan-gis']) }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola Peta PDF</a>
                </div>
            </div>

            <!-- Kisah Perubahan -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">DAMPAK</span>
                        <strong style="color: #1d4ed8; font-size: 13px;">{{ $kisahCount }} Kisah</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Kisah Perubahan</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Kelola cerita inspiratif, dampak lapangan, dan transformasi komunitas mitra.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.articles.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Tulis Kisah</a>
                    <a href="{{ route('admin.articles.index', ['category' => 'kisah-perubahan']) }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola Kisah</a>
                </div>
            </div>

            <!-- Liputan Media -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">MEDIA</span>
                        <strong style="color: #1d4ed8; font-size: 13px;">{{ $liputanCount }} Liputan</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Liputan Media</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Publikasikan berita pers dan liputan eksternal mengenai program YNKI.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.media-coverages.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Tulis Liputan</a>
                    <a href="{{ route('admin.media-coverages.index') }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola Liputan</a>
                </div>
            </div>

            <!-- 10. Story Foto & Video -->
            <div style="background: #fbfdfc; border: 1.5px solid #d2e8d1; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">PUSTAKA</span>
                        <strong style="color: #1d4ed8; font-size: 13px;">{{ $mediaStoriesCount }} Media</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Story Foto & Video</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Upload dokumentasi foto tapak resolusi tinggi dan tautan video YouTube.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #eef4f0; padding-top: 12px;">
                    <a href="{{ route('admin.media-stories.create') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">+ Upload Media</a>
                    <a href="{{ route('admin.media-stories.index') }}" class="btn-action btn-outline" style="font-size: 12px; padding: 6px 12px;">Kelola Galeri</a>
                </div>
            </div>
            @endif

            <!-- 11. Pesan Kontak Masuk -->
            <div style="background: #fffbf7; border: 1.5px solid #fed7aa; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #fff3e6; color: #c2410c; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">INTERAKSI</span>
                        <strong style="color: #c2410c; font-size: 13px;">{{ $totalContactMessagesCount }} Pesan</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Pesan Kontak Masuk</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Tinjau dan balas pertanyaan, permohonan riset, dan surat masuk dari formulir kontak.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #fed7aa; padding-top: 12px;">
                    <a href="{{ route('admin.contact-messages.index') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px; background: #FF8000; border-color: #FF8000;">Lihat Pesan</a>
                    @if ($unreadContactMessagesCount > 0)
                        <span style="font-size: 11.5px; color: #c2410c; font-weight: 700; align-self: center;">{{ $unreadContactMessagesCount }} Belum Dibaca</span>
                    @endif
                </div>
            </div>

            <!-- 12. Pendaftaran Ikut Serta & Relawan -->
            <div style="background: #fbfdfc; border: 1.5px solid #bbf7d0; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="background: #eaf5ee; color: #0F5132; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">KOMUNITAS</span>
                        <strong style="color: #117710; font-size: 13px;">{{ $totalParticipationsCount }} Pendaftar</strong>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #0e241b; margin: 0 0 6px;">Pendaftaran Ikut Serta</h4>
                    <p style="font-size: 12.5px; color: #536b5f; margin: 0 0 14px; line-height: 1.5;">Data pendaftar relawan lapangan, magang akademik, riset, dan kemitraan CSO.</p>
                </div>
                <div style="display: flex; gap: 8px; border-top: 1px solid #bbf7d0; padding-top: 12px;">
                    <a href="{{ route('admin.participations.index') }}" class="btn-action btn-primary" style="font-size: 12px; padding: 6px 12px;">Lihat Pendaftar</a>
                    @if ($unreadParticipationsCount > 0)
                        <span style="font-size: 11.5px; color: #117710; font-weight: 700; align-self: center;">{{ $unreadParticipationsCount }} Belum Ditinjau</span>
                    @endif
                </div>
            </div>

        </div>
    </div>



    <!-- Tabel Log Aktivitas Admin -->
    <div class="card-table" id="activity-logs">
        <div class="card-header">
            <h3>Riwayat Aktivitas Admin (Audit Trail)</h3>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Administrator</th>
                        <th>Aksi</th>
                        <th>Modul</th>
                        <th>Keterangan</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentLogs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                            <td><strong>{{ $log->user->name ?? 'System' }}</strong></td>
                            <td><span class="badge badge-success">{{ $log->action }}</span></td>
                            <td>{{ $log->module }}</td>
                            <td>{{ $log->details }}</td>
                            <td><code>{{ $log->formatted_ip }}</code></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Belum ada aktivitas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($recentLogs->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
                {{ $recentLogs->fragment('activity-logs')->links() }}
            </div>
        @endif
    </div>
@endsection
