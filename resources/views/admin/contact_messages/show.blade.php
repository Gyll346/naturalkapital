@extends('layouts.admin')

@section('title', 'Detail Pesan Masuk - ' . $message->name)

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.contact-messages.index') }}" class="btn-action btn-outline" style="font-size: 13px;">
            &larr; Kembali ke Daftar Pesan
        </a>

        <div style="display: flex; gap: 8px;">
            @if ($message->is_read)
                <form action="{{ route('admin.contact-messages.unread', $message->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-action btn-outline" style="font-size: 12.5px;">
                        Tandai Belum Dibaca
                    </button>
                </form>
            @else
                <form action="{{ route('admin.contact-messages.read', $message->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-action btn-outline" style="font-size: 12.5px;">
                        Tandai Sudah Dibaca
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini secara permanen?');" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action" style="font-size: 12.5px; background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3;">
                    Hapus Pesan
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div style="background: #dafbe1; color: #1a7f37; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Konten Pesan Utama -->
        <div class="card-table" style="padding: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 16px;">
                <div>
                    <span style="background: #eaf5ee; color: var(--primary-dark); font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                        Topik: {{ str_replace('_', ' ', $message->subject ?? 'Umum') }}
                    </span>
                    <h2 style="font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 700; color: var(--primary-dark); margin: 12px 0 4px;">
                        Pesan dari {{ $message->name }}
                    </h2>
                    <div style="font-size: 12.5px; color: var(--text-muted);">
                        Diterima pada: {{ $message->created_at ? $message->created_at->format('l, d F Y - H:i') . ' WIB' : '-' }}
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <h4 style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px;">
                    Isi Pesan:
                </h4>
                <div style="background: #fbfdfc; border: 1px solid #e2ece5; border-radius: 10px; padding: 20px; font-size: 15px; line-height: 1.8; color: #1e293b; white-space: pre-wrap; font-family: inherit;">
{{ $message->message }}
                </div>
            </div>

            <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border); display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="mailto:{{ $message->email }}?subject=Re:%20{{ urlencode($message->subject ?? 'Tanggapan dari YNKI') }}" class="btn-action btn-primary" style="font-size: 13px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    Balas via Email ({{ $message->email }})
                </a>

                @if ($message->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}?text={{ urlencode('Halo ' . $message->name . ', kami dari Yayasan Natural Kapital Indonesia menanggapi pesan Anda...') }}" target="_blank" class="btn-action" style="font-size: 13px; background: #25d366; color: #ffffff; border: none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                @endif
            </div>
        </div>

        <!-- Sidebar Profil Pengirim -->
        <div style="display: flex; flex-direction: column; gap: 18px;">
            <div class="card-table" style="padding: 22px;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--primary-dark); margin: 0 0 16px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                    Informasi Pengirim
                </h3>

                <div style="margin-bottom: 14px;">
                    <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Nama Lengkap</div>
                    <div style="font-size: 14px; font-weight: 600; color: var(--text-dark); margin-top: 3px;">{{ $message->name }}</div>
                </div>

                <div style="margin-bottom: 14px;">
                    <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Alamat Email</div>
                    <div style="font-size: 13.5px; color: var(--primary); margin-top: 3px;">
                        <a href="mailto:{{ $message->email }}" style="color: inherit; text-decoration: none;">{{ $message->email }}</a>
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">No. Telepon / WA</div>
                    <div style="font-size: 13.5px; color: var(--text-dark); margin-top: 3px;">
                        {{ $message->phone ?: 'Tidak dicantumkan' }}
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Waktu Kirim</div>
                    <div style="font-size: 13px; color: var(--text-dark); margin-top: 3px;">
                        {{ $message->created_at ? $message->created_at->diffForHumans() : '-' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Status Baca</div>
                    <div style="margin-top: 4px;">
                        @if ($message->is_read)
                            <span style="background: #dafbe1; color: #1a7f37; font-size: 11.5px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">Sudah Dibaca</span>
                        @else
                            <span style="background: #fff3e6; color: #FF8000; font-size: 11.5px; font-weight: 700; padding: 3px 8px; border-radius: 4px;">Belum Dibaca</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
