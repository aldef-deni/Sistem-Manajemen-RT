@extends('layouts.app')

@section('title', 'Detail Kunjungan')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;max-width:900px;">
    <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#94a3b8;">
        <a href="{{ route('dashboard') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('visitor.index') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">E-Visitor</a>
        <span>/</span>
        <span style="color:#1e293b;font-weight:600;">Detail</span>
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3);">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <h1 style="font-size:22px;font-weight:700;color:#1e293b;">Detail Kunjungan</h1>
        </div>
        <div style="display:flex;gap:8px;">
            @if($visitor->status !== 'checkout')
            <form method="POST" action="{{ route('visitor.checkout', $visitor->id) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;color:white;background:linear-gradient(135deg,#2563eb,#3b82f6);border:none;cursor:pointer;">✓ Check Out</button>
            </form>
            @endif
            <a href="{{ route('visitor.edit', $visitor->id) }}" style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;color:white;background:linear-gradient(135deg,#0d9488,#0f766e);border:none;text-decoration:none;">✏️ Edit</a>
            <a href="{{ route('visitor.index') }}" style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;">← Kembali</a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                    <span style="font-weight:600;color:#1e293b;">👤 Identitas Tamu</span>
                    <span style="padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;background:#f0fdfa;color:#0d9488;border:1px solid #99f6e4;">{{ $visitor->kode_kunjungan }}</span>
                </div>
                <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;">Nama</div><div style="font-weight:600;color:#1e293b;">{{ $visitor->nama_tamu }}</div></div>
                    <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;">NIK</div><div style="color:#1e293b;">{{ $visitor->nik ?? '-' }}</div></div>
                    <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;">No. HP</div><div style="color:#1e293b;">{{ $visitor->no_hp }}</div></div>
                    <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;">Email</div><div style="color:#1e293b;">{{ $visitor->email ?? '-' }}</div></div>
                </div>
            </div>
            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;"><span style="font-weight:600;color:#1e293b;">📍 Tujuan & Kepentingan</span></div>
                <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;">Tujuan</div><div style="font-weight:600;color:#1e293b;">{{ strtoupper($visitor->tujuan_blok) }}</div></div>
                    <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;">Nama Tujuan</div><div style="color:#1e293b;">{{ $visitor->nama_tujuan ?? '-' }}</div></div>
                    <div style="grid-column:span 2;"><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;">Kepentingan</div>
                        <div style="display:flex;gap:6px;margin-top:4px;">@foreach($visitor->kepentingan ?? [] as $k)<span style="padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;background:#f0fdfa;color:#0d9488;border:1px solid #99f6e4;">{{ $k }}</span>@endforeach</div>
                    </div>
                    <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;">Deskripsi</div><div style="color:#1e293b;">{{ strtoupper($visitor->deskripsi_kepentingan ?? '-') }}</div></div>
                    @if($visitor->catatan_tambahan)
                    <div style="grid-column:span 2;"><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;font-weight:600;">Catatan</div><div style="color:#1e293b;">{{ $visitor->catatan_tambahan }}</div></div>
                    @endif
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px;">
            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;"><span style="font-weight:600;color:#1e293b;">📊 Ringkasan</span></div>
                <div style="padding:20px;display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;justify-content:space-between;"><span style="font-size:13px;color:#64748b;">Status</span>
                        @if($visitor->status === 'checkin')<span style="padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;background:#f0fdf4;color:#16a34a;">✅ Check In</span>
                        @elseif($visitor->status === 'checkout')<span style="padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;background:#f8fafc;color:#64748b;">✓ Checkout</span>
                        @else<span style="padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;background:#fefce8;color:#ca8a04;">🌙 Menginap</span>@endif
                    </div>
                    <div style="display:flex;justify-content:space-between;"><span style="font-size:13px;color:#64748b;">Check In</span><span style="font-weight:600;color:#1e293b;">{{ $visitor->jam_checkin }}</span></div>
                    <div style="display:flex;justify-content:space-between;"><span style="font-size:13px;color:#64748b;">Check Out</span><span style="font-weight:600;color:#1e293b;">{{ $visitor->jam_checkout ?? '-' }}</span></div>
                    <div style="display:flex;justify-content:space-between;"><span style="font-size:13px;color:#64748b;">Durasi</span><span style="font-weight:600;color:#1e293b;">{{ $visitor->durasi ?? '-' }}</span></div>
                    <div style="display:flex;justify-content:space-between;"><span style="font-size:13px;color:#64748b;">Tanggal</span><span style="color:#1e293b;">{{ \Carbon\Carbon::parse($visitor->tanggal)->format('d M Y') }}</span></div>
                </div>
            </div>
            @if($visitor->no_plat)
            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;"><span style="font-weight:600;color:#1e293b;">🚗 Kendaraan</span></div>
                <div style="padding:20px;display:flex;flex-direction:column;gap:8px;">
                    <div style="display:inline-flex;align-items:center;gap:4px;padding:6px 14px;border-radius:8px;font-size:14px;font-weight:700;background:#f1f5f9;color:#1e293b;border:1px solid #e2e8f0;font-family:monospace;width:fit-content;">{{ $visitor->no_plat }}</div>
                    <div style="font-size:13px;color:#64748b;">{{ $visitor->jenis_kendaraan ?? '-' }}</div>
                </div>
            </div>
            @endif
            @if($visitor->foto_dokumentasi)
            <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;"><span style="font-weight:600;color:#1e293b;">📸 Foto</span></div>
                <div style="padding:16px;">
                    <img src="{{ Storage::url($visitor->foto_dokumentasi) }}" style="width:100%;border-radius:8px;object-fit:cover;max-height:200px;" alt="Foto dokumentasi">
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
