@extends('layouts.app')

@section('title', 'Detail Jadwal')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('jadwal-kegiatan.index') }}" class="text-teal-600 hover:underline font-medium">Jadwal Kegiatan</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Detail</span>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Jadwal</h1>
                <p class="text-sm text-slate-500">{{ $jadwal->nama_kegiatan }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('jadwal-kegiatan.edit', $jadwal->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition font-medium text-sm shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form action="{{ route('jadwal-kegiatan.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                @csrf @method('DELETE')
                <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-xl hover:bg-red-600 transition font-medium text-sm shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="flex gap-6">
        <div class="flex-1 space-y-6">
            <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                    <h3 style="font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px;">
                        <svg style="width:16px; height:16px; color:#14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Informasi Kegiatan
                    </h3>
                    @if($jadwal->status == 'aktif')
                        <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#dcfce7; color:#16a34a;">Aktif</span>
                    @elseif($jadwal->status == 'selesai')
                        <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#e2e8f0; color:#475569;">Selesai</span>
                    @else
                        <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#fee2e2; color:#dc2626;">Dibatalkan</span>
                    @endif
                </div>
                <div style="padding:20px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div style="grid-column:1/3;">
                            <p style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-bottom:4px;">Nama Kegiatan</p>
                            <p style="font-weight:700; color:#1e293b; font-size:16px;">{{ $jadwal->nama_kegiatan }}</p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-bottom:4px;">Kategori</p>
                            <p style="font-weight:600; color:#334155;">{{ $jadwal->kategori }}</p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-bottom:4px;">Jenis Jadwal</p>
                            <p style="font-weight:600; color:#334155;">{{ $jadwal->jenis_jadwal }}</p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-bottom:4px;">Lokasi</p>
                            <p style="font-weight:600; color:#334155;">{{ $jadwal->lokasi ?? '-' }}</p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-bottom:4px;">Penanggung Jawab</p>
                            <p style="font-weight:600; color:#334155;">{{ $jadwal->penanggungJawab->nama_lengkap ?? '-' }}</p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-bottom:4px;">Tanggal Mulai</p>
                            <p style="font-weight:600; color:#334155;">{{ $jadwal->tanggal_mulai->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-bottom:4px;">Jam</p>
                            <p style="font-weight:600; color:#334155;">{{ $jadwal->jam_mulai ?? '-' }} {{ $jadwal->jam_selesai ? '- ' . $jadwal->jam_selesai : '' }}</p>
                        </div>
                        @if($jadwal->deskripsi)
                        <div style="grid-column:1/3;">
                            <p style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-bottom:4px;">Deskripsi</p>
                            <p style="color:#475569; font-size:13px; line-height:1.6; background:#f8fafc; padding:12px; border-radius:8px;">{{ $jadwal->deskripsi }}</p>
                        </div>
                        @endif
                        @if($jadwal->petugas && count($jadwal->petugas) > 0)
                        <div style="grid-column:1/3;">
                            <p style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-bottom:6px;">Petugas / Kelompok</p>
                            <div style="display:flex; flex-wrap:wrap; gap:6px;">
                                @foreach($jadwal->petugas as $p)
                                    <span style="padding:4px 12px; background:#f0fdfa; color:#0d9488; border-radius:6px; font-size:12px; font-weight:600; border:1px solid #ccfbf1;">{{ $p }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
