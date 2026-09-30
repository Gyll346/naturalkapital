<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ContactMessage;
use App\Models\LgosComponent;
use App\Models\MediaStory;
use App\Models\Participation;
use App\Models\PortfolioProject;
use App\Models\TeamMember;
use App\Models\TransparencyReport;
use Illuminate\Http\Request;

class PageContentController extends Controller
{
  // 1. Tim & Pengurus YNKI (/tim/)
  public function team()
  {
    $leadershipMembers = collect();
    $expertMembers = collect();

    try {
      $allMembers = TeamMember::with('category')
        ->where('status', 'active')
        ->orderBy('sort_order', 'asc')
        ->get();

      if ($allMembers->isNotEmpty()) {
        $leadershipMembers = $allMembers->filter(function ($m) {
          $catName = strtolower($m->category->category_name ?? '');
          return !str_contains($catName, 'ahli') && !str_contains($catName, 'lapangan');
        });

        $expertMembers = $allMembers->filter(function ($m) {
          $catName = strtolower($m->category->category_name ?? '');
          return str_contains($catName, 'ahli') || str_contains($catName, 'lapangan');
        });

        if ($leadershipMembers->isEmpty()) {
          $leadershipMembers = $allMembers;
        }
      }
    } catch (\Throwable $e) {}

    return view('public.tim', compact('leadershipMembers', 'expertMembers'));
  }

  // 2. LGOS: Sistem Operasi Organisasi (/lgos/)
  public function lgos()
  {
    $lgosDocs = [];
    try {
      $components = LgosComponent::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->get();

      foreach ($components as $c) {
        if (!empty($c->document_pdf_path) && $c->sort_order <= 5) {
          $order = intval($c->sort_order);
          $lgosDocs[$order] = '/storage/' . ltrim($c->document_pdf_path, '/');
        }
      }
    } catch (\Throwable $e) {}

    return view('public.lgos', compact('lgosDocs'));
  }

  // 3. Portfolio (/portofolio/)
  public function portfolio()
  {
    $projects = PortfolioProject::orderByRaw("CASE WHEN status = 'ongoing' THEN 0 ELSE 1 END")
      ->orderBy('sort_order', 'asc')
      ->get();

    return view('public.portfolio.index', compact('projects'));
  }

  // 3b. Portfolio Single Article (/portofolio/{slug})
  public function portfolioDetail($slug)
  {
    $catalog = $this->getPortfolioCatalogue();

    // Look up in database first
    $dbProject = PortfolioProject::where('slug', $slug)
      ->orWhere('id', is_numeric($slug) ? (int)$slug : 0)
      ->first();

    if ($dbProject) {
      $isOngoing = strtolower($dbProject->status) === 'ongoing' || str_contains(strtolower($dbProject->status), 'berjalan');
      $catSlug = 'restorasi';
      $catLower = strtolower($dbProject->category ?? '');
      if (str_contains($catLower, 'spasial') || str_contains($catLower, 'pemetaan') || str_contains($catLower, 'intelligence')) {
        $catSlug = 'pemetaan';
      } elseif (str_contains($catLower, 'komoditas') || str_contains($catLower, 'commodity')) {
        $catSlug = 'komoditas';
      } elseif (str_contains($catLower, 'kapasitas') || str_contains($catLower, 'capacity')) {
        $catSlug = 'kapasitas';
      } elseif (str_contains($catLower, 'kebijakan') || str_contains($catLower, 'policy') || str_contains($catLower, 'governance')) {
        $catSlug = 'kebijakan';
      }

      $project = [
        'title' => $dbProject->project_title,
        'slug' => $dbProject->slug ?: \Illuminate\Support\Str::slug($dbProject->project_title),
        'category' => $dbProject->category,
        'category_slug' => $catSlug,
        'status' => $isOngoing ? 'ongoing' : 'completed',
        'location' => $dbProject->location ?? 'Kalimantan Barat',
        'period' => $dbProject->period ?? '-',
        'partner_donor' => $dbProject->partner_donor ?? 'Yayasan Natural Kapital Indonesia',
        'summary' => $dbProject->summary ?? $dbProject->description,
        'description' => $dbProject->description ?? $dbProject->summary,
        'document_pdf_path' => $dbProject->document_pdf_path,
      ];
    } elseif (isset($catalog[$slug])) {
      $project = $catalog[$slug];
    } else {
      // Fuzzy matching by slug
      $matched = null;
      foreach ($catalog as $key => $val) {
        if (\Illuminate\Support\Str::slug($val['title']) === $slug || str_contains($key, $slug) || str_contains($slug, $key)) {
          $matched = $val;
          break;
        }
      }
      if ($matched) {
        $project = $matched;
      } else {
        abort(404, 'Artikel portofolio tidak ditemukan.');
      }
    }

    // Get 3 other related projects
    $relatedProjects = [];
    foreach ($catalog as $key => $p) {
      if ($p['slug'] !== $project['slug']) {
        $relatedProjects[] = $p;
      }
      if (count($relatedProjects) >= 3) break;
    }

    return view('public.portfolio.show', compact('project', 'relatedProjects'));
  }

