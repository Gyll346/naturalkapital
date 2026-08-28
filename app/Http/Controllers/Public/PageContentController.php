<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamMember;
use App\Models\LgosComponent;
use App\Models\PortfolioProject;
use App\Models\TransparencyReport;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\MediaStory;
use App\Models\ContactMessage;
use App\Models\Participation;

class PageContentController extends Controller
{
    // 1. Tim & Pengurus YNKI (/tim/)
    public function team()
    {
        $leadershipMembers = TeamMember::where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();

        $originalHtml = file_get_contents(base_path('tim/index.html'));

        $dynamicCardsHtml = '';
        foreach ($leadershipMembers as $member) {
            $photoSrc = $member->photo_path 
                ? '/storage/' . $member->photo_path 
                : '/wp-content/uploads/2026/05/Michael-Eko-for-YNKI__MG_7449-600x600.webp';
            
            $dynamicCardsHtml .= '
            <div class="lead-card">
              <div class="lead-photo-wrap">
                <img src="' . htmlspecialchars($photoSrc) . '" alt="' . htmlspecialchars($member->full_name) . '" onerror="this.src=\'/wp-content/uploads/2026/05/Michael-Eko-for-YNKI__MG_7449-600x600.webp\'" />
              </div>
              <div class="lead-body">
                <h3 class="lead-name">' . htmlspecialchars($member->full_name) . '</h3>
                <div class="lead-role">' . htmlspecialchars($member->position) . '</div>
                <p class="lead-bio">' . htmlspecialchars($member->bio ?? '') . '</p>
              </div>
            </div>';
        }

        $pattern = '/<div class="leadership-grid-4">.*?<\/div>\s*<\/div>\s*<\/section>/s';
        $replacement = '<div class="leadership-grid-4">' . $dynamicCardsHtml . '</div></div></section>';
        $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

        return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // 2. LGOS: Sistem Operasi Organisasi (/lgos/)
    public function lgos()
    {
        $components = LgosComponent::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $originalHtml = file_get_contents(base_path('lgos/index.html'));

        $dynamicRowsHtml = '';
        foreach ($components as $c) {
            $downloadBtn = $c->document_pdf_path
                ? '<a href="/storage/' . htmlspecialchars($c->document_pdf_path) . '" target="_blank" class="download-btn" style="background:#117710;color:#fff;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg> Unduh PDF</a>'
                : '<span style="color:#888;font-size:12px;">Tersedia di Internal</span>';

            $dynamicRowsHtml .= '
            <tr>
              <td>
                <div class="comp-name">' . htmlspecialchars($c->component_name) . '</div>
                <div class="comp-label">' . htmlspecialchars($c->code ?? 'KOMPONEN') . '</div>
              </td>
              <td>
                <div class="comp-desc">' . htmlspecialchars($c->description) . '</div>
              </td>
              <td style="text-align: center; vertical-align: middle;">' . $downloadBtn . '</td>
            </tr>';
        }

        $pattern = '/<tbody>.*?<\/tbody>/s';
        $replacement = '<tbody>' . $dynamicRowsHtml . '</tbody>';
        $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

        return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // 3. Portfolio (/portofolio/)
    public function portfolio()
    {
        $projects = PortfolioProject::orderBy('sort_order', 'asc')->get();
        $originalHtml = file_get_contents(base_path('portofolio/index.html'));

        $dynamicCardsHtml = '';
        foreach ($projects as $p) {
            $coverImg = $p->image_cover_path
                ? '/storage/' . $p->image_cover_path
                : '/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp';

            $downloadDoc = $p->document_pdf_path
                ? '<a href="/storage/' . htmlspecialchars($p->document_pdf_path) . '" target="_blank" style="color:#117710;font-weight:700;font-size:12.5px;text-decoration:none;margin-top:10px;display:inline-block;">&darr; Unduh Factsheet PDF</a>'
                : '';

            $statusBadge = $p->status === 'ongoing'
                ? '<span style="background:#eaf5ee;color:#0F5132;padding:4px 10px;border-radius:50px;font-size:11px;font-weight:700;">Ongoing</span>'
                : '<span style="background:#f0f4f2;color:#5a7364;padding:4px 10px;border-radius:50px;font-size:11px;font-weight:700;">Completed</span>';

            $dynamicCardsHtml .= '
            <div class="proyek-card" data-cat="' . htmlspecialchars($p->category) . '">
              <div class="proyek-img-wrap" style="position:relative;">
                <img src="' . htmlspecialchars($coverImg) . '" alt="' . htmlspecialchars($p->project_title) . '" style="width:100%;height:220px;object-fit:cover;" onerror="this.src=\'/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp\'" />
                <div style="position:absolute;top:12px;left:12px;">' . $statusBadge . '</div>
              </div>
              <div class="proyek-bd" style="padding:22px;">
                <div class="proyek-cat" style="font-size:11px;font-weight:800;color:#117710;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">' . htmlspecialchars($p->category) . '</div>
                <h3 class="proyek-title" style="font-size:16px;font-weight:800;color:#0e241b;margin:0 0 10px;line-height:1.4;">' . htmlspecialchars($p->project_title) . '</h3>
                <p style="font-size:13.5px;color:#536b5f;line-height:1.6;margin:0 0 12px;">' . htmlspecialchars($p->summary) . '</p>
                <div style="font-size:12px;color:#7a9485;border-top:1px solid #eef4f0;padding-top:10px;">
                  <strong>Lokasi:</strong> ' . htmlspecialchars($p->location ?? 'Kalimantan Barat') . ' | <strong>Mitra:</strong> ' . htmlspecialchars($p->partner_donor ?? 'YNKI') . '
                </div>
                ' . $downloadDoc . '
              </div>
            </div>';
        }

        $pattern = '/<div class="proyek-grid">.*?<\/div>\s*<\/div>\s*<\/section>/s';
        $replacement = '<div class="proyek-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-top:30px;">' . $dynamicCardsHtml . '</div></div></section>';
        $renderedHtml = preg_replace($pattern, $replacement, $originalHtml);

        return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
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

