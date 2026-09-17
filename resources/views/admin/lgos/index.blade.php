@extends('layouts.admin')

@section('title', 'Komponen LGOS')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 700; color: var(--primary-dark); margin: 0 0 4px;">
                Daftar 8 Komponen LGOS
            </h2>
            <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                Kelola dokumen dan pengaturan 8 komponen Landscape Governance Operating System. Komponen 1–5 menyediakan unduhan dokumen, sedangkan komponen 6–8 mengarahkan ke form Kontak Kami.
            </p>
        </div>
        <a href="{{ route('admin.lgos.create') }}" class="btn-action btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Komponen
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
            <form action="{{ route('admin.lgos.index') }}" method="GET" style="display: flex; gap: 10px; flex-grow: 1; max-width: 480px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama komponen atau peran..." class="form-control" style="padding: 8px 12px; font-size: 13px;">
                <button type="submit" class="btn-action btn-primary" style="padding: 8px 14px; font-size: 13px;">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.lgos.index') }}" class="btn-action btn-outline" style="padding: 8px 12px; font-size: 13px;">Reset</a>
                @endif
            </form>

            <div style="font-size: 12.5px; color: var(--text-muted);">
                Total: <strong>{{ $components->count() }}</strong> komponen
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 110px;">Kode</th>
                        <th>Nama Komponen & Peran</th>
                        <th>Deskripsi Singkat</th>
                        <th>Dokumen / Aksi Card</th>
                        <th style="width: 70px; text-align: center;">Urutan</th>
                        <th style="width: 90px; text-align: center;">Status</th>
                        <th style="width: 130px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($components as $c)
                        <tr>
                            <td><span style="background: #eaf5ee; color: #117710; font-weight: 700; font-size: 11px; padding: 3px 8px; border-radius: 4px; letter-spacing: 0.5px;">{{ $c->code ?? 'KOMP-' . $c->sort_order }}</span></td>
                            <td>
                                <strong style="font-size: 13.5px; color: var(--text-dark); display: block;">{{ $c->component_name }}</strong>
                                @if ($c->role)
                                    <span style="font-size: 12px; color: #117710; font-weight: 600;">{{ $c->role }}</span>
                                @endif
                            </td>
                            <td style="max-width: 300px; font-size: 12.5px; color: #536b5f;">{{ Str::limit($c->description, 100) }}</td>
                            <td>
                                @if ($c->sort_order <= 5)
                                    @if ($c->document_pdf_path)
                                        <a href="/storage/{{ $c->document_pdf_path }}" target="_blank" style="color: #117710; font-weight: 600; text-decoration: none; font-size: 12px; display: inline-flex; align-items: center; gap: 5px; background: #eaf6ea; padding: 4px 10px; border-radius: 6px; border: 1px solid #b5ddb5;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                            Unduh PDF
                                        </a>
                                    @else
                                        <span style="color: #c45e00; font-size: 11.5px; background: #fff4ea; padding: 4px 8px; border-radius: 6px; border: 1px solid #fed2a4; display: inline-flex; align-items: center; gap: 4px;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            Belum Diunggah
                                        </span>
                                    @endif
                                @else
                                    <span style="color: #6366f1; font-size: 11.5px; background: #eef2ff; padding: 4px 8px; border-radius: 6px; border: 1px solid #c7d2fe; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                        Redirect Kontak Kami
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center; font-weight: 700;">{{ $c->sort_order }}</td>
                            <td style="text-align: center;">
                                @if ($c->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="{{ route('admin.lgos.edit', $c->id) }}" class="btn-action btn-outline" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                    <form action="{{ route('admin.lgos.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus komponen LGOS ini?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action" style="background: #ffebe9; color: #cf222e; border: none; padding: 5px 10px; font-size: 12px;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                @if (request('search'))
                                    Tidak ditemukan komponen LGOS dengan kata kunci "<strong>{{ request('search') }}</strong>".
                                @else
                                    Belum ada komponen LGOS yang ditambahkan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
