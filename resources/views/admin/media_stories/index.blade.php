@extends('layouts.admin')

@section('title', 'Story Foto & Video Lapangan')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 700; color: var(--primary-dark);">
            Dokumentasi Foto & Video Lapangan
        </h2>
        <a href="{{ route('admin.media-stories.create') }}" class="btn-action btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Media Baru
        </a>
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
                        <th>Media</th>
                        <th>Judul & Keterangan</th>
                        <th>Tipe</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kredit Foto</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stories as $s)
                        <tr>
                            <td>
                                @if ($s->media_type === 'photo' && $s->image_path)
                                    <img src="/storage/{{ $s->image_path }}" alt="{{ $s->title }}" style="width: 55px; height: 40px; border-radius: 4px; object-fit: cover;">
                                @elseif ($s->media_type === 'video')
                                    <div style="width: 55px; height: 40px; border-radius: 4px; background: #072214; color: #ffffff; display: flex; align-items: center; justify-content: center;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                    </div>
                                @else
                                    <div style="width: 55px; height: 40px; border-radius: 4px; background: #f0f4f2; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $s->title }}</strong><br>
                                <small style="color: var(--text-muted);">{{ Str::limit($s->caption, 80) }}</small>
                            </td>
                            <td>
                                @if ($s->media_type === 'video')
                                    <span class="badge badge-warning">Video</span>
                                @else
                                    <span class="badge badge-success">Foto</span>
                                @endif
                            </td>
                            <td>{{ $s->category }}</td>
                            <td>{{ $s->location ?? '-' }}</td>
                            <td>{{ $s->photographer_credits ?? '-' }}</td>
                            <td>
                                @if ($s->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.media-stories.edit', $s->id) }}" class="btn-action btn-outline">Edit</a>
                                    <form action="{{ route('admin.media-stories.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus media story ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action" style="background: #ffebe9; color: #cf222e; border: none;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Belum ada dokumentasi foto atau video yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $stories->links() }}
        </div>
    </div>
@endsection
