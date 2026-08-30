@extends('layouts.app')

@section('title', 'Manajemen Arisan RT')

@section('content')
<div style="space-y: 1.25rem;">
    {{-- Header --}}
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: linear-gradient(135deg, #14b8a6, #0d9488); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            </div>
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Manajemen Arisan RT</h1>
                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #64748b;">
                    <a href="{{ route('dashboard') }}" style="color: #0d9488; font-weight: 500; text-decoration: none;">Dashboard</a>
                    <span>/</span>
                    <span style="color: #334155; font-weight: 500;">Arisan</span>
                </div>
            </div>
        </div>
        <a href="{{ route('arisan.create') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; text-decoration: none; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Arisan Baru
        </a>
    </div>

    @if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem;">{{ session('success') }}</div>
    @endif

    {{-- Stat Cards --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
        <div style="background: linear-gradient(135deg, #14b8a6, #0d9488); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(20,184,166,0.3);">
            <p style="font-size: 0.6875rem; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.05em;">Total Arisan</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $totalArisan }}</p>
            <p style="font-size: 0.6875rem; opacity: 0.7; margin-top: 0.25rem;">Seluruh arisan terdaftar</p>
            <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); opacity: 0.2; font-size: 3rem;">🎁</div>
        </div>
        <div style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(16,185,129,0.3);">
            <p style="font-size: 0.6875rem; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.05em;">Arisan Aktif</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $arisanAktif }}</p>
            <p style="font-size: 0.6875rem; opacity: 0.7; margin-top: 0.25rem;">Sedang berjalan</p>
            <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); opacity: 0.2; font-size: 3rem;">📈</div>
        </div>
        <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(249,115,22,0.3);">
            <p style="font-size: 0.6875rem; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.05em;">Total Peserta</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $totalPeserta }}</p>
            <p style="font-size: 0.6875rem; opacity: 0.7; margin-top: 0.25rem;">Dari semua arisan</p>
            <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); opacity: 0.2; font-size: 3rem;">👥</div>
        </div>
    </div>

    {{-- Table --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; overflow: hidden;">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="font-weight: 700; color: #1e293b; font-size: 0.9375rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 18px; height: 18px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Daftar Arisan
            </h3>
            <form method="GET" action="{{ route('arisan.index') }}" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." style="padding: 0.375rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; width: 180px;">
                <button type="submit" style="padding: 0.375rem 0.75rem; background: #14b8a6; color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; cursor: pointer;">Cari</button>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">No</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Nama Arisan</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Nominal Iuran</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Periode</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Tgl Mulai</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Peserta</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Status</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arisan as $a)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #64748b;">{{ $arisan->firstItem() + $loop->index }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            <span style="font-size: 0.875rem; font-weight: 700; color: #1e293b; text-transform: uppercase;">{{ $a->nama }}</span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; font-weight: 600; color: #1e293b;">Rp {{ number_format($a->nominal_iuran, 0, ',', '.') }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <span style="display: inline-block; padding: 3px 10px; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; @if($a->periode === 'mingguan') background: #dbeafe; color: #1d4ed8; @else background: #fed7aa; color: #c2410c; @endif">
                                {{ ucfirst($a->periode) }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: center; font-size: 0.875rem; color: #475569; white-space: nowrap;">{{ $a->tanggal_mulai->format('d M Y') }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #475569; border-radius: 9999px;">
                                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $a->peserta_count }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            @if($a->status === 'aktif')
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #ecfdf5; color: #047857; border-radius: 9999px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span> Aktif
                            </span>
                            @elseif($a->status === 'selesai')
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #dbeafe; color: #1d4ed8; border-radius: 9999px;">Selesai</span>
                            @else
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #fef2f2; color: #b91c1c; border-radius: 9999px;">Dibatalkan</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <div style="display: inline-flex; gap: 0.25rem;">
                                <a href="{{ route('arisan.show', $a) }}" style="padding: 6px; color: #14b8a6; background: none; border: none; border-radius: 0.5rem; cursor: pointer;" title="Peserta">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('arisan.edit', $a) }}" style="padding: 6px; color: #f97316; background: none; border: none; border-radius: 0.5rem; cursor: pointer;" title="Edit">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('arisan.destroy', $a) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus arisan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 6px; color: #ef4444; background: none; border: none; border-radius: 0.5rem; cursor: pointer;" title="Hapus">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 3rem 1rem; text-align: center; color: #94a3b8; font-size: 0.875rem;">Belum ada arisan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($arisan->hasPages())
        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
            <p style="font-size: 0.8125rem; color: #64748b;">Menampilkan {{ $arisan->firstItem() }} sampai {{ $arisan->lastItem() }} dari {{ $arisan->total() }} entrì</p>
            {{ $arisan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
