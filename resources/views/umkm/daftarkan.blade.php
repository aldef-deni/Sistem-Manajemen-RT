@extends('layouts.app')

@section('title', 'Daftarkan Usaha Saya')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#0f766e);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3)">
                <svg style="width:22px;height:22px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h1 style="font-size:1.25rem;font-weight:700;color:#0f172a">Daftarkan Usaha Saya</h1>
                <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:#64748b">
                    <a href="{{ route('umkm.index') }}" style="color:#0d9488;text-decoration:none;font-weight:500">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('umkm.index') }}" style="color:#0d9488;text-decoration:none;font-weight:500">Direktori UMKM</a>
                    <span>/</span>
                    <span style="color:#475569">Daftarkan Usaha</span>
                </div>
            </div>
        </div>
        <a href="{{ route('umkm.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    {{-- Info Banner --}}
    <div style="background:linear-gradient(135deg,#f0fdfa,#ccfbf1);border:1px solid #99f6e4;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:12px">
        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#0d9488,#0f766e);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg style="width:20px;height:20px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p style="font-size:0.85rem;color:#0f766e;font-weight:600;margin:0">Data ini diambil dari akun anggotaRT Anda</p>
            <p style="font-size:0.75rem;color:#0d9488;margin:2px 0 0 0">Untuk info nama dan profil warga sudah terisi otomatis. Setelah verifikasi usaha akan ditampilkan dalam 1-2 hari kerja.</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('umkm.store-daftarkan') }}" method="POST" enctype="multipart/form-data" style="display:flex;gap:24px">
        @csrf
        <div style="flex:1;display:flex;flex-direction:column;gap:20px">
            {{-- Form Data Usaha --}}
            <div style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                    <div style="width:8px;height:8px;border-radius:50%;background:#0d9488"></div>
                    <h2 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0">Form Data Usaha / UMKM</h2>
                </div>

                {{-- Nama Pemilik --}}
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Nama Pemilik / Warga <span style="color:#dc2626">*</span></label>
                    <input type="text" value="{{ Auth::user()->name }}" readonly style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;background:#f8fafc;color:#64748b">
                    <p style="font-size:0.7rem;color:#94a3b8;margin:4px 0 0 0">Klik nama pemilik untuk detail warga</p>
                </div>

                {{-- Nama Usaha --}}
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Nama Usaha <span style="color:#dc2626">*</span></label>
                    <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}" placeholder="Contoh: Warung Makde Didi, Batik Jaya, dll" required style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('nama_usaha')
                        <p style="font-size:0.7rem;color:#dc2626;margin:4px 0 0 0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:8px">Kategori <span style="color:#dc2626">*</span></label>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                        @php
                            $kategoriList = ['Kuliner', 'Fashion', 'Jasa', 'Pertanian', 'Kerajinan', 'Teknologi', 'Kesehatan', 'Pendidikan', 'Lainnya'];
                            $kategoriIcons = [
                                'Kuliner' => '🍜',
                                'Fashion' => '👗',
                                'Jasa' => '✂️',
                                'Pertanian' => '🌾',
                                'Kerajinan' => '🎨',
                                'Teknologi' => '💻',
                                'Kesehatan' => '❤️',
                                'Pendidikan' => '📚',
                                'Lainnya' => '📦',
                            ];
                        @endphp
                        @foreach($kategoriList as $k)
                            @php
                                $isSelected = old('kategori') === $k;
                            @endphp
                            <label style="display:flex;align-items:center;gap:6px;padding:10px 12px;border-radius:10px;border:{{ $isSelected ? '2px solid #0d9488' : '1px solid #e2e8f0' }};background:{{ $isSelected ? '#f0fdfa' : 'white' }};cursor:pointer;transition:all 0.2s;font-size:0.8rem;font-weight:500;color:#374151">
                                <input type="radio" name="kategori" value="{{ $k }}" {{ $isSelected ? 'checked' : '' }} style="display:none">
                                <span style="font-size:1rem">{{ $kategoriIcons[$k] }}</span>
                                {{ $k }}
                            </label>
                        @endforeach
                    </div>
                    @error('kategori')
                        <p style="font-size:0.7rem;color:#dc2626;margin:4px 0 0 0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi Usaha --}}
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Deskripsi Usaha <span style="color:#dc2626">*</span> <span style="font-weight:400;color:#94a3b8">• maksimal 1.000 karakter</span></label>
                    <textarea name="deskripsi_usaha" rows="3" maxlength="1000" placeholder="Contoh: Warung makan yang menyajikan makanan rumahan..." required style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;resize:vertical;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">{{ old('deskripsi_usaha') }}</textarea>
                    <div style="display:flex;justify-content:space-between;margin-top:4px">
                        @error('deskripsi_usaha')
                            <p style="font-size:0.7rem;color:#dc2626;margin:0">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <span style="font-size:0.7rem;color:#94a3b8">/1000 karakter</span>
                    </div>
                </div>

                {{-- Produk / Layanan --}}
                <div style="margin-bottom:0">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Produk / Layanan <span style="font-weight:400;color:#94a3b8">• opsional, koma untuk item list</span></label>
                    <textarea name="produk_layanan" rows="2" maxlength="500" placeholder="Contoh: Nasi goreng, Mie ayam, Catering, Delivery order..." style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;resize:vertical;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">{{ old('produk_layanan') }}</textarea>
                    <div style="display:flex;justify-content:flex-end;margin-top:4px">
                        <span style="font-size:0.7rem;color:#94a3b8">/500 karakter</span>
                    </div>
                </div>
            </div>

            {{-- Lokasi & Jam Operasional --}}
            <div style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                    <div style="width:8px;height:8px;border-radius:50%;background:#0d9488"></div>
                    <h2 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0">Lokasi & Jam Operasional</h2>
                </div>

                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Alamat / Lokasi <span style="font-weight:400;color:#94a3b8">• opsional</span></label>
                    <input type="text" name="alamat_lokasi" value="{{ old('alamat_lokasi') }}" placeholder="Contoh: Jl. A. Yani No. 12, RT 03 / RW 05" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <div style="margin-bottom:0">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Jam Operasional <span style="font-weight:400;color:#94a3b8">• opsional</span></label>
                    <input type="text" name="jam_operasional" value="{{ old('jam_operasional') }}" placeholder="Contoh: Senin-Sabtu 08:00-17:00, Setiap hari buka jam 1" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                </div>
            </div>

            {{-- Kontak --}}
            <div style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                    <div style="width:8px;height:8px;border-radius:50%;background:#0d9488"></div>
                    <h2 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0">Kontak</h2>
                </div>

                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">No. Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxxx" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08xxxxxxxxxx" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Instagram</label>
                        <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="username (tanpa @)" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            {{-- Foto Usaha --}}
            <div style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                    <div style="width:8px;height:8px;border-radius:50%;background:#0d9488"></div>
                    <h2 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0">Foto Usaha <span style="font-weight:400;color:#94a3b8">• opsional, max 2MB</span></h2>
                </div>

                <div id="dropZone" style="border:2px dashed #e2e8f0;border-radius:12px;padding:32px;text-align:center;cursor:pointer;transition:all 0.2s" onclick="document.getElementById('fotoInput').click()" onmouseover="this.style.borderColor='#0d9488'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <svg style="width:40px;height:40px;color:#94a3b8;margin:0 auto 8px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p style="font-size:0.85rem;color:#475569;margin:0">Klik untuk upload foto usaha</p>
                    <p style="font-size:0.7rem;color:#94a3b8;margin:4px 0 0 0">JPG, PNG, atau WEBP. Maks 2MB.</p>
                    <input type="file" name="foto_usaha" id="fotoInput" accept="image/*" style="display:none" onchange="previewFoto(this)">
                </div>
                <div id="fotoPreview" style="margin-top:12px;display:none">
                    <img id="previewImg" src="" alt="Preview" style="max-width:200px;border-radius:10px;border:1px solid #e2e8f0">
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display:flex;justify-content:flex-end;gap:12px">
                <a href="{{ route('umkm.index') }}" style="padding:12px 28px;border-radius:10px;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    Batal
                </a>
                <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:10px;background:linear-gradient(135deg,#0d9488,#0f766e);color:white;font-weight:600;font-size:0.875rem;border:none;cursor:pointer;box-shadow:0 4px 12px rgba(13,148,136,0.3);transition:all 0.2s">
                    <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Daftarkan Usaha
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('fotoPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Radio card selection
document.querySelectorAll('input[name="kategori"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('input[name="kategori"]').forEach(function(r) {
            var label = r.closest('label');
            if (r.checked) {
                label.style.border = '2px solid #0d9488';
                label.style.background = '#f0fdfa';
            } else {
                label.style.border = '1px solid #e2e8f0';
                label.style.background = 'white';
            }
        });
    });
});

// Init selected radio
var selected = document.querySelector('input[name="kategori"]:checked');
if (selected) {
    selected.closest('label').style.border = '2px solid #0d9488';
    selected.closest('label').style.background = '#f0fdfa';
}
</script>
@endsection
