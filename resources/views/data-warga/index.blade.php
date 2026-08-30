@extends('layouts.app')

@section('title', 'Data Warga')
@section('page-title', 'Data Warga')
@section('page-subtitle')
    {{ now()->translatedFormat('l, d F Y') }}
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Dashboard Kependudukan</h2>
                <p class="text-xs text-slate-400">Dashboard <span class="mx-1">/</span> Kependudukan</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0 flex-wrap">
            <a href="#" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Statistik & Grafik
            </a>
            <a href="{{ route('kartu-keluarga.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                </svg>
                Kirim via Kartu Keluarga
            </a>
            <a href="#" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Excel
            </a>
            <a href="#" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-500 text-white text-xs font-semibold rounded-lg hover:bg-red-600 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- Ringkasan Kependudukan --}}
    <div>
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Ringkasan Kependudukan</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
            <div class="stat-card stat-card-blue">
                <p class="text-[10px] font-medium text-blue-100 uppercase tracking-wider">Total Warga</p>
                <p class="text-2xl font-extrabold mt-0.5">{{ $totalWarga }}</p>
                <p class="text-[10px] text-blue-200/70">Jumlah warga aktif</p>
                <div class="absolute top-2 right-2 opacity-20">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="stat-card stat-card-emerald">
                <p class="text-[10px] font-medium text-emerald-100 uppercase tracking-wider">Total KK</p>
                <p class="text-2xl font-extrabold mt-0.5">{{ $totalKK }}</p>
                <p class="text-[10px] text-emerald-200/70">Jumlah keluarga</p>
                <div class="absolute top-2 right-2 opacity-20">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                </div>
            </div>
            <div class="stat-card stat-card-indigo">
                <p class="text-[10px] font-medium text-indigo-100 uppercase tracking-wider">Laki-Laki</p>
                <p class="text-2xl font-extrabold mt-0.5">{{ $lakiLaki }}</p>
                <p class="text-[10px] text-indigo-200/70">dari {{ $totalWarga }} total jml</p>
                <div class="absolute top-2 right-2 opacity-20">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
            <div class="stat-card stat-card-pink">
                <p class="text-[10px] font-medium text-pink-100 uppercase tracking-wider">Perempuan</p>
                <p class="text-2xl font-extrabold mt-0.5">{{ $perempuan }}</p>
                <p class="text-[10px] text-pink-200/70">dari {{ $totalWarga }} total jml</p>
                <div class="absolute top-2 right-2 opacity-20">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
            <div class="stat-card stat-card-teal">
                <p class="text-[10px] font-medium text-teal-100 uppercase tracking-wider">Meninggal</p>
                <p class="text-2xl font-extrabold mt-0.5">0</p>
                <p class="text-[10px] text-teal-200/70">tercatat di sistem</p>
                <div class="absolute top-2 right-2 opacity-20">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Distribusi Usia & Status --}}
    <div>
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Distribusi Usia & Status</h3>
        <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
            <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl p-3 text-white text-center">
                <p class="text-2xl font-extrabold">{{ $balita }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wider opacity-90">Balita</p>
                <p class="text-[9px] opacity-70">usia 0-4 tahun</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl p-3 text-white text-center">
                <p class="text-2xl font-extrabold">{{ $anak }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wider opacity-90">Anak</p>
                <p class="text-[9px] opacity-70">usia 5-12 tahun</p>
            </div>
            <div class="bg-gradient-to-br from-emerald-400 to-emerald-500 rounded-xl p-3 text-white text-center">
                <p class="text-2xl font-extrabold">{{ $janda }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wider opacity-90">Janda</p>
                <p class="text-[9px] opacity-70">berstatus janda</p>
            </div>
            <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl p-3 text-white text-center">
                <p class="text-2xl font-extrabold">{{ $duda }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wider opacity-90">Duda</p>
                <p class="text-[9px] opacity-70">berstatus duda</p>
            </div>
            <div class="bg-gradient-to-br from-rose-400 to-rose-500 rounded-xl p-3 text-white text-center">
                <p class="text-2xl font-extrabold">{{ $pemuda }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wider opacity-90">Pemuda</p>
                <p class="text-[9px] opacity-70">usia 18-59 tahun</p>
            </div>
        </div>
    </div>

    {{-- Catatan --}}
    <div class="flex items-start gap-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
        <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <p class="text-xs text-blue-600">Klik Nama untuk ke Detail KK</p>
    </div>

    {{-- Filter & Search --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="font-semibold text-blue-600">{{ $totalWarga }} Orang</span>
                <span class="text-slate-300">|</span>
                <span>1 meninggal tercatat</span>
                <span class="text-slate-300">|</span>
                <a href="#" class="text-red-500 hover:text-red-600 font-medium">Lihat Data Almar & Kole</a>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK..."
                       class="w-full sm:w-56 pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Detail Data Warga
            </h3>
            <span class="text-xs text-slate-400">Pakai Tanggal</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-10">No</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">NIK</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Nama Lengkap</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">No. KK</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Status Hubungan</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Domisili</th>
                        <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($wargas as $i => $w)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-2.5 text-xs text-slate-500 text-center">
                                {{ ($wargas->currentPage() - 1) * $wargas->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-2.5">
                                <a href="{{ route('kartu-keluarga.show', $w->kartu_keluarga_id) }}" class="text-xs font-bold text-red-500 hover:text-red-600 transition-colors">
                                    {{ $w->nik }}
                                </a>
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                                        {{ strtoupper(substr($w->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('kartu-keluarga.show', $w->kartu_keluarga_id) }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 transition-colors">
                                            {{ $w->nama_lengkap }}
                                        </a>
                                        <p class="text-[10px] text-slate-400">{{ $w->role }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-600">{{ $w->kartuKeluarga->no_kk ?? '-' }}</td>
                            <td class="px-4 py-2.5 text-xs text-slate-600">{{ $w->status_hubungan }}</td>
                            <td class="px-4 py-2.5">
                                @if ($w->domisili === 'Tetap')
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 rounded">Tetap</span>
                                @elseif ($w->domisili === 'Kontrakan')
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-orange-100 text-orange-700 rounded">Kontrakan</span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 rounded">{{ $w->domisili }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-green-100 text-green-700 rounded">Aktif</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-slate-500">Belum ada data warga</p>
                                    <p class="text-xs text-slate-400 mt-1">Tambahkan data melalui menu Kartu Keluarga</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($wargas->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-500">
                    Menampilkan {{ $wargas->firstItem() }} - {{ $wargas->lastItem() }} dari {{ $totalWarga }} warga
                </p>
                {{ $wargas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
