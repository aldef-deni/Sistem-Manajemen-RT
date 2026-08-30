@extends('layouts.app')

@section('title', 'Rencana Pembelian')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #0d9488, #0f766e); box-shadow: 0 4px 12px rgba(13,148,136,0.3);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Rencana Pembelian</h1>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('barang.index') }}" class="text-teal-600 hover:underline font-medium">Inventaris</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Rencana Pembelian</span>
                </div>
            </div>
        </div>
        <a href="{{ route('barang.rencana.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-lg transition-all hover:scale-105" style="background: linear-gradient(135deg, #0d9488, #0f766e); box-shadow: 0 4px 12px rgba(13,148,136,0.3);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Rencana
        </a>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 p-3 rounded-lg text-sm font-medium" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #e0f2fe, #bae6fd);">
                <svg class="w-6 h-6" style="color: #0284c7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</p>
                <p class="text-2xl font-bold text-slate-800">{{ $total }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #fffbeb, #fde68a);">
                <svg class="w-6 h-6" style="color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pending</p>
                <p class="text-2xl font-bold text-slate-800">{{ $pending }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #ecfdf5, #a7f3d0);">
                <svg class="w-6 h-6" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Terealisasi</p>
                <p class="text-2xl font-bold text-slate-800">{{ $terbeli }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #e0e7ff, #c7d2fe);">
                <svg class="w-6 h-6" style="color: #4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Est. Pending</p>
                <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($estPending, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-1">
        <div class="flex items-center gap-1 overflow-x-auto">
            @php $currentStatus = request('status', 'semua'); @endphp
            @foreach($statusCounts as $key => $count)
                <a href="{{ route('barang.rencana.index', array_merge(request()->except('status', 'page'), ['status' => $key])) }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap transition-colors
                          {{ $currentStatus === $key ? 'text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}"
                   @if($currentStatus === $key) style="background: linear-gradient(135deg, #0d9488, #0f766e);" @endif>
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
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Jml</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase">Est. Harga</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Prioritas</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Tgl Rencana</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rencana as $r)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-mono text-teal-600 font-semibold">{{ $r->kode_rencana }}</td>
                        <td class="px-4 py-3">
                            <span class="text-sm font-bold text-slate-800 uppercase">{{ $r->nama_barang }}</span>
                            @if($r->keterangan)
                                <p class="text-xs text-slate-500 mt-0.5">{{ Str::limit($r->keterangan, 30) }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">{{ $r->kategori }}</span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-slate-700">{{ $r->jumlah }} {{ $r->satuan }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-bold text-slate-800">Rp {{ number_format($r->estimasi_harga, 0, ',', '.') }}</span>
                            @if($r->jumlah > 1)
                                <p class="text-xs text-slate-500">Total: Rp {{ number_format($r->estimasi_harga * $r->jumlah, 0, ',', '.') }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full {{ $r->prioritas_badge['bg'] }} {{ $r->prioritas_badge['text'] }}">
                                {{ $r->prioritas_badge['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-slate-600">{{ $r->tanggal_rencana->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full {{ $r->status_badge['bg'] }} {{ $r->status_badge['text'] }}">
                                {{ $r->status_badge['icon'] }} {{ ucfirst($r->status) }}
                            </span>
                            @if($r->barang)
                                <a href="{{ route('barang.show', $r->barang) }}" class="block text-xs text-teal-600 hover:underline mt-0.5">Lihat inventaris</a>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                @if($r->status === 'direncanakan')
                                    <form action="{{ route('barang.rencana.update-status', $r) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" class="px-2 py-1 text-xs font-semibold text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100">Setuju</button>
                                    </form>
                                @elseif($r->status === 'disetujui')
                                    <form action="{{ route('barang.rencana.update-status', $r) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="terbeli">
                                        <button type="submit" class="px-2 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100" onclick="return confirm('Tandai sebagai terbeli? Ini akan membuat data inventaris baru.')">Beli</button>
                                    </form>
                                @endif
                                @if(!in_array($r->status, ['terbeli', 'dibatalkan']))
                                    <form action="{{ route('barang.rencana.update-status', $r) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="dibatalkan">
                                        <button type="submit" class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-50 rounded-lg hover:bg-red-100">Batal</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-slate-500">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                            Belum ada rencana pembelian
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rencana->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-sm text-slate-500">Menampilkan {{ $rencana->firstItem() }} sampai {{ $rencana->lastItem() }} dari {{ $rencana->total() }} rencana</p>
            {{ $rencana->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
