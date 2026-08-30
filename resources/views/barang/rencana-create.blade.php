@extends('layouts.app')

@section('title', 'Tambah Rencana Pembelian')

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
                <a href="{{ route('barang.rencana.index') }}" class="text-teal-600 hover:underline font-medium">Rencana Pembelian</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Tambah</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">Tambah Rencana Pembelian</h1>
        </div>
        <a href="{{ route('barang.rencana.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('barang.rencana.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kode_rencana" value="{{ $kode }}">

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-3xl">
            {{-- Informasi Barang --}}
            <div class="mb-6">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #0d9488;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    INFORMASI BARANG
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                            placeholder="Contoh: Proyektor Epson EB-X51">
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
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" required min="1"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" name="satuan" value="{{ old('satuan', 'unit') }}" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                            placeholder="unit / pcs / set">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Prioritas <span class="text-red-500">*</span></label>
                        <select name="prioritas" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>🔴 Tinggi</option>
                            <option value="sedang" {{ old('prioritas', 'sedang') == 'sedang' ? 'selected' : '' }}>🟡 Sedang</option>
                            <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>🟢 Rendah</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Anggaran --}}
            <div class="mb-6">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #0d9488;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                    ANGGARAN
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estimasi Harga Satuan</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-sm text-slate-500">Rp</span>
                            <input type="number" name="estimasi_harga" value="{{ old('estimasi_harga', 0) }}" min="0"
                                class="w-full pl-8 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Sumber Dana Rencana</label>
                        <input type="text" name="sumber_dana" value="{{ old('sumber_dana') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                            placeholder="Kas RT / Iuran Khusus / Donasi">
                        <p class="text-xs text-slate-500 mt-1">Sumber dana aktual diisi saat realisasi</p>
                    </div>
                </div>
            </div>

            {{-- Jadwal & Keterangan --}}
            <div class="mb-6">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #0d9488;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    JADWAL & KETERANGAN
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Rencana <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_rencana" value="{{ old('tanggal_rencana', date('Y-m-d')) }}" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('tanggal_rencana') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan / Alasan</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                            placeholder="Jelaskan kebutuhan dan urgensi pembelian...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                <a href="{{ route('barang.rencana.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">
                    ← Kembali
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-lg transition-all hover:scale-105" style="background: linear-gradient(135deg, #0d9488, #0f766e); box-shadow: 0 4px 12px rgba(13,148,136,0.3);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Rencana
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
