# Website Yayasan Natural Kapital Indonesia (YNKI)

Portal web resmi Yayasan Natural Kapital Indonesia (YNKI) yang mengintegrasikan halaman publik berkinerja tinggi, komponen visual interaktif 3D WebGL, sistem animasi scroll reveal responsif, serta panel admin content management system (CMS) berbasis Laravel.

---

## 📌 Ringkasan Arsitektur

Website ini mengusung arsitektur **Hybrid Laravel**:
1. **Frontend Dinamis & Template Engine (Blade)**:
   - Halaman utama/beranda dimuat melalui `resources/views/public/home.blade.php` oleh `HomeController`.
   - Terintegrasi dengan database untuk konten dinamis, termasuk artikel riset, portofolio program, transparansi, serta tim ahli.
2. **WebGL 3D Interactive Gallery**:
   - Bagian *Mitra & Pendukung Kami* di beranda ditenagai komponen 3D WebGL OGL Circular Gallery (`assets/js/ynki-circular-gallery.js`).
3. **Universal Subpage Fallback Handler**:
   - Router Laravel (`routes/web.php`) menyediakan fallback cerdas untuk melayani 190+ subhalaman tematik, laporan riset, serta arsip statis dengan penanganan MIME-type dan CORS otomatis.
4. **Admin Panel CMS (Protected)**:
   - Panel admin aman di `/admin/dashboard` untuk mengelola data anggota tim, artikel, donasi, portofolio, dokumen transparansi, serta formulir partisipasi dan kontak.

---

## 🚀 Cara Menjalankan Website (Lokal / Development)

### Prasyarat:
- **PHP** >= 8.1
- **Composer**
- **Node.js & NPM** (opsional untuk asset bundling / static runner)
- **Database**: SQLite (default lokal) atau MySQL/MariaDB

### Langkah Menjalankan:

1. **Instalasi Dependensi PHP**:
   ```bash
   composer install
   ```

2. **Konfigurasi Environment**:
   Salin `.env.example` ke `.env` (jika belum ada) dan generate app key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```

4. **Pastikan Tautan Asset (Symlink / Junction Windows)**:
   Aset publik terhubung ke folder `public/`:
   ```bash
   php artisan storage:link
   ```
   *(Catatan Windows: Folder `assets/`, `wp-content/`, dan `wp-includes/` telah dijunction ke dalam folder `public/` agar terbaca langsung oleh web server).*

5. **Jalankan Server Laravel**:
   ```bash
   php artisan serve
   ```
   Akses melalui browser di: **http://127.0.0.1:8000**

---

## 🌟 Fitur-Fitur Utama

### 1. Halaman Beranda Interaktif
- **Hero & Profil Singkat**: Banner berlatar video/gambar dengan overlay kontras tinggi dan tipografi modern.
- **3D Circular Gallery Mitra & Pendukung**: Animasi melingkar tiga dimensi interaktif menggunakan WebGL (OGL) untuk menampilkan mitra kerja sama YNKI.
- **Scroll Reveal & Fade-in Animations**: Animasi halus `fadeInUp` berbasis `IntersectionObserver` pada kontainer `.ynki-section` dan `.ynki-container`.

### 2. Panel Admin CMS (`/login` & `/admin/dashboard`)
- **Manajemen Donasi**: Verifikasi transaksi donasi, pengelolaan nomor rekening & QRIS yayasan, serta ekspor laporan (Excel & PDF).
- **Pengurus & Tim Ahli**: CRUD pengurus dan anggota tim dengan foto profil, biografi, serta peran jabatan.
- **Portofolio & Program**: Pengelolaan program lapangan, target dampak, status capaian, dan dokumentasi.
- **Transparansi & Akuntabilitas**: Publikasi laporan tahunan (*Annual Report*), laporan keuangan terverifikasi, dan audit.
- **Pusat Pesan & Partisipasi**: Manajemen pesan dari halaman *Kontak Kami* dan pendaftaran sukarelawan dari form *Ikut Terlibat*.

### 3. Subhalaman Literasi & Pengetahuan
- Navigasi lengkap untuk:
  - `/tim` & `/tim-ynki` (Struktur Organisasi & Tim Ahli)
  - `/portofolio` (Program Konservasi & Pemberdayaan)
  - `/lgos` (Komponen Pendukung LGOS)
  - `/transparansi` (Laporan Tahunan & Dokumen Akuntabilitas)
  - `/news-features`, `/penelitian-laporan`, `/analisis-kebijakan`, `/perspektif-budaya`
  - `/story-foto-video` & `/data-spasial-dan-gis`
  - `/kontak-kami` & `/ikut-terlibat`

---

## 📂 Struktur Direktori Penting

```plaintext
naturalkapital/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/         # Controller CMS (Dashboard, Donasi, Tim, Artikel, dll.)
│   │   ├── Auth/          # Login & Autentikasi
│   │   └── Public/        # Controller Halaman Publik (HomeController, PageContentController, dll.)
│   └── Models/            # Model Eloquent (TeamMember, Donation, Article, Portfolio, dll.)
├── assets/
│   ├── css/               # ynki-responsive-system.css, tema warna, tata letak
│   ├── js/                # ynki-circular-gallery.js, ynki-footer.js, interaksi DOM
│   └── images/            # Logo mitra, galeri, ikon, dan aset visual
├── database/              # Migrasi skema database & seeder
├── public/                # Document root web server (termasuk junction assets)
├── resources/
│   └── views/
│       ├── admin/         # View Blade Panel Admin
│       ├── layouts/       # Master layout Blade
│       └── public/        # View Blade halaman publik (home.blade.php, dll.)
├── routes/
│   └── web.php            # Rute publik, admin CMS, dan fallback handler
├── wp-content/            # Aset pustaka gambar, font, dan stylesheet pendukung
└── wp-includes/           # Pustaka utilitas Javascript & dependensi jQuery
```

---

## 🎨 Desain & Identitas Visual
- **Warna Identitas Utama**:
  - Forest Green / Hijau Rimba: `#117710`
  - Deep Dark Green: `#0c430c`
  - Golden Orange: `#FF8000`
  - Blue Accent: `#A0D2F5`
  - Accent Red: `#B22231`
- **Tipografi**: Inter / Arial / Sans-serif yang bersih, jelas, dan terbaca di semua perangkat.
- **Responsivitas**: Desain ramah perangkat mobile, tablet, dan desktop dengan sistem menu drawer modern.

---

## 🔒 Keamanan & Praktik Terbaik
- Route form donasi, partisipasi, dan kontak dilindungi CSRF token Laravel.
- Route `/login` tersembunyi dari navigasi umum dengan pembatasan percobaan login (rate limiter / throttle).
- Penanganan sanitize input dan validasi form pada seluruh controller admin dan publik.
