@extends('layouts.app')

@section('title', 'Manajemen Pinjaman')

@section('content')
<div style="space-y: 1.25rem;">
    {{-- Header --}}
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: linear-gradient(135deg, #14b8a6, #0d9488); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1\"/></svg>
            </div>
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Dashboard Pinjaman</h1>
                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #64748b;">
                    <a href="{{ route('dashboard') }}" style="color: #0d9488; font-weight: 500; text-decoration: none;">Dashboard</a>
                    <span>/</span>
                    <span style="color: #334155; font-weight: 500;">Pinjaman</span>
                </div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <a href="{{ route('pinjaman.jenis') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border: 2px solid #14b8a6; color: #0d9488; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; background: white; text-decoration: none;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Jenis Pinjaman
            </a>
            <a href="{{ route('pinjaman.ajukan') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; text-decoration: none; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajukan Pinjaman
            </a>
        </div>
    </div>

    @if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem;">{{ session('success') }}</div>
    @endif

    {{-- Stat Cards --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
        <div style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(16,185,129,0.3);">
            <p style="font-size: 0.6875rem; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.05em;">Total Piutang</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</p>
            <p style="font-size: 0.6875rem; opacity: 0.7; margin-top: 0.25rem;">Seluruh pinjaman berjalan</p>
        </div>
        <div style="background: linear-gradient(135deg, #14b8a6, #0d9488); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(20,184,166,0.3);">
            <p style="font-size: 0.6875rem; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.05em;">Pinjaman Aktif</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $pinjamanAktif }}</p>
            <p style="font-size: 0.6875rem; opacity: 0.7; margin-top: 0.25rem;">Pinjaman sedang berjalan</p>
        </div>
        <div style="background: linear-gradient(135deg, #f97316, #ea580c); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(249,115,22,0.3);">
            <p style="font-size: 0.6875rem; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.05em;">Pengajuan Pending</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $pengajuanPending }}</p>
            <p style="font-size: 0.6875rem; opacity: 0.7; margin-top: 0.25rem;">Menunggu persetujuan</p>
        </div>
        <div style="background: linear-gradient(135deg, #eab308, #ca8a04); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(234,179,8,0.3);">
            <p style="font-size: 0.6875rem; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.05em;">Bayar Online Pending</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $bayarOnlinePending }}</p>
            <p style="font-size: 0.6875rem; opacity: 0.7; margin-top: 0.25rem;">Tidak ada pending</p>
        </div>
    </div>

    {{-- Jadwal Angsuran + Pinjaman Bermasalah --}}
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; overflow: hidden;">
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                <h3 style="font-weight: 700; color: #1e293b; font-size: 0.9375rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 18px; height: 18px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Jadwal Angsuran Hari Ini
                </h3>
                <span style="padding: 4px 10px; background: #f0fdfa; color: #0d9488; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">{{ now()->format('d M Y') }}</span>
            </div>
            <div style="padding: 2.5rem; text-align: center;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: #ecfdf5; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                    <svg style="width: 28px; height: 28px; color: #10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p style="font-size: 0.875rem; color: #94a3b8;">Tidak ada angsuran jatuh tempo hari ini</p>
            </div>
        </div>

        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; overflow: hidden;">
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;">
                <h3 style="font-weight: 700; color: #1e293b; font-size: 0.9375rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 18px; height: 18px; color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    Pinjaman Bermasalah
                </h3>
            </div>
            <div style="padding: 2.5rem; text-align: center;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: #f0fdfa; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                    <svg style="width: 28px; height: 28px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <p style="font-size: 0.875rem; color: #94a3b8;">Semua pinjaman dalam kondisi baik</p>
            </div>
        </div>
    </div>

    {{-- Menu Cepat --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.25rem;">
        <h3 style="font-weight: 700; color: #1e293b; font-size: 0.9375rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 18px; height: 18px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Menu Cepat
        </h3>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
            <a href="{{ route('pinjaman.index') }}" style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem; padding: 1.5rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; text-decoration: none; color: #475569; transition: all 0.15s; text-align: center;">
                <div style="width: 48px; height: 48px; border-radius: 0.75rem; background: #f0fdfa; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 24px; height: 24px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span style="font-size: 0.875rem; font-weight: 600;">Data Pengajuan</span>
            </a>
            <a href="{{ route('pinjaman.index') }}" style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem; padding: 1.5rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; text-decoration: none; color: #475569; text-align: center;">
                <div style="width: 48px; height: 48px; border-radius: 0.75rem; background: #f0fdfa; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 24px; height: 24px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1\"/></svg>
                </div>
                <span style="font-size: 0.875rem; font-weight: 600;">Pinjaman Aktif</span>
            </a>
            <a href="{{ route('pinjaman.index') }}" style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem; padding: 1.5rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; text-decoration: none; color: #475569; text-align: center;">
                <div style="width: 48px; height: 48px; border-radius: 0.75rem; background: #f0fdfa; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 24px; height: 24px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span style="font-size: 0.875rem; font-weight: 600;">Laporan</span>
            </a>
            <a href="{{ route('pinjaman.index') }}" style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem; padding: 1.5rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; text-decoration: none; color: #475569; text-align: center;">
                <div style="width: 48px; height: 48px; border-radius: 0.75rem; background: #fef3c7; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 24px; height: 24px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span style="font-size: 0.875rem; font-weight: 600;">Verifikasi Bayar</span>
            </a>
        </div>
    </div>
</div>
@endsection
