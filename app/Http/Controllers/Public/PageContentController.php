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

  // 5. News & Features (/news-features/) - Kabar Terkini dari Program YNKI
  public function newsFeatures()
  {
    $category = ArticleCategory::where('slug', 'news-features')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    $originalHtml = file_get_contents(base_path('news-features/index.html'));

    $cardsHtml = '';
    foreach ($articles as $art) {
      $imgThumb = $art->featured_image_path
        ? '/storage/' . $art->featured_image_path
        : null;

      $imgHtml = $imgThumb
        ? '<img src="' . htmlspecialchars($imgThumb) . '" alt="' . htmlspecialchars($art->title) . '" onerror="this.onerror=null;this.parentElement.innerHTML=\'<svg width=\\\'40\\\' height=\\\'40\\\' viewBox=\\\'0 0 24 24\\\' fill=\\\'none\\\' stroke=\\\'currentColor\\\' stroke-width=\\\'1.5\\\' stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\'><path d=\\\'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z\\\'/><polyline points=\\\'14 2 14 8 20 8\\\'/><line x1=\\\'16\\\' y1=\\\'13\\\' x2=\\\'8\\\' y2=\\\'13\\\'/><line x1=\\\'16\\\' y1=\\\'17\\\' x2=\\\'8\\\' y2=\\\'17\\\'/><polyline points=\\\'10 9 9 9 8 9\\\'/></svg>\';">'
        : '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>';

      $cardsHtml .= '
                        <!-- Admin Dynamic Card -->
                        <div class="doc-card">
                          <div class="doc-img">
                            ' . $imgHtml . '
                          </div>
                          <div class="doc-body">
                            <div class="doc-meta">
                              <span class="doc-year">' . ($art->published_at ? $art->published_at->format('Y') : date('Y')) . '</span>
                            </div>
                            <div class="doc-loc">' . htmlspecialchars(strtoupper($art->category->category_name ?? 'YNKI NEWS')) . '</div>
                            <h3>
                              <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '">' . htmlspecialchars($art->title) . '</a>
                            </h3>
                            <p>' . htmlspecialchars($art->excerpt ?? '') . '</p>
                            <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '" class="btn-dl">Baca Selengkapnya
                              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14" />
                                <path d="M12 5l7 7-7 7" />
                              </svg>
                            </a>
                          </div>
                        </div>';
    }

    $pattern = '/<div class="docs-grid">\s*(<!-- 1 -->.*?<\/div>\s*<\/div>)/s';
    if (preg_match($pattern, $originalHtml)) {
      $renderedHtml = preg_replace($pattern, '<div class="docs-grid">' . $cardsHtml . '$1', $originalHtml);
    } else {
      $renderedHtml = str_replace('<div class="docs-grid">', '<div class="docs-grid">' . $cardsHtml, $originalHtml);
    }

    return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  // 6. Penelitian & Laporan (/penelitian-laporan/) - Kumpulan Penelitian & Laporan
  public function researchReports()
  {
    $category = ArticleCategory::where('slug', 'penelitian-laporan')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    $originalHtml = file_get_contents(base_path('penelitian-laporan/index.html'));

    $itemsHtml = '';
    foreach ($articles as $art) {
      $pdfBtn = $art->attachment_pdf_path
        ? '<a href="/storage/' . htmlspecialchars($art->attachment_pdf_path) . '" target="_blank" class="btn-download"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Unduh PDF</a>'
        : '';

      $itemsHtml .= '
            <div class="pen-item">
              <div class="pen-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              </div>
              <div class="pen-content">
                <div class="pen-meta">
                  <span class="pen-badge badge-spasial">' . htmlspecialchars($art->category->category_name ?? 'Riset') . '</span>
                  <span class="pen-year">' . ($art->published_at ? $art->published_at->format('Y') : date('Y')) . '</span>
                </div>
                <h3>' . htmlspecialchars($art->title) . '</h3>
                <p>' . htmlspecialchars($art->excerpt ?? '') . '</p>
                <div class="pen-actions">
                  ' . $pdfBtn . '
                  <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '" class="btn-read" style="margin-left:8px;">Baca Online &rarr;</a>
                </div>
              </div>
            </div>';
    }

    $pattern = '/<div class="penelitian-list">.*?<\/div>\s*<\/div>\s*<\/section>/s';
    $replacement = '<div class="penelitian-list">' . $itemsHtml . '</div></div></section>';
    $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

    return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  // 7. Analisis & Kebijakan (/analisis-kebijakan/) - Kumpulan Analisis & Policy Brief
  public function policyAnalysis()
  {
    $category = ArticleCategory::where('slug', 'analisis-kebijakan')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    $originalHtml = file_get_contents(base_path('analisis-kebijakan/index.html'));

    $docsHtml = '';
    foreach ($articles as $art) {
      $pdfBtn = $art->attachment_pdf_path
        ? '<a href="/storage/' . htmlspecialchars($art->attachment_pdf_path) . '" target="_blank" class="btn-dl"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Unduh PDF</a>'
        : '';

      $docsHtml .= '
            <div class="doc-card">
              <div class="doc-header">
                <span class="doc-badge b-policy">' . htmlspecialchars($art->category->category_name ?? 'Kebijakan') . '</span>
                <span class="doc-year">' . ($art->published_at ? $art->published_at->format('Y') : date('Y')) . '</span>
              </div>
              <h3>' . htmlspecialchars($art->title) . '</h3>
              <p>' . htmlspecialchars($art->excerpt ?? '') . '</p>
              <div class="doc-actions">
                ' . $pdfBtn . '
                <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '" style="color:#117710;font-weight:700;font-size:13px;text-decoration:none;margin-left:12px;">Baca Ringkasan &rarr;</a>
              </div>
            </div>';
    }

    $pattern = '/<div class="docs-grid">\s*(<!-- 1 -->.*?<\/div>\s*<\/div>)/s';
    if (preg_match($pattern, $originalHtml)) {
      $renderedHtml = preg_replace($pattern, '<div class="docs-grid">' . $docsHtml . '$1', $originalHtml);
    } else {
      $renderedHtml = str_replace('<div class="docs-grid">', '<div class="docs-grid">' . $docsHtml, $originalHtml);
    }

    return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  // 8. Perspektif Budaya (/perspektif-budaya/) - Artikel Perspektif Budaya
  public function culturalPerspective()
  {
    $category = ArticleCategory::where('slug', 'perspektif-budaya')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    $originalHtml = file_get_contents(base_path('perspektif-budaya/index.html'));

    $cardsHtml = '';
    foreach ($articles as $art) {
      $imgThumb = $art->featured_image_path
        ? '/storage/' . $art->featured_image_path
        : null;

      $imgHtml = $imgThumb
        ? '<img src="' . htmlspecialchars($imgThumb) . '" alt="' . htmlspecialchars($art->title) . '" onerror="this.onerror=null;this.parentElement.innerHTML=\'<svg width=\\\'40\\\' height=\\\'40\\\' viewBox=\\\'0 0 24 24\\\' fill=\\\'none\\\' stroke=\\\'currentColor\\\' stroke-width=\\\'1.5\\\' stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\'><path d=\\\'M4 19.5A2.5 2.5 0 0 1 6.5 17H20\\\'></path><path d=\\\'M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z\\\'></path></svg>\';">'
        : '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>';

      $cardsHtml .= '
                        <!-- Admin Dynamic Card -->
                        <div class="doc-card">
                          <div class="doc-img">
                            ' . $imgHtml . '
                          </div>
                          <div class="doc-body">
                            <div class="doc-meta">
                              <span class="doc-year">' . ($art->published_at ? $art->published_at->format('Y') : date('Y')) . '</span>
                            </div>
                            <h3>
                              <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '">' . htmlspecialchars($art->title) . '</a>
                            </h3>
                            <p>' . htmlspecialchars($art->excerpt ?? '') . '</p>
                            <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '" class="btn-dl">Baca Esai Lengkap
                              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14" />
                                <path d="M12 5l7 7-7 7" />
                              </svg>
                            </a>
                          </div>
                        </div>';
    }

    $pattern = '/<div class="docs-grid">\s*(<!-- 1 -->.*?<\/div>\s*<\/div>)/s';
    if (preg_match($pattern, $originalHtml)) {
      $renderedHtml = preg_replace($pattern, '<div class="docs-grid">' . $cardsHtml . '$1', $originalHtml);
    } else {
      $renderedHtml = str_replace('<div class="docs-grid">', '<div class="docs-grid">' . $cardsHtml, $originalHtml);
    }

    return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  // 9. Data Spasial & GIS (/data-spasial-dan-gis/) - Peta & Analisis GIS
  public function spatialGis()
  {
    $category = ArticleCategory::where('slug', 'data-spasial-dan-gis')->first();
    $articles = Article::where('status', 'published')
      ->when($category, fn($q) => $q->where('category_id', $category->id))
      ->orderBy('published_at', 'desc')
      ->get();

    $path = file_exists(base_path('data-spasial-dan-gis/index.html'))
      ? base_path('data-spasial-dan-gis/index.html')
      : base_path('data-spasial-gis/index.html');

    $originalHtml = file_get_contents($path);

    $gisCardsHtml = '';
    foreach ($articles as $art) {
      $imgCover = $art->featured_image_path
        ? '/storage/' . $art->featured_image_path
        : '/assets/images/data-spasial-gis/image12.png';

      $pdfLink = $art->attachment_pdf_path
        ? '/storage/' . $art->attachment_pdf_path
        : '/artikel-cms/' . $art->slug;

      $gisCardsHtml .= '
            <div class="gis-product-card">
              <div class="card-img-wrap">
                <img src="' . htmlspecialchars($imgCover) . '" alt="' . htmlspecialchars($art->title) . '" loading="lazy" onerror="this.src=\'/assets/images/data-spasial-gis/image12.png\'">
              </div>
              <div style="padding:22px 20px;flex:1;display:flex;flex-direction:column;justify-content:space-between;">
                <div>
                  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                    <span class="tag-category">' . htmlspecialchars($art->category->category_name ?? 'ANALISIS SPASIAL') . '</span>
                    <span class="year-badge">' . ($art->published_at ? $art->published_at->format('Y') : date('Y')) . ' · GIS</span>
                  </div>
                  <h3 style="font-size:17.5px;font-weight:700;color:#0e241b;margin:0 0 10px;line-height:1.38;">
                    ' . htmlspecialchars($art->title) . '
                  </h3>
                  <p style="font-size:14px;line-height:1.68;color:#435c50;margin:0;">
                    ' . htmlspecialchars($art->excerpt ?? '') . '
                  </p>
                </div>
                <div style="margin-top:16px;padding-top:14px;border-top:1px solid #eef4f0;display:flex;gap:10px;">
                  <a href="' . htmlspecialchars($pdfLink) . '" target="_blank" class="btn-hero-primary" style="font-size:12.5px;padding:10px 18px;">
                    Unduh Peta &amp; Laporan &rarr;
                  </a>
                  <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '" style="color:#117710;font-weight:700;font-size:12.5px;align-self:center;text-decoration:none;">
                    Detail
                  </a>
                </div>
              </div>
            </div>';
    }

    // Replace the 5 GIS product cards grid
    $pattern = '/<!-- 5 GIS Product Cards Grid -->\s*<div style="display:grid;grid-template-columns:repeat\(auto-fit, minmax\(320px, 1fr\)\);gap:26px;">.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/s';
    $replacement = '<!-- 5 GIS Product Cards Grid -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:26px;">' . $gisCardsHtml . '</div></div></div></div>';

    $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

    return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  // 10. Story Foto & Video (/story-foto-video/ & /stori-foto-video/)
  public function mediaStories()
  {
    $photos = MediaStory::where('is_active', true)
      ->where('media_type', 'photo')
      ->orderBy('sort_order', 'asc')
      ->get();

    $videos = MediaStory::where('is_active', true)
      ->where('media_type', 'video')
      ->orderBy('sort_order', 'asc')
      ->get();

    $path = file_exists(base_path('story-foto-video/index.html'))
      ? base_path('story-foto-video/index.html')
      : base_path('stori-foto-video/index.html');

    $originalHtml = file_get_contents($path);

    // 1. Render Foto-Foto dari Lapangan
    $photosHtml = '';
    foreach ($photos as $s) {
      $imgSrc = $s->image_path
        ? '/storage/' . $s->image_path
        : '/assets/images/stori-foto-video/image5.png';

      $photosHtml .= '
            <div class="photo-story-card">
              <div class="card-img-wrap">
                <img src="' . htmlspecialchars($imgSrc) . '" alt="' . htmlspecialchars($s->title) . '" loading="lazy" onerror="this.src=\'/assets/images/stori-foto-video/image5.png\'">
              </div>
              <div style="padding:22px 20px;flex:1;display:flex;flex-direction:column;justify-content:space-between;">
                <div>
                  <div class="tag-location">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    ' . htmlspecialchars($s->location ?? 'Kalimantan Barat') . ' · ' . htmlspecialchars($s->category) . '
                  </div>
                  <h3 style="font-size:17.5px;font-weight:700;color:#0e241b;margin:0 0 10px;line-height:1.38;">
                    ' . htmlspecialchars($s->title) . '
                  </h3>
                  <p style="font-size:14px;line-height:1.68;color:#435c50;margin:0;">
                    ' . htmlspecialchars($s->caption ?? '') . '
                  </p>
                </div>
                <div style="margin-top:16px;padding-top:14px;border-top:1px solid #eef4f0;display:flex;justify-content:space-between;align-items:center;">
                  <small style="color:#888;font-size:12px;">' . htmlspecialchars($s->photographer_credits ?? 'YNKI') . '</small>
                  <a href="' . htmlspecialchars($imgSrc) . '" target="_blank" class="btn-hero-primary" style="font-size:12px;padding:8px 16px;">
                    Lihat Foto Resolusi Penuh &rarr;
                  </a>
                </div>
              </div>
            </div>';
    }

    // 2. Render Saksikan Perubahan Nyata di Lapangan (Video)
    $videosHtml = '';
    foreach ($videos as $v) {
      $embedUrl = $v->youtube_url;
      if (str_contains($embedUrl, 'watch?v=')) {
        $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
      }

      $videosHtml .= '
            <div class="video-card">
              <div class="video-thumb-wrap" style="position:relative;height:200px;background:#000;">
                <iframe src="' . htmlspecialchars($embedUrl) . '" style="width:100%;height:100%;border:none;" allowfullscreen></iframe>
              </div>
              <div style="padding:22px 20px;flex:1;display:flex;flex-direction:column;justify-content:space-between;">
                <div>
                  <div style="font-size:12.5px;color:#117710;font-weight:700;margin-bottom:6px;">DOKUMENTER LAPANGAN</div>
                  <h3 style="font-size:17px;font-weight:700;color:#0e241b;margin:0 0 8px;line-height:1.38;">
                    ' . htmlspecialchars($v->title) . '
                  </h3>
                  <p style="font-size:13.5px;line-height:1.65;color:#435c50;margin:0;">
                    ' . htmlspecialchars($v->caption ?? '') . '
                  </p>
                </div>
                <div style="margin-top:16px;padding-top:14px;border-top:1px solid #eef4f0;">
                  <a href="' . htmlspecialchars($v->youtube_url) . '" target="_blank" rel="noopener noreferrer" class="read-more-btn" style="color:#117710;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    Tonton di YouTube &rarr;
                  </a>
                </div>
              </div>
            </div>';
    }

    // Replace photo section
    $patternPhoto = '/<!-- 3 Photo Story Cards Grid -->\s*<div style="display:grid;grid-template-columns:repeat\(auto-fit, minmax\(320px, 1fr\)\);gap:26px;">.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/s';
    $replacementPhoto = '<!-- 3 Photo Story Cards Grid -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:26px;">' . $photosHtml . '</div></div></div></div>';

    $renderedHtml = preg_replace($patternPhoto, $replacementPhoto, $originalHtml);

    // Replace video section
    $patternVideo = '/<!-- 3 Video Cards Grid -->\s*<div style="display:grid;grid-template-columns:repeat\(auto-fit, minmax\(320px, 1fr\)\);gap:26px;">.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/s';
    $replacementVideo = '<!-- 3 Video Cards Grid -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:26px;">' . $videosHtml . '</div></div></div></div>';

    if ($renderedHtml) {
      $renderedHtml = preg_replace($patternVideo, $replacementVideo, $renderedHtml);
    }

    return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  // 11. Ikut Terlibat / Ikut Serta (/ikut-terlibat/, /ikut-serta/)
  public function ikutTerlibat()
  {
    $filePath = base_path('ikut-terlibat/index.html');
    $html = file_exists($filePath) ? file_get_contents($filePath) : '';
    return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  public function storeParticipation(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:150',
      'email' => 'required|email|max:150',
      'phone' => 'required|string|max:50',
      'interest' => 'required|string|max:100',
      'message' => 'nullable|string|max:3000',
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
    $filePath = base_path('kontak-kami/index.html');
    $html = file_exists($filePath) ? file_get_contents($filePath) : '';
    return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  public function storeContactMessage(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:150',
      'email' => 'required|email|max:150',
      'phone' => 'nullable|string|max:50',
      'subject' => 'nullable|string|max:150',
      'message' => 'required|string|max:5000',
    ]);

    ContactMessage::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'phone' => $validated['phone'] ?? null,
      'subject' => $validated['subject'] ?? 'Umum',
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