  private function getPortfolioCatalogue(): array
  {
    return [
      'sistem-cerdas-pemantauan-lanskap-gis' => [
        'title' => 'Sistem Cerdas Pemantauan Lanskap & GIS',
        'slug' => 'sistem-cerdas-pemantauan-lanskap-gis',
        'category' => 'Landscape Intelligence & Innovation',
        'category_slug' => 'pemetaan',
        'status' => 'ongoing',
        'location' => 'Kalimantan Barat',
        'period' => '2023 – Sekarang',
        'partner_donor' => 'Yayasan Natural Kapital Indonesia & Mitra',
        'summary' => 'Platform geospasial cerdas dan pemantauan lanskap berbasis data spasial real-time untuk mendukung perencanaan wilayah dan konservasi terpadu di Kalimantan Barat.',
        'description' => 'Inisiatif pengembangan sistem cerdas pemantauan spasial berbasis GIS (Geographic Information System) dan citra satelit resolusi tinggi untuk memantau dinamika perubahan tutupan lahan, potensi deforestasi, hidrologi gambut, serta koridor Area Bernilai Konservasi Tinggi (ABKT) di Kalimantan Barat. Platform ini memadukan analisis kecerdasan buatan, data lapangan partisipatif komunitas, dan integrasi kebijakan satu peta (One Map Policy) guna menyediakan data geospasial yang akurat dan terbuka bagi pengambil kebijakan publik, pengelola kawasan, serta mitra pembangunan berkelanjutan.',
        'document_pdf_path' => null,
      ],
      'tfca-kalimantan-mitigasi-adaptasi-iklim-desa-gambut' => [
        'title' => 'Program TFCA Kalimantan – Penguatan Mitigasi & Adaptasi Iklim Desa Gambut',
        'slug' => 'tfca-kalimantan-mitigasi-adaptasi-iklim-desa-gambut',
        'category' => 'Restorasi & Konservasi',
        'category_slug' => 'restorasi',
        'status' => 'ongoing',
        'location' => 'Kab. Kubu Raya, Kalimantan Barat',
        'period' => 'April 2026 – Maret 2028',
        'partner_donor' => 'Yayasan Kehati (TFCA Kalimantan)',
        'summary' => 'Program penguatan mitigasi dan adaptasi iklim desa gambut untuk komunitas yang rentan terhadap perubahan iklim melalui restorasi hidrologis dan ekonomi hijau.',
        'description' => 'Program kemitraan multipihak bersama Yayasan Kehati dalam kerangka Tropical Forest Conservation Act (TFCA) Kalimantan. Inisiatif strategis ini berfokus pada penguatan kapasitas ketahanan sosial dan ekologis desa-desa gambut di Kabupaten Kubu Raya dalam menghadapi ancaman perubahan iklim, kebakaran hutan dan lahan, serta penurunan kualitas hidrologis lahan gambut. Pendekatan mencakup restorasi hidrologis melalui pembangunan sekat kanal (canal blocking), revegetasi vegetasi endemik bernilai ekonomi, serta penguatan model mata pencaharian ramah gambut (paludikultur dan agroforestri).',
        'document_pdf_path' => null,
      ],
      'restorasi-gambut-pm-haze-singapore' => [
        'title' => 'Restorasi Gambut – PM Haze Singapore',
        'slug' => 'restorasi-gambut-pm-haze-singapore',
        'category' => 'Restorasi & Konservasi',
        'category_slug' => 'restorasi',
        'status' => 'ongoing',
        'location' => 'Desa Kalibandung, Kab. Kubu Raya',
        'period' => 'Mei 2022 – Sekarang',
        'partner_donor' => 'PM Haze – Singapore',
        'summary' => 'Pengembangan model restorasi gambut berbasis komunitas di Desa Kalibandung sebagai replikasi praktik terbaik mitigasi kabut asap.',
        'description' => 'Kolaborasi internasional bersama People\'s Movement to Stop Haze (PM Haze) Singapore untuk mewujudkan bentang lahan gambut yang sehat dan bebas dari ancaman kabut asap lintas batas (transboundary haze). Melalui pendampingan intensif bagi kelompok masyarakat di Desa Kalibandung, program ini mengintegrasikan pemantauan tinggi muka air tanah gambut, pembibitan pohon lokal (jelutung rawa, belangeran), serta kampanye edukasi kesadaran publik regional mengenai pentingnya pelestarian ekosistem gambut tropis Kalimantan Barat.',
        'document_pdf_path' => null,
      ],
      'penilaian-rantai-pasok-karet-berkelanjutan-kalbar' => [
        'title' => 'Penilaian Rantai Pasok Karet Berkelanjutan di Kalimantan Barat',
        'slug' => 'penilaian-rantai-pasok-karet-berkelanjutan-kalbar',
        'category' => 'Komoditas Berkelanjutan',
        'category_slug' => 'komoditas',
        'status' => 'ongoing',
        'location' => 'Kalimantan Barat',
        'period' => 'April 2023 – Sekarang',
        'partner_donor' => 'PT. Inovasi Digital',
        'summary' => 'Rekomendasi intervensi untuk rantai pasok karet berkelanjutan yang mendukung kesejahteraan petani dan kelestarian lingkungan.',
        'description' => 'Inisiatif kajian komprehensif rantai pasok karet alam di berbagai kabupaten sentra Kalimantan Barat. Proyek ini memetakan alur distribusi dari petani swadaya ke tengkulak hingga pabrik pengolahan, mengidentifikasi hambatan legalitas dan mutu bahan olah karet rakyat (Bokar), serta merumuskan rekomendasi intervensi digital dan ketertelusuran (traceability) guna meningkatkan nilai tawar dan pendapatan petani karet hutan.',
        'document_pdf_path' => null,
      ],
      'promosi-hortikultura-petani-kecil-kalbar' => [
        'title' => 'Promosi Hortikultura Petani Kecil di Kalimantan Barat',
        'slug' => 'promosi-hortikultura-petani-kecil-kalbar',
        'category' => 'Komoditas Berkelanjutan',
        'category_slug' => 'komoditas',
        'status' => 'ongoing',
        'location' => 'Kalimantan Barat',
        'period' => '2020 – Sekarang',
        'partner_donor' => 'PT. East West Indonesia (Ewindo)',
        'summary' => 'Lahan demo untuk komoditas hortikultura sebagai diversifikasi pendapatan petani kecil ramah lingkungan.',
        'description' => 'Program pendampingan budidaya hortikultura berkelanjutan yang bekerja sama dengan PT. East West Seed Indonesia. Mengembangkan lahan-lahan percontohan (demo plots) tanaman sayuran unggul seperti cabai, bawang merah, dan jagung manis di lahan-lahan marjinal tanpa bakar, guna memperkuat kemandirian pangan lokal, ketahanan ekonomi rumah tangga petani, serta membuka akses pasar yang adil.',
        'document_pdf_path' => null,
      ],
      'survei-rantai-pasok-karet-kesiapan-eudr-tropenbos' => [
        'title' => 'Survei Rantai Pasok Karet & Kesiapan EUDR – Tropenbos Indonesia',
        'slug' => 'survei-rantai-pasok-karet-kesiapan-eudr-tropenbos',
        'category' => 'Komoditas Berkelanjutan',
        'category_slug' => 'komoditas',
        'status' => 'completed',
        'location' => 'Kab. Ketapang, Sanggau, Sintang',
        'period' => 'Januari 2025 – Maret 2025',
        'partner_donor' => 'Tropenbos Indonesia',
        'summary' => 'Dokumen strategis rantai pasok karet dan rekomendasi kesiapan EUDR untuk tiga kabupaten di Kalimantan Barat.',
        'description' => 'Studi mendalam mengenai profil petani karet swadaya, legalitas lahan (STDB), koordinat geolokasi poligon kebun, serta rantai pasok lokal di Ketapang, Sanggau, dan Sintang. Menghasilkan kertas kebijakan dan peta jalan kesiapan pemenuhan regulasi European Union Deforestation Regulation (EUDR) agar pekebun rakyat tidak terdiskriminasi dari pasar ekspor global.',
        'document_pdf_path' => null,
      ],
      'kerangka-strategis-sop-abkt-perda-6-2018' => [
        'title' => 'Penyusunan Kerangka Strategis & SOP ABKT Perda 6 Tahun 2018 Kalimantan Barat',
        'slug' => 'kerangka-strategis-sop-abkt-perda-6-2018',
        'category' => 'Kebijakan & Tata Kelola',
        'category_slug' => 'kebijakan',
        'status' => 'completed',
        'location' => 'Kalimantan Barat',
        'period' => 'Januari 2025 – Maret 2025',
        'partner_donor' => 'Tropenbos Indonesia',
        'summary' => 'Dokumen kerangka strategis, panduan, dan SOP implementasi ABKT untuk mendukung pelaksanaan Perda 6 Tahun 2018.',
        'description' => 'Fasilitasi teknis dan legal perumusan instrumen pelaksanaan Peraturan Daerah Provinsi Kalimantan Barat No. 6 Tahun 2018 tentang Pengelolaan Usaha Berbasis Lahan Berkelanjutan. Dokumen ini memuat standar operasional prosedur penetapan, pengelolaan, pemantauan, dan resolusi konflik untuk Area Bernilai Konservasi Tinggi (ABKT) di luar kawasan hutan negara.',
        'document_pdf_path' => null,
      ],
      'program-undp-kalfor-perencanaan-hutan-ketapang' => [
        'title' => 'Program UNDP KalFor – Penguatan Perencanaan Kawasan Hutan Ketapang',
        'slug' => 'program-undp-kalfor-perencanaan-hutan-ketapang',
        'category' => 'Kebijakan & Tata Kelola',
        'category_slug' => 'kebijakan',
        'status' => 'completed',
        'location' => '3 Desa di Kab. Ketapang',
        'period' => 'Juni 2023 – Juni 2024',
        'partner_donor' => 'UNDP KalFor',
        'summary' => 'Pendampingan intensif di 3 desa di Kabupaten Ketapang untuk penguatan perencanaan kawasan hutan berbasis masyarakat.',
        'description' => 'Pendampingan desa dalam kerangka proyek Kalimantan Forest (KalFor) UNDP bersama Kementerian Lingkungan Hidup dan Kehutanan. Program ini berhasil memfasilitasi integrasi kawasan berhutan bernilai konservasi tinggi ke dalam Rencana Pembangunan Jangka Menengah Desa (RPJMDes) dan peraturan desa tentang perlindungan sumber daya alam di 3 desa dampingan.',
        'document_pdf_path' => null,
      ],
      'program-peat-impacts-indonesia-pengelolaan-gambut' => [
        'title' => 'Program Peat IMPACTS Indonesia – Peningkatan Pengelolaan Gambut',
        'slug' => 'program-peat-impacts-indonesia-pengelolaan-gambut',
        'category' => 'Restorasi & Konservasi',
        'category_slug' => 'restorasi',
        'status' => 'completed',
        'location' => 'Kab. Kubu Raya',
        'period' => 'Juni 2022 – Mei 2023',
        'partner_donor' => 'ICRAF Indonesia',
        'summary' => 'Pelatihan dan penguatan kapasitas petani kecil untuk mengembangkan agroforestri di lahan gambut secara berkelanjutan.',
        'description' => 'Penguatan kapasitas petani gambut melalui transfer teknologi budidaya agroforestri cerdas iklim bersama ICRAF (World Agroforestry). Mengombinasikan tanaman kehutanan seperti jelutung dan pinang dengan tanaman musiman untuk mencegah kebakaran lahan gambut sekaligus menjamin penghasilan berkala masyarakat desa.',
        'document_pdf_path' => null,
      ],
      'penilaian-hcv-hcs-pt-perintis-sawit-andalan' => [
        'title' => 'Penilaian HCV-HCS PT. Perintis Sawit Andalan',
        'slug' => 'penilaian-hcv-hcs-pt-perintis-sawit-andalan',
        'category' => 'Pemetaan & Spasial',
        'category_slug' => 'pemetaan',
        'status' => 'completed',
        'location' => 'Kab. Bengkayang',
        'period' => 'Mei 2022 – November 2022',
        'partner_donor' => 'PT. Perintis Sawit Andalan',
        'summary' => 'Penilaian komprehensif HCV-HCS untuk mendukung praktik pengelolaan perkebunan sawit yang bertanggung jawab.',
        'description' => 'Penilaian lapangan saintifik terpadu mengidentifikasi keanekaragaman hayati, koridor satwa, kawasan resapan air, situs budaya adat, dan cadangan biomassa karbon tinggi di dalam areal izin perkebunan kelapa sawit di Bengkayang, menghasilkan rencana aksi pengelolaan dan pemantauan lingkungan.',
        'document_pdf_path' => null,
      ],
      'pemetaan-hcv-hcs-lanskap-sawit-kalbar' => [
        'title' => 'Pemetaan HCV-HCS Lanskap Sawit Kalimantan Barat',
        'slug' => 'pemetaan-hcv-hcs-lanskap-sawit-kalbar',
        'category' => 'Pemetaan & Spasial',
        'category_slug' => 'pemetaan',
        'status' => 'completed',
        'location' => 'Kalimantan Barat',
        'period' => 'November 2020 – Maret 2021',
        'partner_donor' => 'NMI-CSF – Abler Nordic',
        'summary' => 'Pemetaan HCV dan HCS lanskap sawit di Kalimantan Barat untuk mendukung pengelolaan bertanggung jawab.',
        'description' => 'Pemetaan spasial skala lanskap menggunakan data satelit optik dan radar untuk mengidentifikasi fragmentasi habitat dan zona konservasi kritis di kawasan perkebunan kelapa sawit seluruh Kalimantan Barat.',
        'document_pdf_path' => null,
      ],
      'pelatihan-petani-sawit-swadaya-gema-sawit-lestari' => [
        'title' => 'Pelatihan Petani Sawit Swadaya – Kelompok Gema Sawit Lestari',
        'slug' => 'pelatihan-petani-sawit-swadaya-gema-sawit-lestari',
        'category' => 'Pengembangan Kapasitas',
        'category_slug' => 'kapasitas',
        'status' => 'completed',
        'location' => 'Kab. Sanggau',
        'period' => 'November 2020 – Mei 2021',
        'partner_donor' => 'NMI-CSF (Climate Smart Fund)',
        'summary' => 'Pelatihan petani sawit swadaya untuk peningkatan kapasitas kelompok Gema Sawit Lestari menuju pertanian bertanggung jawab.',
        'description' => 'Pelatihan intensif mencakup pemupukan berimbang ramah lingkungan, panen higienis, pencegahan kebakaran, dan manajemen kebun mandiri guna mempersiapkan petani swadaya menuju sertifikasi sawit berkelanjutan.',
        'document_pdf_path' => null,
      ],
      'insentif-tumpang-sari-padi-lahan-kering-sawit' => [
        'title' => 'Insentif Tumpang Sari Padi Lahan Kering untuk Petani Sawit Swadaya',
        'slug' => 'insentif-tumpang-sari-padi-lahan-kering-sawit',
        'category' => 'Komoditas Berkelanjutan',
        'category_slug' => 'komoditas',
        'status' => 'completed',
        'location' => 'Kab. Sanggau',
        'period' => 'November 2020 – Mei 2021',
        'partner_donor' => 'NMI-CSF (Climate Smart Fund)',
        'summary' => 'Lahan demo pertanian padi lahan kering sebagai alternatif mata pencaharian berkelanjutan bagi petani sawit swadaya.',
        'description' => 'Program ketahanan pangan lokal dengan menanam padi gogo/lahan kering di antara tanaman kelapa sawit usia belum menghasilkan (TBM), menghasilkan panen padi mandiri bagi keluarga petani tanpa membuka lahan baru dengan cara membakar.',
        'document_pdf_path' => null,
      ],
      'analisis-spasial-dampak-deforestasi-sintang-sanggau' => [
        'title' => 'Analisis Spasial Dampak Deforestasi Petani Kecil Sintang & Sanggau',
        'slug' => 'analisis-spasial-dampak-deforestasi-sintang-sanggau',
        'category' => 'Pemetaan & Spasial',
        'category_slug' => 'pemetaan',
        'status' => 'completed',
        'location' => 'Kab. Sintang dan Sanggau',
        'period' => 'Februari 2020 – Juni 2020',
        'partner_donor' => 'NMI-CSF – Abler Nordic',
        'summary' => 'Rekomendasi dokumen intervensi untuk petani kecil di Sintang berdasarkan analisis dampak deforestasi.',
        'description' => 'Analisis temporal perubahan tutupan lahan 10 tahun terakhir untuk melihat dinamika pembukaan lahan oleh pekebun rakyat serta rekomendasi zonasi penyangga konservasi.',
        'document_pdf_path' => null,
      ],
      'rencana-strategis-cagar-biosfer-bkds-kapuas-hulu' => [
        'title' => 'Rencana Strategis Cagar Biosfer Betung Kerihun Danau Sentarum',
        'slug' => 'rencana-strategis-cagar-biosfer-bkds-kapuas-hulu',
        'category' => 'Kebijakan & Tata Kelola',
        'category_slug' => 'kebijakan',
        'status' => 'completed',
        'location' => 'Kab. Kapuas Hulu',
        'period' => 'Juli 2020',
        'partner_donor' => 'GIZ SFM',
        'summary' => 'Rekomendasi strategis dan strategi pengelolaan Cagar Biosfer BKDS Kapuas Hulu untuk konservasi jangka panjang.',
        'description' => 'Kerangka strategis pengelolaan kawasan Cagar Biosfer UNESCO Betung Kerihun Danau Sentarum Kapuas Hulu (BKDS) yang memadukan konservasi koridor ekologis dan pemanfaatan berkelanjutan hasil hutan bukan kayu oleh masyarakat adat Dayak dan Melayu.',
        'document_pdf_path' => null,
      ],
      'restorasi-gambut-kalibandung' => [
        'title' => 'Restorasi Gambut Kalibandung',
        'slug' => 'restorasi-gambut-kalibandung',
        'category' => 'Restorasi & Konservasi',
        'category_slug' => 'restorasi',
        'status' => 'completed',
        'location' => 'Desa Kalibandung, Kab. Kubu Raya',
        'period' => 'Agustus 2019 – September 2021',
        'partner_donor' => 'WWF-US',
        'summary' => 'Restorasi revegetasi hutan desa Kalibandung, Kubu Raya untuk pemulihan ekosistem gambut yang terdegradasi.',
        'description' => 'Program revegetasi lahan gambut terdegradasi bekas kebakaran hutan melalui penanaman lebih dari 20.000 bibit pohon lokal bersama Lembaga Pengelola Hutan Desa (LPHD) Kalibandung.',
        'document_pdf_path' => null,
      ],
      'rencana-pemulihan-lanskap-delta-kapuas' => [
        'title' => 'Rencana Pemulihan Lanskap Delta Kapuas untuk Konsesi Sawit & Gambut',
        'slug' => 'rencana-pemulihan-lanskap-delta-kapuas',
        'category' => 'Restorasi & Konservasi',
        'category_slug' => 'restorasi',
        'status' => 'completed',
        'location' => 'Lanskap Delta Kapuas, Kubu Raya',
        'period' => 'September 2019 – Desember 2019',
        'partner_donor' => 'WWF Indonesia',
        'summary' => 'Rekomendasi dokumen area pemulihan di lanskap Delta Kapuas untuk konsesi sawit dan kawasan gambut.',
        'description' => 'Delineasi koridor hidrologis dan zona restorasi prioritas di bentang delta muara sungai Kapuas guna menyelaraskan izin usaha perkebunan dengan fungsi tata air dan pencegahan intrusi air laut.',
        'document_pdf_path' => null,
      ],
      'analisis-hcv-hcs-agropolitan-kapuas-hulu' => [
        'title' => 'Analisis HCV-HCS Kawasan Agropolitan Kapuas Hulu',
        'slug' => 'analisis-hcv-hcs-agropolitan-kapuas-hulu',
        'category' => 'Pemetaan & Spasial',
        'category_slug' => 'pemetaan',
        'status' => 'completed',
        'location' => 'Kab. Kapuas Hulu',
        'period' => 'Juli 2019',
        'partner_donor' => 'WWF Id – KAK',
        'summary' => 'Analisis HCV dan HCS di kawasan agropolitan Kabupaten Kapuas Hulu untuk mendukung perencanaan tata guna lahan.',
        'description' => 'Kajian spasial dan ekologis untuk penyusunan masterplan kawasan agropolitan berbasis komoditas unggulan lokal (karet, tengkawang, kratom) dengan tetap mempertahankan tutupan hutan dan kawasan bernilai konservasi tinggi di Kabupaten Kapuas Hulu.',
        'document_pdf_path' => null,
      ],
    ];
  }

