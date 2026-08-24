<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationAccount;
use App\Models\TeamMember;
use App\Models\Article;
use App\Models\LgosComponent;
use App\Models\PortfolioProject;
use App\Models\TransparencyReport;
use App\Models\MediaStory;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Keuangan & Donasi
        $totalDonationAmount = Donation::where('status', 'verified')->sum('amount');
        $pendingDonationsCount = Donation::where('status', 'pending')->count();
        $verifiedDonationsCount = Donation::where('status', 'verified')->count();

        // 2. Modul Tentang Kami
        $totalTeamMembersCount = TeamMember::where('status', 'active')->count();
        $totalLgosCount = LgosComponent::count();
        $totalPortfolioCount = PortfolioProject::count();
        $totalTransparencyCount = TransparencyReport::count();

        // 3. Modul Pustaka & Pengetahuan
        $newsCount = Article::whereHas('category', fn($q) => $q->where('slug', 'news-features'))->count();
        $researchCount = Article::whereHas('category', fn($q) => $q->where('slug', 'penelitian-laporan'))->count();
        $policyCount = Article::whereHas('category', fn($q) => $q->where('slug', 'analisis-kebijakan'))->count();
        $cultureCount = Article::whereHas('category', fn($q) => $q->where('slug', 'perspektif-budaya'))->count();
        $gisCount = Article::whereHas('category', fn($q) => $q->where('slug', 'data-spasial-dan-gis'))->count();
        $mediaStoriesCount = MediaStory::count();
        $totalArticlesCount = Article::count();

        // 4. Hitung Total Dokumen File PDF & Media Terunggah
        $totalPdfDocsCount = TransparencyReport::whereNotNull('file_pdf_path')->count()
            + LgosComponent::whereNotNull('document_pdf_path')->count()
            + PortfolioProject::whereNotNull('document_pdf_path')->count()
            + Article::whereNotNull('attachment_pdf_path')->count();

        $recentDonations = Donation::with(['account', 'program'])
            ->latest()
            ->take(5)
            ->get();

        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalDonationAmount',
            'pendingDonationsCount',
            'verifiedDonationsCount',
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
            'totalPdfDocsCount',
            'recentDonations',
            'recentLogs'
        ));
    }
}
