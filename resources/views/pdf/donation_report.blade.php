<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penerimaan Donasi Resmi YNKI</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1b2e23; }
        .header { text-align: center; border-bottom: 2px solid #0F5132; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; margin: 0 0 4px; color: #0F5132; text-transform: uppercase; }
        .header p { font-size: 11px; margin: 0; color: #5a7364; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #0F5132; color: #ffffff; padding: 8px 6px; font-size: 11px; text-align: left; }
        td { padding: 8px 6px; border-bottom: 1px solid #e1ebe5; font-size: 11px; }
        .total-row { background: #e8f5ed; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Yayasan Natural Kapital Indonesia (YNKI)</h1>
        <p>Laporan Resmi Rekapitulasi Donasi Terverifikasi | Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Nama Donatur</th>
                <th>Program Lanskap</th>
                <th>Rekening</th>
                <th>Jumlah (IDR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($donations as $index => $donation)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $donation->invoice_number }}</strong></td>
                    <td>{{ $donation->created_at->format('d/m/Y') }}</td>
                    <td>{{ $donation->is_anonymous ? 'Hamba Allah (Anonim)' : $donation->donor_name }}</td>
                    <td>{{ $donation->program->program_name ?? 'Donasi Umum' }}</td>
                    <td>{{ $donation->account->bank_name ?? '-' }}</td>
                    <td>Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">TOTAL DONASI TERVERIFIKASI:</td>
                <td>Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Pontianak, {{ date('d F Y') }}<br><strong>Bendahara & Manajemen YNKI</strong></p>
    </div>
</body>
</html>
