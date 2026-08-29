@extends('layouts.app')

@section('title', 'Detail Kartu Keluarga')
@section('page-title', 'Data Kartu Keluarga')
@section('page-subtitle', 'Detail')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Data Kartu Keluarga</h2>
            <p class="text-xs text-slate-400 mt-1">No. KK: <span class="font-bold text-blue-600">{{ $kartu_keluarga->no_kk }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('kartu-keluarga.edit', $kartu_keluarga) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-semibold rounded-lg hover:bg-amber-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('kartu-keluarga.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 text-sm font-semibold rounded-lg hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card stat-card-blue">
            <p class="text-xs font-medium text-blue-100 uppercase tracking-wider">Nomor KK</p>
            <p class="text-lg font-bold mt-1">{{ $kartu_keluarga->no_kk }}</p>
        </div>
        <div class="stat-card stat-card-green">
            <p class="text-xs font-medium text-green-100 uppercase tracking-wider">Total Anggota</p>
            <p class="text-2xl font-extrabold mt-1">{{ $kartu_keluarga->anggota->count() }}</p>
        </div>
        <div class="stat-card stat-card-purple">
            <p class="text-xs font-medium text-purple-100 uppercase tracking-wider">RT / RW</p>
            <p class="text-lg font-bold mt-1">{{ $kartu_keluarga->rt ?? '-' }} / {{ $kartu_keluarga->rw ?? '-' }}</p>
        </div>
    </div>

    {{-- Alamat --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Alamat Lengkap
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <div>
                <span class="text-slate-400">Alamat:</span>
                <p class="font-medium text-slate-700">{{ $kartu_keluarga->alamat }}</p>
            </div>
            <div>
                <span class="text-slate-400">Desa/Kelurahan:</span>
                <p class="font-medium text-slate-700">{{ $kartu_keluarga->desa ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-400">Kecamatan:</span>
                <p class="font-medium text-slate-700">{{ $kartu_keluarga->kecamatan ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-400">Kabupaten/Kota:</span>
                <p class="font-medium text-slate-700">{{ $kartu_keluarga->kabupaten ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-400">Kode Pos:</span>
                <p class="font-medium text-slate-700">{{ $kartu_keluarga->kode_pos ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Anggota Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Daftar Anggota Keluarga
            </h3>
            <span class="text-xs text-slate-400">{{ $kartu_keluarga->anggota->count() }} orang</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">NIK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">JK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tgl Lahir</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status Hubungan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($kartu_keluarga->anggota as $i => $a)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-slate-500">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-mono font-medium text-slate-700">{{ $a->nik }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($a->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700">{{ $a->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $a->jenis_kelamin ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500">{{ $a->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if ($a->status_hubungan === 'Kepala Keluarga')
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-blue-100 text-blue-700 rounded-full">Kepala KK</span>
                                @else
                                    <span class="text-sm text-slate-600">{{ $a->status_hubungan }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 rounded-full">{{ $a->role }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
