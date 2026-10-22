@extends('layouts.admin')

@section('title', 'Edit Artikel & Riset')

@section('content')
    <div style="max-width: 860px; margin: 0 auto;">
        <div class="card-table">
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 20px;">
                Ubah Artikel / Publikasi YNKI
            </h2>

            @if ($errors->any())
                <div style="background: #ffebe9; color: #cf222e; padding: 12px 16px; border-radius: 8px; font-size: 13.5px; margin-bottom: 20px; border: 1px solid #ff818266;">
                    <strong style="display: block; margin-bottom: 4px;">Gagal memperbarui artikel:</strong>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" id="articleEditForm">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Judul Artikel / Laporan Riset *</label>
                    <input type="text" name="title" value="{{ old('title', $article->title) }}" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 14px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Kategori *</label>
                        <select name="category_id" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Penulis / Kontributor</label>
                        <input type="text" name="author_name" value="{{ old('author_name', $article->author_name) }}" placeholder="Contoh: Dr. Budi Santoso / Tim YNKI" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Status Publikasi *</label>
                        <select name="status" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                            <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Terbitkan (Published)</option>
                            <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Simpan Sebagai Draft</option>
                            <option value="archived" {{ old('status', $article->status) === 'archived' ? 'selected' : '' }}>Arsipkan</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Ringkasan Pendek (Excerpt)</label>
                    <textarea name="excerpt" rows="2" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 14px;">{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>

                <div style="margin-bottom: 20px; position: relative;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Isi Lengkap Konten Artikel *</label>
                    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
                    <div id="editor-container" style="min-height: 280px; font-size: 15px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; background: #fff;">{!! old('content', $article->content) !!}</div>
                    <textarea name="content" id="contentInput" style="position: absolute; opacity: 0; pointer-events: none; width: 1px; height: 1px;">{{ old('content', $article->content) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                            Gambar Sampul (Cover)
                            <span style="font-weight: 400; color: var(--text-muted); font-size: 11.5px;">(Maks 8MB, otomatis dikompresi & dioptimasi WebP)</span>
                        </label>
                        @if ($article->featured_image_path)
                            <div style="margin-bottom: 8px;">
                                <img src="/storage/{{ $article->featured_image_path }}" alt="Cover" style="max-height: 80px; border-radius: 4px;">
                            </div>
                        @endif
                        <input type="file" name="featured_image" accept="image/*" style="width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Lampiran Dokumen PDF Riset</label>
                        @if ($article->attachment_pdf_path)
                            <div style="margin-bottom: 8px;">
                                <a href="/storage/{{ $article->attachment_pdf_path }}" target="_blank" style="color: #cf222e; font-weight: 600; font-size: 12px;">📄 Lihat PDF Saat Ini</a>
                            </div>
                        @endif
                        <input type="file" name="attachment_pdf" accept="application/pdf" style="width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;">
                    </div>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 20px;">Simpan Perubahan</button>
                    <a href="{{ route('admin.articles.index') }}" class="btn-action btn-outline" style="padding: 10px 20px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tuliskan isi artikel lengkap di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['link', 'clean']
                ]
            }
        });

        var form = document.getElementById('articleEditForm');
        var contentInput = document.getElementById('contentInput');

        function syncContent() {
            var html = quill.getSemanticHTML ? quill.getSemanticHTML() : quill.root.innerHTML;
            if (quill.getText().trim().length === 0 && !quill.root.querySelector('img')) {
                contentInput.value = '';
            } else {
                contentInput.value = html;
            }
        }

        quill.on('text-change', syncContent);

        form.addEventListener('submit', function (e) {
            syncContent();
            if (!contentInput.value || contentInput.value.trim().length === 0) {
                e.preventDefault();
                alert('Isi konten artikel tidak boleh kosong!');
                quill.focus();
            }
        });
    });
</script>
@endpush