  // 4. Transparansi (/transparansi/)
  public function transparansi()
  {
    $reports = TransparencyReport::where('is_active', true)
      ->orderBy('report_year', 'desc')
      ->get();

    return view('public.transparansi', compact('reports'));
  }

  // Helper untuk membaca artikel lama dari resources/legacy_articles
  private function getLegacyArticles(string $subfolder): array
  {
    $dir = resource_path("legacy_articles/{$subfolder}");
    if (!is_dir($dir)) {
      return [];
    }

    $items = [];
    $folders = glob($dir . '/*', GLOB_ONLYDIR);
    foreach ($folders as $f) {
      $slug = basename($f);
      $htmlFile = $f . '/index.html';
      if (!file_exists($htmlFile)) {
        continue;
      }

      $html = file_get_contents($htmlFile, false, null, 0, 50000);
      preg_match('/<title>(.*?)<\/title>/is', $html, $t);
      $title = isset($t[1]) ? trim(explode('-', $t[1])[0]) : ucwords(str_replace('-', ' ', $slug));

      preg_match('/property=["\']og:image["\']\s+content=["\']([^"\']+)["\']/is', $html, $img);
      if (empty($img[1])) {
        preg_match('/content=["\']([^"\']+)["\']\s+property=["\']og:image["\']/is', $html, $img);
      }
      $featuredImage = $img[1] ?? null;

      preg_match('/property=["\']og:description["\']\s+content=["\']([^"\']+)["\']/is', $html, $desc);
      if (empty($desc[1])) {
        preg_match('/content=["\']([^"\']+)["\']\s+property=["\']og:description["\']/is', $html, $desc);
      }
      $excerpt = $desc[1] ?? '';

      preg_match('/(?:property=["\']article:published_time["\']\s+content=["\']([^"\']+)["\']|content=["\']([^"\']+)["\']\s+property=["\']article:published_time["\'])/is', $html, $date);
      $publishedTime = $date[1] ?? ($date[2] ?? null);
      $year = $publishedTime ? date('Y', strtotime($publishedTime)) : 'Arsip';

      $items[] = (object) [
        'title' => $title,
        'slug' => $slug,
        'featured_image_path' => $featuredImage,
        'excerpt' => $excerpt,
        'year' => $year,
        'url' => "/kategori/{$subfolder}/{$slug}",
        'is_legacy' => true,
      ];
    }

    return $items;
  }

