# 📝 CATATAN PENGEMBANGAN SISTEM INFORMASI & CMS YNKI
### Yayasan Natural Kapital Indonesia
*Dokumen ini mencatat seluruh riwayat perubahan, penambahan fitur, pembaruan skema database, perbaikan bug, dan progres pengembangan sistem berbasis Laravel 11 MVC & Docker.*

---

## 📌 Informasi Proyek
- **Nama Sistem**: Sistem Informasi & CMS Admin Yayasan Natural Kapital Indonesia (YNKI)
- **Arsitektur**: Model-View-Controller (MVC)
- **Framework & Bahasa**: Laravel 11.x (PHP 8.2+)
- **Database**: MySQL / MariaDB 10.11 (Kompatibel 100% dengan MySQL 8.0)
- **Web Server & Container**: Nginx Alpine + PHP-FPM + Docker Compose
- **Dokumen Acuan**: [`PRD_Sistem_Informasi_Admin_YNKI.md`](./PRD_Sistem_Informasi_Admin_YNKI.md)

---

## 🗂️ Log Riwayat Pengembangan (Changelog)

### [Perbaikan Bug: Undefined Array Key pada Portfolio Update] - 2026-08-24
#### Diperbaiki:
1. **Pencegahan Error Undefined Array Key pada Controller**:
   - Menambahkan *null coalescing operator* (`$validated['description'] ?? null`, `$validated['location'] ?? null`, dsb.) pada seluruh controller CRUD (`PortfolioController`, `LgosController`, `TransparencyController`, `MediaStoryController`) agar field opsional yang dikirim kosong atau tidak dicentang tidak lagi memicu error `Undefined array key`.
2. **Form Portfolio Input Lengkap**:
   - Menambahkan field input `description` (Deskripsi Lengkap Proyek) pada form tambah (`create.blade.php`) dan edit (`edit.blade.php`) modul Portfolio.

### [Fase 2: Integrasi Dinamis Database 10 Subhalaman & Panel CMS Admin] - 2026-08-24
#### Ditambahkan & Diselesaikan:
1. **Integrasi Dinamis Database 10 Subhalaman**:
   - **Tentang Kami**:
     - `Tim & Pengurus YNKI` (`/tim/`): Dewan Pengurus & Kepemimpinan dinamis dari tabel `team_members`.
     - `LGOS: Sistem Operasi Organisasi` (`/lgos/`): 5 Komponen Pendukung LGOS dinamis dari tabel `lgos_components`, admin bisa CRUD & upload SOP/Pedoman PDF, guest bisa mengunduh PDF.
     - `Portfolio` (`/portofolio/`): Program & Proyek YNKI dinamis dari tabel `portfolio_projects`, admin bisa CRUD & upload foto sampul/PDF.
     - `Transparansi & Laporan Mitra` (`/transparansi/`): Laporan Tahunan, Audit Keuangan WTP, dan Dokumen Kebijakan dinamis dari tabel `transparency_reports`, guest dapat langsung mengunduh PDF.
   - **Pustaka & Pengetahuan**:
     - `News & Features` (`/news-features/`): Kabar Terkini dinamis dari tabel `articles` kategori `news-features`, guest membaca langsung.
     - `Penelitian & Laporan` (`/penelitian-laporan/`): Publikasi riset & studi dinamis dari tabel `articles` kategori `penelitian-laporan` + unduh PDF.
     - `Analisis & Kebijakan` (`/analisis-kebijakan/`): Policy brief & naskah kebijakan dinamis dari tabel `articles` kategori `analisis-kebijakan` + unduh PDF.
     - `Perspektif Budaya` (`/perspektif-budaya/`): Esai dan kajian budaya dinamis dari tabel `articles` kategori `perspektif-budaya`, guest membaca langsung.
     - `Data Spasial dan GIS` (`/data-spasial-dan-gis/`): Peta analisis GIS & hotspot dinamis dari tabel `articles` kategori `data-spasial-dan-gis` + unduh file.
     - `Story Foto & Video` (`/story-foto-video/`): Galeri foto dokumentasi lapangan dan video aksi YouTube embed dinamis dari tabel `media_stories`.
2. **Panel Admin CMS Baru**:
   - Menu `Komponen LGOS`: CRUD komponen & upload pedoman PDF.
   - Menu `Portfolio Proyek`: CRUD proyek, pilar lanskap, lokasi, mitra, dan upload factsheet PDF.
   - Menu `Transparansi Laporan`: CRUD laporan tahunan, audit WTP, dan upload file laporan PDF.
   - Menu `Story Foto & Video`: Upload dokumentasi foto beresolusi tinggi dan input tautan YouTube video lapangan.
   - Menu `Tim & Pengurus`: CRUD dewan pengurus, jabatan, foto WebP, LinkedIn, dan urutan tampilan.
   - Menu `Artikel, Riset & Peta`: CRUD artikel berita, laporan riset PDF, policy brief, esai budaya, dan peta GIS.

