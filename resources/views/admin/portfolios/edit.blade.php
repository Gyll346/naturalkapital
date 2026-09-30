@extends('layouts.admin')

@section('title', 'Edit Proyek Portfolio')

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
                Edit Proyek Portfolio
            </h3>

            @if ($errors->any())
                <div style="background: #ffebe9; color: #cf222e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.portfolios.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label" for="project_title">Nama / Judul Proyek</label>
                    <input type="text" class="form-control" id="project_title" name="project_title" value="{{ old('project_title', $project->project_title) }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="category">Kategori Pilar Lanskap</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="Landscape Governance" {{ $project->category === 'Landscape Governance' ? 'selected' : '' }}>Landscape Governance</option>
                            <option value="Natural Capital & Restoration" {{ $project->category === 'Natural Capital & Restoration' ? 'selected' : '' }}>Natural Capital & Restoration</option>
                            <option value="Sustainable Commodity System" {{ $project->category === 'Sustainable Commodity System' ? 'selected' : '' }}>Sustainable Commodity System</option>
                            <option value="Pengetahuan Lanskap & Inovasi" {{ $project->category === 'Pengetahuan Lanskap & Inovasi' ? 'selected' : '' }}>Pengetahuan Lanskap & Inovasi</option>
                            <option value="Institutional Sustainability & Partnership" {{ $project->category === 'Institutional Sustainability & Partnership' ? 'selected' : '' }}>Institutional Sustainability & Partnership</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status Pelaksanaan</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="ongoing" {{ $project->status === 'ongoing' ? 'selected' : '' }}>Sedang Berjalan (Ongoing)</option>
                            <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="location">Lokasi / Wilayah</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $project->location) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="partner_donor">Mitra / Donor</label>
                        <input type="text" class="form-control" id="partner_donor" name="partner_donor" value="{{ old('partner_donor', $project->partner_donor) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="period">Periode Pelaksanaan</label>
                        <input type="text" class="form-control" id="period" name="period" value="{{ old('period', $project->period) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="summary">Ringkasan Capaian & Intervensi Proyek</label>
                    <textarea class="form-control" id="summary" name="summary" rows="3" required>{{ old('summary', $project->summary) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi Lengkap Proyek (Opsional)</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $project->description) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="image_cover">Ganti Foto Sampul (JPG/PNG/WebP)</label>
                        <input type="file" class="form-control" id="image_cover" name="image_cover" accept="image/*">
                        <small style="display: block; color: var(--text-muted); font-size: 11.5px; margin-top: 4px;">
                            Foto sampul otomatis dikompresi & dioptimasi ke format WebP ringan agar website cepat dimuat.
                        </small>
                        @if ($project->image_cover_path)
                            <img src="/storage/{{ $project->image_cover_path }}" alt="Sampul" style="height: 60px; margin-top: 8px; border-radius: 4px;">
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="document_pdf">Ganti Laporan Proyek (PDF)</label>
                        <input type="file" class="form-control" id="document_pdf" name="document_pdf" accept="application/pdf">
                        @if ($project->document_pdf_path)
                            <p style="font-size: 12px; margin-top: 6px;"><a href="/storage/{{ $project->document_pdf_path }}" target="_blank" style="color: #cf222e; font-weight: 600;">Lihat PDF Terlampir</a></p>
                        @endif
                    </div>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 24px; font-size: 14px;">Simpan Perubahan</button>
                    <a href="{{ route('admin.portfolios.index') }}" class="btn-action btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
