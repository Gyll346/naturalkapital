@extends('layouts.admin')

@section('title', 'Edit Pengurus / Tim')

@section('content')
    <div style="max-width: 680px; margin: 0 auto;">
        <div class="card-table">
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 20px;">
                Ubah Data Anggota Tim / Pengurus
            </h2>

            <form action="{{ route('admin.teams.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Kategori Struktur *</label>
                    <select name="category_id" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $member->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Lengkap & Gelar *</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $member->full_name) }}" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Jabatan / Posisi *</label>
                    <input type="text" name="position" value="{{ old('position', $member->position) }}" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Perbarui Foto Profil</label>
                    @if ($member->photo_path)
                        <div style="margin-bottom: 10px;">
                            <img src="/storage/{{ $member->photo_path }}" alt="{{ $member->full_name }}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" style="width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;">
                    <small style="display: block; color: var(--text-muted); font-size: 12px; margin-top: 5px;">
                        Foto akan otomatis di-resize (maks. 800px) dan dikompresi ke format WebP/JPEG ringan agar website cepat dimuat.
                    </small>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Tautan Profil LinkedIn</label>
                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $member->linkedin_url) }}" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Biografi Singkat</label>
                    <textarea name="bio" rows="3" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">{{ old('bio', $member->bio) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Urutan Tampilan</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $member->sort_order) }}" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Status *</label>
                        <select name="status" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                            <option value="active" {{ $member->status === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="alumni" {{ $member->status === 'alumni' ? 'selected' : '' }}>Alumni</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 20px;">Simpan Perubahan</button>
                    <a href="{{ route('admin.teams.index') }}" class="btn-action btn-outline" style="padding: 10px 20px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
