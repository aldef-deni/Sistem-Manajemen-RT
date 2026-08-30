@extends('layouts.app')

@section('title', 'Detail Tabungan')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    {{-- Breadcrumb --}}
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('tabungan.index') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; font-size: 0.875rem; text-decoration: none;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Tabungan
        </a>
    </div>

    {{-- Header Card --}}
    <div style="background: linear-gradient(135deg, #14b8a6, #0d9488); border-radius: 1rem; padding: 1.5rem; color: white; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700;">
                    {{ strtoupper(substr($tabungan->anggota->nama_lengkap ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 700;">{{ $tabungan->anggota->nama_lengkap ?? '-' }}</h2>
                    <p style="font-size: 0.8125rem; opacity: 0.85;">{{ $tabungan->no_rekening }} — {{ ucfirst($tabungan->jenis_tabungan) }}</p>
                </div>
            </div>
            <div style="text-align: right;">
                <p style="font-size: 0.8125rem; opacity: 0.85;">Saldo Saat Ini</p>
                <p style="font-size: 1.75rem; font-weight: 700;">Rp {{ number_format($tabungan->saldo, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem;">
            <p style="font-size: 0.75rem; font-weight: 600; color: #64748b;">No HP</p>
            <p style="font-size: 0.9375rem; font-weight: 600; color: #1e293b; margin-top: 0.25rem;">{{ $tabungan->anggota->no_hp ?? '-' }}</p>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem;">
            <p style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Jenis Tabungan</p>
            <p style="font-size: 0.9375rem; font-weight: 600; color: #1e293b; margin-top: 0.25rem;">{{ ucfirst($tabungan->jenis_tabungan) }}</p>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem;">
            <p style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Status</p>
            <p style="margin-top: 0.25rem;">
                @if($tabungan->status === 'aktif')
                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #ecfdf5; color: #047857; border-radius: 9999px;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span> Aktif
                </span>
                @else
                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #64748b; border-radius: 9999px;">{{ ucfirst($tabungan->status) }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Riwayat Transaksi --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; overflow: hidden;">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;">
            <h3 style="font-weight: 700; color: #1e293b; font-size: 0.9375rem;">Riwayat Transaksi</h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">No</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Tanggal</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Jenis</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Nominal</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Saldo</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Keterangan</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tabungan->transaksi as $idx => $trx)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #64748b;">{{ $idx + 1 }}</td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #334155; white-space: nowrap;">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            @if($trx->jenis === 'setoran')
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #ecfdf5; color: #047857; border-radius: 9999px;">
                                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                Setoran
                            </span>
                            @else
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #fef2f2; color: #b91c1c; border-radius: 9999px;">
                                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                Penarikan
                            </span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; font-weight: 600; @if($trx->jenis === 'setoran') color: #059669; @else color: #dc2626; @endif">
                            @if($trx->jenis === 'setoran')+@else-@endif Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; font-weight: 600; color: #1e293b;">Rp {{ number_format($trx->saldo_sesudah, 0, ',', '.') }}</td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #64748b; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $trx->keterangan ?? '-' }}</td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #475569;">{{ $trx->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 3rem 1rem; text-align: center; color: #94a3b8; font-size: 0.875rem;">
                            Belum ada transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
