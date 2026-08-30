@extends('layouts.app')

@section('title', 'Edit Kunjungan')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;max-width:800px;">
    <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#94a3b8;">
        <a href="{{ route('dashboard') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('visitor.index') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">E-Visitor</a>
        <span>/</span>
        <span style="color:#1e293b;font-weight:600;">Edit</span>
    </div>

    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3);">
            <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
        <h1 style="font-size:22px;font-weight:700;color:#1e293b;">Edit Kunjungan — {{ $visitor->kode_kunjungan }}</h1>
    </div>

    <form method="POST" action="{{ route('visitor.update', $visitor->id) }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:20px;">
        @csrf @method('PUT')

        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:20px;display:flex;flex-direction:column;gap:16px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Tipe Kunjungan *</label>
                    <div style="display:flex;gap:12px;">
                        <label style="display:flex;align-items:center;gap:8px;padding:10px 16px;border-radius:8px;border:2px solid {{ $visitor->tipe_kunjungan === 'singkat' ? '#0d9488' : '#e2e8f0' }};cursor:pointer;background:{{ $visitor->tipe_kunjungan === 'singkat' ? '#f0fdfa' : 'white' }};">
                            <input type="radio" name="tipe_kunjungan" value="singkat" {{ $visitor->tipe_kunjungan === 'singkat' ? 'checked' : '' }} style="accent-color:#0d9488;"> Kunjungan Singkat
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;padding:10px 16px;border-radius:8px;border:2px solid {{ $visitor->tipe_kunjungan === 'menginap' ? '#0d9488' : '#e2e8f0' }};cursor:pointer;background:{{ $visitor->tipe_kunjungan === 'menginap' ? '#f0fdfa' : 'white' }};">
                            <input type="radio" name="tipe_kunjungan" value="menginap" {{ $visitor->tipe_kunjungan === 'menginap' ? 'checked' : '' }} style="accent-color:#0d9488;"> Tamu Menginap
                        </label>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Nama Tamu *</label><input type="text" name="nama_tamu" value="{{ $visitor->nama_tamu }}" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;"></div>
                    <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">NIK</label><input type="text" name="nik" value="{{ $visitor->nik }}" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;"></div>
                    <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">No. HP *</label><input type="text" name="no_hp" value="{{ $visitor->no_hp }}" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;"></div>
                    <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Email</label><input type="email" name="email" value="{{ $visitor->email }}" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;"></div>
                    <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">No. Plat</label><input type="text" name="no_plat" value="{{ $visitor->no_plat }}" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;text-transform:uppercase;"></div>
                    <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                            <option value="">— Pilih —</option>
                            <option value="Motor" {{ $visitor->jenis_kendaraan === 'Motor' ? 'selected' : '' }}>Motor</option>
                            <option value="Mobil" {{ $visitor->jenis_kendaraan === 'Mobil' ? 'selected' : '' }}>Mobil</option>
                            <option value="Tak Kendaraan" {{ $visitor->jenis_kendaraan === 'Tak Kendaraan' ? 'selected' : '' }}>Tak Kendaraan</option>
                        </select>
                    </div>
                    <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Tujuan / Blok *</label><input type="text" name="tujuan_blok" value="{{ $visitor->tujuan_blok }}" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;"></div>
                    <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Nama Tujuan</label><input type="text" name="nama_tujuan" value="{{ $visitor->nama_tujuan }}" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;"></div>
                    <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">WA Host</label><input type="text" name="wa_host" value="{{ $visitor->wa_host }}" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;"></div>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">Kepentingan *</label>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        @foreach(['Kunjungan Biasa', 'Antar Paket', 'Menginap', 'Kerja/Bisnis', 'Lainnya'] as $k)
                        <label style="display:flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;border:1px solid #e2e8f0;cursor:pointer;font-size:13px;">
                            <input type="checkbox" name="kepentingan[]" value="{{ $k }}" {{ in_array($k, $visitor->kepentingan ?? []) ? 'checked' : '' }} style="accent-color:#0d9488;"> {{ $k }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Deskripsi *</label><input type="text" name="deskripsi_kepentingan" value="{{ $visitor->deskripsi_kepentingan }}" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;text-transform:uppercase;"></div>
                <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Catatan Tambahan</label><textarea name="catatan_tambahan" rows="2" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;resize:vertical;">{{ $visitor->catatan_tambahan }}</textarea></div>
                <div><label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Foto Dokumentasi</label><input type="file" name="foto_dokumentasi" accept="image/*" style="padding:8px;font-size:14px;"></div>
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <a href="{{ route('visitor.index') }}" style="padding:10px 24px;border-radius:10px;font-weight:600;font-size:14px;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;">← Batal</a>
            <button type="submit" style="padding:10px 24px;border-radius:10px;font-weight:600;font-size:14px;color:white;background:linear-gradient(135deg,#0d9488,#0f766e);border:none;cursor:pointer;">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
