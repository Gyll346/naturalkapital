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
    $originalHtml = file_get_contents(base_path('tim/index.html'));

    try {
      $allMembers = TeamMember::with('category')
        ->where('status', 'active')
        ->orderBy('sort_order', 'asc')
        ->get();

      if ($allMembers->isNotEmpty()) {
        // Kelompokkan pengurus dewan vs tim ahli / lainnya
        $leadershipMembers = $allMembers->filter(function ($m) {
          $catName = strtolower($m->category->category_name ?? '');
          return !str_contains($catName, 'ahli') && !str_contains($catName, 'lapangan');
        });

        $expertMembers = $allMembers->filter(function ($m) {
          $catName = strtolower($m->category->category_name ?? '');
          return str_contains($catName, 'ahli') || str_contains($catName, 'lapangan');
        });

        // Fallback jika belum dibagi kategori khusus ahli: masukkan ke leadership
        if ($leadershipMembers->isEmpty()) {
          $leadershipMembers = $allMembers;
        }

        $renderCards = function ($members) {
          $html = '';
          foreach ($members as $member) {
            $photoSrc = $member->photo_path
              ? (str_starts_with($member->photo_path, 'http') || str_starts_with($member->photo_path, '/') ? $member->photo_path : '/storage/' . $member->photo_path)
              : '/wp-content/uploads/2026/05/Michael-Eko-for-YNKI__MG_7449-600x600.webp';

            $linkedinLink = '';
            if (!empty($member->linkedin_url)) {
              $linkedinLink = '<a href="' . htmlspecialchars($member->linkedin_url) . '" target="_blank" rel="noopener" class="lead-linkedin" title="Profil LinkedIn" style="display:inline-flex; align-items:center; gap:4px; font-size:12px; color:#0a66c2; text-decoration:none; margin-top:8px; font-weight:600;"><svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.6 1.6 0 0 0-1.6 1.6 1.6 1.6 0 0 0 1.6 1.6 1.6 1.6 0 0 0 1.6-1.6 1.6 1.6 0 0 0-1.6-1.6z"/></svg> LinkedIn</a>';
            }

            $html .= '
                        <div class="lead-card">
                          <div class="lead-photo-wrap">
                            <img src="' . htmlspecialchars($photoSrc) . '" alt="' . htmlspecialchars($member->full_name) . '" loading="lazy" onerror="this.src=\'/wp-content/uploads/2026/05/Michael-Eko-for-YNKI__MG_7449-600x600.webp\'" />
                          </div>
                          <div class="lead-body">
                            <h3 class="lead-name">' . htmlspecialchars($member->full_name) . '</h3>
                            <div class="lead-role">' . htmlspecialchars($member->position) . '</div>
                            <p class="lead-bio">' . htmlspecialchars($member->bio ?? '') . '</p>
                            ' . $linkedinLink . '
                          </div>
                        </div>';
          }
          return $html;
        };

        // Render dynamic Dewan Pengurus
        if ($leadershipMembers->isNotEmpty()) {
          $dynamicLeadershipHtml = $renderCards($leadershipMembers);
          $patternLeadership = '/(<section id="leadership-section"[^>]*>.*?<div class="leadership-grid-4">)(.*?)(<\/div>\s*<\/div>\s*<\/section>)/s';
          $originalHtml = preg_replace($patternLeadership, '$1' . $dynamicLeadershipHtml . '$3', $originalHtml);
        }

        // Render dynamic Tim Ahli Pendukung jika ada data anggota tim ahli di DB
        if ($expertMembers->isNotEmpty()) {
          $dynamicExpertHtml = $renderCards($expertMembers);
          $patternExpert = '/(<section id="tim-ahli-section"[^>]*>.*?<div class="leadership-grid-4">)(.*?)(<\/div>\s*<\/div>\s*<\/section>)/s';
          $originalHtml = preg_replace($patternExpert, '$1' . $dynamicExpertHtml . '$3', $originalHtml);
        }
      }
    } catch (\Throwable $e) {
      // Fallback gracefully ke konten statis tim/index.html jika DB tidak terhubung
    }

    return response($originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  // 2. LGOS: Sistem Operasi Organisasi (/lgos/)
  public function lgos()
  {
    $originalHtml = file_get_contents(base_path('lgos/index.html'));

    try {
      $components = LgosComponent::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->get();

      foreach ($components as $c) {
        if (!empty($c->document_pdf_path) && $c->sort_order <= 5) {
          $order = intval($c->sort_order);
          $pdfUrl = '/storage/' . ltrim($c->document_pdf_path, '/');

          // Match the card with this component order number (e.g. KOMPONEN 01) and inject the uploaded PDF download link
          $pattern = '/(<div class="lgos-num">\s*KOMPONEN\s*0*' . $order . '\s*<\/div>[\s\S]*?<a\s+[^>]*?href=")([^"]*)("[\s\S]*?class="[^"]*btn-lgos-doc download[^"]*")/i';

          if (preg_match($pattern, $originalHtml)) {
            $originalHtml = preg_replace($pattern, '$1' . $pdfUrl . '$3 target="_blank" download', $originalHtml, 1);
          }
        }
      }
    } catch (\Throwable $e) {
      // Graceful fallback to static lgos/index.html
    }

    return response($originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
  }

  // 3. Portfolio (/portofolio/)
  public function portfolio()
  {
    $projects = PortfolioProject::orderByRaw("CASE WHEN status = 'ongoing' THEN 0 ELSE 1 END")
      ->orderBy('sort_order', 'asc')
      ->get();
    $originalHtml = file_get_contents(base_path('portofolio/index.html'));

    if ($projects->isEmpty()) {
      return response($originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    $dynamicCardsHtml = '';
    foreach ($projects as $p) {
      $isOngoing = strtolower($p->status) === 'ongoing' || str_contains(strtolower($p->status), 'berjalan');
      $statusBadge = $isOngoing
        ? '<span class="tag-status berjalan">Sedang Berjalan</span>'
        : '<span class="tag-status selesai">Selesai</span>';

      $catSlug = 'restorasi';
      $catLower = strtolower($p->category ?? '');
      if (str_contains($catLower, 'spasial') || str_contains($catLower, 'pemetaan') || str_contains($catLower, 'intelligence')) {
        $catSlug = 'pemetaan';
      } elseif (str_contains($catLower, 'komoditas') || str_contains($catLower, 'commodity')) {
        $catSlug = 'komoditas';
      } elseif (str_contains($catLower, 'kapasitas') || str_contains($catLower, 'capacity')) {
        $catSlug = 'kapasitas';
      } elseif (str_contains($catLower, 'kebijakan') || str_contains($catLower, 'policy') || str_contains($catLower, 'governance')) {
        $catSlug = 'kebijakan';
      }

      $slug = $p->slug ?: \Illuminate\Support\Str::slug($p->project_title);

      $downloadDoc = $p->document_pdf_path
        ? '<a href="/storage/' . htmlspecialchars($p->document_pdf_path) . '" target="_blank" download style="color:#117710;font-weight:700;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">&darr; Factsheet PDF</a>'
        : '';

      $desc = $p->description ?: ($p->summary ?? '');
      $summary = $p->summary ?: ($p->description ?? '');

      $dynamicCardsHtml .= '
            <div class="proyek-card" data-cat="' . htmlspecialchars($catSlug) . '" data-title="' . htmlspecialchars($p->project_title) . '" data-status="' . ($isOngoing ? 'Sedang Berjalan' : 'Selesai') . '" data-location="' . htmlspecialchars($p->location ?? 'Kalimantan Barat') . '" data-period="' . htmlspecialchars($p->period ?? '-') . '" data-partner="' . htmlspecialchars($p->partner_donor ?? 'YNKI') . '" data-desc="' . htmlspecialchars($desc) . '">
              <div class="proyek-cat-bar ' . htmlspecialchars($catSlug) . '"></div>
              <div class="proyek-body">
                <div class="proyek-tags">
                  <span class="tag-cat ' . htmlspecialchars($catSlug) . '">' . htmlspecialchars($p->category) . '</span>
                  ' . $statusBadge . '
                </div>
                <h4>' . htmlspecialchars($p->project_title) . '</h4>
                <div class="proyek-meta">
                  <span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' . htmlspecialchars($p->location ?? 'Kalimantan Barat') . '</span>
                  <span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>' . htmlspecialchars($p->period ?? '-') . '</span>
                  <span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>' . htmlspecialchars($p->partner_donor ?? 'YNKI') . '</span>
                </div>
                <p class="proyek-desc">' . htmlspecialchars($summary) . '</p>
                <div class="proyek-card-footer">
                  <a href="/portofolio/' . htmlspecialchars($slug) . '" class="btn-read-article">
                    <span>Baca Selengkapnya</span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                  </a>
                  ' . ($downloadDoc ? '<div style="margin-top:8px;text-align:right;">' . $downloadDoc . '</div>' : '') . '
                </div>
              </div>
            </div>';
    }

    $pattern = '/<div class="proyek-grid">.*?<\/div>\s*<\/div>\s*<\/section>/s';
    $replacement = '<div class="proyek-grid">' . $dynamicCardsHtml . '</div></div></section>';
    $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

    return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
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

    $originalHtml = file_get_contents(base_path('transparansi/index.html'));

    $dynamicCardsHtml = '';
    foreach ($reports as $r) {
      $dynamicCardsHtml .= '
            <div class="laporan-card" style="background:#fff;border:1.5px solid #d2e8d1;border-radius:16px;padding:26px;display:flex;flex-direction:column;justify-content:space-between;">
              <div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                  <span style="background:#eaf5ee;color:#0F5132;padding:4px 12px;border-radius:50px;font-size:11.5px;font-weight:700;">' . htmlspecialchars($r->category) . '</span>
                  <span style="font-weight:800;color:#117710;font-size:15px;">' . htmlspecialchars($r->report_year) . '</span>
                </div>
                <h3 style="font-size:16.5px;font-weight:800;color:#0e241b;margin:0 0 10px;line-height:1.4;">' . htmlspecialchars($r->title) . '</h3>
                <p style="font-size:13.5px;color:#536b5f;line-height:1.6;margin:0 0 16px;">' . htmlspecialchars($r->summary ?? '') . '</p>
              </div>
              <div style="border-top:1px solid #eef4f0;padding-top:16px;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:12px;color:#888;">PDF (' . htmlspecialchars($r->file_size ?? 'Dokumen Resmi') . ')</span>
                <a href="/storage/' . htmlspecialchars($r->file_pdf_path) . '" target="_blank" style="background:#117710;color:#fff;padding:8px 18px;border-radius:8px;text-decoration:none;font-weight:700;font-size:12.5px;display:inline-flex;align-items:center;gap:6px;">
                  &darr; Unduh PDF
                </a>
              </div>
            </div>';
    }

    $pattern = '/<div class="laporan-grid">.*?<\/div>\s*<\/div>\s*<\/section>/s';
    $replacement = '<div class="laporan-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-top:30px;">' . $dynamicCardsHtml . '</div></div></section>';
    $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

    return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
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
        : '/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp';

      $cardsHtml .= '
            <div class="pptx-card">
              <div class="pptx-card-thumb">
                <img src="' . htmlspecialchars($imgThumb) . '" alt="' . htmlspecialchars($art->title) . '" onerror="this.src=\'/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp\'">
              </div>
              <div class="pptx-card-body">
                <span class="pptx-cat-tag">' . htmlspecialchars($art->category->category_name ?? 'BERITA PROGRAM') . '</span>
                <h3 class="pptx-card-title">' . htmlspecialchars($art->title) . '</h3>
                <div class="pptx-card-date">' . ($art->published_at ? $art->published_at->translatedFormat('d F Y') : date('d F Y')) . '</div>
                <p class="pptx-card-text">' . htmlspecialchars($art->excerpt ?? '') . '</p>
                <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '" class="btn-read-more">Baca Selengkapnya &rarr;</a>
              </div>
            </div>';
    }

    $pattern = '/<div class="pptx-cards-3col">.*?<\/div>\s*<\/div>\s*<\/section>/s';
    $replacement = '<div class="pptx-cards-3col">' . $cardsHtml . '</div></div></section>';
    $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

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

    $pattern = '/<div class="docs-grid">.*?<\/div>\s*<\/div>\s*<\/section>/s';
    $replacement = '<div class="docs-grid">' . $docsHtml . '</div></div></section>';
    $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

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
        : '/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp';

      $cardsHtml .= '
            <div class="berita-card">
              <div class="berita-thumb">
                <img src="' . htmlspecialchars($imgThumb) . '" alt="' . htmlspecialchars($art->title) . '" onerror="this.src=\'/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp\'">
              </div>
              <div class="berita-body">
                <span class="berita-topic t-bud">' . htmlspecialchars($art->category->category_name ?? 'Budaya & Tradisi') . '</span>
                <h3 class="berita-title">' . htmlspecialchars($art->title) . '</h3>
                <div class="berita-date">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                  ' . ($art->published_at ? $art->published_at->translatedFormat('l, d F Y') : date('d F Y')) . '
                </div>
                <p style="font-size:13.5px;color:#536b5f;line-height:1.6;margin:8px 0 12px;">' . htmlspecialchars($art->excerpt ?? '') . '</p>
                <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '" class="berita-read">Baca Selengkapnya &rarr;</a>
              </div>
            </div>';
    }

    $pattern = '/<div class="berita-grid">.*?<\/div>\s*<\/div>\s*<\/section>/s';
    $replacement = '<div class="berita-grid">' . $cardsHtml . '</div></div></section>';
    $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

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
