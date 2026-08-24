<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function exportDonationsExcel(Request $request)
    {
        $donations = Donation::with(['account', 'program'])->latest()->get();
        
        $filename = 'Laporan_Donasi_YNKI_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($donations) {
            $file = fopen('php://output', 'w');
            // Header CSV
            fputcsv($file, ['No. Invoice', 'Tanggal', 'Nama Donatur', 'Email', 'No Telepon', 'Program', 'Rekening Tujuan', 'Jumlah (IDR)', 'Status', 'Catatan']);

            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->invoice_number,
                    $donation->created_at->format('Y-m-d H:i'),
                    $donation->is_anonymous ? 'Hamba Allah (Anonim)' : $donation->donor_name,
                    $donation->donor_email,
                    $donation->donor_phone ?? '-',
                    $donation->program->program_name ?? 'Donasi Umum',
                    $donation->account->bank_name ?? '-',
                    $donation->amount,
                    strtoupper($donation->status),
                    $donation->donor_notes ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportDonationsPdf(Request $request)
    {
        $donations = Donation::with(['account', 'program'])
            ->where('status', 'verified')
            ->latest()
            ->get();

        $totalAmount = $donations->sum('amount');
        $pdf = Pdf::loadView('pdf.donation_report', compact('donations', 'totalAmount'));
        return $pdf->download('Laporan_Donasi_Resmi_YNKI_' . date('Ymd') . '.pdf');
    }
}
