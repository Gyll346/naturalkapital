@extends('layouts.admin')

@section('title', 'Edit Media Story')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.media-stories.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Daftar Media
            </a>
        </div>

        <div class="card-table">
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 24px;">
                Edit Dokumentasi Foto / Video
            </h3>

            @if ($errors->any())
                <div style="background: #ffebe9; color: #cf222e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.media-stories.update', $story->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label" for="title">Judul Dokumentasi Media</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $story->title) }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="media_type">Tipe Media</label>
                        <select class="form-control" id="media_type" name="media_type" required onchange="toggleMediaInputs(this.value)">
                            <option value="photo" {{ $story->media_type === 'photo' ? 'selected' : '' }}>Foto Dokumentasi Lapangan</option>
                            <option value="video" {{ $story->media_type === 'video' ? 'selected' : '' }}>Video Dokumenter (YouTube URL)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="category">Kategori Tema</label>
                        <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $story->category) }}">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="location">Lokasi Pengambilan</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $story->location) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="photographer_credits">Kredit Fotografer / Tim</label>
                        <input type="text" class="form-control" id="photographer_credits" name="photographer_credits" value="{{ old('photographer_credits', $story->photographer_credits) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="caption">Keterangan / Deskripsi Media</label>
                    <textarea class="form-control" id="caption" name="caption" rows="3">{{ old('caption', $story->caption) }}</textarea>
                </div>

                <div class="form-group" id="photo_input_group" style="{{ $story->media_type === 'photo' ? 'display:block;' : 'display:none;' }}">
                    <label class="form-label" for="image">
                        Ganti File Foto
                        <span style="font-weight: 400; color: var(--text-muted); font-size: 11.5px;">(Maks 10MB, otomatis dikompresi & dioptimasi WebP)</span>
                    </label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    @if ($story->image_path)
                        <img src="/storage/{{ $story->image_path }}" alt="Foto" style="height: 60px; margin-top: 8px; border-radius: 4px;">
                    @endif
                </div>

                <div class="form-group" id="video_input_group" style="{{ $story->media_type === 'video' ? 'display:block;' : 'display:none;' }}">
                    <label class="form-label" for="youtube_url">Tautan Video YouTube</label>
                    <input type="url" class="form-control" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $story->youtube_url) }}">
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ $story->is_active ? 'checked' : '' }} style="accent-color: var(--primary); width: 16px; height: 16px;">
                    <label for="is_active" style="font-size: 13.5px; cursor: pointer;">Tampilkan di halaman publik Story Foto & Video</label>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 24px; font-size: 14px;">Simpan Perubahan</button>
                    <a href="{{ route('admin.media-stories.index') }}" class="btn-action btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMediaInputs(val) {
            if (val === 'video') {
                document.getElementById('video_input_group').style.display = 'block';
                document.getElementById('photo_input_group').style.display = 'none';
            } else {
                document.getElementById('video_input_group').style.display = 'none';
                document.getElementById('photo_input_group').style.display = 'block';
            }
        }
    </script>
@endsection
