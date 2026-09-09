<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan Subscribe</title>
    <style>
        @page { margin: 28px 30px; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #1e293b; font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        .header { padding-bottom: 14px; border-bottom: 3px solid #2563eb; }
        .brand { color: #1d4ed8; font-size: 18px; font-weight: bold; letter-spacing: .6px; }
        .title { margin-top: 3px; color: #0f172a; font-size: 22px; font-weight: bold; }
        .meta { margin-top: 6px; color: #64748b; }
        .summary { width: 100%; margin: 16px 0; border-spacing: 8px 0; }
        .summary td { width: 33.33%; padding: 12px 14px; border: 1px solid #dbeafe; border-radius: 7px; background: #eff6ff; }
        .summary .label { color: #64748b; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .summary .value { margin-top: 5px; color: #1e3a8a; font-size: 17px; font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { padding: 8px 7px; border: 1px solid #334155; background: #0f172a; color: #fff; font-size: 8px; text-align: left; text-transform: uppercase; }
        table.data td { padding: 7px; border: 1px solid #e2e8f0; vertical-align: top; }
        table.data tr:nth-child(even) td { background: #f8fafc; }
        .right { text-align: right; }
        .muted { color: #64748b; font-size: 8px; }
        .amount { color: #047857; font-weight: bold; white-space: nowrap; }
        .total-row td { padding: 10px 7px !important; background: #dcfce7 !important; color: #14532d; font-weight: bold; }
        .empty { padding: 30px !important; color: #94a3b8; text-align: center; }
        .footer { margin-top: 14px; padding-top: 8px; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">SISTEM MANAJEMEN RT</div>
        <div class="title">Laporan Pendapatan Subscribe</div>
        <div class="meta">Periode {{ $filters['label'] }} · Dicetak {{ $generatedAt->format('d/m/Y H:i') }} WIB</div>
    </div>

    <table class="summary">
        <tr>
            <td><div class="label">Total Pendapatan</div><div class="value">Rp {{ number_format($total, 0, ',', '.') }}</div></td>
            <td><div class="label">Transaksi Disetujui</div><div class="value">{{ number_format($payments->count(), 0, ',', '.') }}</div></td>
            <td><div class="label">Rata-rata Transaksi</div><div class="value">Rp {{ number_format($payments->count() ? round($total / $payments->count()) : 0, 0, ',', '.') }}</div></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:12%">Diverifikasi</th>
                <th style="width:18%">Pengguna</th>
                <th style="width:17%">Pengirim</th>
                <th style="width:11%">Tanggal Transfer</th>
                <th style="width:20%">Periode Aktif</th>
                <th style="width:18%" class="right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $payment->verified_at?->format('d/m/Y H:i') }}<div class="muted">oleh {{ $payment->verifier?->name ?? '-' }}</div></td>
                    <td><strong>{{ $payment->user?->name ?? 'Pengguna dihapus' }}</strong><div class="muted">{{ $payment->user?->username ?? '-' }} · {{ $payment->user?->role_label ?? '-' }}</div></td>
                    <td>{{ $payment->sender_bank }}<div class="muted">a.n. {{ $payment->sender_account_name }}</div></td>
                    <td>{{ $payment->paid_at?->format('d/m/Y') }}</td>
                    <td>{{ $payment->starts_at?->format('d/m/Y') ?? '-' }} - {{ $payment->ends_at?->format('d/m/Y') ?? '-' }}</td>
                    <td class="right amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">Tidak ada pendapatan pada periode ini.</td></tr>
            @endforelse
            <tr class="total-row"><td colspan="6" class="right">TOTAL PENDAPATAN</td><td class="right">Rp {{ number_format($total, 0, ',', '.') }}</td></tr>
        </tbody>
    </table>

    <div class="footer">Dokumen ini dihasilkan otomatis oleh Sistem Manajemen RT. Pendapatan hanya berasal dari pembayaran subscribe berstatus disetujui.</div>
</body>
</html>
