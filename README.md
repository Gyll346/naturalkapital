# Website Yayasan Natural Kapital Indonesia (YNKI)

Website statis hasil konversi dari WordPress (Avada Theme) yang telah dioptimasi, diperbaiki, dan siap dijalankan secara lokal maupun di-deploy ke hosting statis (seperti Vercel, Netlify, Cloudflare Pages, GitHub Pages, atau Apache/Nginx).

---

## 🚀 Cara Menjalankan Website

### Cara 1: Double-Click File Batch (Paling Mudah di Windows)
Cukup double-click file **[`start-website.bat`](./start-website.bat)**. File ini akan otomatis membuka browser di `http://localhost:3000` dan menjalankan web server lokal.

### Cara 2: Menggunakan Node.js / NPM
Buka terminal / PowerShell di folder ini, lalu jalankan salah satu perintah berikut:
```bash
npm start
```
atau
```bash
npm run dev
```
atau (zero-dependency server bawaan Node.js):
```bash
node server.js
```

Lalu buka browser Anda di: **http://localhost:3000**

---

## 🛠️ Pembersihan & Optimasi yang Dilakukan

1. **Konversi Script & Asset LiteSpeed Cache**:
   - Mengubah script `type="litespeed/javascript"` yang sebelumnya menunda/menghambat eksekusi JavaScript menjadi script standar yang langsung dieksekusi secara sinkron.
   - Mengubah `<link rel="preload" ... as="style">` menjadi `<link rel="stylesheet">` langsung agar CSS segera diterapkan tanpa delay.

2. **Perbaikan Gambar LazyLoad**:
   - Menghubungkan kembali atribut `data-src` dan `data-srcset` ke `src` dan `srcset` asli, menggantikan placeholder dummy SVG agar semua gambar dan logo tampil tajam dan instan.

3. **Perbaikan Duplikasi Judul Hero Responsif**:
   - Menyelaraskan aturan breakpoint Avada untuk kelas `.fusion-no-small-visibility`, `.fusion-no-medium-visibility`, dan `.fusion-no-large-visibility` agar judul hero tampil tepat satu kali di setiap ukuran layar.

4. **Perbaikan Grid Mitra & Pendukung (Partner Badges)**:
   - Menata ulang layout partner carousel menjadi CSS Grid rapi (2 baris x 10 logo) dengan card badge putih yang elegan dan responsif.

5. **Perbaikan Halaman Sub (Tim, Kontak Kami, Portofolio, dll.)**:
   - Menyematkan stylesheet global Avada sehingga form kontak, Google Maps iframe, header menu navigasi, kartu pengurus tim, dan footer tampil utuh di seluruh 199 halaman.

6. **Script Batch Optimizer**:
   - Disertakan file **[`optimize-static.js`](./optimize-static.js)** jika Anda ingin menjalankan ulang optimasi untuk seluruh file HTML:
     ```bash
     npm run optimize
     ```

---

## 🌐 Struktur Direktori Utama

- `index.html`: Halaman Beranda (Home)
- `profil/`: Halaman Tentang Kami (Profil)
- `tim/`: Halaman Tim & Pengurus YNKI
- `portofolio/`: Halaman Portofolio
- `kontak-kami/`: Halaman Kontak Kami
- `kebijakan-privasi/`: Halaman Kebijakan Privasi
- `kategori/`: Halaman Arsip Kategori Berita & Artikel
- `wp-content/`: Aset gambar, font, ikon, dan stylesheet tema Avada
- `wp-includes/`: Library JavaScript pendukung (jQuery)
- `server.js`: Web server lokal berbasis Node.js
- `start-website.bat`: Launcher cepat Windows
