@extends('layouts.admin')

@section('title', 'Tulis Artikel & Riset Baru')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card-table">
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 20px;">
                Editor Artikel / Publikasi YNKI
            </h2>

            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Judul Artikel / Laporan Riset *</label>
                    <input type="text" name="title" required placeholder="Judul artikel informatif dan relevan..." style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 14px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Kategori *</label>
                        <select name="category_id" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Status Publikasi *</label>
                        <select name="status" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                            <option value="published">Langsung Terbitkan (Published)</option>
                            <option value="draft">Simpan Sebagai Draft</option>
                            <option value="archived">Arsipkan</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Ringkasan Pendek (Excerpt)</label>
                    <textarea name="excerpt" rows="2" placeholder="Ringkasan 1-2 kalimat untuk preview..." style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 14px;"></textarea>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Isi Lengkap Konten Artikel *</label>
                    <textarea name="content" rows="12" required placeholder="Tuliskan isi artikel lengkap di sini..." style="width: 100%; padding: 12px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; line-height: 1.6;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                            Gambar Sampul (Cover Image)
                            <span style="font-weight: 400; color: var(--text-muted); font-size: 11.5px;">(Maks 8MB, otomatis dikompresi & dioptimasi WebP)</span>
                        </label>
                        <input type="file" name="featured_image" accept="image/*" style="width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Lampiran Dokumen PDF Riset (Opsional)</label>
                        <input type="file" name="attachment_pdf" accept="application/pdf" style="width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;">
                    </div>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 20px;">Simpan & Terbitkan</button>
                    <a href="{{ route('admin.articles.index') }}" class="btn-action btn-outline" style="padding: 10px 20px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