---

## 🎯 Status Progres Modul Sistem

| Modul | Deskripsi | Status |
| :--- | :--- | :---: |
| 👥 **Tim & Pengurus** | CRUD Dewan Pengurus, upload foto & bio | ✅ Terhubung Database Dinamis |
| ⚙️ **Komponen LGOS** | CRUD 5 Komponen, upload & unduh PDF SOP | ✅ Terhubung Database Dinamis |
| 📁 **Portfolio Proyek** | CRUD Proyek, upload factsheet & foto | ✅ Terhubung Database Dinamis |
| 📑 **Transparansi & Laporan** | CRUD Laporan Tahunan/Audit WTP & unduh PDF | ✅ Terhubung Database Dinamis |
| 📰 **News & Features** | CRUD Kabar Berita & baca langsung | ✅ Terhubung Database Dinamis |
| 🔬 **Penelitian & Laporan** | CRUD Riset, abstrak & unduh PDF riset | ✅ Terhubung Database Dinamis |
| 📜 **Analisis & Kebijakan** | CRUD Policy brief & unduh PDF kebijakan | ✅ Terhubung Database Dinamis |
| 🌿 **Perspektif Budaya** | CRUD Esai budaya & baca langsung | ✅ Terhubung Database Dinamis |
| 🗺️ **Data Spasial & GIS** | CRUD Peta GIS & unduh file spasial | ✅ Terhubung Database Dinamis |
| 📸🎥 **Story Foto & Video** | CRUD Galeri foto & pemutar video YouTube | ✅ Terhubung Database Dinamis |
| 💳 **Rekening & Donasi** | CRUD Rekening bank, QRIS & verifikasi bukti transfer | ✅ Selesai & Berjalan |
| 📊 **Dashboard & Ekspor** | Statistik donasi, audit log, ekspor Excel/PDF | ✅ Selesai & Berjalan |

---

### [Pembaruan Dashboard Admin, Footer LinkedIn & Penyelarasan Halaman Baca Artikel] - 2026-08-24
#### Ditambahkan & Disempurnakan:
1. **Pusat Pengelolaan Konten & Dokumen File Website (Dashboard Admin)**:
   - **Metrik Total Dokumen & Publikasi**: Menambahkan penghitung otomatis seluruh file PDF dokumen resmi, factsheet, policy brief, naskah riset, dan laporan audit WTP pada `DashboardController`.
   - **Widget 10 Kartu Modul Cepat**: Menambahkan grid hub pengelolaan file 10 subhalaman langsung di halaman utama admin (`/admin/dashboard`) lengkap dengan tombol `+ Upload / Tambah` dan `Kelola File`.
   - **Sub-Navigasi Sidebar Admin**: Menambahkan submenu navigasi langsung per kategori (*News & Features*, *Penelitian & Laporan (PDF)*, *Analisis & Kebijakan (PDF)*, *Perspektif Budaya*, *Data Spasial & GIS (PDF)*).
   - **Tab Filter Kategori Cepat**: Menambahkan filter tab pill pada halaman kelola artikel admin (`/admin/articles`) beserta badge unduh file PDF langsung.

2. **Perbaikan Route Exception `public.team`**:
   - Memperbaiki `RouteNotFoundException: Route [public.team] not defined` pada `routes/web.php` dengan menambahkan nama rute `name('public.team')` dan alias kompatibilitas `name('public.tim')`.

3. **Integrasi Ikon & Tautan LinkedIn pada Footer Seluruh Halaman Website**:
   - Menambahkan tautan resmi LinkedIn YNKI (`https://id.linkedin.com/company/natural-kapital-foundation`) lengkap dengan SVG ikon vektor resmi pada footer halaman Beranda (`index.html`) dan seluruh 91 template subhalaman website.
   - Menyelaraskan tampilan tombol sosial media footer (YouTube, Facebook, Instagram, X/Twitter, dan LinkedIn) dengan desain kartu kotak hijau transparan (*rounded translucent square card*) yang identik dengan tema asli website.

4. **Penyelarasan Total Navbar & Footer Halaman Baca Artikel/Ringkasan**:
   - Menyelaraskan halaman baca artikel (`/artikel-cms/{slug}`) agar menggunakan template header, navbar (`awb-menu`), mega-menu dropdown, dan footer asli persis seperti yang digunakan pada bagian *Pustaka & Pengetahuan*.
   - Menyajikan banner judul hero, sidebar metadata dokumen, box ringkasan/abstrak, isi artikel lengkap, banner unduh PDF resmi, serta grid artikel terkait secara responsif.

