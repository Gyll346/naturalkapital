<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamCategory;
use App\Models\TeamMember;
use App\Models\LgosComponent;
use App\Models\PortfolioProject;
use App\Models\TransparencyReport;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\MediaStory;

class PageContentController extends Controller
{
    // 1. Tim & Pengurus YNKI (/tim/)
    public function team()
    {
        $leadershipMembers = TeamMember::where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->get();

        $originalHtml = file_get_contents(base_path('tim/index.html'));

        // Generate dynamic HTML cards for Dewan Pengurus & Kepemimpinan
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

        // Replace the leadership cards in original HTML
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

        // Generate dynamic rows for 5 Komponen Pendukung
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

    // 5. News & Features (/news-features/)
    public function newsFeatures()
    {
        $category = ArticleCategory::where('slug', 'news-features')->first();
        $articles = Article::where('status', 'published')
            ->when($category, fn($q) => $q->where('category_id', $category->id))
            ->orderBy('published_at', 'desc')
            ->get();

        $originalHtml = file_get_contents(base_path('news-features/index.html'));

        $articlesHtml = $this->renderArticleCards($articles, false);
        $renderedHtml = $this->injectArticlesIntoHtml($originalHtml, $articlesHtml);

        return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // 6. Penelitian & Laporan (/penelitian-laporan/)
    public function researchReports()
    {
        $category = ArticleCategory::where('slug', 'penelitian-laporan')->first();
        $articles = Article::where('status', 'published')
            ->when($category, fn($q) => $q->where('category_id', $category->id))
            ->orderBy('published_at', 'desc')
            ->get();

        $originalHtml = file_get_contents(base_path('penelitian-laporan/index.html'));
        $articlesHtml = $this->renderArticleCards($articles, true);
        $renderedHtml = $this->injectArticlesIntoHtml($originalHtml, $articlesHtml);

        return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // 7. Analisis & Kebijakan (/analisis-kebijakan/)
    public function policyAnalysis()
    {
        $category = ArticleCategory::where('slug', 'analisis-kebijakan')->first();
        $articles = Article::where('status', 'published')
            ->when($category, fn($q) => $q->where('category_id', $category->id))
            ->orderBy('published_at', 'desc')
            ->get();

        $originalHtml = file_get_contents(base_path('analisis-kebijakan/index.html'));
        $articlesHtml = $this->renderArticleCards($articles, true);
        $renderedHtml = $this->injectArticlesIntoHtml($originalHtml, $articlesHtml);

        return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // 8. Perspektif Budaya (/perspektif-budaya/)
    public function culturalPerspective()
    {
        $category = ArticleCategory::where('slug', 'perspektif-budaya')->first();
        $articles = Article::where('status', 'published')
            ->when($category, fn($q) => $q->where('category_id', $category->id))
            ->orderBy('published_at', 'desc')
            ->get();

        $originalHtml = file_get_contents(base_path('perspektif-budaya/index.html'));
        $articlesHtml = $this->renderArticleCards($articles, false);
        $renderedHtml = $this->injectArticlesIntoHtml($originalHtml, $articlesHtml);

        return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // 9. Data Spasial & GIS (/data-spasial-dan-gis/)
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
        $articlesHtml = $this->renderArticleCards($articles, true);
        $renderedHtml = $this->injectArticlesIntoHtml($originalHtml, $articlesHtml);

        return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // 10. Story Foto & Video (/story-foto-video/ & /stori-foto-video/)
    public function mediaStories()
    {
        $stories = MediaStory::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $path = file_exists(base_path('story-foto-video/index.html'))
            ? base_path('story-foto-video/index.html')
            : base_path('stori-foto-video/index.html');

        $originalHtml = file_get_contents($path);

        $photosHtml = '';
        $videosHtml = '';

        foreach ($stories as $s) {
            if ($s->media_type === 'photo') {
                $imgSrc = $s->image_path ? '/storage/' . $s->image_path : '/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp';
                $photosHtml .= '
                <div class="story-photo-card" style="background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.06);border:1px solid #d2e8d1;">
                  <img src="' . htmlspecialchars($imgSrc) . '" alt="' . htmlspecialchars($s->title) . '" style="width:100%;height:230px;object-fit:cover;" onerror="this.src=\'/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp\'">
                  <div style="padding:18px;">
                    <span style="background:#eaf5ee;color:#0F5132;padding:3px 10px;border-radius:50px;font-size:11px;font-weight:700;">' . htmlspecialchars($s->category) . '</span>
                    <h4 style="font-size:15px;font-weight:800;color:#0e241b;margin:8px 0 6px;">' . htmlspecialchars($s->title) . '</h4>
                    <p style="font-size:13px;color:#536b5f;line-height:1.5;margin:0 0 10px;">' . htmlspecialchars($s->caption ?? '') . '</p>
                    <small style="color:#888;font-size:11.5px;">Lokasi: ' . htmlspecialchars($s->location ?? 'Kalbar') . ' | ' . htmlspecialchars($s->photographer_credits ?? 'YNKI') . '</small>
                  </div>
                </div>';
            } else {
                $embedUrl = $s->youtube_url;
                if (str_contains($embedUrl, 'watch?v=')) {
                    $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                }
                $videosHtml .= '
                <div class="story-video-card" style="background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.06);border:1px solid #d2e8d1;">
                  <iframe src="' . htmlspecialchars($embedUrl) . '" style="width:100%;height:230px;border:none;" allowfullscreen></iframe>
                  <div style="padding:18px;">
                    <span style="background:#fef7e6;color:#b45309;padding:3px 10px;border-radius:50px;font-size:11px;font-weight:700;">Video Lapangan</span>
                    <h4 style="font-size:15px;font-weight:800;color:#0e241b;margin:8px 0 6px;">' . htmlspecialchars($s->title) . '</h4>
                    <p style="font-size:13px;color:#536b5f;line-height:1.5;margin:0;">' . htmlspecialchars($s->caption ?? '') . '</p>
                  </div>
                </div>';
            }
        }

        // Replace photo & video sections
        $patternPhoto = '/<div class="foto-grid">.*?<\/div>\s*<\/div>\s*<\/section>/s';
        $replacementPhoto = '<div class="foto-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;margin-top:24px;">' . $photosHtml . '</div></div></section>';
        
        $renderedHtml = preg_replace($patternPhoto, $replacementPhoto, $originalHtml);

        $patternVideo = '/<div class="video-grid">.*?<\/div>\s*<\/div>\s*<\/section>/s';
        $replacementVideo = '<div class="video-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:24px;margin-top:24px;">' . $videosHtml . '</div></div></section>';
        
        if ($renderedHtml) {
            $renderedHtml = preg_replace($patternVideo, $replacementVideo, $renderedHtml);
        }

        return response($renderedHtml ?: $originalHtml, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // Helper: Render Article Cards
    private function renderArticleCards($articles, $hasPdfDownload = false)
    {
        $html = '';
        foreach ($articles as $art) {
            $cover = $art->featured_image_path
                ? '/storage/' . $art->featured_image_path
                : '/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp';

            $pdfDownload = ($hasPdfDownload && $art->attachment_pdf_path)
                ? '<a href="/storage/' . htmlspecialchars($art->attachment_pdf_path) . '" target="_blank" style="background:#117710;color:#fff;padding:6px 14px;border-radius:6px;text-decoration:none;font-weight:700;font-size:12px;display:inline-flex;align-items:center;gap:4px;">&darr; Unduh PDF</a>'
                : '<a href="/artikel-cms/' . htmlspecialchars($art->slug) . '" style="color:#117710;font-weight:700;font-size:13px;text-decoration:none;">Baca Selengkapnya &rarr;</a>';

            $html .= '
            <article class="fusion-post-grid post" style="background:#fff;border-radius:14px;overflow:hidden;border:1.5px solid #d2e8d1;box-shadow:0 6px 20px rgba(0,0,0,0.04);margin-bottom:24px;display:flex;flex-direction:column;justify-content:space-between;">
              <div>
                <img src="' . htmlspecialchars($cover) . '" alt="' . htmlspecialchars($art->title) . '" style="width:100%;height:200px;object-fit:cover;" onerror="this.src=\'/wp-content/uploads/2026/05/kebijakan-tata-ruang-dan-hutan.webp\'">
                <div style="padding:22px;">
                  <span style="background:#eaf5ee;color:#0F5132;padding:3px 10px;border-radius:50px;font-size:11px;font-weight:700;">' . htmlspecialchars($art->category->category_name ?? 'Publikasi') . '</span>
                  <h3 style="font-size:16px;font-weight:800;color:#0e241b;margin:10px 0 8px;line-height:1.4;">
                    <a href="/artikel-cms/' . htmlspecialchars($art->slug) . '" style="color:#0e241b;text-decoration:none;">' . htmlspecialchars($art->title) . '</a>
                  </h3>
                  <p style="font-size:13.5px;color:#536b5f;line-height:1.6;margin:0 0 12px;">' . htmlspecialchars($art->excerpt ?? '') . '</p>
                </div>
              </div>
              <div style="padding:14px 22px;border-top:1px solid #eef4f0;display:flex;justify-content:space-between;align-items:center;">
                <small style="color:#888;font-size:12px;">' . ($art->published_at ? $art->published_at->format('d M Y') : 'Baru') . '</small>
                ' . $pdfDownload . '
              </div>
            </article>';
        }
        return $html;
    }

    // Helper: Inject dynamic articles into HTML container
    private function injectArticlesIntoHtml($html, $dynamicArticlesHtml)
    {
        // Try common article grid containers
        $patterns = [
            '/<div class="fusion-posts-container.*?<\/div>\s*<\/div>/s',
            '/<div class="article-grid">.*?<\/div>\s*<\/div>/s',
            '/<div class="pustaka-grid">.*?<\/div>\s*<\/div>/s',
            '/<div id="posts-container".*?<\/div>/s',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html)) {
                $replacement = '<div class="fusion-posts-container" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-top:30px;">' . $dynamicArticlesHtml . '</div>';
                return preg_replace($pattern, $replacement, $html);
            }
        }

        return $html;
    }
}
