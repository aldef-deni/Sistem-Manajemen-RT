@extends('layouts.app')

@section('title', 'Tambah Inventaris')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('barang.index') }}" class="text-teal-600 hover:underline font-medium">Inventaris</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Tambah</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">Tambah Inventaris</h1>
        </div>
        <a href="{{ route('barang.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" id="barangForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-5">
                {{-- Informasi Barang --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #0d9488;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        INFORMASI BARANG
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kode Barang <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="text" name="kode_barang" value="{{ $kode }}" readonly
                                    class="flex-1 px-3 py-2 border border-slate-300 rounded-lg text-sm bg-slate-50 font-mono">
                                <button type="button" onclick="generateKode()" class="px-3 py-2 text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 rounded-lg hover:bg-teal-100">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Auto
                                </button>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Kode unik identitas barang</p>
                            @error('kode_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Contoh: Laptop ASUS VivoBook">
                            @error('nama_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Elektronik" {{ old('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                <option value="Perlengkapan" {{ old('kategori') == 'Perlengkapan' ? 'selected' : '' }}>Perlengkapan</option>
                                <option value="Furniture" {{ old('kategori') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="ATK" {{ old('kategori') == 'ATK' ? 'selected' : '' }}>ATK</option>
                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kondisi <span class="text-red-500">*</span></label>
                            <select name="kondisi" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="Perlu Perbaikan" {{ old('kondisi') == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                            </select>
                            @error('kondisi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" required min="1"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                            <input type="text" name="satuan" value="{{ old('satuan', 'unit') }}" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Unit / Pcs / Set">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Penyimpanan</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', 'Gudang RT 01 / Ruang Meeting') }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Gudang RT 01 / Ruang Meeting">
                        </div>
                    </div>
                </div>

                {{-- Data Pembelian --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #0d9488;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        DATA PEMBELIAN
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Pembelian</label>
                            <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian') }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Harga Pembelian (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-sm text-slate-500">Rp</span>
                                <input type="number" name="harga_pembelian" value="{{ old('harga_pembelian', 0) }}" min="0"
                                    class="w-full pl-8 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Sumber Dana</label>
                            <select name="sumber_dana" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">-- Pilih Sumber Dana --</option>
                                <option value="Kas RT" {{ old('sumber_dana') == 'Kas RT' ? 'selected' : '' }}>Kas RT</option>
                                <option value="Iuran Khusus" {{ old('sumber_dana') == 'Iuran Khusus' ? 'selected' : '' }}>Iuran Khusus</option>
                                <option value="Donasi" {{ old('sumber_dana') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                                <option value="APBD" {{ old('sumber_dana') == 'APBD' ? 'selected' : '' }}>APBD</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #0d9488;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        KETERANGAN TAMBAHAN
                    </h3>
                    <textarea name="keterangan" rows="3"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                        placeholder="Deskripsi spesifikasi atau catatan penting tentang barang ini...">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                {{-- Foto Utama --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-3 flex items-center gap-2" style="color: #0d9488;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        FOTO UTAMA
                    </h3>
                    <div id="fotoDropzone" class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-teal-400 transition-colors cursor-pointer relative" onclick="document.getElementById('fotoInput').click()">
                        <input type="file" name="foto_utama" id="fotoInput" accept="image/*" class="hidden" onchange="previewFoto(this)">
                        <div id="fotoPlaceholder">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm text-slate-500">Klik atau seret foto ke sini</p>
                            <p class="text-xs text-slate-400 mt-1">JPG / PNG - Maks 2MB</p>
                        </div>
                        <div id="fotoPreview" class="hidden">
                            <img id="previewImg" class="max-h-40 mx-auto rounded-lg">
                            <button type="button" onclick="removeFoto(event)" class="mt-2 text-xs text-red-500 hover:underline">Hapus Foto</button>
                        </div>
                    </div>
                </div>

                {{-- Gallery --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-3 flex items-center gap-2" style="color: #0d9488;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        GALLERY TAMBAHAN
                    </h3>
                    <label class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg cursor-pointer transition-all hover:scale-105" style="background: linear-gradient(135deg, #0d9488, #0f766e);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Pilih Beberapa Foto
                        <input type="file" name="foto_gallery[]" multiple accept="image/*" class="hidden">
                    </label>
                    <p class="text-xs text-slate-500 mt-2">Bisa pilih lebih dari 1 foto sekaligus</p>
                </div>

                {{-- Tips --}}
                <div class="rounded-xl p-4 border" style="background: #fffbeb; border-color: #fde68a;">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #fef3c7;">
                            <svg class="w-4 h-4" style="color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm" style="color: #92400e;">Tips Pengisian</h4>
                            <ul class="text-xs mt-1 space-y-1" style="color: #a16207;">
                                <li>• Isi kode barang dengan nilai unik</li>
                                <li>• Foto membantu identifikasi barang</li>
                                <li>• Periksa kembali data sebelum disimpan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('barang.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">
                ← Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-lg transition-all hover:scale-105" style="background: linear-gradient(135deg, #0d9488, #0f766e); box-shadow: 0 4px 12px rgba(13,148,136,0.3);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Inventaris
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function generateKode() {
        fetch('{{ route("barang.get-kode") }}')
            .then(r => r.json())
            .then(data => {
                document.querySelector('input[name="kode_barang"]').value = data.kode;
            });
    }

    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('fotoPlaceholder').classList.add('hidden');
                document.getElementById('fotoPreview').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeFoto(e) {
        e.stopPropagation();
        document.getElementById('fotoInput').value = '';
        document.getElementById('fotoPlaceholder').classList.remove('hidden');
        document.getElementById('fotoPreview').classList.add('hidden');
    }
</script>
@endpush
@endsection
