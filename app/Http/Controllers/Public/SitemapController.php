<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\PortfolioProject;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml for search engines (Google, Bing, etc.)
     */
    public function index(): Response
    {
        $baseUrl = rtrim(config('app.url', 'https://naturalkapital.or.id'), '/');
        // Ensure https if local default http://localhost
        if (str_contains($baseUrl, 'localhost')) {
            $baseUrl = 'https://naturalkapital.or.id';
        }

        // 1. Static Public Pages
        $staticPages = [
            // [path, priority, changefreq]
            ['/', '1.0', 'daily'],
            ['/sejarah-visi-misi', '0.9', 'monthly'],
            ['/tim', '0.9', 'monthly'],
            ['/lgos', '0.9', 'monthly'],
            ['/landscape-governance', '0.9', 'monthly'],
            ['/natural-capital', '0.9', 'monthly'],
            ['/sustainable-commodity', '0.9', 'monthly'],
            ['/landscape-intelligence', '0.9', 'monthly'],
            ['/institutional-partnership', '0.9', 'monthly'],
            ['/dampak', '0.8', 'weekly'],
            ['/kisah-perubahan', '0.8', 'weekly'],
            ['/liputan-media', '0.8', 'weekly'],
            ['/portofolio', '0.8', 'weekly'],
            ['/transparansi', '0.8', 'monthly'],
            ['/news-features', '0.9', 'daily'],
            ['/penelitian-laporan', '0.8', 'monthly'],
            ['/analisis-kebijakan', '0.8', 'monthly'],
            ['/perspektif-budaya', '0.8', 'monthly'],
            ['/data-spasial-dan-gis', '0.7', 'monthly'],
            ['/story-foto-video', '0.7', 'weekly'],
            ['/kontak-kami', '0.8', 'monthly'],
            ['/ikut-terlibat', '0.8', 'monthly'],
        ];

        // 2. Dynamic Articles (from database)
        $articles = [];
        try {
            $articles = Article::where('status', 'published')
                ->latest('published_at')
                ->get(['slug', 'updated_at', 'published_at']);
        } catch (\Throwable $e) {
            // fallback if DB not ready
        }

        // 3. Dynamic Portfolio Projects (from database)
        $projects = [];
        try {
            $projects = PortfolioProject::where('status', 'published')
                ->latest('updated_at')
                ->get(['slug', 'updated_at']);
        } catch (\Throwable $e) {
            // fallback
        }

        // 4. Legacy Static Articles (under resources/legacy_articles/news-features)
        $legacyNewsFeatures = [];
        $legacyDir = resource_path('legacy_articles/news-features');
        if (is_dir($legacyDir)) {
            $folders = array_diff(scandir($legacyDir), ['.', '..']);
            foreach ($folders as $folder) {
                if (is_dir($legacyDir . '/' . $folder)) {
                    $legacyNewsFeatures[] = $folder;
                }
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

        // Render Static Pages
        $today = date('Y-m-d');
        foreach ($staticPages as $page) {
            $loc = $baseUrl . $page[0];
            $priority = $page[1];
            $changefreq = $page[2];

            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
            $xml .= "    <lastmod>{$today}</lastmod>\n";
            $xml .= "    <changefreq>{$changefreq}</changefreq>\n";
            $xml .= "    <priority>{$priority}</priority>\n";
            $xml .= "  </url>\n";
        }

        // Render Dynamic Database Articles
        foreach ($articles as $article) {
            $loc = $baseUrl . '/artikel-cms/' . $article->slug;
            $lastmod = ($article->updated_at ?? $article->published_at ?? now())->format('Y-m-d');

            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        // Render Legacy Articles
        foreach ($legacyNewsFeatures as $slug) {
            $loc = $baseUrl . '/news-features/' . $slug;
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
            $xml .= "    <lastmod>{$today}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        // Render Dynamic Portfolio Projects
        foreach ($projects as $project) {
            $loc = $baseUrl . '/portofolio/' . $project->slug;
            $lastmod = ($project->updated_at ?? now())->format('Y-m-d');

            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
