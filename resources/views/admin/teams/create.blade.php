@extends('layouts.admin')

@section('title', 'Tambah Pengurus / Tim')

@section('content')
    <div style="max-width: 680px; margin: 0 auto;">
        <div class="card-table">
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 20px;">
                Form Anggota Tim / Pengurus Baru
            </h2>

            <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Kategori Struktur *</label>
                    <select name="category_id" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Lengkap & Gelar *</label>
                    <input type="text" name="full_name" required placeholder="Misal: Dr. Ir. Budi Santoso, M.Sc." style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Jabatan / Posisi *</label>
                    <input type="text" name="position" required placeholder="Misal: Direktur Eksekutif / Manajer Program" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Foto Profil</label>
                    <input type="file" name="photo" accept="image/*" style="width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Tautan Profil LinkedIn</label>
                    <input type="url" name="linkedin_url" placeholder="https://id.linkedin.com/in/username" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Biografi Singkat</label>
                    <textarea name="bio" rows="3" placeholder="Deskripsi latar belakang dan kepakaran..." style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Urutan Tampilan</label>
                        <input type="number" name="sort_order" value="0" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Status *</label>
                        <select name="status" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                            <option value="active">Aktif</option>
                            <option value="alumni">Alumni</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 20px;">Simpan Pengurus</button>
                    <a href="{{ route('admin.teams.index') }}" class="btn-action btn-outline" style="padding: 10px 20px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
