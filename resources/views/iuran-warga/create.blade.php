@extends('layouts.app')

@section('title', 'Tambah Tagihan Iuran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Tagihan Iuran</h1>
            <p class="text-sm text-slate-500 mt-0.5">Buat tagihan iuran manual untuk warga</p>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('iuran-warga.index') }}" class="text-teal-600 hover:underline font-medium">Iuran Warga</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Tambah Tagihan</span>
    </div>

    {{-- Errors --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <ul class="text-sm text-red-700 font-medium list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('iuran-warga.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Data Warga --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Data Warga --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Data Warga</h3>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Warga <span class="text-red-500">*</span></label>
                        <select name="anggota_keluarga_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" required>
                            <option value="">— Pilih Warga —</option>
                            @foreach($warga as $w)
                                <option value="{{ $w->id }}" {{ old('anggota_keluarga_id') == $w->id ? 'selected' : '' }}>
                                    {{ $w->nama_lengkap }} — {{ $w->nik }} (RT {{ $w->kartuKeluarga->rt ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Ketik untuk mencari nama atau NIK warga
                        </p>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Keterangan</h3>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan <span class="text-slate-400 font-normal">(opsional)</span></label>
                        <textarea name="catatan" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 resize-none" placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Right: Detail Iuran --}}
            <div class="space-y-6">
                {{-- Detail Iuran --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Detail Iuran</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Iuran <span class="text-red-500">*</span></label>
                            <select name="jenis_iuran_id" id="jenis_iuran_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" required onchange="updateNominal()">
                                <option value="">— Pilih Jenis Iuran —</option>
                                @foreach($jenisIurans as $j)
                                    <option value="{{ $j->id }}" data-nominal="{{ $j->nominal_default }}" {{ old('jenis_iuran_id') == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Periode <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <select name="bulan" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" required>
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ old('bulan', date('m')) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                                <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" min="2020" max="2030" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" required>
                            </div>
                            <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Pilih bulan dan tahun periode tagihan
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nominal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-500">Rp</span>
                                <input type="number" name="nominal" id="nominal" value="{{ old('nominal', 0) }}" min="0" class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" required>
                            </div>
                            <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Otomatis terisi, dapat diubah manual
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Info --}}
                <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h4 class="text-sm font-bold text-blue-800">Informasi</h4>
                    </div>
                    <ul class="space-y-2 text-xs text-blue-700">
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>Tagihan dibuat dengan status <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> Belum Dibayar</span></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>Warga akan mendapat notifikasi tagihan baru</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>Untuk banyak tagihan sekaligus, gunakan <strong>Generate Tagihan</strong></span>
                        </li>
                    </ul>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('iuran-warga.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 shadow-lg shadow-teal-500/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Tagihan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function updateNominal() {
    const select = document.getElementById('jenis_iuran_id');
    const option = select.options[select.selectedIndex];
    const nominal = option.getAttribute('data-nominal');
    if (nominal) {
        document.getElementById('nominal').value = nominal;
    }
}
</script>
@endsection
