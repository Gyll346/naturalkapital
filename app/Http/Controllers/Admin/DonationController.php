<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Donation::with(['account', 'program'])->latest();

        if ($status && in_array($status, ['pending', 'verified', 'rejected'])) {
            $query->where('status', $status);
        }

        $donations = $query->paginate(15);
        return view('admin.donations.index', compact('donations', 'status'));
    }

    public function show($id)
    {
        $donation = Donation::with(['account', 'program', 'verifier'])->findOrFail($id);
        return view('admin.donations.show', compact('donation'));
    }

    public function verify(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);
        $donation->update([
            'status' => 'verified',
            'verified_by_user_id' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        if ($donation->program) {
            $donation->program->increment('collected_amount', $donation->amount);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'VERIFY_DONATION',
            'module' => 'DONATION',
            'record_id' => $donation->id,
            'details' => "Memverifikasi donasi {$donation->invoice_number} senilai Rp " . number_format($donation->amount, 0, ',', '.'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', "Donasi #{$donation->invoice_number} berhasil diverifikasi.");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $donation = Donation::findOrFail($id);
        $donation->update([
            'status' => 'rejected',
            'verified_by_user_id' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'REJECT_DONATION',
            'module' => 'DONATION',
            'record_id' => $donation->id,
            'details' => "Menolak donasi {$donation->invoice_number} dengan alasan: " . $request->input('rejection_reason'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('warning', "Donasi #{$donation->invoice_number} telah ditolak.");
    }
}
