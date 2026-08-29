@extends('layouts.app')

@section('title', 'Data Kartu Keluarga')
@section('page-title', 'Data Kartu Keluarga')
@section('page-subtitle')
    {{ now()->translatedFormat('l, d F Y') }}
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Data Kartu Keluarga</h2>
                    <p class="text-xs text-slate-400">Dashboard <span class="mx-1">/</span> Kartu Keluarga</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="#" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span class="hidden sm:inline">Upload Excel</span>
            </a>
            <a href="{{ route('kartu-keluarga.create') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-all shadow-sm shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Tambah KK</span>
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Total KK Card --}}
    <div class="stat-card stat-card-blue w-fit">
        <p class="text-xs font-medium text-blue-100 uppercase tracking-wider">Total KK</p>
        <p class="text-3xl font-extrabold mt-1">{{ $totalKK }}</p>
        <div class="absolute top-3 right-3 opacity-20">
            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>
            </svg>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('kartu-keluarga.index') }}">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No KK, Nama, atau Alamat..."
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <h3 class="text-sm font-semibold text-slate-700">Daftar Kartu Keluarga</h3>
            </div>
            <span class="text-xs text-slate-400">{{ $totalKK }} KK terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">No. KK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Kepala Keluarga</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">No. HP</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Alamat</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Anggota</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">                        @forelse ($kartuKeluargas as $kk)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-slate-500 text-center">
                                {{ ($kartuKeluargas->currentPage() - 1) * $kartuKeluargas->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-bold text-blue-600">{{ $kk->no_kk }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                        {{ strtoupper(substr($kk->kepala_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700 leading-tight">{{ $kk->kepala_name }}</p>
                                        <span class="inline-block mt-0.5 px-1.5 py-0.5 text-[9px] font-semibold bg-blue-50 text-blue-600 rounded">Kepala KK</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1 text-sm text-slate-600">
                                    <svg class="w-3.5 h-3.5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                    </svg>
                                    {{ $kk->kepala_no_hp }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-500 max-w-[150px] truncate">
                                {{ $kk->alamat }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('kartu-keluarga.show', $kk) }}" class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Lihat
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- View --}}
                                    <a href="{{ route('kartu-keluarga.show', $kk) }}" class="w-7 h-7 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors" title="Lihat Detail">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('kartu-keluarga.edit', $kk) }}" class="w-7 h-7 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-100 transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('kartu-keluarga.destroy', $kk) }}" onsubmit="return confirm('Yakin ingin menghapus Kartu Keluarga ini? Semua anggota juga akan dihapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7 h-7 rounded-md bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition-colors" title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                                    </svg>
                                    <p class="text-sm font-medium text-slate-500">Belum ada data Kartu Keluarga</p>
                                    <p class="text-xs text-slate-400 mt-1">Klik "Tambah KK" untuk menambahkan data baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}                        @if ($kartuKeluargas->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">
                {{ $kartuKeluargas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
