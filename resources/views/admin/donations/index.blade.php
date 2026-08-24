@extends('layouts.admin')

@section('title', 'Data Transaksi Donasi')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.donations.index') }}" class="btn-action {{ !$status ? 'btn-primary' : 'btn-outline' }}">Semua</a>
            <a href="{{ route('admin.donations.index') }}?status=pending" class="btn-action {{ $status === 'pending' ? 'btn-primary' : 'btn-outline' }}">Menunggu Verifikasi</a>
            <a href="{{ route('admin.donations.index') }}?status=verified" class="btn-action {{ $status === 'verified' ? 'btn-primary' : 'btn-outline' }}">Terverifikasi</a>
            <a href="{{ route('admin.donations.index') }}?status=rejected" class="btn-action {{ $status === 'rejected' ? 'btn-primary' : 'btn-outline' }}">Ditolak</a>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.reports.donations.excel') }}" class="btn-action btn-outline">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Unduh Excel / CSV
            </a>
            <a href="{{ route('admin.reports.donations.pdf') }}" class="btn-action btn-outline">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Unduh Laporan PDF
            </a>
        </div>
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

    <div class="card-table">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Donatur</th>
                        <th>Program</th>
                        <th>Jumlah Donasi</th>
                        <th>Tujuan Transfer</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donations as $donation)
                        <tr>
                            <td><strong>{{ $donation->invoice_number }}</strong></td>
                            <td>
                                {{ $donation->is_anonymous ? 'Hamba Allah (Anonim)' : $donation->donor_name }}<br>
                                <small style="color: var(--text-muted);">{{ $donation->donor_email }}</small>
                            </td>
                            <td>{{ $donation->program->program_name ?? 'Donasi Umum Lanskap' }}</td>
                            <td><strong>Rp {{ number_format($donation->amount, 0, ',', '.') }}</strong></td>
                            <td>{{ $donation->account->bank_name ?? '-' }}</td>
                            <td>
                                @if ($donation->status === 'verified')
                                    <span class="badge badge-success">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Terverifikasi
                                    </span>
                                @elseif ($donation->status === 'pending')
                                    <span class="badge badge-warning">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        Menunggu
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td>{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.donations.show', $donation->id) }}" class="btn-action btn-outline">
                                    Detail & Validasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Tidak ada transaksi donasi yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $donations->links() }}
        </div>
    </div>
@endsection
