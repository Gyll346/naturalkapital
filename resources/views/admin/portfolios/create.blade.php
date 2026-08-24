@extends('layouts.admin')

@section('title', 'Tambah Proyek Portfolio')

@section('content')
    <div style="max-width: 850px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.portfolios.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Daftar Portfolio
            </a>
        </div>

        <div class="card-table">
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 24px;">
                Tambah Proyek Portfolio Baru
            </h3>

            @if ($errors->any())
                <div style="background: #ffebe9; color: #cf222e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.portfolios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="project_title">Nama / Judul Proyek</label>
                    <input type="text" class="form-control" id="project_title" name="project_title" value="{{ old('project_title') }}" required placeholder="Contoh: Penguatan Mitigasi & Adaptasi Iklim Lanskap Gambut">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="category">Kategori Pilar Lanskap</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="Landscape Governance">Landscape Governance</option>
                            <option value="Natural Capital & Restoration">Natural Capital & Restoration</option>
                            <option value="Sustainable Commodity System">Sustainable Commodity System</option>
                            <option value="Landscape Intelligence & Innovation">Landscape Intelligence & Innovation</option>
                            <option value="Institutional Sustainability & Partnership">Institutional Sustainability & Partnership</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status Pelaksanaan</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="ongoing">Sedang Berjalan (Ongoing)</option>
                            <option value="completed">Selesai (Completed)</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="location">Lokasi / Wilayah</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Kab. Kubu Raya">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="partner_donor">Mitra / Donor</label>
                        <input type="text" class="form-control" id="partner_donor" name="partner_donor" value="{{ old('partner_donor') }}" placeholder="Contoh: TFCA Kalimantan">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="period">Periode Pelaksanaan</label>
                        <input type="text" class="form-control" id="period" name="period" value="{{ old('period') }}" placeholder="Contoh: 2023 - 2025">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="summary">Ringkasan Capaian & Intervensi Proyek</label>
                    <textarea class="form-control" id="summary" name="summary" rows="3" required placeholder="Jelaskan ringkasan kegiatan dan capaian utama proyek...">{{ old('summary') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi Lengkap Proyek (Opsional)</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Detail narasi proyek, metodologi, dan output lanjutan...">{{ old('description') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="image_cover">Foto Sampul Proyek (JPG/PNG/WebP)</label>
                        <input type="file" class="form-control" id="image_cover" name="image_cover" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="document_pdf">Laporan Proyek / Factsheet (PDF)</label>
                        <input type="file" class="form-control" id="document_pdf" name="document_pdf" accept="application/pdf">
                    </div>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 24px; font-size: 14px;">Simpan Proyek</button>
                    <a href="{{ route('admin.portfolios.index') }}" class="btn-action btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
