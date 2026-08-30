@extends('layouts.app')

@section('title', 'Registrasi Tamu')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;max-width:800px;">
    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3);">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h1 style="font-size:22px;font-weight:700;color:#1e293b;">Registrasi Tamu</h1>
        </div>
        <a href="{{ route('visitor.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;">← Kembali</a>
    </div>

    <form method="POST" action="{{ route('visitor.store') }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:20px;">
        @csrf

        {{-- Section 1: Tipe Kunjungan --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">1</div>
                <span style="font-weight:600;color:#1e293b;">Tipe Kunjungan</span>
            </div>
            <div style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <label id="tipe-singkat" style="display:flex;align-items:center;gap:14px;padding:20px;border-radius:12px;border:2px solid #0d9488;background:#f0fdfa;cursor:pointer;transition:all 0.2s;">
                        <input type="radio" name="tipe_kunjungan" value="singkat" checked style="display:none;">
                        <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                            <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#0d9488;">Kunjungan Singkat</div>
                            <div style="font-size:12px;color:#64748b;">Check Out saat selesai</div>
                        </div>
                    </label>
                    <label id="tipe-menginap" style="display:flex;align-items:center;gap:14px;padding:20px;border-radius:12px;border:2px solid #e2e8f0;background:white;cursor:pointer;transition:all 0.2s;">
                        <input type="radio" name="tipe_kunjungan" value="menginap" style="display:none;">
                        <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                            <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#d97706;">Tamu Menginap</div>
                            <div style="font-size:12px;color:#64748b;">Tinggal beberapa hari</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Section 2: Identitas Tamu --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">2</div>
                <span style="font-weight:600;color:#1e293b;">Identitas Tamu</span>
                <span style="font-size:12px;color:#94a3b8;">— Isi data tamu dengan lengkap</span>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:16px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Nama Lengkap Tamu *</label>
                    <input type="text" name="nama_tamu" value="{{ old('nama_tamu') }}" required placeholder="Nama lengkap tamu" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                    @error('nama_tamu')<div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">NIK <span style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                    <input type="text" name="nik" value="{{ old('nik') }}" placeholder="08123456789" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">No. HP / WhatsApp *</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08123456789" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Email <span style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">WA Host <span style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                    <input type="text" name="wa_host" value="{{ old('wa_host') }}" placeholder="No WA tuan rumah" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                </div>
            </div>
        </div>

        {{-- Section 3: Kendaraan --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">3</div>
                <span style="font-weight:600;color:#1e293b;">Kendaraan</span>
                <span style="font-size:12px;color:#94a3b8;">— Opsional</span>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:16px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">No. Plat Nomor *</label>
                        <input type="text" name="no_plat" value="{{ old('no_plat') }}" placeholder="K 1234 ABC" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;text-transform:uppercase;font-family:monospace;font-weight:600;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Jenis Kendaraan *</label>
                        <select name="jenis_kendaraan" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                            <option value="">— Pilih Jenis —</option>
                            <option value="Motor" {{ old('jenis_kendaraan') === 'Motor' ? 'selected' : '' }}>🏍️ Motor</option>
                            <option value="Mobil" {{ old('jenis_kendaraan') === 'Mobil' ? 'selected' : '' }}>🚗 Mobil</option>
                            <option value="Tak Kendaraan" {{ old('jenis_kendaraan') === 'Tak Kendaraan' ? 'selected' : '' }}>🚶 Tak Kendaraan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 4: Tujuan & Kepentingan --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">4</div>
                <span style="font-weight:600;color:#1e293b;">Tujuan & Kepentingan</span>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:16px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Tujuan / Blok Rumah *</label>
                        <input type="text" name="tujuan_blok" value="{{ old('tujuan_blok') }}" required placeholder="Blok A, No. 12" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Nama Tujuan Rumah</label>
                        <input type="text" name="nama_tujuan" value="{{ old('nama_tujuan') }}" placeholder="Nama tuan rumah yang dituju" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:10px;">Kepentingan *</label>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        @foreach(['Kunjungan Biasa', 'Antar Paket', 'Menginap', 'Kerja/Bisnis', 'Lainnya'] as $k)
                        <label style="display:flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;border:1px solid #e2e8f0;cursor:pointer;font-size:13px;font-weight:500;color:#374151;transition:all 0.2s;" class="kepentingan-card">
                            <input type="checkbox" name="kepentingan[]" value="{{ $k }}" {{ in_array($k, old('kepentingan', [])) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#0d9488;">
                            {{ $k }}
                        </label>
                        @endforeach
                    </div>
                    @error('kepentingan')<div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Deskripsi Kepentingan *</label>
                    <input type="text" name="deskripsi_kepentingan" value="{{ old('deskripsi_kepentingan') }}" required placeholder="ANTAR PAKET" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;text-transform:uppercase;">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Catatan Tambahan <span style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                    <textarea name="catatan_tambahan" rows="2" placeholder="Catatan atau informasi lainnya..." style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;resize:vertical;">{{ old('catatan_tambahan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section 5: Foto Dokumentasi --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">5</div>
                <span style="font-weight:600;color:#1e293b;">Foto Dokumentasi</span>
                <span style="font-size:12px;color:#94a3b8;">— Opsional</span>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:16px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:10px;">Tipe Foto</label>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        @foreach(['Foto Wajah', 'Foto KTP', 'Foto Kendaraan', 'Foto Barang', 'Lainnya'] as $tf)
                        <label style="display:flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;border:1px solid #e2e8f0;cursor:pointer;font-size:12px;font-weight:500;color:#374151;">
                            <input type="radio" name="tipe_foto" value="{{ $tf }}" {{ old('tipe_foto') === $tf ? 'checked' : '' }} style="width:14px;height:14px;accent-color:#0d9488;">
                            {{ $tf }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <label style="display:block;padding:30px;border:2px dashed #e2e8f0;border-radius:12px;text-align:center;cursor:pointer;transition:all 0.2s;" for="foto_dokumentasi">
                    <svg style="width:40px;height:40px;color:#94a3b8;margin:0 auto 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div style="font-size:14px;font-weight:600;color:#374151;">Drag & drop foto di sini</div>
                    <div style="font-size:12px;color:#94a3b8;">JPG, PNG, WEBP — Maks 2 MB</div>
                    <input type="file" name="foto_dokumentasi" id="foto_dokumentasi" accept="image/*" style="display:none;">
                </label>
            </div>
        </div>

        {{-- Buttons --}}
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <a href="{{ route('visitor.index') }}" style="padding:12px 24px;border-radius:10px;font-weight:600;font-size:14px;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;">Batal</a>
            <button type="submit" style="padding:12px 24px;border-radius:10px;font-weight:700;font-size:14px;color:white;background:linear-gradient(135deg,#0d9488,#0f766e);border:none;cursor:pointer;box-shadow:0 4px 12px rgba(13,148,136,0.3);display:flex;align-items:center;gap:8px;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan & Check-In
            </button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('input[name="tipe_kunjungan"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('tipe-singkat').style.borderColor = this.value === 'singkat' ? '#0d9488' : '#e2e8f0';
        document.getElementById('tipe-singkat').style.background = this.value === 'singkat' ? '#f0fdfa' : 'white';
        document.getElementById('tipe-menginap').style.borderColor = this.value === 'menginap' ? '#0d9488' : '#e2e8f0';
        document.getElementById('tipe-menginap').style.background = this.value === 'menginap' ? '#f0fdfa' : 'white';
    });
});
document.querySelectorAll('.kepentingan-card').forEach(card => {
    const cb = card.querySelector('input[type="checkbox"]');
    cb.addEventListener('change', function() {
        card.style.borderColor = this.checked ? '#0d9488' : '#e2e8f0';
        card.style.background = this.checked ? '#f0fdfa' : 'white';
    });
});
</script>
@endsection
