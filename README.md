# Website Yayasan Natural Kapital Indonesia (YNKI)

Portal web resmi Yayasan Natural Kapital Indonesia (YNKI) yang mengintegrasikan halaman publik berkinerja tinggi, komponen visual interaktif 3D WebGL, sistem animasi scroll reveal responsif, proteksi keamanan login & sesi, halaman error kustom, serta panel admin Content Management System (CMS) berbasis Laravel.

---

## 📌 Ringkasan Arsitektur

Website ini mengusung arsitektur **Hybrid Laravel**:

1. **Frontend Dinamis & Template Engine (Blade)**:
   - Halaman utama/beranda dimuat melalui `resources/views/public/home.blade.php` oleh `HomeController`.
   - Menggunakan sistem styling terpadu `assets/css/ynki-responsive-system.css` dan interaksi `assets/js/ynki-footer.js`.
   - Terintegrasi dengan database untuk konten dinamis: artikel riset, portofolio program, transparansi, serta tim ahli.
2. **WebGL 3D Interactive Gallery**:
   - Bagian *Mitra & Pendukung Kami* di beranda ditenagai komponen 3D WebGL OGL Circular Gallery (`assets/js/ynki-circular-gallery.js`).
3. **Universal Subpage Fallback & Custom 404 Error Handler**:
   - Router Laravel (`routes/web.php`) melayani subhalaman tematik, laporan riset, serta arsip statis.
   - Halaman error kustom di `resources/views/errors/404.blade.php` mempertahankan header & footer bawaan halaman beranda, sehingga pengguna tetap berada di ekosistem navigasi YNKI jika mengakses URL yang tidak ada.
4. **Admin Panel CMS & Security (Protected)**:
   - Panel admin di `/admin/dashboard` dilindungi autentikasi, anti-cache middleware (`PreventBackHistory`), dan deteksi bfcache browser.
   - Halaman login admin diproteksi di URL kustom `/masuk` dengan sistem limitasi percobaan per perangkat (IP), hitung mundur lockout, dan fitur sembunyikan/tampilkan kata sandi.

---

## 🚀 Cara Menjalankan Website (Lokal / Development)

### Opsi 1: Menggunakan Docker Compose (Direkomendasikan)
Stack Docker telah dikonfigurasi lengkap dengan PHP-FPM, Nginx, MariaDB, dan phpMyAdmin:

1. **Jalankan Container**:
   ```bash
   docker compose up -d
   ```
2. **Akses Layanan**:
   - **Website & Portal**: http://localhost:8000
   - **phpMyAdmin (Manajemen DB)**: http://127.0.0.1:8080

---

### Opsi 2: Menggunakan PHP Lokal

#### Prasyarat:
- **PHP** >= 8.2
- **Composer**
- **Database**: MySQL/MariaDB atau SQLite

#### Langkah Menjalankan:
1. **Instalasi Dependensi PHP**:
   ```bash
   composer install
   ```
2. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```
4. **Symlink Storage & Aset Publik**:
   ```bash
   php artisan storage:link
   ```
5. **Jalankan Server**:
   ```bash
   php artisan serve
   ```
   Akses melalui browser di: **http://127.0.0.1:8000**

---

## 🌟 Fitur-Fitur Utama

### 1. Halaman Publik & Konten Tematik
- **Beranda Interaktif**: Banner video/gambar, profil yayasan, counter statistik capaian, dan slider visual.
- **3D Circular Gallery Mitra & Pendukung**: Animasi melingkar tiga dimensi interaktif menggunakan WebGL (OGL) untuk mitra kolaborasi YNKI.
- **Sistem Tata Kelola Lanskap (STL / LGOS)**: Informasi sistem operasi 8 komponen terintegrasi YNKI.
- **Subhalaman Publik Lengkap**:
  - `/sejarah-visi-misi` (Sejarah, Visi & Misi Yayasan)
  - `/tim` (Profil Pengurus & Tim Ahli YNKI)
  - `/portofolio` (Rekam Jejak & Program Perubahan)
  - `/landscape-governance` (Program Landscape Governance)
  - `/natural-capital` (Natural Capital & Restoration)
  - `/sustainable-commodity` (Sustainable Commodity System)
  - `/landscape-intelligence` (Pengetahuan Lanskap & Inovasi)
  - `/institutional-partnership` (Institutional Sustainability & Partnership)
  - `/dampak` & `/kisah-perubahan` (Capaian Lapangan & Cerita Masyarakat)
  - `/liputan-media` & `/kategori/news-features` (Publikasi Berita & Media)
  - `/penelitian-laporan` & `/analisis-kebijakan` (Pustaka Dokumen Riset)
  - `/perspektif-budaya`, `/data-spasial-dan-gis`, `/stori-foto-video`
  - `/kontak-kami` & `/ikut-terlibat` (Formulir Kontak & Pendaftaran Relawan)

### 2. Halaman Error 404 Kustom Berstandar Brand
- Menangkap semua URL yang tidak valid atau salah ketik (contoh: `/profil`).
- Mempertahankan **header navigasi** (desktop dropdown & hamburger mobile) serta **footer lengkap** yang identik dengan halaman beranda.
- Dilengkapi tombol navigasi cepat kembali ke beranda, formulir kontak, dan rekomendasi halaman populer.

### 3. Keamanan Portal Masuk Admin (`/masuk`)
- **URL Login Tersembunyi**: Login admin dapat diakses melalui `/masuk` (bukan `/login` default).
- **Proteksi Brute-Force Berbasis Perangkat**: Membatasi 3 kali kesalahan input email/password berturut-turut per alamat IP perangkat.
- **Pemblokiran Sementara & Countdown Timer**: Perangkat yang terblokir akan dikunci selama 5 menit, form input dinonaktifkan (`disabled readonly`), dan timer hitung mundur tetap berjalan bahkan setelah halaman direfresh.
- **Toggle Kata Sandi**: Fitur *hide / unhide password* interaktif dengan ikon mata SVG.
- **Pengingat Sesi**: Opsi *Remember Me* untuk mempertahankan sesi login terenkripsi.

### 4. Panel Admin CMS (`/admin/dashboard`) & Proteksi Sesi
- **Anti-Cache & Anti-Undo Logout**: Dilengkapi middleware `PreventBackHistory` serta pendeteksi BFCache browser (`pageshow`). Saat admin menekan tombol **Keluar**, sesi langsung dibersihkan dan jika pengguna menekan tombol *Back/Undo* browser, halaman otomatis ter-refresh dan terlempar ke halaman `/masuk`.
- **Manajemen Pesan Masuk**: Inbox pesan dari formulir kontak publik lengkap dengan status baca/belum baca.
- **Pusat Partisipasi & Donasi**: Rekapitulasi relawan dan donasi masuk.
- **Manajemen Konten CMS**: CRUD artikel, tim, transparansi dokumen, dan rekam jejak portofolio program.

---

## 📂 Struktur Direktori Proyek

```plaintext
naturalkapital/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Controller CMS (Dashboard, Donasi, Pesan, dll.)
│   │   │   ├── Auth/          # LoginController (login /masuk, throttle, lockout, logout)
│   │   │   └── Public/        # Controller Halaman Publik (HomeController, dll.)
│   │   └── Middleware/        # PreventBackHistory.php (Anti-cache browser setelah logout)
│   └── Models/                # Eloquent Models (User, ActivityLog, ContactMessage, dll.)
├── assets/
│   ├── css/                   # ynki-responsive-system.css, tema warna resmi & responsive layout
│   ├── js/                    # ynki-circular-gallery.js, ynki-footer.js, drawer & interaksi UI
│   └── images/                # Dokumentasi visual, logo mitra, foto kegiatan
├── docker/                    # Konfigurasi container PHP-FPM dan Nginx
├── docker-compose.yml         # Orkestrasi container (ynki-app, ynki-nginx, ynki-db, ynki-pma)
├── public/                    # Document root publik web server
├── resources/
│   └── views/
│       ├── admin/             # View Blade Dashboard & Modul CMS
│       ├── auth/              # View Blade Login (login.blade.php)
│       ├── errors/            # View Blade Error Kustom (404.blade.php)
│       ├── layouts/           # Master Layout (admin.blade.php, app.blade.php)
│       └── public/            # View Blade Halaman Publik (home.blade.php, dll.)
├── routes/
│   └── web.php                # Rute publik, admin CMS, URL /masuk, dan fallback handler
└── database/                  # File migrasi skema tabel & data seeder awal
```

---

## 🎨 Identitas Visual & Desain Sistem

- **Pondasi Brand**: Hijau Alam `#117710` (Elemen utama, navbar baris atas, footer, judul section).
- **Aksen Sorotan**: Oranye `#FF8000` (Badge status, tag, highlight teks hero).
- **Aksen Sekunder**: Ungu `#8000FF` & Biru `#A0D2F5` (Tag kategori riset dan tombol sekunder).
- **CTA Utama**: Merah Komitmen `#B22231` (Tombol donasi dan aksi penting).
- **Tipografi**: `Montserrat` (Judul & Heading) dipadukan dengan `Inter` (Body & Teks Bacaan).
- **Responsivitas**: Adaptif penuh pada resolusi Desktop, Laptop, Tablet, serta Ponsel Cerdas.

---

## 🔒 Praktik Keamanan Sistem

- Seluruh formulir web publik dan panel admin dilindungi verifikasi token CSRF.
- Pembatasan laju request (*Rate Limiting*) pada pengiriman form dan percobaan otentikasi.
- Header no-cache HTTP pada area sensitif untuk mencegah pembacaan data pribadi dari cache browser pengguna umum.
- Isolasi port internal database pada konfigurasi Docker jaringan tertutup.
