@extends('layouts.app')

@section('title', 'Bantuan Sosial Warga')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;">
    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#94a3b8;">
        <a href="{{ route('dashboard') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Dashboard</a>
        <span>/</span>
        <span style="color:#334155;font-weight:600;">Bantuan Sosial</span>
    </div>

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3);">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <div>
                <h1 style="font-size:22px;font-weight:700;color:#1e293b;">Bantuan Sosial Warga</h1>
            </div>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('bantuan-sosial.kurang-mampu') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;font-weight:600;font-size:14px;color:white;text-decoration:none;background:linear-gradient(135deg,#f59e0b,#d97706);box-shadow:0 4px 12px rgba(245,158,11,0.3);border:none;cursor:pointer;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Warga Kurang Mampu
            </a>
            <a href="{{ route('bantuan-sosial.tambah-penerima') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;font-weight:600;font-size:14px;color:white;text-decoration:none;background:linear-gradient(135deg,#0d9488,#0f766e);box-shadow:0 4px 12px rgba(13,148,136,0.3);border:none;cursor:pointer;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Penerima
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#818cf8);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#1e293b;">{{ $totalPenerima }}</div>
                <div style="font-size:13px;color:#94a3b8;">Total Penerima</div>
            </div>
        </div>
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#1e293b;">{{ $aktif }}</div>
                <div style="font-size:13px;color:#94a3b8;">Aktif</div>
            </div>
        </div>
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#fbbf24);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#1e293b;">{{ $perluDitinjau }}</div>
                <div style="font-size:13px;color:#94a3b8;">Perlu Ditinjau</div>
            </div>
        </div>
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#8b5cf6,#a78bfa);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#1e293b;">{{ $jenisAktif }}</div>
                <div style="font-size:13px;color:#94a3b8;">Jenis Aktif</div>
            </div>
        </div>
    </div>

    {{-- Year Tabs --}}
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
        <span style="font-size:13px;font-weight:600;color:#64748b;">📅 Tahun:</span>
        <a href="{{ route('bantuan-sosial.index', array_merge(request()->query(), ['tahun' => 'all'])) }}" 
           style="padding:6px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;{{ (!request('tahun') || request('tahun') === 'all') ? 'background:#0d9488;color:white;' : 'background:white;color:#64748b;border:1px solid #e2e8f0;' }}">
            Semua
        </a>
        @for($y = date('Y'); $y >= 2020; $y--)
        <a href="{{ route('bantuan-sosial.index', array_merge(request()->query(), ['tahun' => $y])) }}" 
           style="padding:6px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;{{ request('tahun') == $y ? 'background:#0d9488;color:white;' : 'background:white;color:#64748b;border:1px solid #e2e8f0;' }}">
            {{ $y }}
        </a>
        @endfor
    </div>

    {{-- Filters --}}
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <form method="GET" action="{{ route('bantuan-sosial.index') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;flex:1;">
            <select name="jenis" style="padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;background:white;min-width:150px;">
                <option value="">Semua Jenis</option>
                <option value="BLT" {{ request('jenis') === 'BLT' ? 'selected' : '' }}>BLT</option>
                <option value="Sembako" {{ request('jenis') === 'Sembako' ? 'selected' : '' }}>Sembako</option>
                <option value="PKH" {{ request('jenis') === 'PKH' ? 'selected' : '' }}>PKH</option>
                <option value="BPNT" {{ request('jenis') === 'BPNT' ? 'selected' : '' }}>BPNT</option>
                <option value="Lansia" {{ request('jenis') === 'Lansia' ? 'selected' : '' }}>Lansia</option>
                <option value="Lainnya" {{ request('jenis') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <select name="status" style="padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;background:white;min-width:150px;">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <div style="position:relative;flex:1;min-width:200px;">
                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIK..." style="width:100%;padding:8px 12px 8px 36px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
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
                <svg style="width:18px;height:18px;color:#0d9488;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Data Penerima Bantuan
            </div>
            <span style="font-size:13px;color:#94a3b8;">📋 {{ $penerima->total() }} data</span>
        </div>

        @if($penerima->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">#</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">NAMA WARGA</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">NIK</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">JENIS BANTUAN</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">TAHUN</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">STATUS</th>
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">KETERANGAN</th>
                        <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerima as $i => $item)
                    <tr style="border-bottom:1px solid #f1f5f9;{{ $i % 2 === 0 ? '' : 'background:#f8fafc;' }}">
                        <td style="padding:12px 16px;color:#94a3b8;">{{ $penerima->firstItem() + $i }}</td>
                        <td style="padding:12px 16px;font-weight:600;color:#1e293b;">{{ $item->anggota->nama_lengkap ?? '-' }}</td>
                        <td style="padding:12px 16px;color:#64748b;font-family:monospace;font-size:13px;">{{ $item->nik ?? '-' }}</td>
                        <td style="padding:12px 16px;">
                            @foreach($item->jenis_bantuan ?? [] as $jb)
                                @php
                                    $colors = [
                                        'BLT' => ['bg' => '#fef2f2', 'text' => '#dc2626', 'border' => '#fecaca'],
                                        'Sembako' => ['bg' => '#f0fdf4', 'text' => '#16a34a', 'border' => '#bbf7d0'],
                                        'PKH' => ['bg' => '#eff6ff', 'text' => '#2563eb', 'border' => '#bfdbfe'],
                                        'BPNT' => ['bg' => '#fefce8', 'text' => '#ca8a04', 'border' => '#fef08a'],
                                        'Lansia' => ['bg' => '#faf5ff', 'text' => '#9333ea', 'border' => '#e9d5ff'],
                                        'Lainnya' => ['bg' => '#f8fafc', 'text' => '#64748b', 'border' => '#e2e8f0'],
                                    ];
                                    $c = $colors[$jb] ?? $colors['Lainnya'];
                                @endphp
                                <span style="display:inline-block;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;margin:2px;background:{{ $c['bg'] }};color:{{ $c['text'] }};border:1px solid {{ $c['border'] }};">
                                    @if($jb === 'BLT') 💵 @elseif($jb === 'Sembako') 📦 @elseif($jb === 'PKH') 🏠 @elseif($jb === 'BPNT') 🍚 @elseif($jb === 'Lansia') 👴 @else ⚪ @endif {{ $jb }}
                                </span>
                            @endforeach
                        </td>
                        <td style="padding:12px 16px;font-weight:600;color:#1e293b;">{{ $item->tahun }}</td>
                        <td style="padding:12px 16px;">
                            @if($item->status === 'aktif')
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;">
                                    ✅ Aktif
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">
                                    ❌ Nonaktif
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;color:#64748b;font-size:13px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item->keterangan ?? '-' }}</td>
                        <td style="padding:12px 16px;text-align:center;">
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <a href="{{ route('bantuan-sosial.show', $item->id) }}" style="padding:6px;border-radius:6px;background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;cursor:pointer;text-decoration:none;" title="Detail">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('bantuan-sosial.edit', $item->id) }}" style="padding:6px;border-radius:6px;background:#eff6ff;border:1px solid #bfdbfe;color:#2563eb;cursor:pointer;text-decoration:none;" title="Edit">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('bantuan-sosial.destroy', $item->id) }}" onsubmit="return confirm('Yakin hapus data ini?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:6px;border-radius:6px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;cursor:pointer;" title="Hapus">
                                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding:60px 20px;text-align:center;">
                            <div style="font-size:48px;margin-bottom:12px;">📋</div>
                            <div style="font-weight:600;color:#1e293b;margin-bottom:4px;">Belum Ada Data Penerima</div>
                            <div style="font-size:13px;color:#94a3b8;">Belum ada data penerima bantuan yang tercatat.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penerima->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
            {{ $penerima->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
