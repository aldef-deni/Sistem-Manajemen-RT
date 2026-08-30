@extends('layouts.app')

@section('title', 'Iuran Warga')
@section('page-title', 'Iuran Warga')
@section('page-subtitle', 'Kelola tagihan iuran warga per bulan')

@section('content')
<div class="space-y-4">
    {{-- Action Buttons Row --}}
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('iuran-warga.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 shadow-lg shadow-teal-500/25 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Iuran
        </a>
        <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
            Jenis Iuran
        </button>
        <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 border border-slate-200 hover:bg-slate-200 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Bayar Massal
        </button>
        <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 border border-slate-200 hover:bg-slate-200 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Generate Tagihan
        </button>
        <div class="w-px h-6 bg-slate-200 mx-0.5"></div>
        <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Excel
        </button>
        <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-white bg-red-500 hover:bg-red-600 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export PDF
        </button>
    </div>

    {{-- Unpaid Banner --}}
    @if($belumBayar > 0)
    <div class="bg-gradient-to-r from-teal-50 to-emerald-50 border border-teal-200 rounded-xl p-3 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-9 h-9 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-700 truncate">
                Ada <span class="text-teal-600 font-bold">{{ $belumBayar }}</span> tagihan belum dibayar — total
                <span class="text-teal-600 font-bold">Rp {{ number_format($totalBelumBayarNominal, 0, ',', '.') }}</span>
            </p>
        </div>
        <a href="#" onclick="bayarMassal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 transition-all flex-shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Bayar Massal
        </a>
    </div>
    @endif

    {{-- Filter --}}
    <form method="GET" action="{{ route('iuran-warga.index') }}" class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[140px]">
                <label class="text-xs font-semibold text-slate-500 mb-1 block">Status</label>
                <select name="status" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div class="flex-1 min-w-[140px]">
                <label class="text-xs font-semibold text-slate-500 mb-1 block">Bulan</label>
                <select name="bulan" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex-1 min-w-[110px]">
                <label class="text-xs font-semibold text-slate-500 mb-1 block">Tahun</label>
                <select name="tahun" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold text-white bg-teal-600 hover:bg-teal-700 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
        </div>
    </form>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 flex items-center gap-3">
        <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <p class="text-sm font-semibold text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Semua Data Iuran</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">
                {{ $iuran->total() }} data
            </span>
        </div>

        @if($iuran->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase w-8">No</th>
                        <th class="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase">Nama Warga</th>
                        <th class="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase">Jenis Iuran</th>
                        <th class="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase">Periode</th>
                        <th class="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase text-right">Nominal</th>
                        <th class="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase">Tgl Bayar</th>
                        <th class="px-3 py-2 text-[11px] font-bold text-slate-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($iuran as $i => $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-3 py-2 text-xs text-slate-500">{{ ($iuran->currentPage() - 1) * $iuran->perPage() + $i + 1 }}</td>
                        <td class="px-3 py-2">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center text-white font-semibold text-[10px] flex-shrink-0">
                                    {{ strtoupper(substr($item->anggota->nama_lengkap ?? '-', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[13px] font-semibold text-slate-700 truncate">{{ $item->anggota->nama_lengkap ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400">RT {{ $item->anggota->kartuKeluarga->rt ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-2 text-xs text-slate-600">{{ $item->jenisIuran->nama ?? '-' }}</td>
                        <td class="px-3 py-2 text-xs text-slate-600 whitespace-nowrap">{{ $item->periode }}</td>
                        <td class="px-3 py-2 text-xs font-bold text-slate-800 text-right whitespace-nowrap">{{ $item->nominal_formatted }}</td>
                        <td class="px-3 py-2">
                            @if($item->status === 'lunas')
                                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Lunas
                                </span>
                            @else
                                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    Belum Dibayar
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-xs text-slate-500 whitespace-nowrap">
                            {{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-3 py-2">
                            <div class="flex items-center justify-center gap-1">
                                @if($item->status === 'belum_bayar')
                                <form method="POST" action="{{ route('iuran-warga.bayar', $item->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Bayar" class="w-6 h-6 rounded bg-teal-50 text-teal-600 hover:bg-teal-100 flex items-center justify-center transition-all" onclick="return confirm('Konfirmasi pembayaran untuk {{ $item->anggota->nama_lengkap }}?')">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('iuran-warga.edit', $item->id) }}" title="Edit" class="w-6 h-6 rounded bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition-all">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('iuran-warga.destroy', $item->id) }}" class="inline" onsubmit="return confirm('Hapus tagihan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus" class="w-6 h-6 rounded bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-all">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <p class="text-slate-500 font-medium">Belum ada data iuran</p>
                                <a href="{{ route('iuran-warga.create') }}" class="text-sm text-teal-600 hover:underline font-semibold">+ Buat Tagihan Iuran</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($iuran->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-500">Showing {{ $iuran->firstItem() }}–{{ $iuran->lastItem() }} of {{ $iuran->total() }}</p>
            {{ $iuran->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

<script>
function bayarMassal() {
    alert('Fitur Bayar Massal akan segera tersedia!');
}
</script>
@endsection
