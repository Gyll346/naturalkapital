@extends('layouts.admin')

@section('title', 'Dashboard Ringkasan & Pusat Pengelolaan File')

@section('content')
    <!-- Metrik Statistik Utama -->
    <div class="grid-stats">
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Total Donasi Terverifikasi</span>
                <div class="stat-value">Rp {{ number_format($totalDonationAmount, 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon icon-green">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 6v12"></path></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Perlu Verifikasi</span>
                <div class="stat-value" style="color: #b45309;">{{ $pendingDonationsCount }} Transaksi</div>
            </div>
            <div class="stat-icon icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
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

        </div>
    </div>

    <!-- Tabel Donasi Terbaru -->
    <div class="card-table">
        <div class="card-header">
            <h3>Transaksi Donasi Masuk Terbaru</h3>
            <a href="{{ route('admin.donations.index') }}" class="btn-action btn-outline">
                Lihat Semua Donasi
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Donatur</th>
                        <th>Program</th>
                        <th>Jumlah</th>
                        <th>Rekening Tujuan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentDonations as $donation)
                        <tr>
                            <td><strong>{{ $donation->invoice_number }}</strong></td>
                            <td>{{ $donation->is_anonymous ? 'Hamba Allah (Anonim)' : $donation->donor_name }}</td>
                            <td>{{ $donation->program->program_name ?? 'Donasi Umum Lanskap' }}</td>
                            <td><strong>Rp {{ number_format($donation->amount, 0, ',', '.') }}</strong></td>
                            <td>{{ $donation->account->bank_name ?? '-' }}</td>
                            <td>
                                @if ($donation->status === 'verified')
                                    <span class="badge badge-success">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Terverifikasi
                                    </span>
                                @elseif ($donation->status === 'pending')
                                    <span class="badge badge-warning">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        Menunggu
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td>{{ $donation->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Belum ada transaksi donasi yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Log Aktivitas Admin -->
    <div class="card-table">
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
                            <td><code>{{ $log->ip_address }}</code></td>
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
    </div>
@endsection
