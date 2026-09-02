@extends('layouts.app')

@section('title', 'Struktur Organisasi RT')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Struktur Organisasi RT</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Struktur Organisasi RT</h1>
        </div>
        <a href="{{ route('pengaturan.kelola-pengurus') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl text-sm font-semibold shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Kelola Pengurus
        </a>
    </div>

    {{-- Header Card --}}
    <div style="background:linear-gradient(135deg,#0d9488 0%,#115e59 100%);border-radius:16px;padding:24px;color:white;position:relative;overflow:hidden">
        <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.05)"></div>
        <div style="position:absolute;bottom:-60px;left:-30px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,0.03)"></div>

        <div style="display:flex;align-items:center;gap:24px;position:relative;z-index:1">
            {{-- Logo --}}
            <div style="width:90px;height:90px;border-radius:16px;background:rgba(255,255,255,0.15);border:3px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                @if($struktur && $struktur->logo_rt)
                    <img src="{{ asset($struktur->logo_rt) }}" alt="Logo RT" style="width:100%;height:100%;object-fit:contain;border-radius:12px">
                @else
                    <svg style="width:40px;height:40px;color:rgba(255,255,255,0.8)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                @endif
            </div>
            {{-- Info --}}
            <div style="flex:1">
                <h2 style="font-size:22px;font-weight:800;margin:0 0 6px 0">RT 005 / RW 003</h2>
                <div style="display:flex;gap:20px;font-size:13px;opacity:0.85;flex-wrap:wrap">
                    <span style="display:flex;align-items:center;gap:5px">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                        Jl. Merdeka No. 10, RT 005
                    </span>
                    <span style="display:flex;align-items:center;gap:5px">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Kel. Sukamaju, Kec. Cilandak
                    </span>
                    <span style="display:flex;align-items:center;gap:5px">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        (021) 1234-5678
                    </span>
                    <span style="display:flex;align-items:center;gap:5px">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Jakarta Selatan
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
        <div class="space-y-6">
            {{-- Pengurus Inti RT --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14z"/></svg>
                    </div>
                    <span style="font-weight:700;color:#1e293b;font-size:16px">Pengurus Inti RT</span>
                </div>
                <div style="padding:20px">
                    @if($struktur && $struktur->pengurusAktif->count() > 0)
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
                            @foreach($struktur->pengurusAktif->take(6) as $p)
                                <div style="text-align:center;padding:16px 12px;border-radius:12px;background:#f8fafc;border:1px solid #f1f5f9;transition:all 0.2s" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                                    {{-- Foto --}}
                                    <div style="width:64px;height:64px;border-radius:50%;margin:0 auto 10px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;overflow:hidden">
                                        @if($p->foto)
                                            <img src="{{ asset($p->foto) }}" alt="{{ $p->nama }}" style="width:100%;height:100%;object-fit:cover">
                                        @else
                                            <span style="color:#fff;font-size:18px;font-weight:700">{{ $p->initial }}</span>
                                        @endif
                                    </div>
                                    <div style="font-weight:700;color:#1e293b;font-size:14px;margin-bottom:2px">{{ $p->nama }}</div>
                                    <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:0.5px">{{ $p->jabatan }}</div>
                                    @if($p->telepon)
                                        <div style="font-size:11px;color:#94a3b8;margin-top:4px">📱 {{ $p->telepon }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align:center;padding:30px;color:#94a3b8">
                            <svg style="width:48px;height:48px;margin:0 auto 12px;color:#cbd5e1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14z"/></svg>
                            <p style="font-size:14px">Belum ada data pengurus</p>
                            <a href="{{ route('pengaturan.kelola-pengurus') }}" style="display:inline-block;margin-top:8px;font-size:13px;color:#10b981;font-weight:600;text-decoration:none">+ Tambah Pengurus</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Rukun Tetangga --}}
            @if($struktur && $struktur->pengurusAktif->count() > 3)
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span style="font-weight:700;color:#1e293b;font-size:16px">Rukun Tetangga</span>
                </div>
                <div style="padding:20px">
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px">
                        @foreach($struktur->pengurusAktif->skip(3)->take(6) as $p)
                            <div style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;background:#f8fafc;border:1px solid #f1f5f9">
                                <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden">
                                    @if($p->foto)
                                        <img src="{{ asset($p->foto) }}" alt="{{ $p->nama }}" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        <span style="color:#fff;font-size:14px;font-weight:700">{{ $p->initial }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600;color:#1e293b;font-size:13px">{{ $p->nama }}</div>
                                    <div style="font-size:11px;color:#64748b">{{ $p->jabatan }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Peraturan & Tata Tertib --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span style="font-weight:700;color:#1e293b;font-size:15px">Peraturan & Tata Tertib</span>
                </div>
                <div style="padding:20px">
                    @if($struktur && $struktur->peraturan)
                        <div style="font-size:13px;color:#475569;line-height:1.7">{!! nl2br(e(Str::limit($struktur->peraturan, 500))) !!}</div>
                        @if(strlen($struktur->peraturan) > 500)
                            <a href="{{ route('pengaturan.tata-tertib') }}" style="display:inline-block;margin-top:8px;font-size:13px;color:#10b981;font-weight:600;text-decoration:none">Baca selengkapnya →</a>
                        @endif
                    @else
                        <div style="font-size:13px;color:#94a3b8;text-align:center;padding:20px">
                            <p>Belum ada peraturan</p>
                            <a href="{{ route('pengaturan.tata-tertib') }}" style="display:inline-block;margin-top:8px;color:#10b981;font-weight:600;text-decoration:none">Buat Peraturan</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Visi & Misi --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span style="font-weight:700;color:#1e293b;font-size:15px">Visi & Misi</span>
                </div>
                <div style="padding:20px">
                    @if($struktur && $struktur->visi)
                        <div style="margin-bottom:12px">
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#10b981;font-weight:700;margin-bottom:4px">Visi</div>
                            <div style="font-size:13px;color:#475569;line-height:1.6">{{ $struktur->visi }}</div>
                        </div>
                    @endif
                    @if($struktur && $struktur->misi)
                        <div>
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#3b82f6;font-weight:700;margin-bottom:4px">Misi</div>
                            <div style="font-size:13px;color:#475569;line-height:1.6">{!! nl2br(e($struktur->misi)) !!}</div>
                        </div>
                    @endif
                    @if(!$struktur || (!$struktur->visi && !$struktur->misi))
                        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px">Belum ada visi & misi</div>
                    @endif
                </div>
            </div>

            {{-- Quick Info --}}
            <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #bbf7d0;border-radius:16px;padding:20px">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
                    <div style="width:28px;height:28px;border-radius:50%;background:#10b981;display:flex;align-items:center;justify-content:center">
                        <svg style="width:14px;height:14px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span style="font-weight:700;color:#166534;font-size:14px">RT 005 / RW 003</span>
                </div>
                <div style="font-size:12px;color:#166534;line-height:1.8">
                    <div>📍 Kel. Sukamaju, Kec. Cilandak</div>
                    <div>🏙️ Jakarta Selatan</div>
                    <div>📮 12345</div>
                    <div>📧 rt005@example.com</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
