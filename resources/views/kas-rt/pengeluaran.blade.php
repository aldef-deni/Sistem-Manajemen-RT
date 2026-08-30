@extends('layouts.app')

@section('title', 'Tambah Pengeluaran Kas')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Header --}}
    <div style="background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 1rem 1rem 0 0; padding: 1.25rem 1.5rem;">
        <a href="{{ route('kas-rt.index') }}" style="display: inline-flex; align-items: center; gap: 6px; color: rgba(255,255,255,0.8); font-size: 0.875rem; margin-bottom: 0.5rem; text-decoration: none;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Kas RT
        </a>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 48px; height: 48px; border-radius: 0.75rem; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 24px; height: 24px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </div>
            <h1 style="font-size: 1.25rem; font-weight: 700; color: white;">Tambah Pengeluaran Kas</h1>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-b-2xl border border-t-0 border-slate-200 shadow-sm p-6">
        <form action="{{ route('kas-rt.store-pengeluaran') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- Informasi Dasar --}}
            <div class="mb-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi Dasar
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                        @error('tanggal')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('kategori')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Sumber Dana --}}
            <div class="mb-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                    Sumber Dana
                </h3>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keluar dari Kas <span class="text-red-500">*</span></label>
                    <select name="rekening_kas_id" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white">
                        <option value="">-- Pilih Rekening / Kas Sumber Dana --</option>
                        @foreach($rekenings as $rk)
                        <option value="{{ $rk->id }}" {{ old('rekening_kas_id') == $rk->id ? 'selected' : '' }}>
                            {{ $rk->nama }} — Saldo: Rp {{ number_format($rk->saldo, 0, ',', '.') }}
                        </option>
                        @endforeach
                    </select>
                    @error('rekening_kas_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Detail Transaksi --}}
            <div class="mb-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Detail Transaksi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nominal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-red-600">Rp</span>
                            <input type="number" name="nominal" value="{{ old('nominal', 0) }}" min="1" required
                                class="w-full pl-12 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                        </div>
                        @error('nominal')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan <span class="text-slate-400 font-normal">(opsional)</span></label>
                        <textarea name="keterangan" rows="3" placeholder="Tambahkan catatan untuk transaksi ini..."
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all resize-none">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Bukti Dokumen --}}
            <div class="mb-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Bukti Dokumen <span class="text-slate-400 font-normal normal-case">(opsional)</span>
                </h3>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-red-300 transition-colors cursor-pointer" onclick="document.getElementById('buktiInput').click()">
                    <input type="file" id="buktiInput" name="bukti_dokumen" accept="image/jpeg,image/png,application/pdf" class="hidden" onchange="previewFile(this)">
                    <div id="uploadPlaceholder">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <p class="text-sm text-slate-500">Klik untuk upload atau drag & drop file di sini</p>
                        <p class="text-xs text-slate-400 mt-1">JPG, PNG, PDF — Maks. 2MB</p>
                    </div>
                    <div id="filePreview" class="hidden">
                        <div class="flex items-center justify-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="text-left">
                                <p id="fileName" class="text-sm font-semibold text-slate-700"></p>
                                <p id="fileSize" class="text-xs text-slate-400"></p>
                            </div>
                            <button type="button" onclick="removeFile()" class="text-red-400 hover:text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @error('bukti_dokumen')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('kas-rt.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl text-sm font-semibold hover:from-red-600 hover:to-red-700 transition-all shadow-lg shadow-red-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('uploadPlaceholder').classList.add('hidden');
        document.getElementById('filePreview').classList.remove('hidden');
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    }
}

function removeFile() {
    document.getElementById('buktiInput').value = '';
    document.getElementById('uploadPlaceholder').classList.remove('hidden');
    document.getElementById('filePreview').classList.add('hidden');
}
</script>
@endsection
