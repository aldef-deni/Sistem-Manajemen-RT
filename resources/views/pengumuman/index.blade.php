@extends('layouts.app')

@section('title', 'Pengumuman')

@php
    $bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $kategoriColors = [
        'Umum' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
        'Keuangan' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
        'Keamanan' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
        'Kebersihan' => ['bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'border' => 'border-teal-200'],
        'Kegiatan' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'border-purple-200'],
        'Darurat' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
        'Lainnya' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200'],
    ];
    $targetLabels = [
        'semua' => 'Semua Warga',
        'rt' => 'Pengurus RT',
        'rw' => 'Pengurus RW',
        'per_blok' => 'Per Blok',
        'warga_tertentu' => 'Warga Tertentu',
    ];
@endphp

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Pengumuman</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Pengumuman</h1>
                <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <a href="{{ route('pengumuman.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-xl shadow-lg shadow-teal-500/25 hover:shadow-xl hover:shadow-teal-500/30 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Pengumuman
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('pengumuman.index') }}">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="text-sm font-semibold text-slate-600 block mb-1.5">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-600 block mb-1.5">Bulan</label>
                    <select name="bulan" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                        <option value="">Semua</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ $bulanNames[$m] }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-600 block mb-1.5">Tahun</label>
                    <select name="tahun" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                        <option value="">Semua</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="lg:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    @if(request('kategori') || request('bulan') || request('tahun'))
                        <a href="{{ route('pengumuman.index') }}" class="px-4 py-2.5 text-slate-500 hover:text-red-500 border border-slate-300 rounded-xl text-sm font-medium flex items-center gap-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>

    {{-- List Pengumuman --}}
    @if($pengumuman->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($pengumuman as $p)
                @php
                    $colors = $kategoriColors[$p->kategori] ?? $kategoriColors['Lainnya'];
                    $isExpired = $p->tanggal_berakhir && $p->tanggal_berakhir->isPast();
                @endphp
                <a href="{{ route('pengumuman.show', $p->id) }}" class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group flex flex-col">
                    {{-- Card Header --}}
                    <div class="p-5 pb-3 flex-1">
                        <div class="flex items-start justify-between mb-3">
                            <span class="px-3 py-1 {{ $colors['bg'] }} {{ $colors['text'] }} border {{ $colors['border'] }} rounded-lg text-xs font-bold">
                                {{ $p->kategori }}
                            </span>
                            <div class="flex items-center gap-2">
                                @if($p->status === 'draft')
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-xs font-bold">Draft</span>
                                @endif
                                @if($isExpired)
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-xs font-bold">Expired</span>
                                @endif
                            </div>
                        </div>
                        <h3 class="font-bold text-slate-800 text-base leading-snug mb-2 group-hover:text-teal-600 transition line-clamp-2">{{ $p->judul }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-3 leading-relaxed">{!! strip_tags($p->isi) !!}</p>
                    </div>

                    {{-- Card Footer --}}
                    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $p->tanggal_publish->format('d/m/Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ $p->dilihat }}
                                </span>
                            </div>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $targetLabels[$p->target] ?? 'Semua Warga' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $pengumuman->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-20 text-center">
            <div class="w-20 h-20 mx-auto bg-teal-50 rounded-2xl flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1">Belum ada pengumuman</h3>
            <p class="text-slate-500 text-sm">Pengumuman baru akan muncul di sini</p>
        </div>
    @endif
</div>
@endsection
