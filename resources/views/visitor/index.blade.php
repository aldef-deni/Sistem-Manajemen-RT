@extends('layouts.app')

@section('title', 'E-Visitor')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;">
    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3);">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <div>
                <h1 style="font-size:22px;font-weight:700;color:#1e293b;">E-Visitor</h1>
            </div>
        </div>
        <a href="{{ route('visitor.create') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;font-weight:600;font-size:14px;color:white;text-decoration:none;background:linear-gradient(135deg,#0d9488,#0f766e);box-shadow:0 4px 12px rgba(13,148,136,0.3);">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Registrasi Tamu
        </a>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
        <div style="border-radius:14px;padding:20px;color:white;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#1e3a5f,#1e40af);box-shadow:0 4px 12px rgba(30,58,95,0.3);">
            <div>
                <div style="font-size:11px;font-weight:600;opacity:0.8;text-transform:uppercase;letter-spacing:0.5px;">TAMU HARI INI</div>
                <div style="font-size:32px;font-weight:800;margin:4px 0;">{{ $tamuHariIni }}</div>
                <div style="font-size:12px;opacity:0.7;">kunjungan tercatat</div>
            </div>
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div style="border-radius:14px;padding:20px;color:white;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#0d9488,#14b8a6);box-shadow:0 4px 12px rgba(13,148,136,0.3);">
            <div>
                <div style="font-size:11px;font-weight:600;opacity:0.8;text-transform:uppercase;letter-spacing:0.5px;">SEDANG DI DALAM</div>
                <div style="font-size:32px;font-weight:800;margin:4px 0;">{{ $sedangDiDalam }}</div>
                <div style="font-size:12px;opacity:0.7;">belum checkout</div>
            </div>
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <div style="border-radius:14px;padding:20px;color:white;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#d97706,#f59e0b);box-shadow:0 4px 12px rgba(217,119,6,0.3);">
            <div>
                <div style="font-size:11px;font-weight:600;opacity:0.8;text-transform:uppercase;letter-spacing:0.5px;">TAMU MENGINAP</div>
                <div style="font-size:32px;font-weight:800;margin:4px 0;">{{ $tamuMenginap }}</div>
                <div style="font-size:12px;opacity:0.7;">sedang menginap</div>
            </div>
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
            </div>
        </div>
        <div style="border-radius:14px;padding:20px;color:white;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#2563eb,#60a5fa);box-shadow:0 4px 12px rgba(37,99,235,0.3);">
            <div>
                <div style="font-size:11px;font-weight:600;opacity:0.8;text-transform:uppercase;letter-spacing:0.5px;">TOTAL SEMUA</div>
                <div style="font-size:32px;font-weight:800;margin:4px 0;">{{ $totalSemua }}</div>
                <div style="font-size:12px;opacity:0.7;">riwayat lengkap</div>
            </div>
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;background:white;padding:12px 16px;border-radius:12px;border:1px solid #e2e8f0;">
        <span style="font-size:13px;font-weight:600;color:#0d9488;">🔍 Filter:</span>
        <form method="GET" action="{{ route('visitor.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;flex:1;">
            <div style="position:relative;flex:1;min-width:180px;">
                <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, blok, plat nomor..." style="width:100%;padding:8px 10px 8px 34px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
            </div>
            <select name="status" style="padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;background:white;">
                <option value="all">Semua Status</option>
                <option value="checkin" {{ request('status') === 'checkin' ? 'selected' : '' }}>Check In</option>
                <option value="checkout" {{ request('status') === 'checkout' ? 'selected' : '' }}>Checkout</option>
                <option value="menginap" {{ request('status') === 'menginap' ? 'selected' : '' }}>Menginap</option>
            </select>
            <select name="tipe" style="padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;background:white;">
                <option value="all">Semua Tipe</option>
                <option value="singkat" {{ request('tipe') === 'singkat' ? 'selected' : '' }}>Kunjungan Singkat</option>
                <option value="menginap" {{ request('tipe') === 'menginap' ? 'selected' : '' }}>Menginap</option>
            </select>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" style="padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:13px;">
            <button type="submit" style="padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;color:#0d9488;background:#f0fdfa;border:1px solid #99f6e4;cursor:pointer;">✓ Filter</button>
            <a href="{{ route('visitor.index') }}" style="padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;">↻ Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:8px;font-weight:600;color:#1e293b;">
                <svg style="width:18px;height:18px;color:#0d9488;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Daftar Kunjungan
            </div>
            <span style="font-size:13px;color:#94a3b8;">📋 {{ $visitors->total() }} data</span>
        </div>

        @if($visitors->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px 12px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">NO</th>
                        <th style="padding:12px 12px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">JAM / TGL</th>
                        <th style="padding:12px 12px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">DURASI</th>
                        <th style="padding:12px 12px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">IDENTITAS TAMU</th>
                        <th style="padding:12px 12px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">TUJUAN</th>
                        <th style="padding:12px 12px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">KEPERLUAN</th>
                        <th style="padding:12px 12px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">KENDARAAN</th>
                        <th style="padding:12px 12px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">STATUS</th>
                        <th style="padding:12px 12px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visitors as $i => $v)
                    <tr style="border-bottom:1px solid #f1f5f9;{{ $i % 2 === 0 ? '' : 'background:#f8fafc;' }}">
                        <td style="padding:12px;text-align:center;color:#94a3b8;font-weight:600;">{{ $visitors->firstItem() + $i }}</td>
                        <td style="padding:12px;">
                            <div style="display:flex;flex-direction:column;gap:2px;">
                                <span style="display:flex;align-items:center;gap:4px;font-weight:600;color:#10b981;">
                                    <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $v->jam_checkin }}
                                </span>
                                @if($v->jam_checkout)
                                <span style="display:flex;align-items:center;gap:4px;font-weight:600;color:#dc2626;">
                                    <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                                    {{ $v->jam_checkout }}
                                </span>
                                @endif
                                <span style="font-size:11px;color:#94a3b8;">{{ \Carbon\Carbon::parse($v->tanggal)->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td style="padding:12px;">
                            @if($v->durasi)
                            <span style="padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;">{{ $v->durasi }}</span>
                            @else
                            <span style="padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;background:#fefce8;color:#ca8a04;border:1px solid #fef08a;">—</span>
                            @endif
                        </td>
                        <td style="padding:12px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px;flex-shrink:0;">
                                    {{ strtoupper(substr($v->nama_tamu, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;color:#1e293b;">{{ $v->nama_tamu }}</div>
                                    <div style="font-size:11px;color:#64748b;display:flex;align-items:center;gap:4px;">
                                        📱 {{ $v->no_hp }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:12px;">
                            <div style="display:flex;align-items:center;gap:4px;font-weight:600;color:#1e293b;">
                                <svg style="width:14px;height:14px;color:#0d9488;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ strtoupper($v->tujuan_blok) }}
                            </div>
                            @if($v->wa_host)
                            <div style="font-size:11px;color:#64748b;">WA: {{ $v->wa_host }}</div>
                            @endif
                        </td>
                        <td style="padding:12px;">
                            @foreach($v->kepentingan ?? [] as $k)
                            <span style="display:inline-block;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:600;background:#f0fdfa;color:#0d9488;border:1px solid #99f6e4;margin:1px;">{{ $k }}</span>
                            @endforeach
                            <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ strtoupper($v->deskripsi_kepentingan ?? '-') }}</div>
                        </td>
                        <td style="padding:12px;">
                            @if($v->no_plat)
                            <div style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;background:#f1f5f9;color:#1e293b;border:1px solid #e2e8f0;font-family:monospace;">{{ $v->no_plat }}</div>
                            <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $v->jenis_kendaraan ?? '-' }}</div>
                            @else
                            <span style="font-size:12px;color:#94a3b8;">—</span>
                            @endif
                        </td>
                        <td style="padding:12px;">
                            @if($v->status === 'checkin')
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;">✅ Check In</span>
                            @elseif($v->status === 'checkout')
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;">✓ Checkout</span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#fefce8;color:#ca8a04;border:1px solid #fef08a;">🌙 Menginap</span>
                            @endif
                        </td>
                        <td style="padding:12px;text-align:center;">
                            <div style="display:flex;gap:4px;justify-content:center;">
                                <a href="{{ route('visitor.show', $v->id) }}" style="padding:6px;border-radius:6px;background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;text-decoration:none;" title="Detail">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if($v->status === 'checkin' || $v->status === 'menginap')
                                <form method="POST" action="{{ route('visitor.checkout', $v->id) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="padding:6px;border-radius:6px;background:#eff6ff;border:1px solid #bfdbfe;color:#2563eb;cursor:pointer;" title="Check Out">
                                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('visitor.edit', $v->id) }}" style="padding:6px;border-radius:6px;background:#fefce8;border:1px solid #fef08a;color:#ca8a04;text-decoration:none;" title="Edit">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('visitor.destroy', $v->id) }}" onsubmit="return confirm('Yakin hapus data ini?')" style="display:inline;">
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
                        <td colspan="9" style="padding:60px 20px;text-align:center;">
                            <div style="font-size:48px;margin-bottom:12px;">👁️</div>
                            <div style="font-weight:600;color:#1e293b;margin-bottom:4px;">Belum Ada Data Kunjungan</div>
                            <div style="font-size:13px;color:#94a3b8;">Klik "Registrasi Tamu" untuk mencatat kunjungan baru.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($visitors->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">{{ $visitors->links() }}</div>
        @endif
        @endif
    </div>
</div>
@endsection
