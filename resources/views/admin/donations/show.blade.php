@extends('layouts.admin')

@section('title', 'Detail Donasi #' . $donation->invoice_number)

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.donations.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Daftar Donasi
            </a>
        </div>

        @if (session('success'))
            <div style="background: #dafbe1; color: #1a7f37; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('warning'))
            <div style="background: #fff8c5; color: #9a6700; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                {{ session('warning') }}
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 30px;">
            <!-- Rincian Donasi -->
            <div class="card-table">
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: var(--primary-dark); margin-bottom: 20px;">
                    Rincian Transaksi
                </h3>

                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-muted); width: 40%;">Nomor Invoice</td>
                        <td style="padding: 10px 0;"><strong>{{ $donation->invoice_number }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-muted);">Nama Donatur</td>
                        <td style="padding: 10px 0;">{{ $donation->donor_name }} {{ $donation->is_anonymous ? '(Tampil Anonim)' : '' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-muted);">Email Donatur</td>
                        <td style="padding: 10px 0;">{{ $donation->donor_email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-muted);">Nomor Telepon/WA</td>
                        <td style="padding: 10px 0;">{{ $donation->donor_phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-muted);">Program Donasi</td>
                        <td style="padding: 10px 0;">{{ $donation->program->program_name ?? 'Donasi Umum Lanskap YNKI' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-muted);">Rekening Tujuan</td>
                        <td style="padding: 10px 0;">{{ $donation->account->bank_name ?? '-' }} ({{ $donation->account->account_number ?? '-' }})</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-muted);">Jumlah Transfer</td>
                        <td style="padding: 10px 0;"><span style="font-size: 18px; font-weight: 800; color: var(--primary);">Rp {{ number_format($donation->amount, 0, ',', '.') }}</span></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-muted);">Pesan Donatur</td>
                        <td style="padding: 10px 0;"><em>"{{ $donation->donor_notes ?? 'Tidak ada catatan' }}"</em></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-muted);">Status Saat Ini</td>
                        <td style="padding: 10px 0;">
                            @if ($donation->status === 'verified')
                                <span class="badge badge-success">Terverifikasi (Oleh {{ $donation->verifier->name ?? 'Admin' }})</span>
                            @elseif ($donation->status === 'pending')
                                <span class="badge badge-warning">Menunggu Validasi</span>
                            @else
                                <span class="badge badge-danger">Ditolak: {{ $donation->rejection_reason }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Bukti Transfer & Aksi -->
            <div>
                <div class="card-table" style="margin-bottom: 20px;">
                    <h3 style="font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 700; color: var(--primary-dark); margin-bottom: 16px;">
                        Bukti Transfer Donatur
                    </h3>

                    @if ($donation->transfer_proof_path)
                        <a href="/storage/{{ $donation->transfer_proof_path }}" target="_blank">
                            <img src="/storage/{{ $donation->transfer_proof_path }}" alt="Bukti Transfer" style="width: 100%; max-height: 350px; object-fit: contain; border-radius: 8px; border: 1px solid var(--border);">
                        </a>
                        <p style="font-size: 11.5px; color: var(--text-muted); text-align: center; margin-top: 8px;">Klik gambar untuk melihat resolusi penuh</p>
                    @else
                        <div style="background: #f8faf9; border: 1px dashed var(--border); border-radius: 8px; padding: 30px; text-align: center; color: var(--text-muted); font-size: 13px;">
                            Tidak ada lampiran bukti transfer.
                        </div>
                    @endif
                </div>

                @if ($donation->status === 'pending')
                    <div class="card-table">
                        <h4 style="font-family: 'Montserrat', sans-serif; font-size: 15px; font-weight: 700; margin-bottom: 16px;">
                            Aksi Verifikasi
                        </h4>

                        <form action="{{ route('admin.donations.verify', $donation->id) }}" method="POST" style="margin-bottom: 16px;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-action btn-primary" style="width: 100%; padding: 12px; font-size: 13.5px; justify-content: center;" onclick="return confirm('Verifikasi donasi ini dan tambahkan ke saldo program?');">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Verifikasi Donasi Valid
                            </button>
                        </form>

                        <form action="{{ route('admin.donations.reject', $donation->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div style="margin-bottom: 10px;">
                                <input type="text" name="rejection_reason" placeholder="Alasan penolakan (misal: nominal tidak sesuai)" required style="width: 100%; padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px;">
                            </div>
                            <button type="submit" class="btn-action" style="width: 100%; padding: 10px; background: #ffebe9; color: #cf222e; border: 1px solid #ffc1bc; justify-content: center;" onclick="return confirm('Tolak donasi ini?');">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                Tolak Transaksi
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
