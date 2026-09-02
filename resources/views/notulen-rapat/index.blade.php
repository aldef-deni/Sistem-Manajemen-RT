@extends('layouts.app')

@section('title', 'Notulen Rapat')

@section('content')
<div style="space-y:6">
    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <div style="display:flex;align-items:center;gap:12px">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#14b8a6,#0d9488);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(20,184,166,0.3)">
                <svg style="width:22px;height:22px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h1 style="font-size:22px;font-weight:700;color:#1e293b;margin:0">Notulen Rapat</h1>
                <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#64748b;margin-top:2px">
                    <a href="{{ route('dashboard') }}" style="color:#14b8a6;text-decoration:none;font-weight:500">Dashboard</a>
                    <span>/</span>
                    <span style="color:#475569;font-weight:500">Notulen Rapat</span>
                </div>
            </div>
        </div>
        <a href="{{ route('notulen-rapat.create') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border-radius:10px;font-weight:600;font-size:14px;text-decoration:none;box-shadow:0 4px 12px rgba(20,184,166,0.3)">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Notulen
        </a>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
        <div style="background:linear-gradient(135deg,#14b8a6,#0d9488);border-radius:16px;padding:20px;color:white;position:relative;overflow:hidden">
            <div style="position:absolute;top:-10px;right:-10px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.1)"></div>
            <p style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin:0;opacity:0.9">📋 Total Notulen</p>
            <p style="font-size:32px;font-weight:800;margin:8px 0 0">{{ $stats['total'] }}</p>
        </div>
        <div style="background:linear-gradient(135deg,#10b981,#059669);border-radius:16px;padding:20px;color:white;position:relative;overflow:hidden">
            <div style="position:absolute;top:-10px;right:-10px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.1)"></div>
            <p style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin:0;opacity:0.9">✅ Final</p>
            <p style="font-size:32px;font-weight:800;margin:8px 0 0">{{ $stats['final'] }}</p>
        </div>
        <div style="background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:16px;padding:20px;color:white;position:relative;overflow:hidden">
            <div style="position:absolute;top:-10px;right:-10px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.1)"></div>
            <p style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin:0;opacity:0.9">⏳ Menunggu</p>
            <p style="font-size:32px;font-weight:800;margin:8px 0 0">{{ $stats['menunggu'] }}</p>
        </div>
        <div style="background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:16px;padding:20px;color:white;position:relative;overflow:hidden">
            <div style="position:absolute;top:-10px;right:-10px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.1)"></div>
            <p style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin:0;opacity:0.9">✎ Draft</p>
            <p style="font-size:32px;font-weight:800;margin:8px 0 0">{{ $stats['draft'] }}</p>
        </div>
    </div>

    {{-- Filter --}}
    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,0.06);margin-bottom:24px">
        <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0 0 16px">🔍 Cari & Filter Notulen</h3>
        <form method="GET" action="{{ route('notulen-rapat.index') }}" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto auto;gap:12px;align-items:end">
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, moderator, tempat..." style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;outline:none">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px">Status</label>
                <select name="status" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;background:white;outline:none">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="final" {{ request('status') == 'final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px">Tim / Proyek</label>
                <select name="tim_proyek" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;background:white;outline:none">
                    <option value="">Semua Tim</option>
                    @foreach(['Keamanan','Kebersihan','Keuangan','Sosial','Umum'] as $t)
                        <option value="{{ $t }}" {{ request('tim_proyek') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;outline:none">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px">Sampai</label>
                <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" style="width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;outline:none">
            </div>
            <button type="submit" style="padding:9px 16px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border:none;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:4px">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
            <a href="{{ route('notulen-rapat.index') }}" style="padding:9px 12px;background:#f1f5f9;color:#64748b;border:none;border-radius:8px;cursor:pointer;text-decoration:none;display:flex;align-items:center">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </form>
    </div>

    {{-- Notulen List --}}
    @if($notulens->count() > 0)
        <div style="display:flex;flex-direction:column;gap:16px">
            @foreach($notulens as $n)
                @php $sb = $n->status_badge; $tb = $n->tim_badge; @endphp
                <div style="background:white;border-radius:16px;padding:20px 24px;box-shadow:0 1px 3px rgba(0,0,0,0.06);border-left:4px solid {{ $n->status === 'final' ? '#10b981' : ($n->status === 'menunggu' ? '#f59e0b' : '#6366f1') }};transition:all 0.2s" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'">
                    <div style="display:flex;align-items:start;justify-content:space-between">
                        <div style="flex:1">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                                <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0">{{ $n->judul_rapat }}</h3>
                                @php
                                    $borderColor = '#e2e8f0';
                                    if(str_contains($tb['bg'], 'red')) $borderColor = '#fecaca';
                                    elseif(str_contains($tb['bg'], 'green')) $borderColor = '#bbf7d0';
                                    elseif(str_contains($tb['bg'], 'blue')) $borderColor = '#bfdbfe';
                                    elseif(str_contains($tb['bg'], 'yellow')) $borderColor = '#fde68a';
                                @endphp
                                <span style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;border:1px solid {{ $borderColor }};{{ $tb['bg'] }}">
                                    {{ $n->tim_proyek ?? 'Umum' }}
                                </span>
                                <span style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;{{ $sb['bg'] }}">
                                    {{ $sb['icon'] }} {{ $sb['label'] }}
                                </span>
                            </div>
                            <div style="display:flex;align-items:center;gap:14px;font-size:13px;color:#64748b;margin-bottom:10px;flex-wrap:wrap">
                                <div style="display:flex;align-items:center;gap:4px">
                                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $n->tanggal->format('d M Y') }}
                                </div>
                                <div style="display:flex;align-items:center;gap:4px">
                                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ substr($n->waktu_mulai,0,5) }} — {{ substr($n->waktu_selesai,0,5) }}
                                </div>
                                <div style="display:flex;align-items:center;gap:4px">
                                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $n->tempat }}
                                </div>
                            </div>
                            @if($n->poin->count() > 0)
                                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px">
                                    @foreach($n->poin->take(3) as $p)
                                        <span style="padding:3px 10px;border-radius:6px;font-size:11px;background:#f0fdfa;color:#0d9488;border:1px solid #ccfbf1">{{ Str::limit($p->topik, 35) }}</span>
                                    @endforeach
                                    @if($n->poin->count() > 3)
                                        <span style="padding:3px 10px;border-radius:6px;font-size:11px;background:#f1f5f9;color:#64748b">+{{ $n->poin->count() - 3 }} lagi</span>
                                    @endif
                                </div>
                            @endif
                            <div style="display:flex;align-items:center;gap:14px;font-size:12px;color:#94a3b8">
                                <span>👥 {{ $n->jumlah_hadir }}/{{ $n->hadir->count() }} hadir</span>
                                <span>👤 {{ $n->moderator }}</span>
                            </div>
                        </div>
                        <div style="display:flex;gap:6px;flex-shrink:0;margin-left:16px">
                            <a href="{{ route('notulen-rapat.show', $n) }}" style="width:34px;height:34px;border-radius:8px;background:#f0fdfa;color:#14b8a6;display:flex;align-items:center;justify-content:center;text-decoration:none" title="Lihat">
                                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('notulen-rapat.edit', $n) }}" style="width:34px;height:34px;border-radius:8px;background:#eff6ff;color:#3b82f6;display:flex;align-items:center;justify-content:center;text-decoration:none" title="Edit">
                                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('notulen-rapat.destroy', $n) }}" onsubmit="return confirm('Yakin hapus notulen ini?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" style="width:34px;height:34px;border-radius:8px;background:#fef2f2;color:#ef4444;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center" title="Hapus">
                                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;display:flex;justify-content:center">
            {{ $notulens->links() }}
        </div>
    @else
        <div style="background:white;border-radius:16px;padding:60px 20px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div style="width:80px;height:80px;border-radius:50%;background:#eff6ff;margin:0 auto 20px;display:flex;align-items:center;justify-content:center">
                <svg style="width:40px;height:40px;color:#60a5fa" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 style="font-size:18px;font-weight:700;color:#1e293b;margin:0 0 8px">Belum ada notulen rapat</h3>
            <p style="font-size:14px;color:#64748b;margin:0 0 24px">Notulen rapat yang dibuat akan muncul di sini</p>
        </div>
    @endif
</div>
@endsection
