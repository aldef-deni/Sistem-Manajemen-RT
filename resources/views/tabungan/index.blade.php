@extends('layouts.app')

@section('title', 'Data Tabungan Warga')

@section('content')
<div style="space-y: 1.25rem;">
    {{-- Header --}}
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: linear-gradient(135deg, #14b8a6, #0d9488); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Data Tabungan Warga</h1>
                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #64748b;">
                    <a href="{{ route('dashboard') }}" style="color: #0d9488; font-weight: 500; text-decoration: none;">Dashboard</a>
                    <span>/</span>
                    <span style="color: #334155; font-weight: 500;">Tabungan Warga</span>
                </div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <a href="#" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border: 2px solid #14b8a6; color: #0d9488; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; background: white; text-decoration: none;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Verifikasi
            </a>
            <a href="{{ route('tabungan.setoran') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border: 2px solid #14b8a6; color: #0d9488; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; background: white; text-decoration: none;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Setoran
            </a>
            <a href="{{ route('tabungan.penarikan') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; text-decoration: none; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Penarikan
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stat Cards --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
        <div style="background: white; border: 1px solid #e2e8f0; border-left: 4px solid #14b8a6; border-radius: 0.75rem; padding: 1.25rem;">
            <p style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Saldo</p>
            <p style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-top: 0.25rem;">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Seluruh tabungan</p>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-left: 4px solid #3b82f6; border-radius: 0.75rem; padding: 1.25rem;">
            <p style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Rekening</p>
            <p style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-top: 0.25rem;">{{ $totalRekening }}</p>
            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Rekening terdaftar</p>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-left: 4px solid #10b981; border-radius: 0.75rem; padding: 1.25rem;">
            <p style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Rekening Aktif</p>
            <p style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-top: 0.25rem;">{{ $rekeningAktif }}</p>
            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Status aktif</p>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-left: 4px solid #8b5cf6; border-radius: 0.75rem; padding: 1.25rem;">
            <p style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Warga</p>
            <p style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-top: 0.25rem;">{{ $totalWarga }}</p>
            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Warga menabung</p>
        </div>
    </div>

    {{-- Filter --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem 1.25rem;">
        <form method="GET" action="{{ route('tabungan.index') }}" style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.375rem;">Jenis Tabungan</label>
                <select name="jenis" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white;">
                    <option value="">Semua Jenis</option>
                    <option value="sukarela" {{ request('jenis') == 'sukarela' ? 'selected' : '' }}>Sukarela</option>
                    <option value="wajib" {{ request('jenis') == 'wajib' ? 'selected' : '' }}>Wajib</option>
                    <option value="investasi" {{ request('jenis') == 'investasi' ? 'selected' : '' }}>Investasi</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.375rem;">Status</label>
                <select name="status" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; background: white;">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="blokir" {{ request('status') == 'blokir' ? 'selected' : '' }}>Blokir</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.375rem;">Cari Warga</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama warga..." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" style="padding: 0.5rem 1rem; background: #14b8a6; color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; white-space: nowrap;">
                Filter
            </button>
            <a href="{{ route('tabungan.index') }}" style="padding: 0.5rem 1rem; background: white; color: #64748b; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; white-space: nowrap; display: inline-flex; align-items: center; gap: 4px;">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset
            </a>
        </form>
    </div>

    {{-- Table --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; overflow: hidden;">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;">
            <h3 style="font-weight: 700; color: #1e293b; font-size: 0.9375rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 18px; height: 18px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Semua Data Tabungan
            </h3>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">No</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Nama Warga</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">No HP</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Jenis Tabungan</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Saldo</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tabungan as $t)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #64748b;">{{ $tabungan->firstItem() + $loop->index }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.625rem;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #14b8a6, #0d9488); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.8125rem; flex-shrink: 0;">
                                    {{ strtoupper(substr($t->anggota->nama_lengkap ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">{{ $t->anggota->nama_lengkap ?? '-' }}</p>
                                    <p style="font-size: 0.75rem; color: #94a3b8;">{{ $t->no_rekening }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #475569;">{{ $t->anggota->no_hp ?? '-' }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            <span style="display: inline-block; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; @if($t->jenis_tabungan === 'sukarela') background: #d1fae5; color: #047857; @elseif($t->jenis_tabungan === 'wajib') background: #dbeafe; color: #1d4ed8; @else background: #fef3c7; color: #92400e; @endif">
                                {{ ucfirst($t->jenis_tabungan) }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; font-weight: 700; color: #1e293b;">Rp {{ number_format($t->saldo, 0, ',', '.') }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            @if($t->status === 'aktif')
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #ecfdf5; color: #047857; border-radius: 9999px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span>
                                Aktif
                            </span>
                            @elseif($t->status === 'nonaktif')
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #64748b; border-radius: 9999px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #94a3b8;"></span>
                                Nonaktif
                            </span>
                            @else
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #fef2f2; color: #b91c1c; border-radius: 9999px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444;"></span>
                                Blokir
                            </span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <a href="{{ route('tabungan.show', $t) }}" style="padding: 6px; color: #14b8a6; background: none; border: none; border-radius: 0.5rem; cursor: pointer; display: inline-flex;" title="Detail">
                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 3rem 1rem; text-align: center;">
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <div style="width: 64px; height: 64px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin-bottom: 0.75rem;">
                                    <svg style="width: 32px; height: 32px; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <p style="font-size: 0.875rem; font-weight: 600; color: #64748b;">Belum ada data tabungan</p>
                                <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Mulai setor tabungan untuk warga</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tabungan->hasPages())
        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #f1f5f9;">
            {{ $tabungan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
