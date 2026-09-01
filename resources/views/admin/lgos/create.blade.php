@extends('layouts.admin')

@section('title', 'Tambah Komponen LGOS')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.lgos.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Daftar Komponen
            </a>
        </div>

        <div class="card-table">
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 24px;">
                Tambah Komponen LGOS Baru
            </h3>

            @if ($errors->any())
                <div style="background: #ffebe9; color: #cf222e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 0 0 4px;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.lgos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="component_name">Nama Komponen LGOS</label>
                        <input type="text" class="form-control" id="component_name" name="component_name" value="{{ old('component_name') }}" required placeholder="Contoh: Kertas Posisi / Master ToC">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="code">Kode Komponen</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" placeholder="Contoh: KOMPONEN 01">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="role">Peran / Subtitle Komponen</label>
                        <input type="text" class="form-control" id="role" name="role" value="{{ old('role') }}" placeholder="Contoh: Mengapa Kami Ada / Bagaimana Perubahan Terjadi">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="sort_order">Urutan Komponen (1–8)</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 1) }}" required min="1" max="50">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi Singkat</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required placeholder="Jelaskan ruang lingkup dan fungsi komponen LGOS ini...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group" style="background: #f8faf8; padding: 16px; border-radius: 10px; border: 1px solid #d2e8d1;">
                    <label class="form-label" for="document_pdf" style="color: #117710;">Lampiran Dokumen PDF (Khusus Komponen 1–5)</label>
                    <input type="file" class="form-control" id="document_pdf" name="document_pdf" accept="application/pdf">
                    <small style="color: var(--text-muted); font-size: 12px; display: block; margin-top: 6px;">
                        File PDF maksimal 25 MB. Pengunjung halaman LGOS dapat mengunduh dokumen ini secara langsung pada card komponen 1–5.
                    </small>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked style="accent-color: var(--primary); width: 16px; height: 16px;">
                    <label for="is_active" style="font-size: 13.5px; cursor: pointer;">Tampilkan di halaman publik LGOS</label>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 24px; font-size: 14px;">Simpan Komponen</button>
                    <a href="{{ route('admin.lgos.index') }}" class="btn-action btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