  // Kisah Perubahan (/kisah-perubahan/)
  public function kisahPerubahan()
  {
    $category = ArticleCategory::where('slug', 'kisah-perubahan')->first();
    $articles = Article::with(['category', 'author'])
      ->where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    return view('public.kisah-perubahan', compact('articles'));
  }

  // Liputan Media (/liputan-media/)
  public function liputanMedia()
  {
    $category = ArticleCategory::where('slug', 'liputan-media')->first();
    $articles = Article::with(['category', 'author'])
      ->where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    return view('public.liputan-media', compact('articles'));
  }

  // 5. News & Features (/news-features/) - Kabar Terkini dari Program YNKI
  public function newsFeatures()
  {
    $category = ArticleCategory::where('slug', 'news-features')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    $legacyArticles = $this->getLegacyArticles('news-features');

    return view('public.news-features', compact('articles', 'legacyArticles'));
  }

  // 6. Penelitian & Laporan (/penelitian-laporan/) - Kumpulan Penelitian & Laporan
  public function researchReports()
  {
    $category = ArticleCategory::where('slug', 'penelitian-laporan')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    return view('public.penelitian-laporan', compact('articles'));
  }

  // 7. Analisis & Kebijakan (/analisis-kebijakan/) - Kumpulan Analisis & Policy Brief
  public function policyAnalysis()
  {
    $category = ArticleCategory::where('slug', 'analisis-kebijakan')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    return view('public.analisis-kebijakan', compact('articles'));
  }

