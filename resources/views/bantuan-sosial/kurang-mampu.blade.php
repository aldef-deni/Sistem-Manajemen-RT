@extends('layouts.app')

@section('title', 'Warga Kurang Mampu')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;">
    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#94a3b8;">
        <a href="{{ route('dashboard') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('bantuan-sosial.index') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Bantuan Sosial</a>
        <span>/</span>
        <span style="color:#1e293b;font-weight:600;">Warga Kurang Mampu</span>
    </div>

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h1 style="font-size:22px;font-weight:700;color:#1e293b;">Warga Kurang Mampu</h1>
            </div>
        </div>
        <a href="{{ route('bantuan-sosial.ajukan') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;font-weight:600;font-size:14px;color:white;text-decoration:none;background:linear-gradient(135deg,#f59e0b,#d97706);box-shadow:0 4px 12px rgba(245,158,11,0.3);border:none;cursor:pointer;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Ajukan Data
        </a>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#fbbf24);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#1e293b;">{{ $totalPengajuan }}</div>
                <div style="font-size:13px;color:#94a3b8;">Total Pengajuan</div>
            </div>
        </div>
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#1e293b;">{{ $menunggu }}</div>
                <div style="font-size:13px;color:#94a3b8;">Menunggu</div>
            </div>
        </div>
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#1e293b;">{{ $disetujui }}</div>
                <div style="font-size:13px;color:#94a3b8;">Disetujui</div>
            </div>
        </div>
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#ef4444,#f87171);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#1e293b;">{{ $ditolak }}</div>
                <div style="font-size:13px;color:#94a3b8;">Ditolak</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <form method="GET" action="{{ route('bantuan-sosial.kurang-mampu') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;flex:1;">
            <select name="status" style="padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;background:white;min-width:150px;">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="tahun" style="padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;background:white;min-width:150px;">
                <option value="all">Semua Tahun</option>
                @for($y = date('Y'); $y >= 2020; $y--)
                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <div style="position:relative;flex:1;min-width:200px;">
                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIK..." style="width:100%;padding:8px 12px 8px 36px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
            </div>
            <button type="submit" style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;color:white;background:linear-gradient(135deg,#0d9488,#0f766e);border:none;cursor:pointer;display:flex;align-items:center;gap:6px;">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:8px;font-weight:600;color:#1e293b;">
                <svg style="width:18px;height:18px;color:#f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Daftar Pengajuan Warga Kurang Mampu
            </div>
            <span style="font-size:13px;color:#94a3b8;">📋 {{ $pengajuan->total() }} data</span>
        </div>

        @if($pengajuan->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">#</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">NAMA WARGA</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">NIK</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">PENGHASILAN</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">KONDISI RUMAH</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">STATUS</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">TANGGAL</th>
                        <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $i => $item)
                    <tr style="border-bottom:1px solid #f1f5f9;{{ $i % 2 === 0 ? '' : 'background:#f8fafc;' }}">
                        <td style="padding:12px 16px;color:#94a3b8;">{{ $pengajuan->firstItem() + $i }}</td>
                        <td style="padding:12px 16px;font-weight:600;color:#1e293b;">{{ $item->anggota->nama_lengkap ?? '-' }}</td>
                        <td style="padding:12px 16px;color:#64748b;font-family:monospace;font-size:13px;">{{ $item->nik ?? '-' }}</td>
                        <td style="padding:12px 16px;color:#1e293b;font-weight:600;">Rp {{ number_format($item->penghasilan_per_bulan, 0, ',', '.') }}</td>
                        <td style="padding:12px 16px;">
                            @php
                                $kondisiColors = [
                                    'Baik' => ['bg' => '#f0fdf4', 'text' => '#16a34a', 'border' => '#bbf7d0'],
                                    'Sedang' => ['bg' => '#fefce8', 'text' => '#ca8a04', 'border' => '#fef08a'],
                                    'Rusak' => ['bg' => '#fff7ed', 'text' => '#ea580c', 'border' => '#fed7aa'],
                                    'Sangat Rusak' => ['bg' => '#fef2f2', 'text' => '#dc2626', 'border' => '#fecaca'],
                                ];
                                $kc = $kondisiColors[$item->kondisi_rumah] ?? $kondisiColors['Sedang'];
                            @endphp
                            <span style="display:inline-block;padding:3px 10px;border-radius:6px;font-size:12px;font-weight:600;background:{{ $kc['bg'] }};color:{{ $kc['text'] }};border:1px solid {{ $kc['border'] }};">
                                🏠 {{ $item->kondisi_rumah }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;">
                            @if($item->status === 'menunggu')
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#fefce8;color:#ca8a04;border:1px solid #fef08a;">⏳ Menunggu</span>
                            @elseif($item->status === 'disetujui')
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;">✅ Disetujui</span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">❌ Ditolak</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;color:#64748b;font-size:13px;">{{ $item->created_at->format('d M Y') }}</td>
                        <td style="padding:12px 16px;text-align:center;">
                            <div style="display:flex;gap:6px;justify-content:center;">
                                @if($item->status === 'menunggu')
                                <form method="POST" action="{{ route('bantuan-sosial.pengajuan.status', $item->id) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="disetujui">
                                    <button type="submit" style="padding:6px;border-radius:6px;background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;cursor:pointer;font-size:12px;" title="Setujui">✅</button>
                                </form>
                                <form method="POST" action="{{ route('bantuan-sosial.pengajuan.status', $item->id) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" style="padding:6px;border-radius:6px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;cursor:pointer;font-size:12px;" title="Tolak">❌</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding:60px 20px;text-align:center;">
                            <div style="font-size:48px;margin-bottom:12px;">🏠</div>
                            <div style="font-weight:600;color:#1e293b;margin-bottom:4px;">Belum Ada Data Pengajuan</div>
                            <div style="font-size:13px;color:#94a3b8;">Belum ada warga yang mengajukan data kurang mampu.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengajuan->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
            {{ $pengajuan->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
