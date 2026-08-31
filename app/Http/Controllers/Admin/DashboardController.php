<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\Article;
use App\Models\LgosComponent;
use App\Models\PortfolioProject;
use App\Models\TransparencyReport;
use App\Models\MediaStory;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Models\Participation;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Modul Tentang Kami
        $totalTeamMembersCount = TeamMember::where('status', 'active')->count();
        $totalLgosCount = LgosComponent::count();
        $totalPortfolioCount = PortfolioProject::count();
        $totalTransparencyCount = TransparencyReport::count();

        // 2. Modul Pustaka & Pengetahuan
        $newsCount = Article::whereHas('category', fn($q) => $q->where('slug', 'news-features'))->count();
        $researchCount = Article::whereHas('category', fn($q) => $q->where('slug', 'penelitian-laporan'))->count();
        $policyCount = Article::whereHas('category', fn($q) => $q->where('slug', 'analisis-kebijakan'))->count();
        $cultureCount = Article::whereHas('category', fn($q) => $q->where('slug', 'perspektif-budaya'))->count();
        $gisCount = Article::whereHas('category', fn($q) => $q->where('slug', 'data-spasial-dan-gis'))->count();
        $mediaStoriesCount = MediaStory::count();
        $totalArticlesCount = Article::count();

        // 3. Interaksi & Partisipasi
        $totalContactMessagesCount = 0;
        $unreadContactMessagesCount = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('contact_messages')) {
            try {
                $totalContactMessagesCount = ContactMessage::count();
                $unreadContactMessagesCount = ContactMessage::where('is_read', false)->count();
            } catch (\Throwable $e) {}
        }

        $totalParticipationsCount = 0;
        $unreadParticipationsCount = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('participations')) {
            try {
                $totalParticipationsCount = Participation::count();
                $unreadParticipationsCount = Participation::where('is_read', false)->count();
            } catch (\Throwable $e) {}
        }

        // 4. Hitung Total Dokumen File PDF & Media Terunggah
        $totalPdfDocsCount = TransparencyReport::whereNotNull('file_pdf_path')->count()
            + LgosComponent::whereNotNull('document_pdf_path')->count()
            + PortfolioProject::whereNotNull('document_pdf_path')->count()
            + Article::whereNotNull('attachment_pdf_path')->count();

        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalTeamMembersCount',
            'totalLgosCount',
            'totalPortfolioCount',
            'totalTransparencyCount',
            'newsCount',
            'researchCount',
            'policyCount',
            'cultureCount',
            'gisCount',
            'mediaStoriesCount',
            'totalArticlesCount',
            'totalContactMessagesCount',
            'unreadContactMessagesCount',
            'totalParticipationsCount',
            'unreadParticipationsCount',
            'totalPdfDocsCount',
            'recentLogs'
        ));
    }
}
