@extends('layouts.admin')

@section('title', 'Edit Liputan Media')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.media-coverages.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Daftar Liputan Media
            </a>
        </div>

        <div class="card-table">
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 20px;">
                Edit Liputan Media
            </h2>

            @if ($errors->any())
                <div style="background: #ffebe9; color: #cf222e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.media-coverages.update', $coverage->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label" for="title">Judul Artikel yang Dibuat Media *</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $coverage->title) }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="media_name">Nama Media yang Meliput *</label>
                        <input type="text" class="form-control" id="media_name" name="media_name" value="{{ old('media_name', $mediaData['media_name'] ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="topic_category">Topik / Kategori Liputan</label>
                        @php
                            $currentTopic = old('topic_category', $mediaData['topic_category'] ?? 'Restorasi Gambut');
                        @endphp
                        <select class="form-control" id="topic_category" name="topic_category">
                            <option value="Restorasi Gambut" {{ $currentTopic == 'Restorasi Gambut' ? 'selected' : '' }}>Restorasi Gambut</option>
                            <option value="Komoditas Berkelanjutan" {{ $currentTopic == 'Komoditas Berkelanjutan' ? 'selected' : '' }}>Komoditas Berkelanjutan</option>
                            <option value="Pemuda & Pendidikan" {{ $currentTopic == 'Pemuda & Pendidikan' ? 'selected' : '' }}>Pemuda & Pendidikan</option>
                            <option value="Pemberdayaan Masyarakat" {{ $currentTopic == 'Pemberdayaan Masyarakat' ? 'selected' : '' }}>Pemberdayaan Masyarakat</option>
                            <option value="Kebijakan & Tata Kelola" {{ $currentTopic == 'Kebijakan & Tata Kelola' ? 'selected' : '' }}>Kebijakan & Tata Kelola</option>
                            <option value="Analisis & Penelitian" {{ $currentTopic == 'Analisis & Penelitian' ? 'selected' : '' }}>Analisis & Penelitian</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="external_url">Link / URL Artikel Asli Media *</label>
                    <input type="url" class="form-control" id="external_url" name="external_url" value="{{ old('external_url', $mediaData['external_url'] ?? '') }}" required placeholder="https://...">
                    <small style="color: var(--text-muted); font-size: 12px; margin-top: 4px; display: block;">
                        Tautan ini akan langsung dibuka di tab baru saat pengunjung menekan tombol "Baca Selengkapnya".
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="excerpt">Ringkasan / Kutipan Artikel *</label>
                    <textarea class="form-control" id="excerpt" name="excerpt" rows="4" required>{{ old('excerpt', $coverage->excerpt) }}</textarea>
                </div>

                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span style="font-size: 13px; color: #475569;">
                        Tanggal terbit asli: <strong>{{ $coverage->published_at ? $coverage->published_at->format('d F Y') : $coverage->created_at->format('d F Y') }}</strong>
                    </span>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 22px;">Simpan Perubahan</button>
                    <a href="{{ route('admin.media-coverages.index') }}" class="btn-action btn-outline" style="padding: 10px 20px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
