@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('peminjaman.index') }}" class="text-teal-600 hover:underline font-medium">Peminjaman</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Detail</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">{{ $peminjaman->kode_peminjaman }}</h1>
        </div>
        <div class="flex items-center gap-2">
            @if($peminjaman->status === 'dipinjam')
                <form action="{{ route('peminjaman.kembalikan', $peminjaman) }}" method="POST" onsubmit="return confirm('Kembalikan barang ini?')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="kondisi_saat_kembali" value="Baik">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg" style="background: linear-gradient(135deg, #059669, #047857);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Kembalikan
                    </button>
                </form>
            @endif
            <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            {{-- Info Grid --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-sm font-bold uppercase tracking-wider mb-4" style="color: #7c3aed;">Detail Peminjaman</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Kode Peminjaman</p>
                        <p class="text-sm font-bold font-mono" style="color: #7c3aed;">{{ $peminjaman->kode_peminjaman }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Status</p>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full {{ $peminjaman->status_badge['bg'] }} {{ $peminjaman->status_badge['text'] }} border {{ $peminjaman->status_badge['border'] }}">
                            {{ $peminjaman->status_badge['icon'] }} {{ ucfirst($peminjaman->status) }}
                        </span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Barang</p>
                        <p class="text-sm font-bold text-slate-800">{{ $peminjaman->barang->nama_barang ?? '-' }}</p>
                        <p class="text-xs text-slate-500 font-mono">#{{ $peminjaman->barang->kode_barang ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Jumlah</p>
                        <p class="text-sm font-bold text-slate-800">{{ $peminjaman->jumlah_pinjam }} {{ strtoupper($peminjaman->barang->satuan ?? '') }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Kondisi Saat Pinjam</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $peminjaman->kondisi_saat_pinjam }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Kondisi Saat Kembali</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $peminjaman->kondisi_saat_kembali ?: '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Tanggal Pinjam</p>
                        <p class="text-sm text-slate-800">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Rencana Kembali</p>
                        <p class="text-sm text-slate-800">{{ $peminjaman->tanggal_rencana_kembali->format('d M Y') }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Tanggal Kembali</p>
                        <p class="text-sm text-slate-800">{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-xs text-slate-500 mb-0.5">Keperluan</p>
                        <p class="text-sm text-slate-800">{{ $peminjaman->keperluan ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="rounded-xl p-5 text-white" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); box-shadow: 0 10px 15px -3px rgba(124,58,237,0.3);">
                <h3 class="font-bold text-lg">{{ $peminjaman->kode_peminjaman }}</h3>
                <p class="text-purple-200 text-sm mt-1">{{ $peminjaman->barang->nama_barang ?? '-' }}</p>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-purple-200 text-sm">Status</span>
                        <span class="font-semibold">{{ ucfirst($peminjaman->status) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-purple-200 text-sm">Jumlah</span>
                        <span class="font-semibold">{{ $peminjaman->jumlah_pinjam }} {{ strtoupper($peminjaman->barang->satuan ?? '') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-sm font-bold text-slate-700 mb-2">Data Peminjam</h3>
                <div class="space-y-2 text-sm text-slate-600">
                    <p><span class="font-medium">Nama:</span> {{ $peminjaman->nama_peminjam }}</p>
                    <p><span class="font-medium">HP:</span> {{ $peminjaman->no_hp_peminjam ?: '-' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-sm font-bold text-slate-700 mb-2">Riwayat</h3>
                <div class="space-y-2 text-sm text-slate-600">
                    <p>Dicatat: {{ $peminjaman->created_at->format('d M Y H:i') }}</p>
                    @if($peminjaman->tanggal_kembali)
                        <p>Dikembalikan: {{ $peminjaman->tanggal_kembali->format('d M Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
