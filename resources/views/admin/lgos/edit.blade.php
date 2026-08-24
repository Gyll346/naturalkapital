@extends('layouts.admin')

@section('title', 'Edit Komponen LGOS')

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
                Edit Komponen Pendukung LGOS
            </h3>

            @if ($errors->any())
                <div style="background: #ffebe9; color: #cf222e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.lgos.update', $component->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label" for="component_name">Nama Komponen LGOS</label>
                    <input type="text" class="form-control" id="component_name" name="component_name" value="{{ old('component_name', $component->component_name) }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="code">Kode Komponen</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $component->code) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="sort_order">Urutan Tampilan</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $component->sort_order) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi & Ruang Lingkup</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $component->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="document_pdf">Ganti Lampiran Dokumen Pedoman (File PDF)</label>
                    <input type="file" class="form-control" id="document_pdf" name="document_pdf" accept="application/pdf">
                    @if ($component->document_pdf_path)
                        <p style="font-size: 12.5px; color: var(--primary); margin-top: 6px;">
                            File saat ini: <a href="/storage/{{ $component->document_pdf_path }}" target="_blank" style="color: #cf222e; font-weight: 600;">Lihat PDF</a>
                        </p>
                    @endif
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ $component->is_active ? 'checked' : '' }} style="accent-color: var(--primary); width: 16px; height: 16px;">
                    <label for="is_active" style="font-size: 13.5px; cursor: pointer;">Tampilkan di halaman publik LGOS</label>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 24px; font-size: 14px;">Simpan Perubahan</button>
                    <a href="{{ route('admin.lgos.index') }}" class="btn-action btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