  // 8. Perspektif Budaya (/perspektif-budaya/) - Artikel Perspektif Budaya
  public function culturalPerspective()
  {
    $category = ArticleCategory::where('slug', 'perspektif-budaya')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    $legacyArticles = $this->getLegacyArticles('perspektif-budaya');

    return view('public.perspektif-budaya', compact('articles', 'legacyArticles'));
  }

  // 9. Data Spasial & GIS (/data-spasial-dan-gis/) - Peta & Analisis GIS
  public function spatialGis()
  {
    $category = ArticleCategory::where('slug', 'data-spasial-dan-gis')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    return view('public.data-spasial-dan-gis', compact('articles'));
  }

  // 10. Story Foto & Video (/story-foto-video/ & /stori-foto-video/)
  public function mediaStories()
  {
    $photos = MediaStory::where('is_active', true)
      ->where('media_type', 'photo')
      ->orderBy('created_at', 'desc')
      ->get();

    $videos = MediaStory::where('is_active', true)
      ->where('media_type', 'video')
      ->orderBy('created_at', 'desc')
      ->get();

    return view('public.story-foto-video', compact('photos', 'videos'));
  }


  // 11. Ikut Terlibat / Ikut Serta (/ikut-terlibat/, /ikut-serta/)
  public function ikutTerlibat()
  {
    return view('public.ikut-terlibat');
  }

