@extends('layouts.app')

@section('title', 'Edit: ' . $kegiatan->judul)

@section('content')
<div>
    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
        <a href="{{ route('kegiatan-rt.show', $kegiatan) }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:white;border:1.5px solid #e2e8f0;border-radius:10px;color:#64748b;font-size:13px;font-weight:500;text-decoration:none">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <div>
            <h1 style="font-size:22px;font-weight:700;color:#1e293b;margin:0">Edit Kegiatan</h1>
            <p style="font-size:13px;color:#64748b;margin:2px 0 0">Perbarui informasi kegiatan</p>
        </div>
    </div>

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px 18px;margin-bottom:20px">
            <p style="font-size:14px;font-weight:600;color:#991b1b;margin:0 0 4px">Terjadi kesalahan:</p>
            <ul style="margin:0;padding-left:18px;font-size:13px;color:#b91c1c">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('kegiatan-rt.update', $kegiatan) }}" enctype="multipart/form-data" style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">
        @csrf @method('PUT')

        {{-- Left Column --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            {{-- Informasi Kegiatan --}}
            <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#14b8a6,#0d9488);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0;text-transform:uppercase;letter-spacing:0.5px">Informasi Kegiatan</h3>
                </div>
                <div style="padding:22px">
                    <div style="margin-bottom:18px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Judul Kegiatan <span style="color:#ef4444">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $kegiatan->judul) }}" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Artikel / Isi Kegiatan <span style="color:#ef4444">*</span></label>
                        <textarea name="artikel" rows="10" required style="width:100%;padding:14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none;resize:vertical">{{ old('artikel', $kegiatan->artikel) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Galeri Foto --}}
            <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0;text-transform:uppercase;letter-spacing:0.5px">Galeri Foto</h3>
                </div>
                <div style="padding:22px">
                    @if($kegiatan->galeri_foto && count($kegiatan->galeri_foto) > 0)
                        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:14px">
                            @foreach($kegiatan->galeri_foto as $i => $foto)
                                <div style="position:relative;width:80px;height:80px;border-radius:10px;overflow:hidden;border:2px solid #e2e8f0">
                                    <img src="{{ Storage::url($foto) }}" style="width:100%;height:100%;object-fit:cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div style="border:2px dashed #d1d5db;border-radius:12px;padding:30px 20px;text-align:center;background:#f9fafb;cursor:pointer" onclick="document.getElementById('galeriInput').click()">
                        <svg style="width:36px;height:36px;color:#93c5fd;margin:0 auto 8px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                        <p style="font-size:13px;color:#64748b;margin:0">Tambah foto baru (bisa pilih banyak)</p>
                    </div>
                    <input type="file" id="galeriInput" name="galeri_foto[]" multiple accept="image/*" style="display:none">
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            {{-- Publikasi --}}
            <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0;text-transform:uppercase;letter-spacing:0.5px">Publikasi</h3>
                </div>
                <div style="padding:22px;display:flex;flex-direction:column;gap:16px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Status</label>
                        <select name="status" style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:white;outline:none">
                            @foreach(['draft'=>'Draft','publish'=>'Publish','arsip'=>'Arsip'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('status', $kegiatan->status) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Kategori <span style="color:#ef4444">*</span></label>
                        <select name="kategori" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:white;outline:none">
                            <option value="">-- Pilih --</option>
                            @foreach(['Umum','Keagamaan','Kebersihan','Keamanan','Olahraga','Sosial','Lainnya'] as $k)
                                <option value="{{ $k }}" {{ old('kategori', $kegiatan->kategori) == $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tanggal Mulai <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $kegiatan->tanggal_mulai->format('Y-m-d')) }}" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $kegiatan->tanggal_selesai?->format('Y-m-d')) }}" style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Lokasi</label>
                        <input type="text" name="lokasi" value="{{ old('lokasi', $kegiatan->lokasi) }}" placeholder="Contoh: Balai RW 05" style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Foto Utama Baru <span style="color:#94a3b8;font-weight:400">(opsional)</span></label>
                        <input type="file" name="foto_utama" accept="image/*" style="width:100%;padding:8px;font-size:13px">
                        @if($kegiatan->foto_utama)
                            <img src="{{ Storage::url($kegiatan->foto_utama) }}" style="width:100%;height:120px;object-fit:cover;border-radius:8px;margin-top:8px">
                        @endif
                    </div>
                    <button type="submit" style="width:100%;padding:12px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border:none;border-radius:10px;font-weight:600;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 12px rgba(20,184,166,0.3)">
                        <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
