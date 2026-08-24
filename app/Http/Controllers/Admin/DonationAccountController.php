<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonationAccount;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DonationAccountController extends Controller
{
    public function index()
    {
        $accounts = DonationAccount::latest()->get();
        return view('admin.accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:150',
            'branch_name' => 'nullable|string|max:100',
            'instructions' => 'nullable|string',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $qrisPath = null;
        if ($request->hasFile('qris_image')) {
            $qrisPath = $request->file('qris_image')->store('qris', 'public');
        }

        $account = DonationAccount::create([
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_holder' => $validated['account_holder'],
            'branch_name' => $validated['branch_name'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'qris_image_path' => $qrisPath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE_ACCOUNT',
            'module' => 'DONATION_ACCOUNT',
            'record_id' => $account->id,
            'details' => "Menambahkan rekening {$account->bank_name} ({$account->account_number})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Rekening donasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $account = DonationAccount::findOrFail($id);
        return view('admin.accounts.edit', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $account = DonationAccount::findOrFail($id);

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:150',
            'branch_name' => 'nullable|string|max:100',
            'instructions' => 'nullable|string',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('qris_image')) {
            $validated['qris_image_path'] = $request->file('qris_image')->store('qris', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $account->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_ACCOUNT',
            'module' => 'DONATION_ACCOUNT',
            'record_id' => $account->id,
            'details' => "Mengubah rekening {$account->bank_name} ({$account->account_number})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Rekening donasi berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $account = DonationAccount::findOrFail($id);
        $name = $account->bank_name . ' (' . $account->account_number . ')';
        $account->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_ACCOUNT',
            'module' => 'DONATION_ACCOUNT',
            'record_id' => $id,
            'details' => "Menghapus rekening {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Rekening donasi berhasil dihapus.');
    }
}
