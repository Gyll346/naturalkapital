@extends('layouts.app')

@section('title', 'Donasi & Kemitraan - Yayasan Natural Kapital Indonesia')

@section('content')
    <div style="background: linear-gradient(150deg, #092b1a 0%, #0F5132 100%); color: #ffffff; padding: 60px 24px; text-align: center;">
        <h1 style="font-family: 'Montserrat', sans-serif; font-size: 32px; font-weight: 800; margin-bottom: 12px;">
            Dukung Pelestarian Lanskap Kalimantan
        </h1>
        <p style="font-size: 16px; color: rgba(255,255,255,0.85); max-width: 680px; margin: 0 auto;">
            Setiap kontribusi Anda disalurkan secara langsung untuk aksi restorasi gambut, pemberdayaan masyarakat adat, dan penguatan tata kelola lanskap.
        </p>
    </div>

    <div style="max-width: 1100px; margin: -30px auto 60px; padding: 0 24px;">
        @if (session('success'))
            <div style="background: #dafbe1; border: 1px solid #a2e8b6; color: #1a7f37; padding: 18px 24px; border-radius: 12px; font-weight: 600; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px;">
            <!-- Informasi Rekening & QRIS -->
            <div>
                <h2 style="font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 700; color: #0F5132; margin-bottom: 20px;">
                    💳 Rekening Resmi YNKI
                </h2>

                @foreach ($accounts as $acc)
                    <div style="background: #ffffff; border: 1.5px solid #d8e6df; border-radius: 14px; padding: 22px; margin-bottom: 20px; box-shadow: 0 4px 14px rgba(0,0,0,0.02);">
                        <span style="font-size: 11px; font-weight: 800; color: #0F5132; text-transform: uppercase; letter-spacing: 1px;">
                            {{ $acc->bank_name }}
                        </span>
                        <div style="font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 800; color: #1b2e23; margin: 8px 0;">
                            {{ $acc->account_number }}
                        </div>
                        <div style="font-size: 13.5px; font-weight: 600; color: #5a7364;">
                            a.n. {{ $acc->account_holder }}
                        </div>
                        @if ($acc->branch_name)
                            <div style="font-size: 12px; color: #8fa699; margin-top: 4px;">Cabang: {{ $acc->branch_name }}</div>
                        @endif

                        @if ($acc->qris_image_path)
                            <div style="margin-top: 16px; text-align: center; border-top: 1px solid #edf2ee; padding-top: 14px;">
                                <img src="/storage/{{ $acc->qris_image_path }}" alt="QRIS {{ $acc->bank_name }}" style="max-width: 180px; border-radius: 8px; border: 1px solid #e1ebe5;">
                                <p style="font-size: 11px; color: #627b6d; margin-top: 6px;">Pindai QRIS di atas untuk pembayaran instan</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Form Konfirmasi Donasi -->
            <div style="background: #ffffff; border: 1px solid #d8e6df; border-radius: 16px; padding: 32px; box-shadow: 0 8px 24px rgba(0,0,0,0.04);">
                <h2 style="font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 700; color: #0F5132; margin-bottom: 20px;">
                    📝 Form Konfirmasi & Bukti Transfer
                </h2>

                <form action="{{ route('public.donation.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Donatur / Organisasi *</label>
                        <input type="text" name="donor_name" value="{{ old('donor_name') }}" required style="width: 100%; padding: 10px 14px; border: 1.5px solid #d1e2d8; border-radius: 8px; font-size: 14px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Email *</label>
                            <input type="email" name="donor_email" value="{{ old('donor_email') }}" required style="width: 100%; padding: 10px 14px; border: 1.5px solid #d1e2d8; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nomor WhatsApp</label>
                            <input type="text" name="donor_phone" value="{{ old('donor_phone') }}" style="width: 100%; padding: 10px 14px; border: 1.5px solid #d1e2d8; border-radius: 8px; font-size: 14px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Program Donasi *</label>
                        <select name="program_id" style="width: 100%; padding: 10px 14px; border: 1.5px solid #d1e2d8; border-radius: 8px; font-size: 14px; background: #ffffff;">
                            <option value="">-- Donasi Umum Lanskap YNKI --</option>
                            @foreach ($programs as $prog)
                                <option value="{{ $prog->id }}" {{ request('program') == $prog->id ? 'selected' : '' }}>
                                    {{ $prog->program_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Rekening Tujuan Transfer *</label>
                        <select name="donation_account_id" required style="width: 100%; padding: 10px 14px; border: 1.5px solid #d1e2d8; border-radius: 8px; font-size: 14px; background: #ffffff;">
                            @foreach ($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->bank_name }} - {{ $acc->account_number }} (a.n {{ $acc->account_holder }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Jumlah Donasi (Rp) *</label>
                        <input type="number" name="amount" min="10000" step="1000" placeholder="100000" value="{{ old('amount') }}" required style="width: 100%; padding: 10px 14px; border: 1.5px solid #d1e2d8; border-radius: 8px; font-size: 14px;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Unggah Bukti Struk Transfer (Foto/Gambar) *</label>
                        <input type="file" name="transfer_proof" accept="image/*" required style="width: 100%; padding: 8px 12px; border: 1.5px solid #d1e2d8; border-radius: 8px; font-size: 13px;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Pesan / Doa / Catatan</label>
                        <textarea name="donor_notes" rows="3" style="width: 100%; padding: 10px 14px; border: 1.5px solid #d1e2d8; border-radius: 8px; font-size: 14px;"></textarea>
                    </div>

                    <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1">
                        <label for="is_anonymous" style="font-size: 13px; color: #5a7364;">Sembunyikan nama saya dari daftar publik (Anonim / Hamba Allah)</label>
                    </div>

                    <button type="submit" style="width: 100%; background: linear-gradient(135deg, #0F5132 0%, #198754 100%); color: #ffffff; border: none; padding: 14px; border-radius: 10px; font-family: 'Montserrat', sans-serif; font-size: 15px; font-weight: 700; cursor: pointer; box-shadow: 0 6px 18px rgba(15, 81, 50, 0.3);">
                        Kirim Konfirmasi Donasi
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
