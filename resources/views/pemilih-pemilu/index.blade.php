@extends('layouts.app')

@section('title', 'Data Pemilih Pemilu')
@section('page-title', 'Data Pemilih Pemilu')
@section('page-subtitle')
    {{ now()->translatedFormat('l, d F Y') }}
@endsection

@section('content')
@php
    function hitungUmur($tgl) {
        return (int) floor($tgl->diffInYears(now()));
    }
    function getKelompok($umur) {
        if ($umur <= 21) return 'Pemula';
        if ($umur <= 35) return 'Muda';
        if ($umur <= 55) return 'Dewasa';
        return 'Lansia';
    }
    function getKelompokColor($k) {
        return match($k) {
            'Pemula' => 'bg-blue-100 text-blue-700',
            'Muda'   => 'bg-emerald-100 text-emerald-700',
            'Dewasa' => 'bg-orange-100 text-orange-700',
            'Lansia' => 'bg-purple-100 text-purple-700',
            default  => 'bg-slate-100 text-slate-600',
        };
    }
@endphp

<div class="space-y-5">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Data Pemilih Pemilu</h2>
                <p class="text-xs text-slate-400">Dashboard <span class="mx-1">/</span> Kependudukan <span class="mx-1">/</span> Pemilih Pemilu</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="#" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak
            </a>
            <a href="#" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-500 text-white text-xs font-semibold rounded-lg hover:bg-emerald-600 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel
            </a>
            <a href="#" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-500 text-white text-xs font-semibold rounded-lg hover:bg-red-600 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
        <div class="stat-card stat-card-blue">
            <p class="text-[10px] font-medium text-blue-100 uppercase tracking-wider">Total Pemilih</p>
            <p class="text-2xl font-extrabold mt-0.5">{{ $totalAll }}</p>
            <p class="text-[10px] text-blue-200/70">dari {{ $totalAllWarga }} jiwa</p>
            <div class="absolute top-2 right-2 opacity-20"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
        </div>
        <div class="stat-card stat-card-emerald">
            <p class="text-[10px] font-medium text-emerald-100 uppercase tracking-wider">Laki-Laki</p>
            <p class="text-2xl font-extrabold mt-0.5">{{ $laki }}</p>
            <p class="text-[10px] text-emerald-200/70">{{ $totalAll > 0 ? round($laki / $totalAll * 100) : 0 }}% dari pemilih</p>
            <div class="absolute top-2 right-2 opacity-20"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
        </div>
        <div class="stat-card stat-card-orange">
            <p class="text-[10px] font-medium text-orange-100 uppercase tracking-wider">Perempuan</p>
            <p class="text-2xl font-extrabold mt-0.5">{{ $perempuan }}</p>
            <p class="text-[10px] text-orange-200/70">{{ $totalAll > 0 ? round($perempuan / $totalAll * 100) : 0 }}% dari pemilih</p>
            <div class="absolute top-2 right-2 opacity-20"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
        </div>
        <div class="stat-card stat-card-indigo">
            <p class="text-[10px] font-medium text-indigo-100 uppercase tracking-wider">Pemilih Pemula</p>
            <p class="text-2xl font-extrabold mt-0.5">{{ $pemula }}</p>
            <p class="text-[10px] text-indigo-200/70">Usia 17–21 tahun</p>
            <div class="absolute top-2 right-2 opacity-20"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg></div>
        </div>
        <div class="stat-card stat-card-purple">
            <p class="text-[10px] font-medium text-purple-100 uppercase tracking-wider">Lansia (56+)</p>
            <p class="text-2xl font-extrabold mt-0.5">{{ $lansia }}</p>
            <p class="text-[10px] text-purple-200/70">Usia 56 tahun ke atas</p>
            <div class="absolute top-2 right-2 opacity-20"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 7.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="flex items-start gap-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
        <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <p class="text-xs text-blue-600"><strong>Ketentuan pemilih:</strong> WNI berusia ≥ 17 tahun atau sudah/pernah menikah, bukan warga yang meninggal, terdaftar di KK wilayah ini. Data dihitung per <strong>{{ now()->translatedFormat('d F Y') }}</strong>.</p>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('pemilih-pemilu') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-500 font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filter:
        </div>
        <div>
            <select name="kelompok" onchange="this.form.submit()" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <option value="">Kelompok Umur</option>
                <option value="pemula" {{ request('kelompok') === 'pemula' ? 'selected' : '' }}>Pemula (17-21)</option>
                <option value="muda" {{ request('kelompok') === 'muda' ? 'selected' : '' }}>Muda (22-35)</option>
                <option value="dewasa" {{ request('kelompok') === 'dewasa' ? 'selected' : '' }}>Dewasa (36-55)</option>
                <option value="lansia" {{ request('kelompok') === 'lansia' ? 'selected' : '' }}>Lansia (56+)</option>
            </select>
        </div>
        <div>
            <select name="jenis_kelamin" onchange="this.form.submit()" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <option value="">Jenis Kelamin</option>
                <option value="L" {{ request('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                <option value="P" {{ request('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div>
            <select name="domisili" onchange="this.form.submit()" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <option value="">Domisili</option>
                <option value="Tetap" {{ request('domisili') === 'Tetap' ? 'selected' : '' }}>Tetap</option>
                <option value="Kontrakan" {{ request('domisili') === 'Kontrakan' ? 'selected' : '' }}>Kontrakan</option>
                <option value="Kos" {{ request('domisili') === 'Kos' ? 'selected' : '' }}>Kos</option>
            </select>
        </div>
        @if (request()->hasAny(['kelompok', 'jenis_kelamin', 'domisili']))
            <a href="{{ route('pemilih-pemilu') }}" class="text-xs text-red-500 hover:text-red-600 font-medium">Reset Filter</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Daftar Pemilih
            </h3>
            <span class="px-2.5 py-0.5 text-[10px] font-semibold bg-blue-50 text-blue-600 rounded-full">{{ $totalAll }} orang memenuhi syarat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider w-10">No</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">NIK</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Nama Lengkap</th>
                        <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider">L/P</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Tgl Lahir</th>
                        <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Umur</th>
                        <th class="px-4 py-2.5 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Kelompok</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Status Kawin</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Domisili</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">No KK / Kepala KK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pemilih as $i => $p)
                        @php
                            $umur = hitungUmur($p->tanggal_lahir);
                            $kelompok = getKelompok($umur);
                            $kelompokColor = getKelompokColor($kelompok);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-2.5 text-xs text-slate-500 text-center">
                                {{ ($pemilih->currentPage() - 1) * $pemilih->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="px-2 py-0.5 text-[10px] font-mono font-semibold bg-slate-100 text-slate-600 rounded">{{ $p->nik }}</span>
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                                        {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('kartu-keluarga.show', $p->kartu_keluarga_id) }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 transition-colors">
                                            {{ $p->nama_lengkap }}
                                        </a>
                                        <p class="text-[10px] text-slate-400">{{ $p->kartuKeluarga->desa ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                @if ($p->jenis_kelamin === 'L')
                                    <span class="text-blue-600 font-semibold text-xs">♂ L</span>
                                @elseif ($p->jenis_kelamin === 'P')
                                    <span class="text-pink-500 font-semibold text-xs">♀ P</span>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-600">
                                {{ $p->tanggal_lahir->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="text-sm font-bold text-slate-700">{{ $umur }}</span>
                                <span class="text-[10px] text-slate-400"> thn</span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded {{ $kelompokColor }}">{{ $kelompok }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-600">
                                {{ $p->status_kawin ?? '-' }}
                            </td>
                            <td class="px-4 py-2.5">
                                @if ($p->domisili === 'Tetap')
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 rounded">Tetap</span>
                                @elseif ($p->domisili === 'Kontrakan')
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-orange-100 text-orange-700 rounded">Kontrakan</span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 rounded">{{ $p->domisili }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5">
                                <div>
                                    <a href="{{ route('kartu-keluarga.show', $p->kartu_keluarga_id) }}" class="text-[11px] font-bold text-red-500 hover:text-red-600 transition-colors">
                                        {{ $p->kartuKeluarga->no_kk ?? '-' }}
                                    </a>
                                    <p class="text-[10px] text-slate-400">{{ $p->kartuKeluarga->kepala_name ?? '-' }}</p>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <p class="text-sm font-medium text-slate-500">Tidak ada data pemilih yang memenuhi syarat</p>
                                    <p class="text-xs text-slate-400 mt-1">Data warga berusia 17+ tahun akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($pemilih->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-500">
                    Menampilkan {{ $pemilih->firstItem() }} - {{ $pemilih->lastItem() }} dari {{ $totalAll }} pemilih
                </p>
                {{ $pemilih->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
