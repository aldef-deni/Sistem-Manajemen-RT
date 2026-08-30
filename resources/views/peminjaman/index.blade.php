@extends('layouts.app')

@section('title', 'Peminjaman Barang')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); box-shadow: 0 4px 12px rgba(124,58,237,0.3);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Peminjaman Barang</h1>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('barang.index') }}" class="text-teal-600 hover:underline font-medium">Inventaris</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Peminjaman</span>
                </div>
            </div>
        </div>
        <a href="{{ route('peminjaman.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-lg transition-all hover:scale-105" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); box-shadow: 0 4px 12px rgba(124,58,237,0.3);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Catat Peminjaman
        </a>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 p-3 rounded-lg text-sm font-medium" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-center gap-2 p-3 rounded-lg text-sm font-medium" style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #ede9fe, #ddd6fe);">
                <svg class="w-6 h-6" style="color: #7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Catatan</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalCatatan }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #fffbeb, #fde68a);">
                <svg class="w-6 h-6" style="color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sedang Dipinjam</p>
                <p class="text-2xl font-bold text-slate-800">{{ $sedangDipinjam }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #ecfdf5, #a7f3d0);">
                <svg class="w-6 h-6" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Dikembalikan</p>
                <p class="text-2xl font-bold text-slate-800">{{ $dikembalikan }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #fef2f2, #fecaca);">
                <svg class="w-6 h-6" style="color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Terlambat</p>
                <p class="text-2xl font-bold text-slate-800">{{ $terlambat }}</p>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-1">
        <div class="flex items-center gap-1 overflow-x-auto">
            @php $currentStatus = request('status', 'semua'); @endphp
            @foreach($statusCounts as $key => $count)
                <a href="{{ route('peminjaman.index', array_merge(request()->except('status', 'page'), ['status' => $key])) }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap transition-colors
                          {{ $currentStatus === $key ? 'text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}"
                   @if($currentStatus === $key) style="background: linear-gradient(135deg, #7c3aed, #6d28d9);" @endif>
                    @if($key === 'semua')
                        <span class="mr-1">☰</span>
                    @elseif($key === 'dipinjam')
                        <span class="mr-1">📦</span>
                    @elseif($key === 'dikembalikan')
                        <span class="mr-1">✅</span>
                    @elseif($key === 'terlambat')
                        <span class="mr-1">⏰</span>
                    @endif
                    {{ ucfirst($key) }} <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full {{ $currentStatus === $key ? 'bg-white/20' : 'bg-slate-200 text-slate-600' }}">{{ $count }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Peminjam</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Jumlah</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Tgl Pinjam</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Rencana Kembali</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-mono font-semibold" style="color: #7c3aed;">{{ $p->kode_peminjaman }}</td>
                        <td class="px-4 py-3">
                            <span class="text-sm font-bold text-slate-800 uppercase">{{ $p->barang->nama_barang ?? '-' }}</span>
                            <p class="text-xs text-slate-500 font-mono">#{{ $p->barang->kode_barang ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $p->nama_peminjam }}</td>
                        <td class="px-4 py-3 text-center text-sm font-semibold text-slate-700">{{ $p->jumlah_pinjam }} {{ strtoupper($p->barang->satuan ?? '') }}</td>
                        <td class="px-4 py-3 text-center text-sm text-slate-600">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center text-sm text-slate-600">{{ $p->tanggal_rencana_kembali->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full {{ $p->status_badge['bg'] }} {{ $p->status_badge['text'] }} border {{ $p->status_badge['border'] }}">
                                {{ $p->status_badge['icon'] }} {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('peminjaman.show', $p) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">Detail</a>
                                @if($p->status === 'dipinjam')
                                    <form action="{{ route('peminjaman.kembalikan', $p) }}" method="POST" class="inline" onsubmit="return confirm('Kembalikan barang ini?')">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="kondisi_saat_kembali" value="Baik">
                                        <button type="submit" class="px-3 py-1 text-xs font-semibold rounded-lg text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200">Kembali</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-slate-500">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            Belum ada catatan peminjaman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($peminjaman->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-sm text-slate-500">Menampilkan {{ $peminjaman->firstItem() }} sampai {{ $peminjaman->lastItem() }} dari {{ $peminjaman->total() }} catatan</p>
            {{ $peminjaman->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
