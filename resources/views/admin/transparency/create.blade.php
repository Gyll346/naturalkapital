@extends('layouts.admin')

@section('title', 'Unggah Dokumen Transparansi')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.transparency.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Daftar Dokumen
            </a>
        </div>

        <div class="card-table">
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 24px;">
                Unggah Dokumen Transparansi / Audit Baru
            </h3>

            @if ($errors->any())
                <div style="background: #ffebe9; color: #cf222e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.transparency.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="title">Judul Laporan / Dokumen</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Laporan Tahunan & Capaian Kinerja YNKI 2025">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="report_year">Tahun Laporan</label>
                        <input type="number" class="form-control" id="report_year" name="report_year" value="{{ old('report_year', date('Y')) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="category">Kategori Dokumen</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="Laporan Tahunan">Laporan Tahunan</option>
                            <option value="Laporan Audit Keuangan">Laporan Audit Keuangan (WTP)</option>
                            <option value="Dokumen Kebijakan">Dokumen Kebijakan & Safeguards</option>
                            <option value="Laporan Khusus Mitra">Laporan Khusus Mitra</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="summary">Ringkasan Dokumen</label>
                    <textarea class="form-control" id="summary" name="summary" rows="3" placeholder="Jelaskan ringkasan isi laporan...">{{ old('summary') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="file_pdf">Pilih File PDF Laporan</label>
                    <input type="file" class="form-control" id="file_pdf" name="file_pdf" accept="application/pdf" required>
                    <small style="color: var(--text-muted); font-size: 12px;">Format PDF maksimal 30 MB untuk diunduh publik.</small>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked style="accent-color: var(--primary); width: 16px; height: 16px;">
                    <label for="is_active" style="font-size: 13.5px; cursor: pointer;">Tampilkan di halaman publik Transparansi & Laporan</label>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 24px; font-size: 14px;">Unggah Dokumen</button>
                    <a href="{{ route('admin.transparency.index') }}" class="btn-action btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
