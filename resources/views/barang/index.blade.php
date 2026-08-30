@extends('layouts.app')

@section('title', 'Inventaris RT')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #0d9488, #0f766e); box-shadow: 0 4px 12px rgba(13,148,136,0.3);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Inventaris RT</h1>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Inventaris</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('barang.rencana.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-teal-700 bg-white border-2 border-teal-500 rounded-lg hover:bg-teal-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                Rencana Beli
            </a>
            <a href="{{ route('barang.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-lg transition-all hover:scale-105" style="background: linear-gradient(135deg, #0d9488, #0f766e); box-shadow: 0 4px 12px rgba(13,148,136,0.3);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Barang
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 p-3 rounded-lg text-sm font-medium" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Aset --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #e0f2fe, #bae6fd);">
                <svg class="w-6 h-6" style="color: #0284c7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Aset</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalAset }}</p>
            </div>
        </div>
        {{-- Kondisi Baik --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #ecfdf5, #a7f3d0);">
                <svg class="w-6 h-6" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kondisi Baik</p>
                <p class="text-2xl font-bold text-slate-800">{{ $kondisiBaik }}</p>
            </div>
        </div>
        {{-- Perlu Perbaikan --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #fffbeb, #fde68a);">
                <svg class="w-6 h-6" style="color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Perlu Perbaikan</p>
                <p class="text-2xl font-bold text-slate-800">{{ $perluPerbaikan }}</p>
            </div>
        </div>
        {{-- Total Nilai --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #e0e7ff, #c7d2fe);">
                <svg class="w-6 h-6" style="color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Nilai</p>
                <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('barang.index') }}" class="flex items-end gap-4 flex-wrap">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                <select name="kategori" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kondisi</label>
                <select name="kondisi" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">Semua Kondisi</option>
                    @foreach($kondisiList as $kond)
                        <option value="{{ $kond }}" {{ request('kondisi') == $kond ? 'selected' : '' }}>{{ $kond }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama/Kode barang..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white rounded-lg" style="background: linear-gradient(135deg, #0d9488, #0f766e);">
                    Filter
                </button>
                @if(request()->hasAny(['kategori', 'kondisi', 'search']))
                    <a href="{{ route('barang.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Card Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($barangs as $b)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
            {{-- Foto --}}
            <div class="relative h-44 bg-slate-100 overflow-hidden">
                @if($b->foto_utama)
                    <img src="{{ asset('storage/' . $b->foto_utama) }}" alt="{{ $b->nama_barang }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                @endif

                {{-- Kondisi Badge --}}
                <span class="absolute top-2 right-2 px-2 py-0.5 text-xs font-semibold rounded-full {{ $b->kondisi_badge['bg'] }} {{ $b->kondisi_badge['text'] }} border {{ $b->kondisi_badge['border'] }}">
                    ✓ {{ $b->kondisi }}
                </span>

                {{-- Action Buttons on Image --}}
                <div class="absolute top-2 left-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('barang.edit', $b) }}" class="w-7 h-7 rounded-full flex items-center justify-center text-white" style="background: #f97316;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form action="{{ route('barang.destroy', $b) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-7 h-7 rounded-full flex items-center justify-center text-white" style="background: #ef4444;">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info --}}
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full {{ $b->kategori_badge['bg'] }} {{ $b->kategori_badge['text'] }}">
                        {{ $b->kategori_badge['icon'] }} {{ $b->kategori }}
                    </span>
                    <span class="text-xs text-slate-400 font-mono">#{{ $b->kode_barang }}</span>
                </div>
                <h3 class="text-sm font-bold text-slate-800 uppercase mt-1">{{ $b->nama_barang }}</h3>
                <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $b->lokasi ?: 'GUDANG' }}
                </p>

                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                    <span class="text-sm font-bold" style="color: #0d9488;">{{ $b->jumlah }} {{ strtoupper($b->satuan) }}</span>
                    <a href="{{ route('barang.show', $b) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white rounded-lg transition-all hover:scale-105" style="background: linear-gradient(135deg, #0d9488, #0f766e);">
                        Detail →
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <p class="text-slate-500 text-sm">Belum ada data barang</p>
            <a href="{{ route('barang.create') }}" class="inline-flex items-center gap-2 mt-3 px-4 py-2 text-sm font-semibold text-white rounded-lg" style="background: linear-gradient(135deg, #0d9488, #0f766e);">
                + Tambah Barang
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($barangs->hasPages())
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-3 flex items-center justify-between">
        <p class="text-sm text-slate-500">Menampilkan {{ $barangs->firstItem() }} sampai {{ $barangs->lastItem() }} dari {{ $barangs->total() }} barang</p>
        {{ $barangs->links() }}
    </div>
    @endif
</div>
@endsection