  public function storeParticipation(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:150',
      'email' => 'required|email|max:150',
      'phone' => ['required', 'string', 'max:25', 'regex:/^\+?[0-9]+$/'],
      'interest' => 'required|string|max:100',
      'message' => 'required|string|max:3000',
    ], [
      'name.required' => 'Nama lengkap tidak boleh kosong.',
      'email.required' => 'Alamat email tidak boleh kosong.',
      'email.email' => 'Format email tidak valid.',
      'phone.required' => 'Nomor WhatsApp tidak boleh kosong.',
      'phone.regex' => 'Nomor WhatsApp hanya boleh diisi angka dan tanda + (misal: +628123456789).',
      'interest.required' => 'Peminatan keterlibatan wajib dipilih.',
      'message.required' => 'Pesan / latar belakang singkat tidak boleh kosong.',
    ]);

    Participation::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'phone' => $validated['phone'],
      'interest' => $validated['interest'],
      'message' => $validated['message'] ?? null,
      'is_read' => false,
    ]);

    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Terima kasih! Formulir minat keterlibatan Anda telah berhasil dikirim ke Sekretariat YNKI.'
      ]);
    }

    return redirect()->back()->with('success', 'Terima kasih! Formulir minat keterlibatan Anda telah berhasil dikirim ke Sekretariat YNKI.');
  }

  // 12. Kontak Kami / Hubungi Kami (/kontak-kami/, /hubungi-kami/)
  public function kontakKami()
  {
    return view('public.kontak-kami');
  }

  public function storeContactMessage(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:150',
      'email' => 'required|email|max:150',
      'phone' => ['required', 'string', 'max:25', 'regex:/^\+?[0-9]+$/'],
      'subject' => 'required|string|max:150',
      'message' => 'required|string|max:5000',
    ], [
      'name.required' => 'Nama lengkap tidak boleh kosong.',
      'email.required' => 'Alamat email tidak boleh kosong.',
      'email.email' => 'Format alamat email tidak valid.',
      'phone.required' => 'Nomor telepon/WhatsApp tidak boleh kosong.',
      'phone.regex' => 'Nomor telepon/WhatsApp hanya boleh berisi angka dan tanda + (contoh: +628123456789 atau 08123456789).',
      'subject.required' => 'Topik/keperluan pesan tidak boleh kosong.',
      'message.required' => 'Isi pesan tidak boleh kosong.',
    ]);

    ContactMessage::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'phone' => $validated['phone'],
      'subject' => $validated['subject'],
      'message' => $validated['message'],
      'is_read' => false,
    ]);

    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Terima kasih! Pesan Anda telah berhasil terkirim ke Sekretariat YNKI.'
      ]);
    }

    return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah berhasil terkirim ke Sekretariat YNKI.');
  }
}
