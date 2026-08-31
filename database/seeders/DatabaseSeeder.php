<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DonationAccount;
use App\Models\DonationProgram;
use App\Models\TeamCategory;
use App\Models\TeamMember;
use App\Models\ArticleCategory;
use App\Models\Article;
use App\Models\LgosComponent;
use App\Models\PortfolioProject;
use App\Models\TransparencyReport;
use App\Models\MediaStory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Super Admin Awal
        $admin = User::firstOrCreate(
            ['email' => 'admin@naturalkapital.or.id'],
            [
                'name' => 'Super Administrator YNKI',
                'password' => Hash::make('AdminYNKI2026!'),
                'role' => 'superadmin',
                'status' => 'active',
            ]
        );

        // 2. Rekening Bank & QRIS Default
        DonationAccount::firstOrCreate(
            ['account_number' => '146-00-1234567-8'],
            [
                'bank_name' => 'Bank Mandiri',
                'account_holder' => 'Yayasan Natural Kapital Indonesia',
                'branch_name' => 'KC Pontianak',
                'instructions' => 'Mohon cantumkan nama dan nomor invoice donasi pada berita transfer.',
                'is_active' => true,
            ]
        );

        DonationAccount::firstOrCreate(
            ['account_number' => '501-123456-7'],
            [
                'bank_name' => 'Bank Kalbar',
                'account_holder' => 'Yayasan Natural Kapital Indonesia',
                'branch_name' => 'KC Pontianak Utama',
                'instructions' => 'Transfer langsung ke rekening Bank Pembangunan Daerah Kalbar YNKI.',
                'is_active' => true,
            ]
        );

        // 3. Program Donasi Default
        DonationProgram::firstOrCreate(
            ['slug' => 'restorasi-gambut-kalimantan'],
            [
                'program_name' => 'Restorasi Gambut & Pencegahan Karhutla',
                'description' => 'Program restorasi ekosistem gambut terdegradasi bersama komunitas lokal di Kalimantan Barat.',
                'target_amount' => 500000000.00,
                'collected_amount' => 0.00,
                'is_active' => true,
            ]
        );

        // 4. Kategori Tim & Pengurus YNKI
        $categories = [
            ['category_name' => 'Dewan Pembina', 'sort_order' => 1],
            ['category_name' => 'Dewan Pengawas', 'sort_order' => 2],
            ['category_name' => 'Dewan Pengurus YNKI', 'sort_order' => 3],
            ['category_name' => 'Tim Ahli Pendukung YNKI', 'sort_order' => 4],
            ['category_name' => 'Tim Lapangan & Fasilitator Desa', 'sort_order' => 5],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['category_name']] = TeamCategory::firstOrCreate(['category_name' => $cat['category_name']], $cat);
        }

        // 5. Data Awal Anggota Tim & Kepemimpinan YNKI
        $teamData = [
            // Dewan Pengurus
            [
                'category_id' => $catModels['Dewan Pengurus YNKI']->id,
                'full_name' => 'Haryono',
                'position' => 'Direktur YNKI',
                'bio' => 'Memimpin organisasi sejak 2019 dengan fokus pada transformasi sektor tata guna lahan dan ketahanan iklim. Berpengalaman lebih dari 15 tahun di bidang kebijakan lingkungan dan tata kelola lanskap.',
                'photo_path' => null,
                'linkedin_url' => 'https://id.linkedin.com/company/natural-kapital-foundation',
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Dewan Pengurus YNKI']->id,
                'full_name' => 'Putri Lestari',
                'position' => 'Manajer Program Landscape Governance',
                'bio' => 'Berpengalaman dalam fasilitasi multipihak dan pendekatan yurisdiksi. Ahli dalam merancang kerangka tata kelola inklusif yang melibatkan pemerintah, sektor swasta, dan masyarakat lokal.',
                'photo_path' => null,
                'linkedin_url' => 'https://id.linkedin.com/company/natural-kapital-foundation',
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Dewan Pengurus YNKI']->id,
                'full_name' => 'Irawan',
                'position' => 'Manajer Program Natural Capital & Restoration',
                'bio' => 'Ahli ekologi dan restorasi ekosistem gambut dengan rekam jejak panjang di proyek pemulihan lahan basah Kalimantan.',
                'photo_path' => null,
                'linkedin_url' => 'https://id.linkedin.com/company/natural-kapital-foundation',
                'sort_order' => 3,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Dewan Pengurus YNKI']->id,
                'full_name' => 'Mahmudi',
                'position' => 'Manajer Program Sustainable Commodity Systems',
                'bio' => 'Spesialis rantai pasok berkelanjutan dan keterlacakan komoditas. Berpengalaman bekerja dengan pelaku industri dan lembaga sertifikasi internasional.',
                'photo_path' => null,
                'linkedin_url' => 'https://id.linkedin.com/company/natural-kapital-foundation',
                'sort_order' => 4,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Dewan Pengurus YNKI']->id,
                'full_name' => 'Yaya',
                'position' => 'Manajer Program Landscape Intelligence',
                'bio' => 'Ahli GIS, penginderaan jauh, dan sistem informasi lanskap. Mengembangkan platform data spasial untuk mendukung pengambilan keputusan berbasis bukti.',
                'photo_path' => null,
                'linkedin_url' => 'https://id.linkedin.com/company/natural-kapital-foundation',
                'sort_order' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Dewan Pengurus YNKI']->id,
                'full_name' => 'Evelin',
                'position' => 'Strategic Communication Manager',
                'bio' => 'Bertanggung jawab atas strategi komunikasi organisasi, pengelolaan media dan publikasi, serta membangun narasi yang kuat untuk memperluas jangkauan dan dampak YNKI di tingkat nasional dan internasional.',
                'photo_path' => null,
                'linkedin_url' => 'https://id.linkedin.com/company/natural-kapital-foundation',
                'sort_order' => 6,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Dewan Pengurus YNKI']->id,
                'full_name' => 'Zulfa Laylia Hauro',
                'position' => 'HR & Finance Manager',
                'bio' => 'Mengelola sumber daya manusia dan keuangan organisasi dengan prinsip akuntabilitas dan transparansi. Memastikan operasional YNKI berjalan efisien dan sesuai dengan standar tata kelola keuangan yang baik.',
                'photo_path' => null,
                'linkedin_url' => 'https://id.linkedin.com/company/natural-kapital-foundation',
                'sort_order' => 7,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Dewan Pengurus YNKI']->id,
                'full_name' => 'Yuli',
                'position' => 'Knowledge Management Manager',
                'bio' => 'Mengelola sistem pengetahuan organisasi, mendokumentasikan pembelajaran dari lapangan, dan memastikan pengetahuan institusional YNKI terdistribusi secara efektif untuk mendukung inovasi program dan pengambilan keputusan berbasis bukti.',
                'photo_path' => null,
                'linkedin_url' => 'https://id.linkedin.com/company/natural-kapital-foundation',
                'sort_order' => 8,
                'status' => 'active',
            ],

            // Tim Ahli Pendukung YNKI
            [
                'category_id' => $catModels['Tim Ahli Pendukung YNKI']->id,
                'full_name' => 'Ekologi Lanskap & Natural Capital',
                'position' => 'Tim Ahli Ekologi & Sains',
                'bio' => 'Penilaian dan pemulihan ekosistem, valuasi jasa ekosistem, dan pengelolaan sumber daya alam berbasis sains.',
                'photo_path' => null,
                'linkedin_url' => null,
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Tim Ahli Pendukung YNKI']->id,
                'full_name' => 'Tata Kelola & Kebijakan',
                'position' => 'Tim Ahli Kebijakan Publik',
                'bio' => 'Analisis regulasi, advokasi kebijakan, dan penguatan kapasitas institusi pemerintah daerah dan nasional.',
                'photo_path' => null,
                'linkedin_url' => null,
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Tim Ahli Pendukung YNKI']->id,
                'full_name' => 'Keterlacakan Komoditas & Rantai Pasok',
                'position' => 'Tim Ahli Rantai Pasok Hijau',
                'bio' => 'Sistem verifikasi dan sertifikasi komoditas ramah lingkungan untuk pasar domestik dan ekspor internasional.',
                'photo_path' => null,
                'linkedin_url' => null,
                'sort_order' => 3,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Tim Ahli Pendukung YNKI']->id,
                'full_name' => 'Pemberdayaan Masyarakat & Inklusi Sosial',
                'position' => 'Tim Ahli Sosial & Gender',
                'bio' => 'Fasilitasi partisipasi komunitas adat dan lokal, kesetaraan gender, dan penguatan hak-hak sosial ekonomi.',
                'photo_path' => null,
                'linkedin_url' => null,
                'sort_order' => 4,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Tim Ahli Pendukung YNKI']->id,
                'full_name' => 'Ketahanan Iklim & Solusi Berbasis Alam',
                'position' => 'Tim Ahli Adaptasi Iklim',
                'bio' => 'Perencanaan adaptasi iklim, restorasi gambut, dan implementasi solusi berbasis alam di lanskap prioritas.',
                'photo_path' => null,
                'linkedin_url' => null,
                'sort_order' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Tim Ahli Pendukung YNKI']->id,
                'full_name' => 'GIS, Penginderaan Jauh & Inovasi Digital',
                'position' => 'Tim Ahli Data & Pemetaan Spasial',
                'bio' => 'Pemetaan spasial, analisis citra satelit, dan pengembangan platform data untuk pemantauan lanskap.',
                'photo_path' => null,
                'linkedin_url' => null,
                'sort_order' => 6,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Tim Ahli Pendukung YNKI']->id,
                'full_name' => 'Monitoring, Evaluasi & Pembelajaran',
                'position' => 'Tim Ahli MEL & Pengetahuan',
                'bio' => 'Pengembangan kerangka MEL, pengumpulan data lapangan, dan pengelolaan pengetahuan organisasi.',
                'photo_path' => null,
                'linkedin_url' => null,
                'sort_order' => 7,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['Tim Ahli Pendukung YNKI']->id,
                'full_name' => 'Pembiayaan Berkelanjutan & Mobilisasi Sumber Daya',
                'position' => 'Tim Ahli Keuangan Hijau',
                'bio' => 'Pengembangan mekanisme keuangan hijau, penggalangan dana, dan kemitraan strategis dengan donor internasional.',
                'photo_path' => null,
                'linkedin_url' => null,
                'sort_order' => 8,
                'status' => 'active',
            ],
        ];

        foreach ($teamData as $member) {
            TeamMember::firstOrCreate(['full_name' => $member['full_name']], $member);
        }

        // 6. Data Komponen Pendukung LGOS
        $lgosComponents = [
            [
                'component_name' => 'Kerangka Kebijakan & Rencana Strategis (RPJM & RKT)',
                'code' => 'LGOS-01',
                'description' => 'Pedoman perumusan strategi jangka panjang (RPJM 5 tahun) dan Rencana Kerja Tahunan terukur berbasis indikator kinerja utama tata kelola lanskap.',
                'sort_order' => 1,
            ],
            [
                'component_name' => 'SOP Tata Kelola Keuangan & Akuntabilitas Hibah Donor',
                'code' => 'LGOS-02',
                'description' => 'Prosedur operasional baku pengelolaan anggaran, pengadaan barang/jasa, pencatatan transaksi, serta audit berkala sesuai standar akuntansi nirlaba.',
                'sort_order' => 2,
            ],
            [
                'component_name' => 'Pedoman Safeguards Sosial & Lingkungan (ESMS)',
                'code' => 'LGOS-03',
                'description' => 'Kerangka perlindungan hak masyarakat adat, kesetaraan gender, mitigasi dampak lingkungan, dan protokol persetujuan atas dasar informasi awal tanpa paksaan (FPIC).',
                'sort_order' => 3,
            ],
            [
                'component_name' => 'Protokol Pemantauan & Evaluasi Dampak (PMEL)',
                'code' => 'LGOS-04',
                'description' => 'Sistem monitoring terpadu untuk melacak luasan restorasi, status keanekaragaman hayati, dan indeks kesejahteraan masyarakat di wilayah intervensi.',
                'sort_order' => 4,
            ],
            [
                'component_name' => 'Sistem Manajemen Pengetahuan & Komunikasi Data Spasial',
                'code' => 'LGOS-05',
                'description' => 'Platform integrasi data geospasial, repositori publikasi penelitian, dan distribusi infografis kebijakan kepada pemangku kepentingan lintas sektor.',
                'sort_order' => 5,
            ],
        ];

        foreach ($lgosComponents as $comp) {
            LgosComponent::firstOrCreate(['code' => $comp['code']], $comp);
        }

        // 7. Data Portfolio Program & Proyek
        $portfolios = [
            [
                'project_title' => 'Penguatan Mitigasi dan Adaptasi Perubahan Iklim di Lanskap Gambut Terdegradasi',
                'slug' => 'penguatan-mitigasi-adaptasi-iklim-gambut',
                'category' => 'Natural Capital & Restoration',
                'location' => 'Kabupaten Kubu Raya, Kalimantan Barat',
                'partner_donor' => 'TFCA Kalimantan & KLHK',
                'period' => '2022 - 2025',
                'summary' => 'Restorasi hidrologis melalui sekat kanal partisipatif, penanaman vegetasi lokal ramah gambut, dan pembentukan MPA desa.',
                'status' => 'ongoing',
                'sort_order' => 1,
            ],
            [
                'project_title' => 'Fasilitasi Pendekatan Yurisdiksi Berkelanjutan Sektor Kelapa Sawit Swadaya',
                'slug' => 'fasilitasi-yurisdiksi-sawit-swadaya',
                'category' => 'Sustainable Commodity System',
                'location' => 'Kayong Utara & Ketapang',
                'partner_donor' => 'UNDP & Pemkab Kayong Utara',
                'period' => '2021 - 2024',
                'summary' => 'Pendataan petani sawit swadaya (STD-B), pemetaan poligon kebun bebas deforestasi, dan penguatan kelembagaan kelompok tani.',
                'status' => 'completed',
                'sort_order' => 2,
            ],
            [
                'project_title' => 'Pengembangan Sistem Cerdas Pemantauan Lanskap Berbasis GIS & Drone',
                'slug' => 'sistem-cerdas-pemantauan-lanskap-gis',
                'category' => 'Landscape Intelligence & Innovation',
                'location' => 'Lanskap Kubu-Kapuas',
                'partner_donor' => 'Earthqualizer & USAID',
                'period' => '2023 - 2026',
                'summary' => 'Penyusunan dashboard web-GIS interaktif peringatan dini deforestasi, kebakaran gambut, dan pemantauan tutupan hutan real-time.',
                'status' => 'ongoing',
                'sort_order' => 3,
            ],
        ];

        foreach ($portfolios as $p) {
            PortfolioProject::firstOrCreate(['slug' => $p['slug']], $p);
        }

        // 8. Data Laporan Transparansi
        $reports = [
            [
                'title' => 'Laporan Tahunan & Capaian Kinerja YNKI 2025',
                'report_year' => 2025,
                'category' => 'Laporan Tahunan',
                'summary' => 'Ringkasan capaian program restorasi lanskap, tata kelola yurisdiksi, dan pemberdayaan masyarakat sepanjang tahun 2025.',
                'file_pdf_path' => 'reports/annual_report_2025.pdf',
                'file_size' => '4.2 MB',
            ],
            [
                'title' => 'Laporan Hasil Audit Independen Laporan Keuangan YNKI 2024 (Opini WTP)',
                'report_year' => 2024,
                'category' => 'Laporan Audit Keuangan',
                'summary' => 'Laporan audit keuangan independen oleh Kantor Akuntan Publik dengan opini Wajar Tanpa Pengecualian (WTP).',
                'file_pdf_path' => 'reports/audit_report_2024.pdf',
                'file_size' => '2.1 MB',
            ],
            [
                'title' => 'Kebijakan Perlindungan Lingkungan, Sosial, dan Anti-Korupsi YNKI',
                'report_year' => 2024,
                'category' => 'Dokumen Kebijakan',
                'summary' => 'Dokumen panduan etika kelembagaan, anti-suap, pencegahan kekerasan berbasis gender, dan integritas tata kelola.',
                'file_pdf_path' => 'reports/policy_safeguards_2024.pdf',
                'file_size' => '1.5 MB',
            ],
        ];

        foreach ($reports as $r) {
            TransparencyReport::firstOrCreate(['title' => $r['title']], $r);
        }

        // 9. Kategori Artikel Lengkap
        $articleCats = [
            ['category_name' => 'News & Features', 'slug' => 'news-features', 'description' => 'Kabar terkini dan berita kegiatan dari seluruh program YNKI.'],
            ['category_name' => 'Penelitian & Laporan Riset', 'slug' => 'penelitian-laporan', 'description' => 'Kumpulan publikasi penelitian ilmiah, studi lanskap, dan dokumen laporan riset.'],
            ['category_name' => 'Analisis & Kebijakan', 'slug' => 'analisis-kebijakan', 'description' => 'Policy brief, naskah kebijakan, dan analisis tata kelola lanskap.'],
            ['category_name' => 'Perspektif Budaya', 'slug' => 'perspektif-budaya', 'description' => 'Esai reflektif, kearifan lokal, dan telaah budaya lingkungan nusantara.'],
            ['category_name' => 'Data Spasial dan GIS', 'slug' => 'data-spasial-dan-gis', 'description' => 'Peta tematik, analisis tutupan lahan, dan dataset spasial lanskap.'],
            ['category_name' => 'Kisah Perubahan', 'slug' => 'kisah-perubahan', 'description' => 'Cerita inspiratif dampak nyata di tingkat tapak dan komunitas.'],
            ['category_name' => 'Liputan Media', 'slug' => 'liputan-media', 'description' => 'Liputan pers nasional dan internasional mengenai YNKI.'],
        ];

        $artCatModels = [];
        foreach ($articleCats as $aCat) {
            $artCatModels[$aCat['slug']] = ArticleCategory::firstOrCreate(['slug' => $aCat['slug']], $aCat);
        }

        // 10. Data Awal Artikel untuk Tiap Kategori Pustaka
        $sampleArticles = [
            [
                'category_id' => $artCatModels['news-features']->id,
                'author_id' => $admin->id,
                'title' => 'Kolaborasi Multipihak Pulihkan 500 Hektar Lanskap Gambut di Kubu Raya',
                'slug' => 'kolaborasi-multipihak-pulihkan-gambut-kubu-raya',
                'excerpt' => 'YNKI bersama masyarakat desa dan pemerintah daerah menyelesaikan pembangunan sekat kanal untuk menaikkan tinggi muka air gambut.',
                'content' => '<p>Yayasan Natural Kapital Indonesia (YNKI) bersama kelompok tani peduli gambut meresmikan program pemulihan hidrologis di lanskap gambut Kubu Raya.</p>',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'category_id' => $artCatModels['penelitian-laporan']->id,
                'author_id' => $admin->id,
                'title' => 'Studi Neraca Karbon dan Potensi Modal Alam Ekosistem Gambut Pesisir Kalimantan Barat',
                'slug' => 'studi-neraca-karbon-modal-alam-gambut-kalbar',
                'excerpt' => 'Laporan riset kuantitatif mengenai stok karbon atas dan bawah permukaan di lanskap rawa gambut pesisir.',
                'content' => '<p>Penelitian ini menyajikan data primer pengukuran fluks emisi gas rumah kaca dan simpanan karbon biomassa gambut.</p>',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'category_id' => $artCatModels['analisis-kebijakan']->id,
                'author_id' => $admin->id,
                'title' => 'Policy Brief: Integrasi Pendekatan Yurisdiksi dalam Rencana Tata Ruang Wilayah Daerah',
                'slug' => 'policy-brief-integrasi-yurisdiksi-rtrw',
                'excerpt' => 'Rekomendasi strategis bagi pemerintah kabupaten dalam harmonisasi tata batas kawasan lindung dan areal penggunaan lain.',
                'content' => '<p>Naskah kebijakan ini merangkum langkah konkret bagi pembuat kebijakan daerah untuk mempercepat perlindungan hutan bernilai konservasi tinggi.</p>',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'category_id' => $artCatModels['perspektif-budaya']->id,
                'author_id' => $admin->id,
                'title' => 'Harmoni Musik & Lanskap: Keselarasan Perbedaan Menjaga Kelestarian Alam',
                'slug' => 'harmoni-musik-keselarasan-lanskap',
                'excerpt' => 'Refleksi mendalam tentang bagaimana kearifan tradisi dan musik lokal mengajarkan cara berelasi dengan tanah dan rimba.',
                'content' => '<p>Alam bukan sekadar obyek eksploitasi, melainkan entitas yang beresonansi dengan kehidupan sosial dan spiritual masyarakat.</p>',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'category_id' => $artCatModels['data-spasial-dan-gis']->id,
                'author_id' => $admin->id,
                'title' => 'Peta Sebaran Titik Panas (Hotspot) & Kedalaman Gambut Lanskap Kubu-Kapuas 2025',
                'slug' => 'peta-sebaran-hotspot-kedalaman-gambut-2025',
                'excerpt' => 'Peta resolusi tinggi analisis overlay kedalaman gambut dan historis titik kebakaran hutan dan lahan periode 2020-2025.',
                'content' => '<p>Dataset geospasial ini mencakup analisis spasial kerentanan kebakaran pada areal gambut dalam di Kalimantan Barat.</p>',
                'status' => 'published',
                'published_at' => now(),
            ],
        ];

        foreach ($sampleArticles as $art) {
            Article::firstOrCreate(['slug' => $art['slug']], $art);
        }

        // 11. Data Media Stories Foto & Video
        $mediaStories = [
            [
                'title' => 'Pemasangan Sekat Kanal oleh Kelompok Masyarakat Peduli Gambut',
                'media_type' => 'photo',
                'category' => 'Restorasi Gambut',
                'location' => 'Desa Limbung, Kubu Raya',
                'caption' => 'Warga secara gotong royong mendirikan sekat kanal kayu untuk menahan laju penurunan muka air gambut.',
                'photographer_credits' => 'Dokumentasi Tim YNKI',
                'sort_order' => 1,
            ],
            [
                'title' => 'Aksi Penanaman Kembali Bibit Pohon Asli Rawa Gambut',
                'media_type' => 'photo',
                'category' => 'Keanekaragaman Hayati',
                'location' => 'Kubu Padi',
                'caption' => 'Bibit Jelutung dan Belangeran ditanam untuk merehabilitasi kawasan sempadan kanal yang terbakar.',
                'photographer_credits' => 'Dokumentasi Tim YNKI',
                'sort_order' => 2,
            ],
            [
                'title' => 'Dokumenter: Menjaga Nafas Gambut Kalimantan Barat',
                'media_type' => 'video',
                'category' => 'Dokumenter Lapangan',
                'location' => 'Kalimantan Barat',
                'caption' => 'Video dokumentasi perjalanan YNKI mendampingi desa-desa gambut mewujudkan lanskap berketahanan iklim.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'sort_order' => 1,
            ],
        ];

        foreach ($mediaStories as $ms) {
            MediaStory::firstOrCreate(['title' => $ms['title']], $ms);
        }
    }
}
