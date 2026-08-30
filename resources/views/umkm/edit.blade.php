@extends('layouts.app')

@section('title', 'Edit UMKM')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#0f766e);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3)">
                <svg style="width:22px;height:22px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h1 style="font-size:1.25rem;font-weight:700;color:#0f172a">Edit UMKM</h1>
                <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:#64748b">
                    <a href="{{ route('umkm.index') }}" style="color:#0d9488;text-decoration:none;font-weight:500">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('umkm.index') }}" style="color:#0d9488;text-decoration:none;font-weight:500">Direktori UMKM</a>
                    <span>/</span>
                    <span style="color:#475569">Edit</span>
                </div>
            </div>
        </div>
        <a href="{{ route('umkm.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('umkm.update', $umkm->id) }}" method="POST" enctype="multipart/form-data" style="display:flex;gap:24px">
        @csrf
        @method('PUT')
        <div style="flex:1;display:flex;flex-direction:column;gap:20px">
            {{-- Form Data Usaha --}}
            <div style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                    <div style="width:8px;height:8px;border-radius:50%;background:#0d9488"></div>
                    <h2 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0">Form Edit Data Usaha / UMKM</h2>
                </div>

                {{-- Nama Pemilik --}}
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Nama Pemilik / Warga</label>
                    <input type="text" value="{{ $umkm->user->name ?? 'Admin' }}" readonly style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;background:#f8fafc;color:#64748b">
                </div>

                {{-- Nama Usaha --}}
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Nama Usaha <span style="color:#dc2626">*</span></label>
                    <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $umkm->nama_usaha) }}" required style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
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
                                $isSelected = old('kategori', $umkm->kategori) === $k;
                            @endphp
                            <label style="display:flex;align-items:center;gap:6px;padding:10px 12px;border-radius:10px;border:{{ $isSelected ? '2px solid #0d9488' : '1px solid #e2e8f0' }};background:{{ $isSelected ? '#f0fdfa' : 'white' }};cursor:pointer;transition:all 0.2s;font-size:0.8rem;font-weight:500;color:#374151">
                                <input type="radio" name="kategori" value="{{ $k }}" {{ $isSelected ? 'checked' : '' }} style="display:none">
                                <span style="font-size:1rem">{{ $kategoriIcons[$k] }}</span>
                                {{ $k }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Deskripsi Usaha --}}
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Deskripsi Usaha <span style="color:#dc2626">*</span></label>
                    <textarea name="deskripsi_usaha" rows="3" maxlength="1000" required style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;resize:vertical;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">{{ old('deskripsi_usaha', $umkm->deskripsi_usaha) }}</textarea>
                </div>

                {{-- Produk / Layanan --}}
                <div style="margin-bottom:0">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Produk / Layanan</label>
                    <textarea name="produk_layanan" rows="2" maxlength="500" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;resize:vertical;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">{{ old('produk_layanan', $umkm->produk_layanan) }}</textarea>
                </div>
            </div>

            {{-- Lokasi --}}
            <div style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                    <div style="width:8px;height:8px;border-radius:50%;background:#0d9488"></div>
                    <h2 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0">Lokasi & Jam Operasional</h2>
                </div>
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Alamat / Lokasi</label>
                    <input type="text" name="alamat_lokasi" value="{{ old('alamat_lokasi', $umkm->alamat_lokasi) }}" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                </div>
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Jam Operasional</label>
                    <input type="text" name="jam_operasional" value="{{ old('jam_operasional', $umkm->jam_operasional) }}" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
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
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $umkm->no_telepon) }}" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $umkm->whatsapp) }}" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Instagram</label>
                        <input type="text" name="instagram" value="{{ old('instagram', $umkm->instagram) }}" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            {{-- Foto --}}
            <div style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                    <div style="width:8px;height:8px;border-radius:50%;background:#0d9488"></div>
                    <h2 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0">Foto Usaha</h2>
                </div>
                @if($umkm->foto_usaha)
                <div style="margin-bottom:12px">
                    <p style="font-size:0.8rem;color:#64748b;margin:0 0 8px 0">Foto saat ini:</p>
                    <img src="{{ Storage::url($umkm->foto_usaha) }}" alt="Foto Usaha" style="max-width:200px;border-radius:10px;border:1px solid #e2e8f0">
                </div>
                @endif
                <div style="border:2px dashed #e2e8f0;border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all 0.2s" onclick="document.getElementById('fotoInput').click()" onmouseover="this.style.borderColor='#0d9488'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <p style="font-size:0.85rem;color:#475569;margin:0">Klik untuk upload foto baru</p>
                    <p style="font-size:0.7rem;color:#94a3b8;margin:4px 0 0 0">JPG, PNG, atau WEBP. Maks 2MB.</p>
                    <input type="file" name="foto_usaha" id="fotoInput" accept="image/*" style="display:none" onchange="previewFoto(this)">
                </div>
                <div id="fotoPreview" style="margin-top:12px;display:none">
                    <img id="previewImg" src="" alt="Preview" style="max-width:200px;border-radius:10px;border:1px solid #e2e8f0">
                </div>
            </div>

            {{-- Status --}}
            <div style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                    <div style="width:8px;height:8px;border-radius:50%;background:#0d9488"></div>
                    <h2 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin:0">Status</h2>
                </div>
                <select name="status" style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #e2e8f0;font-size:0.85rem;outline:none;background:white">
                    <option value="aktif" {{ old('status', $umkm->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="pending_review" {{ old('status', $umkm->status) === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                    <option value="nonaktif" {{ old('status', $umkm->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div style="display:flex;justify-content:flex-end;gap:12px">
                <a href="{{ route('umkm.index') }}" style="padding:12px 28px;border-radius:10px;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    Batal
                </a>
                <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:10px;background:linear-gradient(135deg,#0d9488,#0f766e);color:white;font-weight:600;font-size:0.875rem;border:none;cursor:pointer;box-shadow:0 4px 12px rgba(13,148,136,0.3);transition:all 0.2s">
                    <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update
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

var selected = document.querySelector('input[name="kategori"]:checked');
if (selected) {
    selected.closest('label').style.border = '2px solid #0d9488';
    selected.closest('label').style.background = '#f0fdfa';
}
</script>
@endsection
