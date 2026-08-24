<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\DonationAccount;
use App\Models\DonationProgram;
use Illuminate\Support\Str;

class PublicDonationController extends Controller
{
    public function index()
    {
        $accounts = DonationAccount::where('is_active', true)->get();
        $programs = DonationProgram::where('is_active', true)->get();

        return view('public.donation', compact('accounts', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:150',
            'donor_email' => 'required|email|max:100',
            'donor_phone' => 'nullable|string|max:30',
            'is_anonymous' => 'nullable|boolean',
            'amount' => 'required|numeric|min:10000',
            'program_id' => 'nullable|exists:donation_programs,id',
            'donation_account_id' => 'required|exists:donation_accounts,id',
            'donor_notes' => 'nullable|string|max:500',
            'transfer_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $invoiceNumber = 'INV-YNKI-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $path = null;
        if ($request->hasFile('transfer_proof')) {
            $path = $request->file('transfer_proof')->store('donations', 'public');
        }

        Donation::create([
            'invoice_number' => $invoiceNumber,
            'donor_name' => $validated['donor_name'],
            'donor_email' => $validated['donor_email'],
            'donor_phone' => $validated['donor_phone'] ?? null,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'amount' => $validated['amount'],
            'program_id' => $validated['program_id'] ?? null,
            'donation_account_id' => $validated['donation_account_id'],
            'transfer_proof_path' => $path,
            'donor_notes' => $validated['donor_notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('public.donation')->with('success', "Terima kasih! Donasi Anda (#{$invoiceNumber}) telah diterima dan sedang menunggu verifikasi tim kami.");
    }
}
