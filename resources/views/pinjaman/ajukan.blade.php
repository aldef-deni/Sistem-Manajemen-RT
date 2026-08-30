@extends('layouts.app')

@section('title', 'Form Pengajuan Pinjaman')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    {{-- Breadcrumb --}}
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('pinjaman.index') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; font-size: 0.875rem; text-decoration: none;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    {{-- Header --}}
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
        <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: linear-gradient(135deg, #14b8a6, #0d9488); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
            <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </div>
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Form Pengajuan Pinjaman</h1>
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #64748b;">
                <a href="{{ route('dashboard') }}" style="color: #0d9488; font-weight: 500; text-decoration: none;">Dashboard</a>
                <span>/</span>
                <a href="{{ route('pinjaman.index') }}" style="color: #0d9488; font-weight: 500; text-decoration: none;">Pinjaman</a>
                <span>/</span>
                <span style="color: #334155; font-weight: 500;">Ajukan Pinjaman</span>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; margin-bottom: 1rem;">{{ session('error') }}</div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        {{-- Form --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                <div style="width: 36px; height: 36px; border-radius: 0.5rem; background: linear-gradient(135deg, #14b8a6, #0d9488); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 18px; height: 18px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h2 style="font-size: 1.0625rem; font-weight: 700; color: #1e293b;">Detail Pengajuan</h2>
            </div>

            <form action="{{ route('pinjaman.store-ajukan') }}" method="POST">
                @csrf

                {{-- Pemohon --}}
                <div style="margin-bottom: 1.25rem;">
                    <h4 style="font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px;">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Pemohon
                    </h4>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Pilih Warga <span style="color: #ef4444;">*</span></label>
                    <select name="anggota_keluarga_id" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; background: white;">
                        <option value="">--- Pilih Warga ---</option>
                        @foreach($wargas as $w)
                        <option value="{{ $w->id }}" {{ old('anggota_keluarga_id') == $w->id ? 'selected' : '' }}>{{ $w->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    @error('anggota_keluarga_id')<p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                </div>

                {{-- Informasi Pinjaman --}}
                <div style="margin-bottom: 1.25rem;">
                    <h4 style="font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px;">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                        Informasi Pinjaman
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Jenis Pinjaman <span style="color: #ef4444;">*</span></label>
                            <select name="jenis_pinjaman_id" id="jenisSelect" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; background: white;" onchange="loadJenisInfo()">
                                <option value="">--- Pilih Jenis ---</option>
                                @foreach($jenisList as $j)
                                <option value="{{ $j->id }}" data-bunga="{{ $j->bunga_persen }}" data-denda="{{ $j->denda_persen }}" data-tenor="{{ $j->tenor_bulan }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                            @error('jenis_pinjaman_id')<p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Nominal Pengajuan <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="nominal" id="nominalInput" value="{{ old('nominal') }}" min="100000" required placeholder="Contoh: 5000000" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                            @error('nominal')<p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div style="margin-top: 1rem;">
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Tenor / Jangka Waktu <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="tenor_bulan" id="tenorInput" value="{{ old('tenor_bulan') }}" min="1" required placeholder="Contoh: 12 (dalam bulan)" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                        @error('tenor_bulan')<p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Keterangan --}}
                <div style="margin-bottom: 1.25rem;">
                    <h4 style="font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Keterangan</h4>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Keperluan Pinjaman <span style="color: #ef4444;">*</span></label>
                        <textarea name="keperluan" rows="3" required placeholder="Jelaskan keperluan pinjaman, contoh: biaya pendidikan, renovasi rumah, modal usaha" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; resize: none;">{{ old('keperluan') }}</textarea>
                        @error('keperluan')<p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Jaminan <span style="font-weight: 400; color: #94a3b8;">(opsional)</span></label>
                        <textarea name="jaminan" rows="2" placeholder="Contoh: Sertifikat tanah, BPKB motor/mobil, dll." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; resize: none;">{{ old('jaminan') }}</textarea>
                        <p style="font-size: 0.6875rem; color: #94a3b8; margin-top: 0.25rem; display: flex; align-items: center; gap: 4px;">
                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Kosongkan jika tidak ada jaminan
                        </p>
                    </div>
                </div>

                {{-- Buttons --}}
                <div style="display: flex; justify-content: space-between; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                    <a href="{{ route('pinjaman.index') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; color: #64748b; font-size: 0.875rem; font-weight: 600; text-decoration: none; border: 1px solid #d1d5db; border-radius: 0.5rem;">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali
                    </a>
                    <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.625rem 1.5rem; background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; border: none; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Ajukan Pinjaman
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar Info --}}
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div id="jenisInfoPanel" style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.25rem;">
                <h3 style="font-size: 0.8125rem; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px;">
                    <span>💎</span> Info Jenis Pinjaman
                </h3>
                <div id="jenisInfoContent" style="text-align: center; padding: 1.5rem 0;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;">
                        <svg style="width: 20px; height: 20px; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p style="font-size: 0.8125rem; color: #94a3b8;">Pilih jenis pinjaman untuk melihat detail</p>
                </div>
            </div>

            <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 0.75rem; padding: 1.25rem;">
                <h3 style="font-size: 0.8125rem; font-weight: 700; color: #92400e; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px;">
                    <span>⚠️</span> Catatan Penting
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="display: flex; align-items: flex-start; gap: 6px; margin-bottom: 0.5rem; font-size: 0.8125rem; color: #92400e;">
                        <span style="color: #10b981; font-weight: 700;">✓</span>
                        Pastikan semua data yang diisi benar dan sesuai
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 6px; margin-bottom: 0.5rem; font-size: 0.8125rem; color: #92400e;">
                        <span style="color: #10b981; font-weight: 700;">✓</span>
                        Pengajuan akan diproses maksimal 3 hari kerja
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 6px; margin-bottom: 0.5rem; font-size: 0.8125rem; color: #92400e;">
                        <span style="color: #10b981; font-weight: 700;">✓</span>
                        Anda akan dihubungi jika pengajuan disetujui atau ditolak
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 6px; font-size: 0.8125rem; color: #92400e;">
                        <span style="color: #10b981; font-weight: 700;">✓</span>
                        Angsuran dibayarkan setiap tanggal 5 tiap bulan
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function loadJenisInfo() {
    var sel = document.getElementById('jenisSelect');
    var opt = sel.options[sel.selectedIndex];
    var panel = document.getElementById('jenisInfoContent');

    if (!opt || !opt.value) {
        panel.innerHTML = '<div style="text-align: center; padding: 1.5rem 0;"><div style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;"><svg style="width: 20px; height: 20px; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><p style="font-size: 0.8125rem; color: #94a3b8;">Pilih jenis pinjaman untuk melihat detail</p></div>';
        return;
    }

    var bunga = opt.dataset.bunga;
    var denda = opt.dataset.denda;
    var tenor = opt.dataset.tenor;

    panel.innerHTML = '<div style="space-y: 0.75rem;">' +
        '<div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;"><span style="font-size: 0.8125rem; color: #64748b;">Bunga/Tahun</span><span style="font-size: 0.8125rem; font-weight: 600; color: #1e293b;">' + bunga + '%</span></div>' +
        '<div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;"><span style="font-size: 0.8125rem; color: #64748b;">Denda/Hari</span><span style="font-size: 0.8125rem; font-weight: 600; color: #ef4444;">' + denda + '%</span></div>' +
        '<div style="display: flex; justify-content: space-between; padding: 0.5rem 0;"><span style="font-size: 0.8125rem; color: #64748b;">Tenor Maks</span><span style="font-size: 0.8125rem; font-weight: 600; color: #1e293b;">' + tenor + ' bulan</span></div>' +
        '</div>';

    document.getElementById('tenorInput').max = tenor;
    document.getElementById('tenorInput').placeholder = 'Maks ' + tenor + ' bulan';
}
</script>
@endsection
