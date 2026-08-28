@extends('layouts.admin')

@section('title', 'Pesan Masuk Kontak Kami')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 700; color: var(--primary-dark); margin: 0 0 4px;">
                Daftar Pesan Masuk (Kontak Kami)
            </h2>
            <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                Kelola dan tanggapi pesan atau pertanyaan yang dikirimkan publik melalui form kontak.
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('admin.contact-messages.index') }}" class="btn-action {{ !request('unread') ? 'btn-primary' : 'btn-outline' }}" style="font-size: 12.5px;">
                Semua Pesan
            </a>
            <a href="{{ route('admin.contact-messages.index', ['unread' => 1]) }}" class="btn-action {{ request('unread') == '1' ? 'btn-primary' : 'btn-outline' }}" style="font-size: 12.5px; position: relative;">
                Belum Dibaca
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
            <form action="{{ route('admin.contact-messages.index') }}" method="GET" style="display: flex; gap: 10px; flex-grow: 1; max-width: 450px;">
                @if (request('unread'))
                    <input type="hidden" name="unread" value="{{ request('unread') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, perihal atau pesan..." class="form-control" style="padding: 8px 12px; font-size: 13px;">
                <button type="submit" class="btn-action btn-primary" style="padding: 8px 14px; font-size: 13px;">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.contact-messages.index', request('unread') ? ['unread' => 1] : []) }}" class="btn-action btn-outline" style="padding: 8px 12px; font-size: 13px;">Reset</a>
                @endif
            </form>

            <div style="font-size: 12.5px; color: var(--text-muted);">
                Total: <strong>{{ $messages->total() }}</strong> pesan
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">Status</th>
                        <th>Pengirim</th>
                        <th>Kontak</th>
                        <th>Topik / Perihal</th>
                        <th>Ringkasan Pesan</th>
                        <th>Tanggal</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $msg)
                        <tr style="{{ !$msg->is_read ? 'background-color: rgba(17, 119, 16, 0.04); font-weight: 500;' : '' }}">
                            <td style="text-align: center;">
                                @if (!$msg->is_read)
                                    <span title="Belum dibaca" style="display: inline-block; width: 10px; height: 10px; background: #FF8000; border-radius: 50%;"></span>
                                @else
                                    <span title="Sudah dibaca" style="display: inline-block; width: 10px; height: 10px; background: #cbd5e1; border-radius: 50%;"></span>
                                @endif
                            </td>
                            <td>
                                <strong style="color: var(--primary-dark); font-size: 13.5px;">{{ $msg->name }}</strong>
                            </td>
                            <td>
                                <div style="font-size: 12.5px;">
                                    <a href="mailto:{{ $msg->email }}" style="color: var(--primary); text-decoration: none;">{{ $msg->email }}</a>
                                </div>
                                @if ($msg->phone)
                                    <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone) }}" target="_blank" style="color: #25d366; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;">
                                            WhatsApp: {{ $msg->phone }}
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span style="background: #f1f5f9; color: #334155; font-size: 11.5px; font-weight: 600; padding: 3px 8px; border-radius: 4px; text-transform: capitalize;">
                                    {{ str_replace('_', ' ', $msg->subject ?? 'Umum') }}
                                </span>
                            </td>
                            <td>
                                <div style="max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 13px; color: var(--text-dark);">
                                    {{ $msg->message }}
                                </div>
                            </td>
                            <td style="font-size: 12px; color: var(--text-muted); white-space: nowrap;">
                                {{ $msg->created_at ? $msg->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="btn-action btn-outline" style="padding: 5px 10px; font-size: 12px;" title="Lihat Pesan Lengkap">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        Buka
                                    </a>
                                    
                                    <form action="{{ route('admin.contact-messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan dari {{ addslashes($msg->name) }}?');" style="display: inline;">
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
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 8px; opacity: 0.5;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <p style="margin: 0; font-size: 14px;">Belum ada pesan masuk yang diterima.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($messages->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
@endsection
