@extends('layouts.app')

@section('title', 'Tambah Penerima Bantuan')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;max-width:800px;">
    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#94a3b8;">
        <a href="{{ route('dashboard') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('bantuan-sosial.index') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Bantuan Sosial</a>
        <span>/</span>
        <span style="color:#1e293b;font-weight:600;">Tambah Penerima</span>
    </div>

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3);">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <div>
                <h1 style="font-size:22px;font-weight:700;color:#1e293b;">Tambah Penerima Bantuan</h1>
            </div>
        </div>
        <a href="{{ route('bantuan-sosial.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;">
            ← Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('bantuan-sosial.store-penerima') }}" style="display:flex;flex-direction:column;gap:20px;">
        @csrf

        {{-- Form Card --}}
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:16px;height:16px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span style="font-weight:600;color:#1e293b;">Form Pendataan Penerima Bantuan</span>
            </div>
            <div style="padding:24px;display:flex;flex-direction:column;gap:24px;">
                {{-- Data Penerima --}}
                <div>
                    <h3 style="font-size:14px;font-weight:700;color:#374151;margin-bottom:16px;text-transform:uppercase;letter-spacing:0.5px;">Data Penerima</h3>
                    
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Pilih Warga *</label>
                    <select name="anggota_keluarga_id" id="warga-select" required style="width:100%;padding:12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                        <option value="">— ketik nama atau NIK —</option>
                        @foreach($warga as $w)
                        <option value="{{ $w->id }}" data-nik="{{ $w->nik }}" data-nokk="{{ $w->kartuKeluarga->no_kk ?? '-' }}">
                            {{ $w->nama_lengkap }} - {{ $w->nik }}
                        </option>
                        @endforeach
                    </select>
                    @error('anggota_keluarga_id')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror

                    <div id="warga-info" style="display:none;margin-top:12px;padding:12px 16px;border-radius:10px;background:#f0fdfa;border:1px solid #99f6e4;display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;">
                                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <div id="warga-nama" style="font-weight:600;color:#1e293b;"></div>
                                <div style="font-size:13px;color:#64748b;">
                                    NIK: <span id="warga-nik-display"></span> | No KK: <span id="warga-nokk-display"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="resetWarga()" style="padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;color:#0d9488;background:white;border:1px solid #99f6e4;cursor:pointer;">🔄 Ganti</button>
                    </div>

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

                <hr style="border:none;border-top:1px solid #e2e8f0;">

                {{-- Jenis Bantuan --}}
                <div>
                    <h3 style="font-size:14px;font-weight:700;color:#374151;margin-bottom:16px;text-transform:uppercase;letter-spacing:0.5px;">Jenis Bantuan *</h3>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                        @php
                            $jenisOptions = [
                                ['value' => 'BLT', 'icon' => '💵', 'label' => 'Bantuan Langsung Tunai', 'color' => '#dc2626'],
                                ['value' => 'Sembako', 'icon' => '📦', 'label' => 'Bantuan Pangan/Sembako', 'color' => '#16a34a'],
                                ['value' => 'PKH', 'icon' => '🏠', 'label' => 'Program Keluarga Harapan', 'color' => '#2563eb'],
                                ['value' => 'BPNT', 'icon' => '🍚', 'label' => 'Bantuan Pangan Non Tunai', 'color' => '#ca8a04'],
                                ['value' => 'Lansia', 'icon' => '👴', 'label' => 'Bantuan Lanjut Usia', 'color' => '#9333ea'],
                                ['value' => 'Lainnya', 'icon' => '⚪', 'label' => 'Bantuan Lain Lain', 'color' => '#64748b'],
                            ];
                        @endphp
                        @foreach($jenisOptions as $opt)
                        <label style="display:flex;align-items:center;gap:10px;padding:12px;border-radius:10px;border:2px solid #e2e8f0;cursor:pointer;transition:all 0.2s;" class="jenis-card">
                            <input type="checkbox" name="jenis_bantuan[]" value="{{ $opt['value'] }}" style="width:18px;height:18px;accent-color:#0d9488;">
                            <span style="font-size:20px;">{{ $opt['icon'] }}</span>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:#1e293b;">{{ $opt['value'] }}</div>
                                <div style="font-size:11px;color:#94a3b8;">{{ $opt['label'] }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('jenis_bantuan')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <hr style="border:none;border-top:1px solid #e2e8f0;">

                {{-- Info Bantuan --}}
                <div>
                    <h3 style="font-size:14px;font-weight:700;color:#374151;margin-bottom:16px;text-transform:uppercase;letter-spacing:0.5px;">Info Bantuan</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Tahun *</label>
                            <select name="tahun" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                                @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Status</label>
                            <select name="status" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Keterangan <span style="font-weight:400;color:#94a3b8;">— optional</span></label>
                        <textarea name="keterangan" rows="2" placeholder="Catatan tambahan..." style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;resize:vertical;">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <a href="{{ route('bantuan-sosial.index') }}" style="padding:10px 24px;border-radius:10px;font-weight:600;font-size:14px;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;display:flex;align-items:center;gap:6px;">
                ⊙ Batal
            </a>
            <button type="submit" style="padding:10px 24px;border-radius:10px;font-weight:600;font-size:14px;color:white;background:linear-gradient(135deg,#0d9488,#0f766e);border:none;cursor:pointer;box-shadow:0 4px 12px rgba(13,148,136,0.3);display:flex;align-items:center;gap:6px;">
                💾 Tambahkan Data
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('warga-select');
    const nikInput = document.getElementById('warga-nik');
    const nokkInput = document.getElementById('warga-nokk');
    const infoBox = document.getElementById('warga-info');
    
    select.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (this.value) {
            nikInput.value = option.dataset.nik || '';
            nokkInput.value = option.dataset.nokk || '';
            document.getElementById('warga-nama').textContent = option.textContent.split(' - ')[0];
            document.getElementById('warga-nik-display').textContent = option.dataset.nik || '-';
            document.getElementById('warga-nokk-display').textContent = option.dataset.nokk || '-';
            infoBox.style.display = 'flex';
            select.style.display = 'none';
        }
    });

    // Jenis bantuan checkbox styling
    document.querySelectorAll('.jenis-card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                card.style.borderColor = '#0d9488';
                card.style.background = '#f0fdfa';
            } else {
                card.style.borderColor = '#e2e8f0';
                card.style.background = 'white';
            }
        });
    });
});

function resetWarga() {
    const select = document.getElementById('warga-select');
    const infoBox = document.getElementById('warga-info');
    select.value = '';
    select.style.display = 'block';
    infoBox.style.display = 'none';
    document.getElementById('warga-nik').value = '';
    document.getElementById('warga-nokk').value = '';
}
</script>
@endsection
