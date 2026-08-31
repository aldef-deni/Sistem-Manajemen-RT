@extends('layouts.app')

@section('title', $pengumuman->judul)

@php
    $kategoriColors = [
        'Umum' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
        'Keuangan' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
        'Keamanan' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
        'Kebersihan' => ['bg' => 'bg-teal-100', 'text' => 'text-teal-700'],
        'Kegiatan' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
        'Darurat' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
        'Lainnya' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700'],
    ];
    $colors = $kategoriColors[$pengumuman->kategori] ?? $kategoriColors['Lainnya'];
    $targetLabels = [
        'semua' => 'Semua Warga', 'rt' => 'Pengurus RT', 'rw' => 'Pengurus RW',
        'per_blok' => 'Per Blok', 'warga_tertentu' => 'Warga Tertentu',
    ];
@endphp

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('pengumuman.index') }}" class="text-teal-600 hover:underline font-medium">Pengumuman</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Detail</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Pengumuman</h1>
                <p class="text-sm text-slate-500">{{ $pengumuman->kode ?? $pengumuman->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('pengumuman.edit', $pengumuman->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition font-medium text-sm shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form action="{{ route('pengumuman.destroy', $pengumuman->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
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
        <div class="flex-1">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="p-6 border-b border-slate-100">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 {{ $colors['bg'] }} {{ $colors['text'] }} rounded-lg text-xs font-bold">{{ $pengumuman->kategori }}</span>
                        @if($pengumuman->status === 'publish')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Publish
                            </span>
                        @else
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold">Draft</span>
                        @endif
                        @if($pengumuman->is_expired)
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-xs font-bold">Expired</span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 leading-snug">{{ $pengumuman->judul }}</h2>
                </div>

                {{-- Isi --}}
                <div class="p-6">
                    <div class="prose prose-slate max-w-none text-sm leading-relaxed">{!! $pengumuman->isi !!}</div>
                </div>

                {{-- Lampiran --}}
                @if($pengumuman->lampiran)
                <div class="px-6 pb-6">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                        <p class="text-xs text-slate-500 font-bold mb-2 uppercase tracking-wider">📎 Lampiran</p>
                        <a href="{{ asset('storage/' . $pengumuman->lampiran) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-teal-700 hover:bg-teal-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            Lihat Lampiran
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="w-72 space-y-4 flex-shrink-0">
            {{-- Info Publikasi --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h4 class="font-bold text-sm text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Info Publikasi
                </h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Status</span>
                        @if($pengumuman->status === 'publish')
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-bold">Publish</span>
                        @else
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-xs font-bold">Draft</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Target</span>
                        <span class="font-semibold text-slate-700 text-xs">{{ $targetLabels[$pengumuman->target] ?? 'Semua Warga' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Tgl Publish</span>
                        <span class="font-semibold text-slate-700 text-xs">{{ $pengumuman->tanggal_publish->format('d/m/Y') }}</span>
                    </div>
                    @if($pengumuman->tanggal_berakhir)
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Berakhir</span>
                        <span class="font-semibold text-slate-700 text-xs">{{ $pengumuman->tanggal_berakhir->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Dilihat</span>
                        <span class="font-semibold text-slate-700 text-xs">{{ $pengumuman->dilihat }}x</span>
                    </div>
                </div>
            </div>

            {{-- Dibuat Oleh --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h4 class="font-bold text-sm text-slate-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Dibuat Oleh
                </h4>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white font-bold text-xs">
                        {{ strtoupper(substr($pengumuman->pembuat->name ?? 'Admin', 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">{{ $pengumuman->pembuat->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-slate-400">{{ $pengumuman->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
