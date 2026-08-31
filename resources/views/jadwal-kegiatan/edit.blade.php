@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('jadwal-kegiatan.index') }}" class="text-teal-600 hover:underline font-medium">Jadwal</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Edit</span>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Jadwal</h1>
        </div>
        <a href="{{ route('jadwal-kegiatan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('jadwal-kegiatan.update', $jadwal->id) }}" method="POST">
        @csrf @method('PUT')

        <div style="background:linear-gradient(135deg,#3b82f6,#2563eb); border-radius:12px 12px 0 0; padding:14px 20px; color:white; display:flex; align-items:center; gap:10px;">
            <svg style="width:18px; height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            <h3 style="font-weight:700; font-size:14px;">Edit Informasi Jadwal</h3>
        </div>

        <div style="background:white; border:1px solid #e2e8f0; border-top:none; border-radius:0 0 12px 12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div style="grid-column:1/3;">
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Nama Kegiatan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $jadwal->nama_kegiatan) }}" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;" required>
                </div>
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Kategori <span style="color:#ef4444;">*</span></label>
                    <select name="kategori" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; background:white;" required>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k }}" {{ old('kategori', $jadwal->kategori) == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Jenis Jadwal <span style="color:#ef4444;">*</span></label>
                    <select name="jenis_jadwal" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; background:white;" required>
                        @foreach($jenisList as $j)
                            <option value="{{ $j }}" {{ old('jenis_jadwal', $jadwal->jenis_jadwal) == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $jadwal->lokasi) }}" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                </div>
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Penanggung Jawab</label>
                    <select name="penanggung_jawab_id" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; background:white;">
                        <option value="">Pilih Warga</option>
                        @foreach($warga as $w)
                            <option value="{{ $w->id }}" {{ old('penanggung_jawab_id', $jadwal->penanggung_jawab_id) == $w->id ? 'selected' : '' }}>{{ $w->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="grid-column:1/3;">
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; resize:vertical;">{{ old('deskripsi', $jadwal->deskripsi) }}</textarea>
                </div>
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Tanggal Mulai <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $jadwal->tanggal_mulai->format('Y-m-d')) }}" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;" required>
                </div>
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $jadwal->tanggal_selesai?->format('Y-m-d')) }}" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                </div>
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                </div>
                <div>
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                </div>
                <div style="grid-column:1/3;">
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:8px;">Kelompok / Petugas</label>
                    <div id="petugas-container" style="display:flex; flex-direction:column; gap:8px;">
                        @if($jadwal->petugas && count($jadwal->petugas) > 0)
                            @foreach($jadwal->petugas as $p)
                                <div style="display:flex; gap:8px;">
                                    <input type="text" name="petugas[]" value="{{ $p }}" style="flex:1; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                                    <button type="button" onclick="this.parentElement.remove()" style="width:38px; height:38px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#ef4444; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div style="display:flex; gap:8px;">
                                <input type="text" name="petugas[]" placeholder="Nama petugas" style="flex:1; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;">
                                <button type="button" onclick="this.parentElement.remove()" style="width:38px; height:38px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#ef4444; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" onclick="addPetugas()" style="margin-top:8px; width:100%; padding:8px; background:#f0fdfa; border:2px dashed #14b8a6; border-radius:8px; color:#0d9488; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px;">
                        <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Petugas/Kelompok
                    </button>
                </div>
                <div style="grid-column:1/3;">
                    <label style="font-size:13px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Status <span style="color:#ef4444;">*</span></label>
                    <select name="status" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; background:white;" required>
                        <option value="aktif" {{ old('status', $jadwal->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ old('status', $jadwal->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ old('status', $jadwal->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; justify-content:space-between; margin-top:24px; padding-top:20px; border-top:1px solid #f1f5f9;">
                <a href="{{ route('jadwal-kegiatan.index') }}" style="padding:10px 24px; background:#f1f5f9; border-radius:8px; font-size:13px; font-weight:600; color:#475569; text-decoration:none;">← Batal</a>
                <button type="submit" style="padding:10px 28px; background:linear-gradient(135deg,#3b82f6,#2563eb); border:none; border-radius:8px; font-size:13px; font-weight:700; color:white; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 2px 8px rgba(59,130,246,0.3);">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function addPetugas() {
    const c = document.getElementById('petugas-container');
    const d = document.createElement('div');
    d.style.cssText = 'display:flex; gap:8px;';
    d.innerHTML = `<input type="text" name="petugas[]" placeholder="Nama petugas" style="flex:1; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:13px;"><button type="button" onclick="this.parentElement.remove()" style="width:38px; height:38px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#ef4444; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"><svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
    c.appendChild(d);
}
</script>
@endsection
