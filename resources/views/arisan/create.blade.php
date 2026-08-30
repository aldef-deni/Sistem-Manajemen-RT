@extends('layouts.app')

@section('title', 'Buat Arisan Baru')

@section('content')
<div class="space-y-4">
    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('arisan.index') }}" class="text-teal-600 hover:underline font-medium">Arisan RT</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Buat Arisan Baru</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Buat Arisan Baru</h1>
        </div>
        <a href="{{ route('arisan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('arisan.store') }}" method="POST" id="arisanForm">
        @csrf

        {{-- Form Header --}}
        <div class="rounded-xl p-6 mb-6" style="background: linear-gradient(135deg, #14b8a6, #0d9488, #0f766e);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">Form Tambah Arisan</h2>
                    <p class="text-sm text-teal-100">Lengkapi informasi untuk membuat arisan baru</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Informasi Arisan --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 text-center">INFORMASI ARISAN</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">NAMA ARISAN <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"
                                placeholder="Contoh: Arisan RT 05 Tahun 2025">
                            @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">NOMINAL IURAN <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-sm text-slate-500">Rp</span>
                                    <input type="number" name="nominal_iuran" value="{{ old('nominal_iuran') }}" required min="1000"
                                        class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"
                                        placeholder="100000">
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Minimal Rp 1.000 per periode</p>
                                @error('nominal_iuran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">TANGGAL MULAI <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                @error('tanggal_mulai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Periode Arisan --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">PERIODE ARISAN <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="periode-option relative border-2 border-slate-200 rounded-xl p-4 cursor-pointer hover:border-teal-400 transition-all has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                                    <input type="radio" name="periode" value="mingguan" class="sr-only" {{ old('periode') == 'mingguan' ? 'checked' : '' }}>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 text-sm">Mingguan</p>
                                            <p class="text-xs text-slate-500">Pengundian setiap minggu</p>
                                        </div>
                                    </div>
                                    <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center hidden checked-ring"></div>
                                </label>
                                <label class="periode-option relative border-2 border-slate-200 rounded-xl p-4 cursor-pointer hover:border-teal-400 transition-all has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                                    <input type="radio" name="periode" value="bulanan" class="sr-only" {{ old('periode', 'bulanan') == 'bulanan' ? 'checked' : '' }}>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 text-sm">Bulanan</p>
                                            <p class="text-xs text-slate-500">Pengundian setiap bulan</p>
                                        </div>
                                    </div>
                                    <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center hidden checked-ring"></div>
                                </label>
                            </div>
                            @error('periode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Setting Undian --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 text-center">SETTING UNDIAN</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">MODE UNDIAN <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative border-2 border-slate-200 rounded-xl p-4 cursor-pointer hover:border-teal-400 transition-all has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                                    <input type="radio" name="mode_undian" value="manual" class="sr-only" {{ old('mode_undian', 'manual') == 'manual' ? 'checked' : '' }}>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 text-sm">Manual (Dacakut)</p>
                                            <p class="text-xs text-slate-500">Pemenang ditentukan oleh admin</p>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative border-2 border-slate-200 rounded-xl p-4 cursor-pointer hover:border-teal-400 transition-all has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                                    <input type="radio" name="mode_undian" value="otomatis" class="sr-only" {{ old('mode_undian') == 'otomatis' ? 'checked' : '' }}>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 text-sm">Otomatis (Acak)</p>
                                            <p class="text-xs text-slate-500">Pemenang diacak otomatis</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">⚠️ Dalam mode manual, pemenang akan ditentukan oleh admin saat pengundian</p>
                            @error('mode_undian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">JUMLAH PENDAMPING PER PERTIGAAN <span class="text-red-500">*</span></label>
                            <input type="number" name="pendamping_per_periode" value="{{ old('pendamping_per_periode', 1) }}" required min="1"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"
                                placeholder="1">
                            <p class="text-xs text-slate-500 mt-1">ℹ️ Jumlah uang yang diterima pada setiap pengundian/periode</p>
                            @error('pendamping_per_periode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 text-center">KETERANGAN</h3>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">KAS UNTUK ARISAN INI <span class="text-xs text-slate-400 font-normal">(OPTIONAL)</span></label>
                        <select name="rekening_kas_id"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm mb-3">
                            <option value="">Pilih Rekening</option>
                            @foreach($rekenings as $rekening)
                                <option value="{{ $rekening->id }}">{{ $rekening->nama }} ({{ number_format($rekening->saldo, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mb-3">ℹ️ Jika tidak dipilih, akan menggunakan kas default saat buat transaksi</p>

                        <label class="block text-sm font-medium text-slate-700 mb-1">KETERANGAN <span class="text-xs text-slate-400 font-normal">(OPTIONAL)</span></label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"
                            placeholder="Keterangan tambahan tentang arisan ini..."></textarea>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('arisan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-lg transition-all hover:scale-105"
                        style="background: linear-gradient(135deg, #14b8a6, #0d9488); box-shadow: 0 4px 12px rgba(13,148,136,0.35);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Arisan
                    </button>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="space-y-4">
                {{-- Catatan Penting --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-800 text-sm mb-2">Catatan Penting:</h4>
                            <ul class="text-xs text-blue-700 space-y-1.5">
                                <li>• Setelah arisan dibuat, tambahkan peserta terlebih dahulu sebelum memulai pengundian</li>
                                <li>• Nomor Rekening: transaksi akan tercatat pada kas tersebut</li>
                                <li>• KAS default akan dipakai jika tidak memilih</li>
                                <li>• KAS harian bisa dipakai jika tidak memilih</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Info Periode --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h4 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Estimasi Periode
                    </h4>
                    <div id="periodeInfo" class="space-y-2 text-sm text-slate-600">
                        <p class="text-slate-400 italic">Pilih periode untuk melihat estimasi</p>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h4 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Tips
                    </h4>
                    <ul class="text-xs text-slate-600 space-y-1.5">
                        <li>• Buat arisan dengan nominal kecil untuk menarik lebih banyak peserta</li>
                        <li>• Untuk arisan besar, pertimbangkan mode undian manual</li>
                        <li>• Peserta bisa ditambahkan setelah arisan dibuat</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.querySelectorAll('input[name="periode"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const info = document.getElementById('periodeInfo');
            if (this.value === 'mingguan') {
                info.innerHTML = `
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-500"></span><span>Setiap 1 minggu sekali</span></div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-500"></span><span>Total periode: Jumlah peserta minggu</span></div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-teal-500"></span><span>Contoh: 20 peserta = 20 minggu</span></div>
                `;
            } else {
                info.innerHTML = `
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span><span>Setiap 1 bulan sekali</span></div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span><span>Total periode: Jumlah peserta bulan</span></div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span><span>Contoh: 20 peserta = 20 bulan (1.6 tahun)</span></div>
                `;
            }
        });
    });
</script>
@endpush
@endsection