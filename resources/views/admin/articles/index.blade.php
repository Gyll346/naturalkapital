@extends('layouts.admin')

@section('title', 'Manajemen Artikel, Riset & Peta PDF')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 700; color: var(--primary-dark); margin: 0 0 4px;">
                @if(isset($selectedCategory))
                    Kelola {{ $selectedCategory->category_name }}
                @else
                    Koleksi Artikel, Berita, & Publikasi Ilmiah
                @endif
            </h2>
            <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                Kelola naskah, foto sampul, status publikasi, dan file lampiran dokumen PDF untuk subhalaman website.
            </p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="btn-action btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            + Upload / Tulis Publikasi Baru
        </a>
    </div>

    <!-- Search Bar & Filter Kategori -->
    <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('admin.articles.index') }}" style="display: flex; gap: 8px; max-width: 480px; width: 100%;">
            @if(request()->has('category'))
                <input type="hidden" name="category" value="{{ request()->get('category') }}">
            @endif
            <div style="position: relative; flex-grow: 1;">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari berdasarkan judul publikasi..." style="padding-left: 36px; padding-right: 12px; height: 38px; font-size: 13px; border-radius: 8px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <button type="submit" class="btn-action btn-primary" style="padding: 0 16px; height: 38px; font-size: 13px; border-radius: 8px;">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.articles.index', request()->has('category') ? ['category' => request('category')] : []) }}" class="btn-action btn-outline" style="padding: 0 14px; height: 38px; font-size: 13px; border-radius: 8px; display: inline-flex; align-items: center;" title="Reset Pencarian">
                    Reset
                </a>
            @endif
        </form>

        <!-- Filter Kategori Tabs -->
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <a href="{{ route('admin.articles.index', request('search') ? ['search' => request('search')] : []) }}" class="btn-action {{ !request()->has('category') ? 'btn-primary' : 'btn-outline' }}" style="font-size: 12.5px; padding: 7px 14px; border-radius: 50px;">
                Semua ({{ \App\Models\Article::count() }})
            </a>
            @foreach($categories as $cat)
                @php
                    $count = \App\Models\Article::where('category_id', $cat->id)->count();
                    $isActive = request()->get('category') === $cat->slug;
                    $catParams = ['category' => $cat->slug];
                    if (request('search')) {
                        $catParams['search'] = request('search');
                    }
                @endphp
                <a href="{{ route('admin.articles.index', $catParams) }}" class="btn-action {{ $isActive ? 'btn-primary' : 'btn-outline' }}" style="font-size: 12.5px; padding: 7px 14px; border-radius: 50px;">
                    {{ $cat->category_name }} ({{ $count }})
                </a>
            @endforeach
        </div>
    </div>

    @if (session('success'))
        <div style="background: #dafbe1; color: #1a7f37; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card-table">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Sampul</th>
                        <th>Judul Publikasi</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Dokumen PDF</th>
                        <th>Status</th>
                        <th>Tanggal Terbit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr>
                            <td>
                                @if ($article->featured_image_path)
                                    <img src="/storage/{{ $article->featured_image_path }}" alt="{{ $article->title }}" style="width: 54px; height: 38px; border-radius: 6px; object-fit: cover;">
                                @else
                                    <div style="width: 54px; height: 38px; border-radius: 6px; background: #f0f4f2; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $article->title }}</strong><br>
                                <small style="color: var(--text-muted); display: inline-flex; align-items: center; gap: 4px; margin-top: 2px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    {{ $article->views_count }} pembaca
                                </small>
                            </td>
                            <td>
                                <span style="background: #eaf5ee; color: #0F5132; padding: 3px 10px; border-radius: 50px; font-size: 11px; font-weight: 700;">
                                    {{ $article->category->category_name ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $article->author->name ?? 'Admin' }}</td>
                            <td>
                                @if ($article->attachment_pdf_path)
                                    <a href="/storage/{{ $article->attachment_pdf_path }}" target="_blank" style="background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                        Unduh PDF
                                    </a>
                                @else
                                    <span style="color: var(--text-muted); font-size: 12px;">Tanpa File PDF</span>
                                @endif
                            </td>
                            <td>
                                @if ($article->status === 'published')
                                    <span class="badge badge-success">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Terbit
                                    </span>
                                @elseif ($article->status === 'draft')
                                    <span class="badge badge-warning">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"></circle></svg>
                                        Draf
                                    </span>
                                @else
                                    <span class="badge badge-danger">Arsip</span>
                                @endif
                            </td>
                            <td>{{ $article->published_at ? $article->published_at->format('d M Y') : '-' }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn-action btn-outline" style="padding: 6px 10px;" title="Edit Publikasi">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus publikasi ini?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action" style="padding: 6px 10px; background: #ffebe9; color: #cf222e; border: 1px solid #ff818266;" title="Hapus Publikasi">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                @if(request('search'))
                                    Tidak ditemukan publikasi dengan judul yang mengandung kata kunci "<strong>{{ request('search') }}</strong>".
                                @else
                                    Belum ada publikasi pada kategori ini.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
