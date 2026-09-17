@extends('layouts.admin')

@section('title', 'Portfolio Program & Proyek')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 700; color: var(--primary-dark);">
            Daftar Portfolio Program & Proyek YNKI
        </h2>
        <a href="{{ route('admin.portfolios.create') }}" class="btn-action btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Proyek Baru
        </a>
    </div>

    @if (session('success'))
        <div style="background: #dafbe1; color: #1a7f37; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card-table">
        <!-- Search Bar -->
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <form action="{{ route('admin.portfolios.index') }}" method="GET" style="display: flex; gap: 10px; flex-grow: 1; max-width: 520px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, kategori, lokasi, mitra, periode, status..." class="form-control" style="padding: 8px 12px; font-size: 13px;">
                <button type="submit" class="btn-action btn-primary" style="padding: 8px 14px; font-size: 13px;">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.portfolios.index') }}" class="btn-action btn-outline" style="padding: 8px 12px; font-size: 13px;">Reset</a>
                @endif
            </form>

            <div style="font-size: 12.5px; color: var(--text-muted);">
                Total: <strong>{{ $projects->total() }}</strong> proyek
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Judul Proyek</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Mitra / Donor</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $p)
                        <tr>
                            <td>
                                <strong>{{ $p->project_title }}</strong><br>
                                <small style="color: var(--text-muted);">{{ Str::limit($p->summary, 80) }}</small>
                            </td>
                            <td><span class="badge badge-success">{{ $p->category }}</span></td>
                            <td>{{ $p->location ?? '-' }}</td>
                            <td>{{ $p->partner_donor ?? '-' }}</td>
                            <td>{{ $p->period ?? '-' }}</td>
                            <td>
                                @if ($p->status === 'ongoing')
                                    <span class="badge badge-warning">Berjalan</span>
                                @else
                                    <span class="badge badge-success">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.portfolios.edit', $p->id) }}" class="btn-action btn-outline">Edit</a>
                                    <form action="{{ route('admin.portfolios.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus proyek portfolio ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action" style="background: #ffebe9; color: #cf222e; border: none;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                @if (request('search'))
                                    Tidak ditemukan proyek portfolio dengan kata kunci "<strong>{{ request('search') }}</strong>".
                                @else
                                    Belum ada proyek portfolio yang ditambahkan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $projects->links() }}
        </div>
    </div>
@endsection
