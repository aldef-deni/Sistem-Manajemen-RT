@extends('layouts.app')

@section('title', $barang->nama_barang)

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('barang.index') }}" class="text-teal-600 hover:underline font-medium">Inventaris</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Detail</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800 uppercase">{{ $barang->nama_barang }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('barang.edit', $barang) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <a href="{{ route('barang.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            {{-- Foto --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                @if($barang->foto_utama)
                    <img src="{{ asset('storage/' . $barang->foto_utama) }}" class="w-full h-64 object-cover">
                @else
                    <div class="h-64 bg-slate-100 flex items-center justify-center">
                        <svg class="w-20 h-20 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                @endif
            </div>

            {{-- Info Grid --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-4" style="color: #0d9488;">Detail Barang</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Kode Barang</p>
                        <p class="text-sm font-bold font-mono text-slate-800">{{ $barang->kode_barang }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Nama Barang</p>
                        <p class="text-sm font-bold text-slate-800">{{ $barang->nama_barang }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Kategori</p>
                        <p class="text-sm font-semibold">{{ $barang->kategori }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Kondisi</p>
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full {{ $barang->kondisi_badge['bg'] }} {{ $barang->kondisi_badge['text'] }}">{{ $barang->kondisi }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Jumlah</p>
                        <p class="text-sm font-bold text-slate-800">{{ $barang->jumlah }} {{ $barang->satuan }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Lokasi</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $barang->lokasi ?: '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Tanggal Pembelian</p>
                        <p class="text-sm text-slate-800">{{ $barang->tanggal_pembelian ? $barang->tanggal_pembelian->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Harga Pembelian</p>
                        <p class="text-sm font-bold text-slate-800">Rp {{ number_format($barang->harga_pembelian, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Sumber Dana</p>
                        <p class="text-sm text-slate-800">{{ $barang->sumber_dana ?: '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Status</p>
                        <p class="text-sm font-semibold capitalize">{{ $barang->status }}</p>
                    </div>
                </div>
                @if($barang->keterangan)
                <div class="mt-4 p-3 bg-slate-50 rounded-lg">
                    <p class="text-xs text-slate-500 mb-1">Keterangan</p>
                    <p class="text-sm text-slate-700">{{ $barang->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #0d9488, #0f766e); box-shadow: 0 10px 15px -3px rgba(13,148,136,0.3);">
                <h3 class="font-bold text-lg">{{ $barang->nama_barang }}</h3>
                <p class="text-teal-100 text-sm mt-1">{{ $barang->kode_barang }}</p>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-teal-100 text-sm">Nilai</span>
                        <span class="font-bold text-lg">Rp {{ number_format($barang->harga_pembelian, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-teal-100 text-sm">Kondisi</span>
                        <span class="font-semibold">{{ $barang->kondisi }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-teal-100 text-sm">Jumlah</span>
                        <span class="font-semibold">{{ $barang->jumlah }} {{ $barang->satuan }}</span>
                    </div>
                </div>
            </div>

            @if($barang->foto_gallery && count($barang->foto_gallery) > 0)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-3" style="color: #0d9488;">Gallery</h3>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($barang->foto_gallery as $foto)
                        <img src="{{ asset('storage/' . $foto) }}" class="w-full h-24 object-cover rounded-lg">
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-sm font-bold text-slate-700 mb-2">Riwayat</h3>
                <div class="space-y-2 text-sm text-slate-600">
                    <p>Dibuat: {{ $barang->created_at->format('d M Y H:i') }}</p>
                    <p>Diupdate: {{ $barang->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
