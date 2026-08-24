<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationAccount;
use App\Models\TeamMember;
use App\Models\Article;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDonationAmount = Donation::where('status', 'verified')->sum('amount');
        $pendingDonationsCount = Donation::where('status', 'pending')->count();
        $verifiedDonationsCount = Donation::where('status', 'verified')->count();
        $totalArticlesCount = Article::count();
        $totalTeamMembersCount = TeamMember::where('status', 'active')->count();

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
            'totalArticlesCount',
            'totalTeamMembersCount',
            'recentDonations',
            'recentLogs'
        ));
    }
}
