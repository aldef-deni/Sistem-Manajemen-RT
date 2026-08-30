@extends('layouts.app')

@section('title', 'Edit Tagihan Iuran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Tagihan Iuran</h1>
            <p class="text-sm text-slate-500 mt-0.5">Ubah detail tagihan iuran warga</p>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('iuran-warga.index') }}" class="text-teal-600 hover:underline font-medium">Iuran Warga</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Edit Tagihan</span>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Info Warga --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Data Warga</h3>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-1">Nama Warga</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                {{ strtoupper(substr($iuran_warga->anggota->nama_lengkap ?? '-', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $iuran_warga->anggota->nama_lengkap ?? '-' }}</p>
                                <p class="text-xs text-slate-400">NIK: {{ $iuran_warga->anggota->nik ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-1">Alamat</p>
                        <p class="text-sm text-slate-700">{{ $iuran_warga->anggota->kartuKeluarga->alamat ?? '-' }}</p>
                        <p class="text-xs text-slate-400">RT {{ $iuran_warga->anggota->kartuKeluarga->rt ?? '-' }} / RW {{ $iuran_warga->anggota->kartuKeluarga->rw ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-1">Jenis Iuran</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $iuran_warga->jenisIuran->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-1">Periode</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $iuran_warga->periode }}</p>
                    </div>
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

                <form method="POST" action="{{ route('iuran-warga.update', $iuran_warga->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan <span class="text-slate-400 font-normal">(opsional)</span></label>
                            <textarea name="catatan" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 resize-none" placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('catatan', $iuran_warga->catatan) }}</textarea>
                        </div>
                    </div>
            </div>
        </div>

        {{-- Right: Edit Nominal --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Ubah Nominal</h3>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nominal <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-500">Rp</span>
                        <input type="number" name="nominal" value="{{ old('nominal', $iuran_warga->nominal) }}" min="0" class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" required>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-slate-500 mb-2">Status Saat Ini</p>
                        @if($iuran_warga->status === 'lunas')
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Lunas — {{ $iuran_warga->tanggal_bayar?->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                Belum Dibayar
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col gap-3">
                <a href="{{ route('iuran-warga.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 shadow-lg shadow-teal-500/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection
