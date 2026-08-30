@extends('layouts.app')

@section('title', 'Ajukan Data Warga Kurang Mampu')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;max-width:800px;">
    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#94a3b8;">
        <a href="{{ route('dashboard') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('bantuan-sosial.kurang-mampu') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Warga Kurang Mampu</a>
        <span>/</span>
        <span style="color:#1e293b;font-weight:600;">Ajukan</span>
    </div>

    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(245,158,11,0.3);">
            <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <h1 style="font-size:22px;font-weight:700;color:#1e293b;">Ajukan Data Warga Kurang Mampu</h1>
        </div>
    </div>

    <form method="POST" action="{{ route('bantuan-sosial.store-ajukan') }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:20px;">
        @csrf

        {{-- Section: Data Warga --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">1</div>
                <span style="font-weight:600;color:#1e293b;">Data Warga</span>
            </div>
            <div style="padding:20px;">
                <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Cari Warga *</label>
                <select name="anggota_keluarga_id" id="warga-select" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                    <option value="">Ketik nama atau NIK warga...</option>
                    @foreach($warga as $w)
                    <option value="{{ $w->id }}" data-nik="{{ $w->nik }}" data-nokk="{{ $w->kartuKeluarga->no_kk ?? '-' }}" data-nama="{{ $w->nama_lengkap }}">
                        {{ $w->nama_lengkap }} - {{ $w->nik }}
                    </option>
                    @endforeach
                </select>
                @error('anggota_keluarga_id')
                <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">NIK</label>
                        <input type="text" id="warga-nik" readonly placeholder="Otomatis terisi" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:#f8fafc;color:#64748b;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">No KK</label>
                        <input type="text" id="warga-nokk" readonly placeholder="Otomatis terisi" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:#f8fafc;color:#64748b;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Data Ekonomi --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">2</div>
                <span style="font-weight:600;color:#1e293b;">Data Ekonomi</span>
            </div>
            <div style="padding:20px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Penghasilan / Bulan (Rp) *</label>
                        <input type="number" name="penghasilan_per_bulan" required min="0" placeholder="0" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                        @error('penghasilan_per_bulan')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Pekerjaan</label>
                        <input type="text" name="pekerjaan" placeholder="Buruh harian, pensiunan, dll..." style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Jumlah Tanggungan *</label>
                        <input type="number" name="jumlah_tanggungan" required min="0" value="0" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Status Kepemilikan Rumah *</label>
                        <select name="status_rumah" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Kontrak">Kontrak</option>
                            <option value="Sewa">Sewa</option>
                            <option value="Numpang">Numpang</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Kondisi Rumah --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">3</div>
                <span style="font-weight:600;color:#1e293b;">Kondisi Rumah</span>
            </div>
            <div style="padding:20px;">
                <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:12px;">Kondisi Fisik Rumah *</label>
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
                    @php
                        $kondisiOptions = [
                            ['value' => 'Baik', 'icon' => '🏠', 'color' => '#10b981'],
                            ['value' => 'Sedang', 'icon' => '🏡', 'color' => '#f59e0b'],
                            ['value' => 'Rusak', 'icon' => '🏚️', 'color' => '#ea580c'],
                            ['value' => 'Sangat Rusak', 'icon' => '🏚️', 'color' => '#dc2626'],
                        ];
                    @endphp
                    @foreach($kondisiOptions as $opt)
                    <label style="display:flex;flex-direction:column;align-items:center;padding:16px 12px;border-radius:12px;border:2px solid #e2e8f0;cursor:pointer;transition:all 0.2s;" class="kondisi-card">
                        <input type="radio" name="kondisi_rumah" value="{{ $opt['value'] }}" {{ $loop->first ? 'checked' : '' }} style="display:none;">
                        <div style="font-size:28px;margin-bottom:6px;">{{ $opt['icon'] }}</div>
                        <span style="font-size:13px;font-weight:600;color:#374151;">{{ $opt['value'] }}</span>
                    </label>
                    @endforeach
                </div>
                @error('kondisi_rumah')
                <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Section: Alasan Pengajuan --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">4</div>
                <span style="font-weight:600;color:#1e293b;">Alasan Pengajuan</span>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:16px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Alasan Pengajuan Bantuan *</label>
                    <textarea name="alasan_pengajuan" required rows="3" placeholder="Jelaskan kondisi dan alasan pengajuan bantuan sosial..." style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;resize:vertical;">{{ old('alasan_pengajuan') }}</textarea>
                    @error('alasan_pengajuan')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Keterangan Tambahan <span style="font-weight:400;color:#94a3b8;">— optional</span></label>
                    <textarea name="keterangan" rows="2" placeholder="Informasi tambahan (optional)..." style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;resize:vertical;">{{ old('keterangan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section: Foto --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700;">5</div>
                <span style="font-weight:600;color:#1e293b;">Foto Rumah</span>
            </div>
            <div style="padding:20px;">
                <label style="display:block;padding:30px;border:2px dashed #e2e8f0;border-radius:12px;text-align:center;cursor:pointer;transition:all 0.2s;" for="foto_rumah">
                    <svg style="width:40px;height:40px;color:#94a3b8;margin:0 auto 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div style="font-size:14px;font-weight:600;color:#374151;">Upload Foto Rumah</div>
                    <div style="font-size:12px;color:#94a3b8;">JPG, PNG, WEBP — Maksimal 2 MB per file</div>
                    <input type="file" name="foto_rumah" id="foto_rumah" accept="image/*" style="display:none;">
                </label>
            </div>
        </div>

        {{-- Buttons --}}
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <a href="{{ route('bantuan-sosial.kurang-mampu') }}" style="padding:10px 24px;border-radius:10px;font-weight:600;font-size:14px;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;display:flex;align-items:center;gap:6px;">
                ← Batal
            </a>
            <button type="submit" style="padding:10px 24px;border-radius:10px;font-weight:600;font-size:14px;color:white;background:linear-gradient(135deg,#f59e0b,#d97706);border:none;cursor:pointer;box-shadow:0 4px 12px rgba(245,158,11,0.3);display:flex;align-items:center;gap:6px;">
                📤 Ajukan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('warga-select');
    const nikInput = document.getElementById('warga-nik');
    const nokkInput = document.getElementById('warga-nokk');
    
    select.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        nikInput.value = option.dataset.nik || '';
        nokkInput.value = option.dataset.nokk || '';
    });

    // Kondisi card selection
    document.querySelectorAll('.kondisi-card').forEach(card => {
        const radio = card.querySelector('input[type="radio"]');
        radio.addEventListener('change', function() {
            document.querySelectorAll('.kondisi-card').forEach(c => {
                c.style.borderColor = '#e2e8f0';
                c.style.background = 'white';
            });
            if (this.checked) {
                card.style.borderColor = '#0d9488';
                card.style.background = '#f0fdfa';
            }
        });
        if (radio.checked) {
            card.style.borderColor = '#0d9488';
            card.style.background = '#f0fdfa';
        }
    });
});
</script>
@endsection
