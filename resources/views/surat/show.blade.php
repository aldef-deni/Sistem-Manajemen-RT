@extends('layouts.app')

@section('title', 'Detail Permohonan Surat')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('surat.index') }}" class="text-teal-600 hover:underline font-medium">Surat</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Detail</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Permohonan Surat</h1>
                <p class="text-sm text-slate-500">{{ $surat->kode_surat }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('surat.edit', $surat->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition font-medium text-sm shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form action="{{ route('surat.destroy', $surat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permohonan ini?')">
                @csrf @method('DELETE')
                <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-xl hover:bg-red-600 transition font-medium text-sm shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="flex gap-6">
        {{-- Main Content --}}
        <div class="flex-1 space-y-6">
            {{-- Info Surat --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Informasi Permohonan
                    </h3>
                    @if($surat->status == 'pending')
                        <span class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu
                        </span>
                    @elseif($surat->status == 'diproses')
                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-spin"></span> Diproses
                        </span>
                    @elseif($surat->status == 'selesai')
                        <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Selesai
                        </span>
                    @else
                        <span class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Ditolak
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-slate-500 font-medium mb-1">Kode Surat</p>
                            <p class="font-mono font-bold text-slate-800 bg-slate-100 px-3 py-1.5 rounded-lg text-sm w-fit">{{ $surat->kode_surat }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium mb-1">Nomor Surat</p>
                            <p class="font-semibold text-slate-800">{{ $surat->nomor_surat ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium mb-1">Tanggal Pengajuan</p>
                            <p class="font-semibold text-slate-800">{{ $surat->created_at->format('d/m/Y H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium mb-1">Jenis Surat</p>
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold border border-blue-100">{{ $surat->jenis_surat }}</span>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-slate-500 font-medium mb-1">Keperluan</p>
                            <p class="text-slate-700 bg-slate-50 rounded-xl p-4 text-sm leading-relaxed">{{ $surat->keperluan }}</p>
                        </div>
                        @if($surat->file_dokumen)
                        <div class="col-span-2">
                            <p class="text-xs text-slate-500 font-medium mb-1">Dokumen Pendukung</p>
                            <a href="{{ asset('storage/' . $surat->file_dokumen) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 text-teal-700 rounded-lg text-sm font-medium hover:bg-teal-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Data Pemohon --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Data Pemohon
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-5 pb-5 border-b border-slate-100">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            {{ strtoupper(substr($surat->nama_pemohon, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">{{ $surat->nama_pemohon }}</h4>
                            @if($surat->anggotaKeluarga)
                                <p class="text-sm text-slate-500">{{ $surat->anggotaKeluarga->kartuKeluarga->no_kk ?? '' }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-500 font-medium">NIK</p>
                            <p class="font-mono font-bold text-slate-800 text-sm mt-0.5">{{ $surat->nik ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-500 font-medium">Jenis Kelamin</p>
                            <p class="font-semibold text-slate-800 text-sm mt-0.5">{{ $surat->anggotaKeluarga->jenis_kelamin ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-500 font-medium">Tempat, Tgl Lahir</p>
                            <p class="font-semibold text-slate-800 text-sm mt-0.5">{{ $surat->anggotaKeluarga->tempat_lahir ?? '' }}, {{ $surat->anggotaKeluarga->tanggal_lahir ? \Carbon\Carbon::parse($surat->anggotaKeluarga->tanggal_lahir)->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-500 font-medium">Agama</p>
                            <p class="font-semibold text-slate-800 text-sm mt-0.5">{{ $surat->anggotaKeluarga->agama ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- File Surat Jadi --}}
            @if($surat->status == 'selesai' && $surat->file_surat_jadi)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Surat Jadi
                    </h3>
                </div>
                <div class="p-6">
                    <a href="{{ asset('storage/' . $surat->file_surat_jadi) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-xl font-bold shadow-md hover:shadow-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Unduh Surat (PDF)
                    </a>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="w-72 space-y-4 flex-shrink-0">
            {{-- Ringkasan Status --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h4 class="font-bold text-sm text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Ringkasan
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Status</span>
                        @if($surat->status == 'pending')
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-xs font-bold">Menunggu</span>
                        @elseif($surat->status == 'diproses')
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-bold">Diproses</span>
                        @elseif($surat->status == 'selesai')
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-bold">Selesai</span>
                        @else
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-bold">Ditolak</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Tgl Pengajuan</span>
                        <span class="text-xs font-semibold text-slate-700">{{ $surat->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($surat->tanggal_proses)
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Tgl Diproses</span>
                        <span class="text-xs font-semibold text-slate-700">{{ $surat->tanggal_proses->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    @if($surat->tanggal_selesai)
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Tgl Selesai</span>
                        <span class="text-xs font-semibold text-emerald-600">{{ $surat->tanggal_selesai->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    @if($surat->catatan_admin)
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Catatan Admin</p>
                        <p class="text-xs text-slate-700 bg-slate-50 rounded-lg p-2.5">{{ $surat->catatan_admin }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Status Update --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h4 class="font-bold text-sm text-slate-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Update Status
                </h4>
                <form action="{{ route('surat.status', $surat->id) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white" required>
                        <option value="pending" {{ $surat->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="diproses" {{ $surat->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ $surat->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ $surat->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <input type="text" name="nomor_surat" value="{{ $surat->nomor_surat }}" placeholder="Nomor Surat (opsional)" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <textarea name="catatan_admin" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 resize-none" placeholder="Catatan admin...">{{ $surat->catatan_admin }}</textarea>
                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-bold rounded-lg text-sm shadow-md hover:shadow-lg transition">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
