@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('jadwal-kegiatan.index') }}" class="text-teal-600 hover:underline font-medium">Jadwal</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Tambah</span>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Jadwal</h1>
        </div>
        <a href="{{ route('jadwal-kegiatan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('jadwal-kegiatan.store') }}" method="POST">
        @csrf

        <div style="background:linear-gradient(135deg,#14b8a6,#0d9488); border-radius:12px 12px 0 0; padding:14px 20px; color:white; display:flex; align-items:center; gap:10px;">
            <svg style="width:18px; height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3 style="font-weight:700; font-size:14px;">Informasi Jadwal</h3>
        </div>

        <div style="background:white; border:1px solid #e2e8f0; border-top:none; border-radius:0 0 12px 12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                {{-- Nama Kegiatan --}}
                <div style="grid-column:1/3;">
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Nama Kegiatan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" placeholder="Contoh: Ronda Malam, Kerja Bakti, dll"
                        style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; outline:none; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='#14b8a6'" onblur="this.style.borderColor='#d1d5db'" required>
                    @error('nama_kegiatan') <p style="color:#ef4444; font-size:11px; margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Kategori <span style="color:#ef4444;">*</span></label>
                    <select name="kategori" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; background:white;" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k }}" {{ old('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jenis Jadwal --}}
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Jenis Jadwal <span style="color:#ef4444;">*</span></label>
                    <select name="jenis_jadwal" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; background:white;" required>
                        <option value="">Pilih Jenis</option>
                        @foreach($jenisList as $j)
                            <option value="{{ $j }}" {{ old('jenis_jadwal') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Lokasi --}}
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Pos RT, Balai Warga, dll"
                        style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; outline:none;"
                        onfocus="this.style.borderColor='#14b8a6'" onblur="this.style.borderColor='#d1d5db'">
                </div>

                {{-- Penanggung Jawab --}}
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Penanggung Jawab</label>
                    <select name="penanggung_jawab_id" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; background:white;">
                        <option value="">Pilih Warga</option>
                        @foreach($warga as $w)
                            <option value="{{ $w->id }}" {{ old('penanggung_jawab_id') == $w->id ? 'selected' : '' }}>{{ $w->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div style="grid-column:1/3;">
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi kegiatan..."
                        style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; resize:vertical; outline:none;"
                        onfocus="this.style.borderColor='#14b8a6'" onblur="this.style.borderColor='#d1d5db'">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Tanggal & Jam --}}
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Tanggal Mulai <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                        style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;" required>
                </div>

                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                        style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                </div>

                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}"
                        style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                </div>

                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}"
                        style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                </div>

                {{-- Petugas --}}
                <div style="grid-column:1/3;">
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                        <svg style="width:14px; height:14px; color:#64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Kelompok / Petugas
                    </label>
                    <div id="petugas-container" style="display:flex; flex-direction:column; gap:8px;">
                        <div style="display:flex; gap:8px;">
                            <input type="text" name="petugas[]" placeholder="Nama petugas atau kelompok" style="flex:1; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; outline:none;" onfocus="this.style.borderColor='#14b8a6'" onblur="this.style.borderColor='#d1d5db'">
                            <button type="button" onclick="this.parentElement.remove()" style="width:38px; height:38px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#ef4444; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addPetugas()" style="margin-top:8px; width:100%; padding:8px; background:#f0fdfa; border:2px dashed #14b8a6; border-radius:8px; color:#0d9488; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; transition:background 0.2s;" onmouseover="this.style.background='#ccfbf1'" onmouseout="this.style.background='#f0fdfa'">
                        <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Petugas/Kelompok
                    </button>
                </div>

                {{-- Status --}}
                <div style="grid-column:1/3;">
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Status <span style="color:#ef4444;">*</span></label>
                    <select name="status" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; background:white;" required>
                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display:flex; justify-content:space-between; margin-top:24px; padding-top:20px; border-top:1px solid #f1f5f9;">
                <a href="{{ route('jadwal-kegiatan.index') }}" style="padding:10px 24px; background:#f1f5f9; border-radius:8px; font-size:13px; font-weight:600; color:#475569; text-decoration:none; display:flex; align-items:center; gap:6px;">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </a>
                <button type="submit" style="padding:10px 28px; background:linear-gradient(135deg,#14b8a6,#0d9488); border:none; border-radius:8px; font-size:13px; font-weight:700; color:white; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 2px 8px rgba(20,184,166,0.3); transition:box-shadow 0.2s;">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function addPetugas() {
    const container = document.getElementById('petugas-container');
    const div = document.createElement('div');
    div.style.cssText = 'display:flex; gap:8px;';
    div.innerHTML = `
        <input type="text" name="petugas[]" placeholder="Nama petugas atau kelompok" style="flex:1; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; outline:none;" onfocus="this.style.borderColor='#14b8a6'" onblur="this.style.borderColor='#d1d5db'">
        <button type="button" onclick="this.parentElement.remove()" style="width:38px; height:38px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#ef4444; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    container.appendChild(div);
}
</script>
@endsection
