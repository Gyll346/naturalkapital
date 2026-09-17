@extends('layouts.admin')

@section('title', 'Manajemen Pengurus & Tim YNKI')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 700; color: var(--primary-dark); margin-bottom: 4px;">
                Struktur Pengurus & Tim Ahli YNKI
            </h2>
            <p style="font-size: 13px; color: var(--text-muted);">
                Kelola data Dewan Pengurus dan Tim Ahli Pendukung YNKI yang ditampilkan pada website publik.
            </p>
        </div>
        <a href="{{ route('admin.teams.create') }}" class="btn-action btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Anggota / Tim Baru
        </a>
    </div>

    @if (session('success'))
        <div style="background: #dafbe1; color: #1a7f37; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Split Tab Pengurus vs Tim Ahli -->
    <div style="display: flex; gap: 10px; margin-bottom: 18px; border-bottom: 2px solid var(--border); padding-bottom: 12px; overflow-x: auto;">
        <a href="{{ route('admin.teams.index', array_merge(['group' => 'all'], request('search') ? ['search' => request('search')] : [])) }}" 
           style="text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 600; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; {{ $group === 'all' ? 'background: var(--primary); color: #ffffff;' : 'background: #ffffff; color: var(--text-dark); border: 1px solid var(--border);' }}">
            <span>Semua Anggota</span>
            <span style="font-size: 11px; padding: 2px 7px; border-radius: 12px; {{ $group === 'all' ? 'background: rgba(255,255,255,0.25); color: #ffffff;' : 'background: #eaf3eb; color: var(--primary);' }}">{{ $countAll }}</span>
        </a>
        <a href="{{ route('admin.teams.index', array_merge(['group' => 'pengurus'], request('search') ? ['search' => request('search')] : [])) }}" 
           style="text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 600; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; {{ $group === 'pengurus' ? 'background: var(--primary); color: #ffffff;' : 'background: #ffffff; color: var(--text-dark); border: 1px solid var(--border);' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            <span>Dewan Pengurus YNKI</span>
            <span style="font-size: 11px; padding: 2px 7px; border-radius: 12px; {{ $group === 'pengurus' ? 'background: rgba(255,255,255,0.25); color: #ffffff;' : 'background: #eaf3eb; color: var(--primary);' }}">{{ $countPengurus }}</span>
        </a>
        <a href="{{ route('admin.teams.index', array_merge(['group' => 'tim-ahli'], request('search') ? ['search' => request('search')] : [])) }}" 
           style="text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 600; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; {{ $group === 'tim-ahli' ? 'background: var(--primary); color: #ffffff;' : 'background: #ffffff; color: var(--text-dark); border: 1px solid var(--border);' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
            <span>Tim Ahli Pendukung YNKI</span>
            <span style="font-size: 11px; padding: 2px 7px; border-radius: 12px; {{ $group === 'tim-ahli' ? 'background: rgba(255,255,255,0.25); color: #ffffff;' : 'background: #eaf3eb; color: var(--primary);' }}">{{ $countTimAhli }}</span>
        </a>
    </div>

    <div class="card-table">
        <!-- Search Bar -->
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <form action="{{ route('admin.teams.index') }}" method="GET" style="display: flex; gap: 10px; flex-grow: 1; max-width: 480px;">
                @if (request('group') && request('group') !== 'all')
                    <input type="hidden" name="group" value="{{ request('group') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lengkap anggota..." class="form-control" style="padding: 8px 12px; font-size: 13px;">
                <button type="submit" class="btn-action btn-primary" style="padding: 8px 14px; font-size: 13px;">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.teams.index', request('group') ? ['group' => request('group')] : []) }}" class="btn-action btn-outline" style="padding: 8px 12px; font-size: 13px;">Reset</a>
                @endif
            </form>

            <div style="font-size: 12.5px; color: var(--text-muted);">
                Total: <strong>{{ $members->total() }}</strong> anggota
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Kategori Struktur</th>
                        <th>Jabatan / Posisi</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>
                                @if ($member->photo_path)
                                    <img src="/storage/{{ $member->photo_path }}" alt="{{ $member->full_name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 38px; height: 38px; border-radius: 50%; background: #e8f5ed; color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                                        {{ substr($member->full_name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $member->full_name }}</strong><br>
                                @if ($member->linkedin_url)
                                    <a href="{{ $member->linkedin_url }}" target="_blank" style="font-size: 11.5px; color: #0a66c2; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.6 1.6 0 0 0-1.6 1.6 1.6 1.6 0 0 0 1.6 1.6 1.6 1.6 0 0 0 1.6-1.6 1.6 1.6 0 0 0-1.6-1.6z"/></svg>
                                        LinkedIn
                                    </a>
                                @endif
                            </td>
                            <td>{{ $member->category->category_name ?? '-' }}</td>
                            <td>{{ $member->position }}</td>
                            <td>{{ $member->sort_order }}</td>
                            <td>
                                @if ($member->status === 'active')
                                    <span class="badge badge-success">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line></svg>
                                        Alumni
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.teams.edit', $member->id) }}" class="btn-action btn-outline">Edit</a>
                                    <form action="{{ route('admin.teams.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Hapus anggota tim ini?');">
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
                                    Tidak ditemukan anggota tim dengan kata kunci "<strong>{{ request('search') }}</strong>".
                                @else
                                    Belum ada pengurus atau anggota tim yang ditambahkan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $members->links() }}
        </div>
    </div>
@endsection
