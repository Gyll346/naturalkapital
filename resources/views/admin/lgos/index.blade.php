@extends('layouts.admin')

@section('title', 'Komponen Pendukung LGOS')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 700; color: var(--primary-dark);">
            Daftar Komponen Pendukung LGOS
        </h2>
        <a href="{{ route('admin.lgos.create') }}" class="btn-action btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Komponen Baru
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
                        <th>Kode</th>
                        <th>Nama Komponen</th>
                        <th>Deskripsi</th>
                        <th>Dokumen PDF</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($components as $c)
                        <tr>
                            <td><code>{{ $c->code ?? '-' }}</code></td>
                            <td><strong>{{ $c->component_name }}</strong></td>
                            <td style="max-width: 350px;">{{ Str::limit($c->description, 120) }}</td>
                            <td>
                                @if ($c->document_pdf_path)
                                    <a href="/storage/{{ $c->document_pdf_path }}" target="_blank" style="color: #cf222e; font-weight: 600; text-decoration: none; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                        Unduh PDF
                                    </a>
                                @else
                                    <span style="color: var(--text-muted); font-size: 12px;">Belum diunggah</span>
                                @endif
                            </td>
                            <td>{{ $c->sort_order }}</td>
                            <td>
                                @if ($c->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.lgos.edit', $c->id) }}" class="btn-action btn-outline">Edit</a>
                                    <form action="{{ route('admin.lgos.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus komponen LGOS ini?');">
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
                                Belum ada komponen LGOS yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
