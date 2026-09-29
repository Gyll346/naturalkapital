<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PublicDonationController;
use App\Http\Controllers\Public\PublicArticleController;
use App\Http\Controllers\Public\PublicTeamController;
use App\Http\Controllers\Public\PageContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonationAccountController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LgosController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\TransparencyController;
use App\Http\Controllers\Admin\MediaStoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ParticipationController;
use App\Http\Controllers\Admin\MediaCoverageController;

/*
|--------------------------------------------------------------------------
| 1. Rute Publik Utama & 10 Subhalaman Dinamis Database YNKI
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::any('/wp-admin/admin-ajax.php', fn() => response()->json(['success' => true, 'data' => []]));
Route::get('/donasi', fn() => redirect('/kontak-kami'))->name('public.donation');
Route::get('/artikel-cms', [PublicArticleController::class, 'index'])->name('public.article.index');
Route::get('/artikel-cms/{slug}', [PublicArticleController::class, 'show'])->name('public.article.show');

// Subhalaman Dinamis Database (Tentang Kami)
Route::get('/sejarah-visi-misi', fn() => view('public.sejarah-visi-misi'))->name('public.sejarah_visi_misi');
Route::get('/sejarah-visi-misi/index.html', fn() => redirect('/sejarah-visi-misi'));
Route::get('/tim', [PageContentController::class, 'team'])->name('public.team');
Route::get('/tim-ynki', [PageContentController::class, 'team'])->name('public.tim');
Route::get('/lgos', [PageContentController::class, 'lgos'])->name('public.lgos');
Route::get('/lgos/index.html', [PageContentController::class, 'lgos']);
Route::get('/portofolio', [PageContentController::class, 'portfolio'])->name('public.portfolio');
Route::get('/portofolio/index.html', [PageContentController::class, 'portfolio']);
Route::get('/portofolio/{slug}', [PageContentController::class, 'portfolioDetail'])->name('public.portfolio.show');
Route::get('/transparansi', [PageContentController::class, 'transparansi'])->name('public.transparansi');
Route::get('/annual-report', [PageContentController::class, 'transparansi'])->name('public.annual_report');

// Subhalaman Dampak & Pembelajaran
Route::get('/dampak', fn() => view('public.dampak'))->name('public.dampak');
Route::get('/dampak/index.html', fn() => redirect('/dampak'));
Route::get('/kisah-perubahan', [PageContentController::class, 'kisahPerubahan'])->name('public.kisah_perubahan');
Route::get('/kisah-perubahan/index.html', fn() => redirect('/kisah-perubahan'));
Route::get('/liputan-media', [PageContentController::class, 'liputanMedia'])->name('public.liputan_media');
Route::get('/liputan-media/index.html', fn() => redirect('/liputan-media'));

// Subhalaman Program Kami
Route::get('/landscape-governance', fn() => view('public.landscape-governance'))->name('public.landscape_governance');
Route::get('/landscape-governance/index.html', fn() => redirect('/landscape-governance'));
Route::get('/natural-capital', fn() => view('public.natural-capital'))->name('public.natural_capital');
Route::get('/natural-capital/index.html', fn() => redirect('/natural-capital'));
Route::get('/sustainable-commodity', fn() => view('public.sustainable-commodity'))->name('public.sustainable_commodity');
Route::get('/sustainable-commodity/index.html', fn() => redirect('/sustainable-commodity'));
Route::get('/landscape-intelligence', fn() => view('public.landscape-intelligence'))->name('public.landscape_intelligence');
Route::get('/landscape-intelligence/index.html', fn() => redirect('/landscape-intelligence'));
Route::get('/institutional-partnership', fn() => view('public.institutional-partnership'))->name('public.institutional_partnership');
Route::get('/institutional-partnership/index.html', fn() => redirect('/institutional-partnership'));


// Subhalaman Dinamis Database (Literasi & Pengetahuan)
Route::get('/news-features', [PageContentController::class, 'newsFeatures'])->name('public.news_features');
Route::get('/kategori/news-features', [PageContentController::class, 'newsFeatures']);
Route::get('/kategori/news-features/{slug}', function ($slug) {
    // Check if static article exists under resources/legacy_articles
    $file = resource_path("legacy_articles/news-features/{$slug}/index.html");
    if (file_exists($file)) {
        return response(file_get_contents($file), 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }
    return redirect('/news-features');
});
Route::get('/news-features/{slug}', function ($slug) {
    return redirect('/kategori/news-features/' . $slug);
});
Route::get('/penelitian-laporan', [PageContentController::class, 'researchReports'])->name('public.research_reports');
Route::get('/analisis-kebijakan', [PageContentController::class, 'policyAnalysis'])->name('public.policy_analysis');
Route::get('/perspektif-budaya', [PageContentController::class, 'culturalPerspective'])->name('public.cultural_perspective');
Route::get('/kategori/perspektif-budaya', [PageContentController::class, 'culturalPerspective']);
Route::get('/kategori/perspektif-budaya/{slug}', function ($slug) {
    // Check if static article exists under resources/legacy_articles
    $file = resource_path("legacy_articles/perspektif-budaya/{$slug}/index.html");
    if (file_exists($file)) {
        return response(file_get_contents($file), 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }
    return redirect('/perspektif-budaya');
});
Route::get('/perspektif-budaya/{slug}', function ($slug) {
    return redirect('/kategori/perspektif-budaya/' . $slug);
});
Route::get('/data-spasial-dan-gis', [PageContentController::class, 'spatialGis'])->name('public.spatial_gis');
Route::get('/data-spasial-gis', [PageContentController::class, 'spatialGis']);
Route::get('/story-foto-video', [PageContentController::class, 'mediaStories'])->name('public.media_stories');
Route::get('/stori-foto-video', [PageContentController::class, 'mediaStories']);

// Halaman Khusus Ikut Serta & Kontak Kami
Route::get('/ikut-terlibat', [PageContentController::class, 'ikutTerlibat'])->name('public.ikut_terlibat');
Route::get('/ikut-serta', [PageContentController::class, 'ikutTerlibat'])->name('public.ikut_serta');
Route::post('/ikut-serta', [PageContentController::class, 'storeParticipation'])->name('public.ikut_serta.store');
Route::get('/kontak-kami', [PageContentController::class, 'kontakKami'])->name('public.kontak_kami');
Route::get('/hubungi-kami', [PageContentController::class, 'kontakKami'])->name('public.hubungi_kami');
Route::post('/kontak-kami', [PageContentController::class, 'storeContactMessage'])->name('public.kontak_kami.store');


/*
|--------------------------------------------------------------------------
| 2. Hidden Login Route (Tanpa tombol login di navbar/footer publik)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| 3. Panel Admin CMS (Dilindungi Auth Middleware)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // CRUD Rekening Bank / QRIS Donasi
    Route::resource('accounts', DonationAccountController::class);
    
    // Verifikasi Transaksi Donasi
    Route::get('donations', [DonationController::class, 'index'])->name('donations.index');
    Route::get('donations/{id}', [DonationController::class, 'show'])->name('donations.show');
    Route::patch('donations/{id}/verify', [DonationController::class, 'verify'])->name('donations.verify');
    Route::patch('donations/{id}/reject', [DonationController::class, 'reject'])->name('donations.reject');

    // Pesan Masuk (Kontak Kami)
    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{id}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::patch('contact-messages/{id}/read', [ContactMessageController::class, 'markAsRead'])->name('contact-messages.read');
    Route::patch('contact-messages/{id}/unread', [ContactMessageController::class, 'markAsUnread'])->name('contact-messages.unread');
    Route::delete('contact-messages/{id}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    // Formulir Partisipasi (Ikut Serta)
    Route::get('participations', [ParticipationController::class, 'index'])->name('participations.index');
    Route::get('participations/{id}', [ParticipationController::class, 'show'])->name('participations.show');
    Route::patch('participations/{id}/read', [ParticipationController::class, 'markAsRead'])->name('participations.read');
    Route::patch('participations/{id}/unread', [ParticipationController::class, 'markAsUnread'])->name('participations.unread');
    Route::delete('participations/{id}', [ParticipationController::class, 'destroy'])->name('participations.destroy');
    
    // CRUD Tim & Pengurus YNKI
    Route::resource('teams', TeamMemberController::class);
    
    // CRUD Komponen Pendukung LGOS
    Route::resource('lgos', LgosController::class);
    
    // CRUD Portfolio Program & Proyek
    Route::resource('portfolios', PortfolioController::class);
    
    // CRUD Dokumen Transparansi & Kebijakan
    Route::resource('transparency', TransparencyController::class);
    
    // CRUD Story Foto & Video Lapangan
    Route::resource('media-stories', MediaStoryController::class);
    
    // CRUD Liputan Media Eksternal
    Route::resource('media-coverages', MediaCoverageController::class);
    
    // CRUD Artikel Berita & Dokumen Riset
    Route::resource('articles', ArticleController::class);
    
    // Pelaporan & Ekspor
    Route::get('reports/donations/excel', [ReportController::class, 'exportDonationsExcel'])->name('reports.donations.excel');
    Route::get('reports/donations/pdf', [ReportController::class, 'exportDonationsPdf'])->name('reports.donations.pdf');
});

/*
|--------------------------------------------------------------------------
| 4. Universal Handler untuk Seluruh Halaman Menu Asli YNKI
|--------------------------------------------------------------------------
| Melayani seluruh subpage menu asli: Sejarah, Tim, LGOS, Program, Portfolio,
| Transparansi, Kisah Perubahan, Riset, Publikasi, dsb. secara otomatis.
*/
Route::fallback(function (\Illuminate\Http\Request $request) {
    $path = trim($request->path(), '/');

    // Daftar prioritas pengecekan file HTML asli
    $candidates = [
        base_path($path . '/index.html'),
        base_path($path . '.html'),
        base_path($path),
    ];

    // Jika path diawali 'kategori/', coba cek juga tanpa prefix 'kategori/'
    if (str_starts_with($path, 'kategori/')) {
        $subPath = substr($path, strlen('kategori/'));
        $candidates[] = base_path($subPath . '/index.html');
        $candidates[] = base_path($subPath . '.html');
        $candidates[] = base_path($subPath);
    }

    foreach ($candidates as $file) {
        if (file_exists($file) && !is_dir($file)) {
            $content = file_get_contents($file);
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $mimes = [
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                'webp' => 'image/webp',
                'css' => 'text/css; charset=UTF-8',
                'js' => 'application/javascript; charset=UTF-8',
                'json' => 'application/json',
                'pdf' => 'application/pdf',
                'html' => 'text/html; charset=UTF-8',
            ];
            $mime = $mimes[$ext] ?? 'text/html; charset=UTF-8';
            return response($content, 200)
                ->header('Content-Type', $mime)
                ->header('Access-Control-Allow-Origin', '*');
        }
    }

    abort(404);
});
