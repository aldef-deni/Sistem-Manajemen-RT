@extends('layouts.app')

@section('title', 'Detail Penerima Bantuan')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;max-width:900px;">
    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#94a3b8;">
        <a href="{{ route('dashboard') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('bantuan-sosial.index') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Bantuan Sosial</a>
        <span>/</span>
        <span style="color:#1e293b;font-weight:600;">Detail</span>
    </div>

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3);">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <div>
                <h1 style="font-size:22px;font-weight:700;color:#1e293b;">Detail Penerima Bantuan</h1>
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('bantuan-sosial.edit', $penerima->id) }}" style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;color:white;background:linear-gradient(135deg,#0d9488,#0f766e);border:none;cursor:pointer;text-decoration:none;display:flex;align-items:center;gap:6px;">✏️ Edit</a>
            <a href="{{ route('bantuan-sosial.index') }}" style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;">← Kembali</a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
        {{-- Main Info --}}
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                    <span style="font-weight:600;color:#1e293b;">👤 Data Penerima</span>
                </div>
                <div style="padding:20px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div>
                            <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;margin-bottom:4px;">Nama Lengkap</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b;">{{ $penerima->anggota->nama_lengkap ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;margin-bottom:4px;">NIK</div>
                            <div style="font-size:15px;color:#1e293b;font-family:monospace;">{{ $penerima->nik ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;margin-bottom:4px;">No KK</div>
                            <div style="font-size:15px;color:#1e293b;font-family:monospace;">{{ $penerima->no_kk ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;margin-bottom:4px;">Tahun</div>
                            <div style="font-size:15px;font-weight:600;color:#1e293b;">{{ $penerima->tahun }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                    <span style="font-weight:600;color:#1e293b;">📋 Jenis Bantuan</span>
                </div>
                <div style="padding:20px;">
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        @foreach($penerima->jenis_bantuan ?? [] as $jb)
                            @php
                                $colors = [
                                    'BLT' => ['bg' => '#fef2f2', 'text' => '#dc2626', 'border' => '#fecaca', 'icon' => '💵'],
                                    'Sembako' => ['bg' => '#f0fdf4', 'text' => '#16a34a', 'border' => '#bbf7d0', 'icon' => '📦'],
                                    'PKH' => ['bg' => '#eff6ff', 'text' => '#2563eb', 'border' => '#bfdbfe', 'icon' => '🏠'],
                                    'BPNT' => ['bg' => '#fefce8', 'text' => '#ca8a04', 'border' => '#fef08a', 'icon' => '🍚'],
                                    'Lansia' => ['bg' => '#faf5ff', 'text' => '#9333ea', 'border' => '#e9d5ff', 'icon' => '👴'],
                                    'Lainnya' => ['bg' => '#f8fafc', 'text' => '#64748b', 'border' => '#e2e8f0', 'icon' => '⚪'],
                                ];
                                $c = $colors[$jb] ?? $colors['Lainnya'];
                            @endphp
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;font-size:13px;font-weight:600;background:{{ $c['bg'] }};color:{{ $c['text'] }};border:1px solid {{ $c['border'] }};">
                                {{ $c['icon'] }} {{ $jb }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($penerima->keterangan)
            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                    <span style="font-weight:600;color:#1e293b;">📝 Keterangan</span>
                </div>
                <div style="padding:20px;">
                    <p style="color:#475569;line-height:1.6;">{{ $penerima->keterangan }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
                    <span style="font-weight:600;color:#1e293b;">📊 Ringkasan</span>
                </div>
                <div style="padding:20px;display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:13px;color:#64748b;">Status</span>
                        @if($penerima->status === 'aktif')
                            <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;">✅ Aktif</span>
                        @else
                            <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">❌ Nonaktif</span>
                        @endif
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:13px;color:#64748b;">Jumlah Jenis</span>
                        <span style="font-size:13px;font-weight:600;color:#1e293b;">{{ count($penerima->jenis_bantuan ?? []) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:13px;color:#64748b;">Tahun</span>
                        <span style="font-size:13px;font-weight:600;color:#1e293b;">{{ $penerima->tahun }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:13px;color:#64748b;">Dibuat</span>
                        <span style="font-size:13px;color:#1e293b;">{{ $penerima->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
                    <span style="font-weight:600;color:#1e293b;">⚡ Aksi</span>
                </div>
                <div style="padding:16px 20px;display:flex;flex-direction:column;gap:8px;">
                    <a href="{{ route('bantuan-sosial.edit', $penerima->id) }}" style="display:flex;align-items:center;gap:8px;padding:10px;border-radius:8px;font-size:13px;font-weight:600;color:#2563eb;background:#eff6ff;border:1px solid #bfdbfe;text-decoration:none;">✏️ Edit Data</a>
                    <form method="POST" action="{{ route('bantuan-sosial.destroy', $penerima->id) }}" onsubmit="return confirm('Yakin hapus data ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="width:100%;display:flex;align-items:center;gap:8px;padding:10px;border-radius:8px;font-size:13px;font-weight:600;color:#dc2626;background:#fef2f2;border:1px solid #fecaca;cursor:pointer;">🗑️ Hapus Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
