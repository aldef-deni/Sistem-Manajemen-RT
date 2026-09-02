@extends('layouts.app')

@section('title', 'Tambah Kegiatan')

@section('content')
<div>
    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
        <a href="{{ route('kegiatan-rt.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:white;border:1.5px solid #e2e8f0;border-radius:10px;color:#64748b;font-size:13px;font-weight:500;text-decoration:none;transition:all 0.2s" onmouseover="this.style.borderColor='#14b8a6';this.style.color='#14b8a6'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <div>
            <h1 style="font-size:22px;font-weight:700;color:#1e293b;margin:0">Buat Kegiatan Baru</h1>
            <p style="font-size:13px;color:#64748b;margin:2px 0 0">Isi informasi kegiatan dan unggah foto galeri</p>
        </div>
    </div>

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:start;gap:10px">
            <svg style="width:20px;height:20px;color:#ef4444;flex-shrink:0;margin-top:1px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p style="font-size:14px;font-weight:600;color:#991b1b;margin:0 0 4px">Terjadi kesalahan:</p>
                <ul style="margin:0;padding-left:18px;font-size:13px;color:#b91c1c">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('kegiatan-rt.store') }}" enctype="multipart/form-data" style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">
        @csrf

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
                        <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Gotong Royong RT 05" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#14b8a6'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Artikel / Isi Kegiatan <span style="color:#ef4444">*</span></label>
                        <textarea name="artikel" rows="10" required style="width:100%;padding:14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none;resize:vertical;transition:border-color 0.2s" onfocus="this.style.borderColor='#14b8a6'" onblur="this.style.borderColor='#e2e8f0'" placeholder="Tulis artikel kegiatan di sini...">{{ old('artikel') }}</textarea>
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
                    <div style="border:2px dashed #d1d5db;border-radius:12px;padding:40px 20px;text-align:center;background:#f9fafb;cursor:pointer;transition:all 0.2s" onclick="document.getElementById('galeriInput').click()" onmouseover="this.style.borderColor='#14b8a6';this.style.background='#f0fdfa'" onmouseout="this.style.borderColor='#d1d5db';this.style.background='#f9fafb'">
                        <svg style="width:48px;height:48px;color:#93c5fd;margin:0 auto 12px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p style="font-size:14px;color:#64748b;margin:0 0 4px">Klik atau drag & drop foto ke sini</p>
                        <p style="font-size:12px;color:#94a3b8;margin:0">JPG, PNG, WEBP — maks. 5 MB per foto, bisa pilih banyak</p>
                    </div>
                    <input type="file" id="galeriInput" name="galeri_foto[]" multiple accept="image/*" style="display:none">
                    <div id="galeriPreview" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:14px"></div>
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
                            <option value="draft">Draft</option>
                            <option value="publish">Publish</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Kategori <span style="color:#ef4444">*</span></label>
                        <select name="kategori" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:white;outline:none">
                            <option value="">-- Pilih --</option>
                            @foreach(['Umum','Keagamaan','Kebersihan','Keamanan','Olahraga','Sosial','Lainnya'] as $k)
                                <option value="{{ $k }}" {{ old('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tanggal Mulai <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tanggal Selesai <span style="color:#94a3b8;font-weight:400">(opsional)</span></label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Lokasi <span style="color:#94a3b8;font-weight:400">(opsional)</span></label>
                        <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Balai RW 05" style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <button type="submit" style="width:100%;padding:12px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border:none;border-radius:10px;font-weight:600;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 12px rgba(20,184,166,0.3);transition:all 0.2s" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                        <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Kegiatan
                    </button>
                </div>
            </div>

            {{-- Thumbnail / Cover --}}
            <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0;text-transform:uppercase;letter-spacing:0.5px">Thumbnail / Cover</h3>
                </div>
                <div style="padding:22px">
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Foto Utama</label>
                    <div style="border:2px dashed #d1d5db;border-radius:10px;padding:20px;text-align:center;background:#f9fafb;cursor:pointer;transition:all 0.2s" onclick="document.getElementById('fotoInput').click()" onmouseover="this.style.borderColor='#14b8a6'" onmouseout="this.style.borderColor='#d1d5db'">
                        <input type="file" id="fotoInput" name="foto_utama" accept="image/*" style="display:none" onchange="previewFoto(this)">
                        <div id="fotoPreviewPlaceholder">
                            <svg style="width:36px;height:36px;color:#93c5fd;margin:0 auto 8px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p style="font-size:13px;color:#64748b;margin:0">Choose File — No file chosen</p>
                        </div>
                        <div id="fotoPreviewImg" style="display:none"></div>
                    </div>
                    <p style="font-size:11px;color:#94a3b8;margin:8px 0 0">JPG, PNG, WEBP — maks. 3 MB</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('galeriInput').addEventListener('change', function(e) {
    const preview = document.getElementById('galeriPreview');
    preview.innerHTML = '';
    Array.from(e.target.files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = function(ev) {
            const div = document.createElement('div');
            div.style.cssText = 'position:relative;width:80px;height:80px;border-radius:10px;overflow:hidden;border:2px solid #e2e8f0';
            div.innerHTML = `<img src="${ev.target.result}" style="width:100%;height:100%;object-fit:cover">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fotoPreviewPlaceholder').style.display = 'none';
            const imgDiv = document.getElementById('fotoPreviewImg');
            imgDiv.style.display = 'block';
            imgDiv.innerHTML = `<img src="${e.target.result}" style="width:100%;height:150px;object-fit:cover;border-radius:8px">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
