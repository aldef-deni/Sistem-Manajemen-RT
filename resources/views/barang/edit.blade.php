@extends('layouts.app')

@section('title', 'Edit Inventaris')

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
                <span class="text-slate-700 font-medium">Edit</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">Edit Inventaris</h1>
        </div>
        <a href="{{ route('barang.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('barang.update', $barang) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                {{-- Informasi Barang --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #0d9488;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        INFORMASI BARANG
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kode Barang</label>
                            <input type="text" value="{{ $barang->kode_barang }}" readonly
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-slate-50 font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                                @foreach(['Elektronik','Perlengkapan','Furniture','ATK','Lainnya'] as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori', $barang->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kondisi <span class="text-red-500">*</span></label>
                            <select name="kondisi" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                                @foreach(['Baik','Rusak Ringan','Rusak Berat','Perlu Perbaikan'] as $kond)
                                    <option value="{{ $kond }}" {{ old('kondisi', $barang->kondisi) == $kond ? 'selected' : '' }}>{{ $kond }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" value="{{ old('jumlah', $barang->jumlah) }}" required min="1"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                            <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Penyimpanan</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $barang->lokasi) }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
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
                            <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', $barang->tanggal_pembelian?->format('Y-m-d')) }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Harga Pembelian (Rp)</label>
                            <input type="number" name="harga_pembelian" value="{{ old('harga_pembelian', $barang->harga_pembelian) }}" min="0"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Sumber Dana</label>
                            <select name="sumber_dana" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                                <option value="">-- Pilih --</option>
                                @foreach(['Kas RT','Iuran Khusus','Donasi','APBD'] as $sd)
                                    <option value="{{ $sd }}" {{ old('sumber_dana', $barang->sumber_dana) == $sd ? 'selected' : '' }}>{{ $sd }}</option>
                                @endforeach
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
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm"
                        placeholder="Deskripsi spesifikasi atau catatan...">{{ old('keterangan', $barang->keterangan) }}</textarea>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-3 flex items-center gap-2" style="color: #0d9488;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        FOTO UTAMA
                    </h3>
                    @if($barang->foto_utama)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $barang->foto_utama) }}" class="w-full h-40 object-cover rounded-lg">
                        </div>
                    @endif
                    <input type="file" name="foto_utama" accept="image/*"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700">
                    <p class="text-xs text-slate-500 mt-1">JPG / PNG - Maks 2MB</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-2">Info</h3>
                    <div class="space-y-2 text-sm text-slate-600">
                        <p><span class="font-medium">Kode:</span> <span class="font-mono">{{ $barang->kode_barang }}</span></p>
                        <p><span class="font-medium">Status:</span> <span class="font-semibold capitalize">{{ $barang->status }}</span></p>
                        <p><span class="font-medium">Dibuat:</span> {{ $barang->created_at->format('d M Y H:i') }}</p>
                        <p><span class="font-medium">Diupdate:</span> {{ $barang->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('barang.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">← Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-lg" style="background: linear-gradient(135deg, #0d9488, #0f766e); box-shadow: 0 4px 12px rgba(13,148,136,0.3);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Update Inventaris
            </button>
        </div>
    </form>
</div>
@endsection
