@extends('layouts.app')

@section('title', 'Catat Peminjaman')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('peminjaman.index') }}" class="text-teal-600 hover:underline font-medium">Peminjaman</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Tambah</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">Catat Peminjaman Barang</h1>
        </div>
        <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kode_peminjaman" value="{{ $kode }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-5">
                {{-- Data Barang --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #7c3aed;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        DATA BARANG
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Barang yang Dipinjam <span class="text-red-500">*</span></label>
                            <select name="barang_id" required id="barangSelect"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">— Pilih Barang —</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}" data-stok="{{ $b->jumlah }}" data-satuan="{{ $b->satuan }}" data-kondisi="{{ $b->kondisi }}"
                                        {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_barang }} (Stok: {{ $b->jumlah }} {{ $b->satuan }}) - {{ $b->kondisi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Pinjam <span class="text-red-500">*</span></label>
                                <input type="number" name="jumlah_pinjam" value="{{ old('jumlah_pinjam', 1) }}" required min="1" id="jumlahPinjam"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <p class="text-xs text-slate-500 mt-1">Tersedia: <span id="stokInfo" class="font-semibold">-</span></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Kondisi Saat Dipinjam <span class="text-red-500">*</span></label>
                                <select name="kondisi_saat_pinjam" required
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="Baik">✓ Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #7c3aed;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        TANGGAL
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Pinjam <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('tanggal_pinjam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Rencana Tanggal Kembali <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_rencana_kembali" value="{{ old('tanggal_rencana_kembali') }}" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('tanggal_rencana_kembali') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Keperluan / Keterangan</label>
                        <textarea name="keperluan" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Untuk acara apa / keperluan peminjaman...">{{ old('keperluan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="space-y-5">
                {{-- Data Peminjam --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-4 flex items-center gap-2" style="color: #7c3aed;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        DATA PEMINJAM
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Warga RT <span class="text-xs text-slate-400 font-normal">(opsional)</span></label>
                            <select name="anggota_keluarga_id" id="wargaSelect"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">— Non-warga / Isi manual —</option>
                                @foreach($wargas as $w)
                                    <option value="{{ $w->id }}" data-nama="{{ $w->nama_lengkap }}"
                                        {{ old('anggota_keluarga_id') == $w->id ? 'selected' : '' }}>
                                        {{ $w->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Pilih jika peminjam adalah warga terdaftar</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Peminjam <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required id="namaPeminjam"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Nama lengkap peminjam">
                            @error('nama_peminjam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">No. HP Peminjam</label>
                            <input type="text" name="no_hp_peminjam" value="{{ old('no_hp_peminjam') }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>
                </div>

                {{-- Catatan Penting --}}
                <div class="rounded-xl p-4 border" style="background: #ede9fe; border-color: #ddd6fe;">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #ddd6fe;">
                            <svg class="w-4 h-4" style="color: #7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm" style="color: #5b21b6;">Catatan Penting</h4>
                            <ul class="text-xs mt-1 space-y-1" style="color: #6d28d9;">
                                <li>• Pastikan stok tersedia sebelum menyimpan</li>
                                <li>• Barang wajib dikembalikan sesuai tanggal</li>
                                <li>• Proses "Kembali" dilakukan setelah barang tiba</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">
                ← Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-lg transition-all hover:scale-105" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); box-shadow: 0 4px 12px rgba(124,58,237,0.3);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Peminjaman
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('barangSelect').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const stok = option.getAttribute('data-stok');
        const satuan = option.getAttribute('data-satuan');
        const kondisi = option.getAttribute('data-kondisi');
        document.getElementById('stokInfo').textContent = stok ? stok + ' ' + satuan : '-';
        document.getElementById('jumlahPinjam').max = stok || '';
        if (kondisi) {
            document.querySelector('select[name="kondisi_saat_pinjam"]').value = kondisi;
        }
    });

    document.getElementById('wargaSelect').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const nama = option.getAttribute('data-nama');
        if (nama) {
            document.getElementById('namaPeminjam').value = nama;
        }
    });
</script>
@endpush
@endsection
