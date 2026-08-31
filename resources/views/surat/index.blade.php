@extends('layouts.app')

@section('title', 'Permohonan Surat')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Permohonan Surat</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Permohonan Surat</h1>
                <p class="text-sm text-slate-500">Kelola permohonan surat warga</p>
            </div>
        </div>
        <a href="{{ route('surat.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-xl shadow-lg shadow-teal-500/25 hover:shadow-xl hover:shadow-teal-500/30 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajukan Surat
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Total Permohonan</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Menunggu</p>
                    <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Sedang Diproses</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['diproses'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Selesai</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['selesai'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('surat.index') }}">
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex flex-wrap items-center gap-3">
            <span class="text-sm font-semibold text-slate-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter:
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, jenis surat..." class="flex-1 min-w-[200px] px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
            <select name="status" class="px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="jenis_surat" class="px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                <option value="">Semua Jenis</option>
                @foreach($jenisList as $j)
                    <option value="{{ $j }}" {{ request('jenis_surat') == $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-teal-500 to-emerald-500 text-white rounded-lg text-sm font-medium hover:shadow-md transition">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>
            @if(request('search') || request('status') || request('jenis_surat'))
                <a href="{{ route('surat.index') }}" class="px-4 py-2.5 text-slate-500 hover:text-red-500 text-sm font-medium flex items-center gap-1 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Daftar Permohonan Surat
            </h3>
            <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-bold">{{ $surat->total() }} data</span>
        </div>

        @if($surat->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 text-left font-semibold">NO</th>
                        <th class="px-6 py-3 text-left font-semibold">KODE</th>
                        <th class="px-6 py-3 text-left font-semibold">TGL AJUAN</th>
                        <th class="px-6 py-3 text-left font-semibold">PEMOHON</th>
                        <th class="px-6 py-3 text-left font-semibold">JENIS SURAT</th>
                        <th class="px-6 py-3 text-left font-semibold">KEPERLUAN</th>
                        <th class="px-6 py-3 text-left font-semibold">STATUS</th>
                        <th class="px-6 py-3 text-left font-semibold">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($surat as $i => $s)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-500 font-medium">{{ ($surat->currentPage() - 1) * $surat->perPage() + $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs bg-slate-100 px-2.5 py-1 rounded-lg text-slate-700 font-bold">{{ $s->kode_surat }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <div>{{ $s->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-400">{{ $s->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($s->nama_pemohon, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $s->nama_pemohon }}</div>
                                    @if($s->nik)
                                        <div class="text-xs text-slate-400 font-mono">NIK: {{ $s->nik }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium border border-blue-100">{{ $s->jenis_surat }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-[200px] truncate text-slate-600" title="{{ $s->keperluan }}">{{ $s->keperluan }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($s->status == 'pending')
                                <span class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold flex items-center gap-1 w-fit">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu
                                </span>
                            @elseif($s->status == 'diproses')
                                <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold flex items-center gap-1 w-fit">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-spin"></span> Diproses
                                </span>
                            @elseif($s->status == 'selesai')
                                <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold flex items-center gap-1 w-fit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Selesai
                                </span>
                            @else
                                <span class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-bold flex items-center gap-1 w-fit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('surat.show', $s->id) }}" class="p-2 bg-teal-50 text-teal-600 rounded-lg hover:bg-teal-100 transition" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('surat.edit', $s->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('surat.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permohonan surat ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $surat->links() }}
        </div>
        @else
        {{-- Empty State --}}
        <div class="px-6 py-16 text-center">
            <div class="w-20 h-20 mx-auto bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1">Belum ada permohonan surat</h3>
            <p class="text-slate-500 text-sm mb-4">Ajukan permohonan surat pertama Anda</p>
            <a href="{{ route('surat.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajukan Surat
            </a>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filter on change
    document.querySelectorAll('select[name="status"], select[name="jenis_surat"]').forEach(function(el) {
        el.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>
@endsection
