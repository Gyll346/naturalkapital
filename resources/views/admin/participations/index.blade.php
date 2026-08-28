@extends('layouts.admin')

@section('title', 'Pendaftaran Ikut Serta & Relawan')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 700; color: var(--primary-dark); margin: 0 0 4px;">
                Daftar Formulir Ikut Serta &amp; Kemitraan
            </h2>
            <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                Kelola data calon relawan, pendaftar riset/magang, dan inisiator kemitraan CSO dari formulir ikut serta.
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('admin.participations.index') }}" class="btn-action {{ !request('unread') ? 'btn-primary' : 'btn-outline' }}" style="font-size: 12.5px;">
                Semua Formulir
            </a>
            <a href="{{ route('admin.participations.index', ['unread' => 1]) }}" class="btn-action {{ request('unread') == '1' ? 'btn-primary' : 'btn-outline' }}" style="font-size: 12.5px;">
                Belum Ditinjau
                @if ($unreadCount > 0)
                    <span style="background: #e63946; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 10px; margin-left: 4px;">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    @if (session('success'))
        <div style="background: #dafbe1; color: #1a7f37; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card-table">
        <!-- Search & Filter Form -->
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <form action="{{ route('admin.participations.index') }}" method="GET" style="display: flex; gap: 10px; flex-grow: 1; max-width: 600px; flex-wrap: wrap;">
                @if (request('unread'))
                    <input type="hidden" name="unread" value="{{ request('unread') }}">
                @endif
                <select name="interest" class="form-control" style="width: auto; padding: 8px 12px; font-size: 13px;">
                    <option value="">Semua Peminatan</option>
                    <option value="relawan" {{ request('interest') == 'relawan' ? 'selected' : '' }}>Relawan Lapangan</option>
                    <option value="riset_magang" {{ request('interest') == 'riset_magang' ? 'selected' : '' }}>Magang / Riset</option>
                    <option value="kemitraan_cso" {{ request('interest') == 'kemitraan_cso' ? 'selected' : '' }}>Kemitraan CSO</option>
                    <option value="kolaborasi_media" {{ request('interest') == 'kolaborasi_media' ? 'selected' : '' }}>Kolaborasi Media</option>
                    <option value="lainnya" {{ request('interest') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, no HP, atau motivasi..." class="form-control" style="flex: 1; min-width: 180px; padding: 8px 12px; font-size: 13px;">
                <button type="submit" class="btn-action btn-primary" style="padding: 8px 14px; font-size: 13px;">
                    Filter
                </button>
                @if (request('search') || request('interest'))
                    <a href="{{ route('admin.participations.index', request('unread') ? ['unread' => 1] : []) }}" class="btn-action btn-outline" style="padding: 8px 12px; font-size: 13px;">Reset</a>
                @endif
            </form>

            <div style="font-size: 12.5px; color: var(--text-muted);">
                Total: <strong>{{ $participations->total() }}</strong> formulir
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">Status</th>
                        <th>Nama Pendaftar</th>
                        <th>Kontak (Email & WA)</th>
                        <th>Peminatan Peran</th>
                        <th>Latar Belakang / Pesan</th>
                        <th>Tanggal Daftar</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($participations as $p)
                        <tr style="{{ !$p->is_read ? 'background-color: rgba(255, 128, 0, 0.04); font-weight: 500;' : '' }}">
                            <td style="text-align: center;">
                                @if (!$p->is_read)
                                    <span title="Belum ditinjau" style="display: inline-block; width: 10px; height: 10px; background: #FF8000; border-radius: 50%;"></span>
                                @else
                                    <span title="Sudah ditinjau" style="display: inline-block; width: 10px; height: 10px; background: #cbd5e1; border-radius: 50%;"></span>
                                @endif
                            </td>
                            <td>
                                <strong style="color: var(--primary-dark); font-size: 13.5px;">{{ $p->name }}</strong>
                            </td>
                            <td>
                                <div style="font-size: 12.5px;">
                                    <a href="mailto:{{ $p->email }}" style="color: var(--primary); text-decoration: none;">{{ $p->email }}</a>
                                </div>
                                <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->phone) }}" target="_blank" style="color: #25d366; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;">
                                        WA: {{ $p->phone }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeColor = '#117710';
                                    $badgeBg = '#eaf5ee';
                                    if ($p->interest === 'relawan') {
                                        $badgeColor = '#0284c7'; $badgeBg = '#e0f2fe';
                                    } elseif ($p->interest === 'riset_magang') {
                                        $badgeColor = '#7c3aed'; $badgeBg = '#ede9fe';
                                    } elseif ($p->interest === 'kemitraan_cso') {
                                        $badgeColor = '#b45309'; $badgeBg = '#fef3c7';
                                    }
                                @endphp
                                <span style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; font-size: 11.5px; font-weight: 700; padding: 4px 9px; border-radius: 6px; text-transform: capitalize; display: inline-block;">
                                    {{ str_replace('_', ' ', $p->interest) }}
                                </span>
                            </td>
                            <td>
                                <div style="max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 13px; color: var(--text-dark);">
                                    {{ $p->message ?: '-' }}
                                </div>
                            </td>
                            <td style="font-size: 12px; color: var(--text-muted); white-space: nowrap;">
                                {{ $p->created_at ? $p->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="{{ route('admin.participations.show', $p->id) }}" class="btn-action btn-outline" style="padding: 5px 10px; font-size: 12px;" title="Lihat Detail Pendaftaran">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        Detail
                                    </a>
                                    
                                    <form action="{{ route('admin.participations.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data formulir dari {{ addslashes($p->name) }}?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action" style="padding: 5px 8px; font-size: 12px; background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3;" title="Hapus">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 36px 20px; color: var(--text-muted);">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 8px; opacity: 0.5;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <p style="margin: 0; font-size: 14px;">Belum ada pendaftaran keterlibatan yang diterima.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($participations->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
                {{ $participations->links() }}
            </div>
        @endif
    </div>
@endsection
