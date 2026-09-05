@extends('layouts.app')

@section('title', $kegiatan->judul)

@section('content')
<div>
    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('kegiatan-rt.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:white;border:1.5px solid #e2e8f0;border-radius:10px;color:#64748b;font-size:13px;font-weight:500;text-decoration:none">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">
        {{-- Main Content --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            {{-- Foto Utama --}}
            @if($kegiatan->foto_utama)
                <div style="border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                    <img src="{{ Storage::url($kegiatan->foto_utama) }}" style="width:100%;max-height:400px;object-fit:cover" alt="{{ $kegiatan->judul }}">
                </div>
            @endif

            {{-- Artikel --}}
            <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                @php
                    $badge = $kegiatan->kategori_badge;
                    $statusBadge = $kegiatan->status_badge;

                    // Rantai ternary bersarang tanpa kurung adalah kesalahan
                    // fatal sejak PHP 8, jadi pemetaan warnanya ditulis biasa.
                    $peta = [
                        'slate'  => ['#f1f5f9', '#475569'],
                        'purple' => ['#faf5ff', '#7c3aed'],
                        'green'  => ['#f0fdf4', '#16a34a'],
                        'red'    => ['#fef2f2', '#dc2626'],
                        'blue'   => ['#eff6ff', '#2563eb'],
                    ];

                    [$badgeBg, $badgeFg] = ['#fff7ed', '#ea580c'];

                    foreach ($peta as $warna => $pasangan) {
                        if (str_contains($badge['bg'] ?? '', $warna)) {
                            [$badgeBg, $badgeFg] = $pasangan;
                            break;
                        }
                    }
                @endphp
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
                    <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:{{ $badgeBg }};color:{{ $badgeFg }}">
                        {{ $kegiatan->kategori }}
                    </span>
                    <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;{{ $kegiatan->status === 'publish' ? 'background:#f0fdf4;color:#16a34a' : ($kegiatan->status === 'arsip' ? 'background:#f1f5f9;color:#64748b' : 'background:#fffbeb;color:#d97706') }}">
                        {{ $statusBadge['label'] }}
                    </span>
                </div>
                <h1 style="font-size:26px;font-weight:800;color:#1e293b;margin:0 0 16px;line-height:1.3">{{ $kegiatan->judul }}</h1>
                <div style="display:flex;align-items:center;gap:16px;font-size:13px;color:#64748b;margin-bottom:24px;flex-wrap:wrap">
                    <div style="display:flex;align-items:center;gap:6px">
                        <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#14b8a6,#0d9488);display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:700">
                            {{ substr($kegiatan->user->name ?? 'A', 0, 1) }}
                        </div>
                        <span>{{ $kegiatan->user->name ?? 'Admin' }}</span>
                    </div>
                    <span>•</span>
                    <div style="display:flex;align-items:center;gap:4px">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $kegiatan->tanggal_mulai->format('d M Y') }}
                        @if($kegiatan->tanggal_selesai)
                            — {{ $kegiatan->tanggal_selesai->format('d M Y') }}
                        @endif
                    </div>
                    <span>•</span>
                    <div style="display:flex;align-items:center;gap:4px">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ $kegiatan->dilihat }} views
                    </div>
                </div>
                <div style="font-size:15px;line-height:1.8;color:#334155">{!! $kegiatan->artikel !!}</div>
            </div>

            {{-- Galeri --}}
            @if($kegiatan->galeri_foto && count($kegiatan->galeri_foto) > 0)
                <div style="background:white;border-radius:16px;padding:22px;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                    <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin:0 0 16px">📷 Galeri Foto</h3>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px">
                        @foreach($kegiatan->galeri_foto as $foto)
                            <div style="border-radius:12px;overflow:hidden;aspect-ratio:1;box-shadow:0 2px 8px rgba(0,0,0,0.1)">
                                <img src="{{ Storage::url($foto) }}" style="width:100%;height:100%;object-fit:cover" alt="Galeri">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div style="display:flex;flex-direction:column;gap:20px">
            {{-- Info Publikasi --}}
            <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9">
                    <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0">📋 Info Publikasi</h3>
                </div>
                <div style="padding:16px 20px;display:flex;flex-direction:column;gap:14px">
                    <div>
                        <p style="font-size:11px;color:#94a3b8;margin:0;text-transform:uppercase;letter-spacing:0.5px">Status</p>
                        <p style="font-size:14px;font-weight:600;color:#1e293b;margin:4px 0 0">{{ $statusBadge['label'] }}</p>
                    </div>
                    <div>
                        <p style="font-size:11px;color:#94a3b8;margin:0;text-transform:uppercase;letter-spacing:0.5px">Kategori</p>
                        <p style="font-size:14px;font-weight:600;color:#1e293b;margin:4px 0 0">{{ $kegiatan->kategori }}</p>
                    </div>
                    <div>
                        <p style="font-size:11px;color:#94a3b8;margin:0;text-transform:uppercase;letter-spacing:0.5px">Lokasi</p>
                        <p style="font-size:14px;font-weight:600;color:#1e293b;margin:4px 0 0">{{ $kegiatan->lokasi ?? '-' }}</p>
                    </div>
                    <div>
                        <p style="font-size:11px;color:#94a3b8;margin:0;text-transform:uppercase;letter-spacing:0.5px">Dibuat Oleh</p>
                        <p style="font-size:14px;font-weight:600;color:#1e293b;margin:4px 0 0">{{ $kegiatan->user->name ?? 'Admin' }}</p>
                    </div>
                    <div>
                        <p style="font-size:11px;color:#94a3b8;margin:0;text-transform:uppercase;letter-spacing:0.5px">Dilihat</p>
                        <p style="font-size:14px;font-weight:600;color:#1e293b;margin:4px 0 0">{{ $kegiatan->dilihat }} kali</p>
                    </div>
                </div>
            </div>

            {{-- Aksi --}}
            <div style="background:white;border-radius:16px;padding:18px;box-shadow:0 1px 3px rgba(0,0,0,0.06);display:flex;flex-direction:column;gap:10px">
                <a href="{{ route('kegiatan-rt.edit', $kegiatan) }}" style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border-radius:10px;font-weight:600;font-size:14px;text-decoration:none">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Kegiatan
                </a>
                <form method="POST" action="{{ route('kegiatan-rt.destroy', $kegiatan) }}" onsubmit="return confirm('Yakin hapus kegiatan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;background:#fef2f2;color:#ef4444;border-radius:10px;font-weight:600;font-size:14px;cursor:pointer;border:1.5px solid #fecaca">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
