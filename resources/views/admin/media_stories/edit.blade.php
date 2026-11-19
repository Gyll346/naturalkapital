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
                            <option value="video" {{ $story->media_type === 'video' ? 'selected' : '' }}>Video Dokumenter (YouTube, IG, FB, TikTok, Upload)</option>
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

                <div class="form-group" id="photo_input_group">
                    <label class="form-label" for="image" id="image_label">
                        {{ $story->media_type === 'video' ? 'Ganti Sampul / Thumbnail Video (JPG/PNG/WebP)' : 'Ganti File Foto' }}
                        <span style="font-weight: 400; color: var(--text-muted); font-size: 11.5px;">(Maks 10MB, otomatis dikompresi & dioptimasi WebP)</span>
                    </label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    @if ($story->image_path)
                        <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                            <img src="/storage/{{ $story->image_path }}" alt="Sampul/Foto" style="height: 60px; border-radius: 4px; object-fit: cover;">
                            <small style="color: var(--text-muted);">Sampul / foto saat ini</small>
                        </div>
                    @endif
                </div>

                <div id="video_input_group" style="{{ $story->media_type === 'video' ? 'display:block;' : 'display:none;' }}">
                    <div class="form-group">
                        <label class="form-label" for="youtube_url">Tautan Video (YouTube, Instagram, Facebook, TikTok, dll.)</label>
                        <input type="text" class="form-control" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', str_starts_with($story->youtube_url ?? '', '/storage/') ? '' : $story->youtube_url) }}" placeholder="https://www.youtube.com/... atau https://www.instagram.com/... atau https://www.facebook.com/...">
                        <small style="color: var(--text-muted); font-size: 12px;">Masukkan tautan video dari YouTube, Instagram Reels/Post, Facebook Video, TikTok, atau link video lainnya.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="video_file">ATAU Unggah File Video Sendiri (MP4 / WebM / MOV)</label>
                        <input type="file" class="form-control" id="video_file" name="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime">
                        @if ($story->youtube_url && str_starts_with($story->youtube_url, '/storage/'))
                            <div style="margin-top: 6px;">
                                <small style="color: #1a7f37; font-weight: 600;">File video tersimpan saat ini:</small>
                                <a href="{{ $story->youtube_url }}" target="_blank" style="font-size: 12px; color: var(--primary); margin-left: 6px;">Buka Video Terunggah &rarr;</a>
                            </div>
                        @else
                            <small style="color: var(--text-muted); font-size: 12px;">Pilih file baru jika ingin mengganti dengan file video dari komputer (maksimal 100MB).</small>
                        @endif
                    </div>
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
            var videoGroup = document.getElementById('video_input_group');
            var imageLabel = document.getElementById('image_label');
            if (val === 'video') {
                videoGroup.style.display = 'block';
                imageLabel.innerHTML = 'Ganti Sampul / Thumbnail Video (JPG/PNG/WebP) <span style="font-weight:400;color:var(--text-muted);font-size:11.5px;">(Disarankan untuk video IG/FB/Upload)</span>';
            } else {
                videoGroup.style.display = 'none';
                imageLabel.innerHTML = 'Ganti File Foto <span style="font-weight:400;color:var(--text-muted);font-size:11.5px;">(Maks 10MB, otomatis dikompresi & dioptimasi WebP)</span>';
            }
        }
    </script>
@endsection
