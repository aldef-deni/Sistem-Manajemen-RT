@extends('layouts.app')

@section('title', 'Jadwal Kegiatan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Jadwal Kegiatan</span>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Jadwal Kegiatan</h1>
                <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <a href="{{ route('jadwal-kegiatan.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-xl shadow-lg shadow-teal-500/25 hover:shadow-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Jadwal
        </a>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">
        <div style="background:linear-gradient(135deg,#14b8a6,#0d9488); border-radius:16px; padding:20px; color:white; box-shadow:0 4px 15px rgba(20,184,166,0.3);">
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <p style="font-size:11px; font-weight:600; opacity:0.85; text-transform:uppercase; letter-spacing:0.05em;">📅 Total Jadwal</p>
                    <p style="font-size:32px; font-weight:800; margin-top:4px;">{{ $stats['total'] }}</p>
                </div>
                <div style="width:48px; height:48px; background:rgba(255,255,255,0.2); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#f59e0b,#d97706); border-radius:16px; padding:20px; color:white; box-shadow:0 4px 15px rgba(245,158,11,0.3);">
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <p style="font-size:11px; font-weight:600; opacity:0.85; text-transform:uppercase; letter-spacing:0.05em;">🔄 Jadwal Harian</p>
                    <p style="font-size:32px; font-weight:800; margin-top:4px;">{{ $stats['harian'] }}</p>
                </div>
                <div style="width:48px; height:48px; background:rgba(255,255,255,0.2); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#3b82f6,#2563eb); border-radius:16px; padding:20px; color:white; box-shadow:0 4px 15px rgba(59,130,246,0.3);">
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <p style="font-size:11px; font-weight:600; opacity:0.85; text-transform:uppercase; letter-spacing:0.05em;">📆 Jadwal Mingguan</p>
                    <p style="font-size:32px; font-weight:800; margin-top:4px;">{{ $stats['mingguan'] }}</p>
                </div>
                <div style="width:48px; height:48px; background:rgba(255,255,255,0.2); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#8b5cf6,#7c3aed); border-radius:16px; padding:20px; color:white; box-shadow:0 4px 15px rgba(139,92,246,0.3);">
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <p style="font-size:11px; font-weight:600; opacity:0.85; text-transform:uppercase; letter-spacing:0.05em;">📌 Hari Ini</p>
                    <p style="font-size:32px; font-weight:800; margin-top:4px;">{{ $stats['hari_ini'] }}</p>
                </div>
                <div style="width:48px; height:48px; background:rgba(255,255,255,0.2); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Today Schedule --}}
    <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        @if($todaySchedule->count() > 0)
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; background:#dcfce7; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:20px; height:20px; color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                </div>
                <div>
                    <h3 style="font-weight:700; color:#1e293b; font-size:14px;">Jadwal Hari Ini</h3>
                    @foreach($todaySchedule as $ts)
                        <p style="font-size:13px; color:#64748b;">• {{ $ts->jam_mulai ? $ts->jam_mulai . ' - ' . ($ts->jam_selesai ?? '') . ' ' : '' }}{{ $ts->nama_kegiatan }} @if($ts->lokasi) — {{ $ts->lokasi }} @endif</p>
                    @endforeach
                </div>
            </div>
        @else
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; background:#fef3c7; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:20px; height:20px; color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 style="font-weight:700; color:#1e293b; font-size:14px;">Tidak Ada Jadwal Hari Ini</h3>
                    <p style="font-size:13px; color:#64748b;">Tidak ada kegiatan yang terjadwal untuk hari {{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('jadwal-kegiatan.index') }}">
        <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <h3 style="font-weight:700; font-size:14px; color:#1e293b; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                <svg style="width:16px; height:16px; color:#14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter Jadwal
            </h3>
            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                <select name="kategori" style="padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; min-width:180px; background:white;">
                    <option value="">Semua Kategori</option>
                    @foreach(['Keamanan', 'Kebersihan', 'Sosial', 'Keagamaan', 'Olahraga', 'Gotong Royong', 'Lainnya'] as $k)
                        <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
                <select name="status" style="padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; min-width:180px; background:white;">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <a href="{{ route('jadwal-kegiatan.index') }}" style="padding:8px 16px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; color:#64748b; text-decoration:none; display:flex; align-items:center; gap:4px; background:white;">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- List --}}
    <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        @if($jadwal->count() > 0)
            <div style="overflow-x:auto;">
                <table style="width:100%; font-size:13px; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                            <th style="padding:12px 16px; text-align:left; font-weight:700; color:#475569; font-size:11px; text-transform:uppercase; letter-spacing:0.05em;">No</th>
                            <th style="padding:12px 16px; text-align:left; font-weight:700; color:#475569; font-size:11px; text-transform:uppercase;">Kegiatan</th>
                            <th style="padding:12px 16px; text-align:left; font-weight:700; color:#475569; font-size:11px; text-transform:uppercase;">Kategori</th>
                            <th style="padding:12px 16px; text-align:left; font-weight:700; color:#475569; font-size:11px; text-transform:uppercase;">Jenis</th>
                            <th style="padding:12px 16px; text-align:left; font-weight:700; color:#475569; font-size:11px; text-transform:uppercase;">Jadwal</th>
                            <th style="padding:12px 16px; text-align:left; font-weight:700; color:#475569; font-size:11px; text-transform:uppercase;">Status</th>
                            <th style="padding:12px 16px; text-align:left; font-weight:700; color:#475569; font-size:11px; text-transform:uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwal as $i => $j)
                        <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:12px 16px; color:#94a3b8; font-weight:600;">{{ ($jadwal->currentPage() - 1) * $jadwal->perPage() + $i + 1 }}</td>
                            <td style="padding:12px 16px;">
                                <div style="font-weight:700; color:#1e293b;">{{ $j->nama_kegiatan }}</div>
                                @if($j->lokasi)
                                    <div style="font-size:11px; color:#94a3b8; display:flex; align-items:center; gap:4px; margin-top:2px;">
                                        <svg style="width:11px; height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $j->lokasi }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding:12px 16px;">
                                @php
                                    $katColors = ['Keamanan' => '#fee2e2,#dc2626', 'Kebersihan' => '#d1fae5,#059669', 'Sosial' => '#dbeafe,#2563eb', 'Keagamaan' => '#f3e8ff,#9333ea', 'Olahraga' => '#fef3c7,#d97706', 'Gotong Royong' => '#ccfbf1,#0d9488'];
                                    [$bg, $fg] = explode(',', $katColors[$j->kategori] ?? '#f1f5f9,#475569');
                                @endphp
                                <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:{{ $bg }}; color:{{ $fg }};">{{ $j->kategori }}</span>
                            </td>
                            <td style="padding:12px 16px; color:#475569; font-size:12px;">{{ $j->jenis_jadwal }}</td>
                            <td style="padding:12px 16px;">
                                <div style="font-weight:600; color:#334155;">{{ $j->tanggal_mulai->format('d/m/Y') }}</div>
                                @if($j->jam_mulai)
                                    <div style="font-size:11px; color:#94a3b8;">{{ $j->jam_mulai }} - {{ $j->jam_selesai ?? '' }}</div>
                                @endif
                            </td>
                            <td style="padding:12px 16px;">
                                @if($j->status == 'aktif')
                                    <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#dcfce7; color:#16a34a;">Aktif</span>
                                @elseif($j->status == 'selesai')
                                    <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#e2e8f0; color:#475569;">Selesai</span>
                                @else
                                    <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#fee2e2; color:#dc2626;">Dibatalkan</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;">
                                <div style="display:flex; gap:6px;">
                                    <a href="{{ route('jadwal-kegiatan.show', $j->id) }}" style="width:30px; height:30px; background:#f0fdfa; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#14b8a6; text-decoration:none;" title="Detail">
                                        <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('jadwal-kegiatan.edit', $j->id) }}" style="width:30px; height:30px; background:#eff6ff; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#3b82f6; text-decoration:none;" title="Edit">
                                        <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('jadwal-kegiatan.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button style="width:30px; height:30px; background:#fef2f2; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#ef4444; border:none; cursor:pointer;" title="Hapus">
                                            <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:12px 16px; border-top:1px solid #f1f5f9;">
                {{ $jadwal->links() }}
            </div>
        @else
            <div style="padding:60px 20px; text-align:center;">
                <div style="width:60px; height:60px; background:#f1f5f9; border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                    <svg style="width:28px; height:28px; color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p style="font-weight:700; color:#475569; font-size:15px;">Belum ada jadwal kegiatan</p>
                <p style="font-size:13px; color:#94a3b8; margin-top:4px;">Klik "Tambah Jadwal" untuk membuat jadwal baru</p>
            </div>
        @endif
    </div>
</div>
@endsection
