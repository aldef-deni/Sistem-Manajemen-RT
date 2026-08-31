@extends('layouts.app')

@section('title', 'Edit Permohonan Surat')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('surat.index') }}" class="text-teal-600 hover:underline font-medium">Surat</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Edit</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Permohonan Surat</h1>
                <p class="text-sm text-slate-500">{{ $surat->kode_surat }}</p>
            </div>
        </div>
        <a href="{{ route('surat.show', $surat->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="flex gap-6">
        <div class="flex-1 space-y-6">
            <form action="{{ route('surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Data Pemohon --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-50 to-emerald-50 border-b border-slate-200 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800">DATA PEMOHON</h3>
                    </div>
                    <div class="p-6">
                        <label class="text-sm font-bold text-slate-700 block mb-1.5">Pilih Warga <span class="text-red-500">*</span></label>
                        <select name="anggota_keluarga_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition bg-white" required>
                            <option value="">-- Pilih Warga --</option>
                            @foreach($warga as $w)
                                <option value="{{ $w->id }}" {{ $surat->anggota_keluarga_id == $w->id ? 'selected' : '' }}>
                                    {{ $w->nama_lengkap }} @if($w->nik) (NIK: {{ $w->nik }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Jenis & Keperluan --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-slate-200 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800">JENIS & KEPERLUAN</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-1.5">Jenis Surat <span class="text-red-500">*</span></label>
                            <select name="jenis_surat" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition bg-white" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                @foreach($jenisSurat as $js)
                                    <option value="{{ $js }}" {{ $surat->jenis_surat == $js ? 'selected' : '' }}>{{ $js }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-1.5">Keperluan <span class="text-red-500">*</span></label>
                            <textarea name="keperluan" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition resize-none" required>{{ $surat->keperluan }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Status & Info Admin --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-slate-200 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800">STATUS & INFORMASI ADMIN</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="text-sm font-bold text-slate-700 block mb-1.5">Status</label>
                                <select name="status" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition bg-white" required>
                                    <option value="pending" {{ $surat->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diproses" {{ $surat->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ $surat->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="ditolak" {{ $surat->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-bold text-slate-700 block mb-1.5">Nomor Surat</label>
                                <input type="text" name="nomor_surat" value="{{ $surat->nomor_surat }}" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition" placeholder="Nomor surat resmi">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-1.5">Catatan Admin</label>
                            <textarea name="catatan_admin" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition resize-none" placeholder="Catatan internal...">{{ $surat->catatan_admin }}</textarea>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-1.5">Dokumen Pendukung (opsional)</label>
                            <input type="file" name="file_dokumen" accept="image/*,.pdf" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                            @if($surat->file_dokumen)
                                <a href="{{ asset('storage/' . $surat->file_dokumen) }}" target="_blank" class="text-xs text-teal-600 hover:underline mt-1 inline-block">📄 Lihat dokumen saat ini</a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('surat.show', $surat->id) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">
                        &larr; Batal
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar --}}
        <div class="w-72 flex-shrink-0">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="font-bold text-sm text-slate-800">Info Edit</h4>
                </div>
                <div class="space-y-2 text-xs text-slate-600">
                    <p>• Perubahan data pemohon akan mempengaruhi data NIK pada surat.</p>
                    <p>• Nomor surat hanya diisi setelah surat resmi diterbitkan.</p>
                    <p>• Status dapat diubah sesuai progress penyelesaian.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
