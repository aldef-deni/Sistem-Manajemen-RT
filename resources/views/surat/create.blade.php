@extends('layouts.app')

@section('title', 'Ajukan Surat')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('surat.index') }}" class="text-teal-600 hover:underline font-medium">Surat</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Ajukan</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Ajukan Permohonan Surat</h1>
                <p class="text-sm text-slate-500">Isi formulir berikut dengan lengkap dan benar</p>
            </div>
        </div>
        <a href="{{ route('surat.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="flex gap-6">
        {{-- Form --}}
        <div class="flex-1 space-y-6">
            <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Section 1: Data Pemohon --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-50 to-emerald-50 border-b border-slate-200 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">DATA PEMOHON</h3>
                            <p class="text-xs text-slate-500">Pilih warga yang mengajukan surat</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700 flex items-center gap-1">
                                Pilih Warga <span class="text-red-500">*</span>
                            </label>
                            <select name="anggota_keluarga_id" id="warga_select" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition bg-white" required>
                                <option value="">-- Pilih Warga --</option>
                                @foreach($warga as $w)
                                    <option value="{{ $w->id }}" data-nik="{{ $w->nik ?? '' }}" data-nama="{{ $w->nama_lengkap }}">
                                        {{ $w->nama_lengkap }} @if($w->nik) (NIK: {{ $w->nik }}) @endif — KK: {{ $w->kartuKeluarga->no_kk ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-400">Pilih warga yang mengajukan surat</p>
                            @error('anggota_keluarga_id')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2: Jenis & Keperluan --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-slate-200 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">JENIS & KEPERLUAN</h3>
                            <p class="text-xs text-slate-500">Tentukan jenis surat dan keperluan</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700 flex items-center gap-1">
                                Jenis Surat <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_surat" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition bg-white" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                @foreach($jenisSurat as $js)
                                    <option value="{{ $js }}" {{ old('jenis_surat') == $js ? 'selected' : '' }}>{{ $js }}</option>
                                @endforeach
                            </select>
                            @error('jenis_surat')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700 flex items-center gap-1">
                                Keperluan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="keperluan" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition resize-none" placeholder="Jelaskan keperluan pengajuan surat secara lengkap..." required>{{ old('keperluan') }}</textarea>
                            <p class="text-xs text-slate-400">Contoh: Untuk keperluan melamar pekerjaan di PT. ABC Tbk.</p>
                            @error('keperluan')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 3: Dokumen Pendukung --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-violet-50 to-purple-50 border-b border-slate-200 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">DOKUMEN PENDUKUNG</h3>
                            <p class="text-xs text-slate-500">Upload dokumen pendukung (opsional)</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <label for="file_dokumen" class="block border-2 border-dashed border-slate-300 rounded-xl p-8 text-center cursor-pointer hover:border-teal-400 hover:bg-teal-50/30 transition group" id="dropzone">
                            <div id="dropzone-content">
                                <div class="w-14 h-14 mx-auto bg-slate-100 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-teal-100 transition">
                                    <svg class="w-7 h-7 text-slate-400 group-hover:text-teal-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Klik atau seret file ke sini</p>
                                <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, PDF • Maks: 2MB • Opsional</p>
                            </div>
                            <div id="dropzone-preview" class="hidden">
                                <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-4">
                                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <p class="text-sm font-semibold text-slate-700" id="file-name"></p>
                                        <p class="text-xs text-slate-400" id="file-size"></p>
                                    </div>
                                    <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </label>
                        <input type="file" name="file_dokumen" id="file_dokumen" accept="image/*,.pdf" class="hidden">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('surat.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        &larr; Batal
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-teal-500/25 hover:shadow-xl hover:shadow-teal-500/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Ajukan Permohonan
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar Info --}}
        <div class="w-72 space-y-4 flex-shrink-0">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg shadow-blue-500/20">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="font-bold text-sm">Informasi Penting</h4>
                </div>
                <div class="space-y-3 text-sm text-blue-100">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-blue-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span><strong>Isi Data Lengkap</strong> — Pastikan semua data yang diisi sudah benar dan sesuai identitas.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-blue-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span><strong>Waktu Proses</strong> — Permohonan akan diproses dalam <strong>1-3 hari kerja</strong> oleh admin.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-blue-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span><strong>Pantau Status</strong> — Status permohonan dapat dipantau di halaman Daftar Surat.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 text-blue-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span><strong>Unduh Surat</strong> — Surat yang telah diproses dapat langsung diunduh dari sistem.</span>
                    </div>
                </div>
            </div>

            {{-- Mode Admin --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h4 class="font-bold text-sm text-slate-800">Mode Admin</h4>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">Anda membuat permohonan <strong>atas nama warga</strong>. Pastikan data pemohon yang dipilih sudah tepat sebelum mengajukan.</p>
            </div>
        </div>
    </div>
</div>

<script>
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('file_dokumen');
const dropzoneContent = document.getElementById('dropzone-content');
const dropzonePreview = document.getElementById('dropzone-preview');
const fileName = document.getElementById('file-name');
const fileSize = document.getElementById('file-size');

dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('border-teal-400', 'bg-teal-50'); });
dropzone.addEventListener('dragleave', () => { dropzone.classList.remove('border-teal-400', 'bg-teal-50'); });
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('border-teal-400', 'bg-teal-50');
    if (e.dataTransfer.files.length) { fileInput.files = e.dataTransfer.files; showFile(e.dataTransfer.files[0]); }
});
dropzone.addEventListener('click', () => { fileInput.click(); });
fileInput.addEventListener('change', function() { if (this.files.length) showFile(this.files[0]); });

function showFile(file) {
    fileName.textContent = file.name;
    fileSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
    dropzoneContent.classList.add('hidden');
    dropzonePreview.classList.remove('hidden');
}

function clearFile() {
    fileInput.value = '';
    dropzoneContent.classList.remove('hidden');
    dropzonePreview.classList.add('hidden');
}
</script>
@endsection
