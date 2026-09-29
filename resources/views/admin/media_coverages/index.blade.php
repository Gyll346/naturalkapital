@extends('layouts.admin')

@section('title', 'Manajemen Liputan Media')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 700; color: var(--primary-dark); margin: 0 0 4px;">
                Liputan Media & Publikasi Eksternal
            </h2>
            <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                Kelola artikel media eksternal (nama media, ringkasan, dan tautan langsung ke sumber asli).
            </p>
        </div>
        <a href="{{ route('admin.media-coverages.create') }}" class="btn-action btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            + Tambah Liputan Media
        </a>
    </div>

    @if (session('success'))
        <div style="background: #dafbe1; color: #1a7f37; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Bar -->
    <div style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('admin.media-coverages.index') }}" style="display: flex; gap: 8px; max-width: 480px; width: 100%;">
            <div style="position: relative; flex-grow: 1;">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari judul atau ringkasan liputan..." style="padding-left: 36px; padding-right: 12px; height: 38px; font-size: 13px; border-radius: 8px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <button type="submit" class="btn-action btn-primary" style="padding: 0 16px; height: 38px; font-size: 13px; border-radius: 8px;">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.media-coverages.index') }}" class="btn-action btn-outline" style="padding: 0 14px; height: 38px; font-size: 13px; border-radius: 8px; display: inline-flex; align-items: center;">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="card-table">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 28%;">Judul Artikel Liputan</th>
                        <th>Media Peliput</th>
                        <th>Topik / Kategori</th>
                        <th>Tanggal Terbit</th>
                        <th>Tautan Asli</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($coverages as $c)
                        @php
                            $data = json_decode($c->content, true) ?: [];
                            $mediaName = $data['media_name'] ?? 'Media Partner';
                            $url = $data['external_url'] ?? '#';
                            $topic = $data['topic_category'] ?? 'Liputan';
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $c->title }}</strong>
                                @if($c->excerpt)
                                    <p style="color: var(--text-muted); font-size: 12px; margin: 4px 0 0; line-height: 1.4;">
                                        "{{ Str::limit($c->excerpt, 90) }}"
                                    </p>
                                @endif
                            </td>
                            <td>
                                <span style="background: #fff3e6; color: #c2410c; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    {{ $mediaName }}
                                </span>
                            </td>
                            <td>
                                <span style="background: #eaf5ee; color: #0F5132; padding: 3px 10px; border-radius: 50px; font-size: 11px; font-weight: 700;">
                                    {{ $topic }}
                                </span>
                            </td>
                            <td>
                                <span style="color: #536b5f; font-size: 12.5px; font-weight: 600;">
                                    {{ $c->published_at ? $c->published_at->format('d M Y') : $c->created_at->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 5px 11px; border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;" title="Buka artikel asli di tab baru">
                                    Buka Sumber
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                </a>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.media-coverages.edit', $c->id) }}" class="btn-action btn-outline" style="padding: 6px 10px;" title="Edit Liputan">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.media-coverages.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus liputan media ini?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action" style="padding: 6px 10px; background: #ffebe9; color: #cf222e; border: 1px solid #ff818266;" title="Hapus Liputan">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 36px;">
                                @if(request('search'))
                                    Tidak ditemukan liputan media dengan kata kunci "<strong>{{ request('search') }}</strong>".
                                @else
                                    Belum ada liputan media yang ditambahkan. Silakan klik tombol <strong>+ Tambah Liputan Media</strong>.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $coverages->links() }}
        </div>
    </div>
@endsection
