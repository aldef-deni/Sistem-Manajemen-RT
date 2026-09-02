@extends('layouts.app')

@section('title', 'Kegiatan RT')

@section('content')
<div style="space-y-6">
    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <div style="display:flex;align-items:center;gap:12px">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#14b8a6,#0d9488);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(20,184,166,0.3)">
                <svg style="width:22px;height:22px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <div>
                <h1 style="font-size:22px;font-weight:700;color:#1e293b;margin:0">Kegiatan</h1>
                <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#64748b;margin-top:2px">
                    <a href="{{ route('dashboard') }}" style="color:#14b8a6;text-decoration:none;font-weight:500">Dashboard</a>
                    <span>/</span>
                    <span style="color:#475569;font-weight:500">Kegiatan</span>
                </div>
            </div>
        </div>
        <a href="{{ route('kegiatan-rt.create') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border-radius:10px;font-weight:600;font-size:14px;text-decoration:none;box-shadow:0 4px 12px rgba(20,184,166,0.3);transition:all 0.2s" onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 16px rgba(20,184,166,0.4)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 12px rgba(20,184,166,0.3)'">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Kegiatan
        </a>
    </div>

    {{-- Filter --}}
    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,0.06);margin-bottom:24px">
        <form method="GET" action="{{ route('kegiatan-rt.index') }}" style="display:grid;grid-template-columns:1fr 2fr 1fr 1fr auto;gap:16px;align-items:end">
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px">Kategori</label>
                <select name="kategori" style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:white;outline:none">
                    <option value="">Semua Kategori</option>
                    @foreach(['Umum','Keagamaan','Kebersihan','Keamanan','Olahraga','Sosial','Lainnya'] as $k)
                        <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px">Cari Kegiatan</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px">Bulan</label>
                <select name="bulan" style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:white;outline:none">
                    <option value="">Semua</option>
                    @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $name)
                        <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px">Tahun</label>
                <select name="tahun" style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:white;outline:none">
                    <option value="">Semua</option>
                    @for($y = date('Y'); $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div style="display:flex;gap:8px">
                <button type="submit" style="padding:10px 18px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border:none;border-radius:10px;cursor:pointer;font-weight:600;display:flex;align-items:center;gap:6px">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['kategori','bulan','tahun','search']))
                    <a href="{{ route('kegiatan-rt.index') }}" style="padding:10px 14px;background:#f1f5f9;color:#64748b;border:none;border-radius:10px;cursor:pointer;font-weight:500;text-decoration:none;display:flex;align-items:center;gap:4px">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Kegiatan List --}}
    @if($kegiatans->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:20px">
            @foreach($kegiatans as $k)
                @php $badge = $k->kategori_badge; $statusBadge = $k->status_badge; @endphp
                <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06);transition:all 0.2s" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'">
                    {{-- Foto --}}
                    <div style="height:200px;background:linear-gradient(135deg,#f0f9ff,#e0f2fe);position:relative;overflow:hidden">
                        @if($k->foto_utama)
                            <img src="{{ Storage::url($k->foto_utama) }}" style="width:100%;height:100%;object-fit:cover" alt="{{ $k->judul }}">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                                <svg style="width:60px;height:60px;color:#93c5fd" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div style="position:absolute;top:12px;left:12px;display:flex;gap:6px">
                            <span style="padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;backdrop-filter:blur(8px);{{ str_replace(['bg-','text-'], ['background:rgba(','color:'], $badge['bg']) }}">
                                {{ $k->kategori }}
                            </span>
                        </div>
                        @if($k->status === 'draft')
                            <div style="position:absolute;top:12px;right:12px">
                                <span style="padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:rgba(245,158,11,0.9);color:white;backdrop-filter:blur(8px)">
                                    Draft
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div style="padding:18px">
                        <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 8px;line-height:1.4">{{ $k->judul }}</h3>
                        <p style="font-size:13px;color:#64748b;margin:0 0 14px;line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                            {{ strip_tags($k->artikel) }}
                        </p>

                        <div style="display:flex;flex-wrap:wrap;gap:12px;font-size:12px;color:#64748b;margin-bottom:14px">
                            <div style="display:flex;align-items:center;gap:4px">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $k->tanggal_mulai->format('d M Y') }}
                            </div>
                            <div style="display:flex;align-items:center;gap:4px">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ $k->dilihat }} views
                            </div>
                            @if($k->lokasi)
                                <div style="display:flex;align-items:center;gap:4px">
                                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $k->lokasi }}
                                </div>
                            @endif
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between;padding-top:14px;border-top:1px solid #f1f5f9">
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#14b8a6,#0d9488);display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:700">
                                    {{ substr($k->user->name ?? 'A', 0, 1) }}
                                </div>
                                <span style="font-size:12px;color:#64748b">{{ $k->user->name ?? 'Admin' }}</span>
                            </div>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('kegiatan-rt.show', $k) }}" style="padding:6px 12px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.2s">
                                    Lihat →
                                </a>
                                <a href="{{ route('kegiatan-rt.edit', $k) }}" style="padding:6px 10px;background:#f1f5f9;color:#64748b;border-radius:8px;font-size:12px;text-decoration:none;transition:all 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('kegiatan-rt.destroy', $k) }}" onsubmit="return confirm('Yakin hapus kegiatan ini?')" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:6px 10px;background:#fef2f2;color:#ef4444;border-radius:8px;font-size:12px;cursor:pointer;border:none;transition:all 0.2s" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;display:flex;justify-content:center">
            {{ $kegiatans->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div style="background:white;border-radius:16px;padding:60px 20px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div style="width:80px;height:80px;border-radius:50%;background:#eff6ff;margin:0 auto 20px;display:flex;align-items:center;justify-content:center">
                <svg style="width:40px;height:40px;color:#60a5fa" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <h3 style="font-size:18px;font-weight:700;color:#1e293b;margin:0 0 8px">Belum ada kegiatan</h3>
            <p style="font-size:14px;color:#64748b;margin:0 0 24px">Kegiatan yang ditambahkan akan muncul di sini</p>
            <a href="{{ route('kegiatan-rt.create') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border-radius:10px;font-weight:600;font-size:14px;text-decoration:none;box-shadow:0 4px 12px rgba(20,184,166,0.3)">
                <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Kegiatan
            </a>
        </div>
    @endif
</div>
@endsection
