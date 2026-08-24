@extends('layouts.admin')

@section('title', 'Tambah Rekening Donasi')

@section('content')
    <div style="max-width: 680px; margin: 0 auto;">
        <div class="card-table">
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 20px;">
                Form Rekening / QRIS Baru
            </h2>

            <form action="{{ route('admin.accounts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Bank / E-Wallet *</label>
                    <input type="text" name="bank_name" placeholder="Misal: Bank Mandiri / BCA / QRIS" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nomor Rekening *</label>
                    <input type="text" name="account_number" placeholder="146-00-1234567-8" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Pemilik Rekening (Atas Nama) *</label>
                    <input type="text" name="account_holder" value="Yayasan Natural Kapital Indonesia" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Cabang Bank (Opsional)</label>
                    <input type="text" name="branch_name" placeholder="KC Pontianak" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Unggah Barcode QRIS (Opsional)</label>
                    <input type="file" name="qris_image" accept="image/*" style="width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label for="is_active" style="font-size: 13px; font-weight: 600;">Aktifkan untuk menerima donasi publik</label>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 20px;">Simpan Rekening</button>
                    <a href="{{ route('admin.accounts.index') }}" class="btn-action btn-outline" style="padding: 10px 20px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
