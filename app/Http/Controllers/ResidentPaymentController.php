<?php

namespace App\Http\Controllers;

use App\Models\IuranWarga;
use App\Models\JenisIuran;
use Illuminate\Http\Request;

class ResidentPaymentController extends Controller
{
    private const MONTHS = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function index(Request $request)
    {
        $filters = $request->validate([
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'status' => ['nullable', 'in:lunas,belum_bayar'],
            'jenis' => ['nullable', 'integer', 'exists:jenis_iuran,id'],
        ]);

        $user = $request->user()->loadMissing('anggotaKeluarga.kartuKeluarga');
        $member = $user->anggotaKeluarga;
        $year = (int) ($filters['tahun'] ?? now()->year);
        $months = self::MONTHS;

        if (! $member) {
            return view('resident-payments.index', [
                'member' => null,
                'filters' => $filters,
                'year' => $year,
                'months' => $months,
            ]);
        }

        $baseQuery = IuranWarga::query()
            ->where('anggota_keluarga_id', $member->id);

        $availableYears = (clone $baseQuery)
            ->select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->map(fn ($value) => (int) $value)
            ->push(now()->year)
            ->push($year)
            ->unique()
            ->sortDesc()
            ->values();

        $types = JenisIuran::query()
            ->whereHas('iuranWarga', fn ($query) => $query->where('anggota_keluarga_id', $member->id))
            ->orderBy('nama')
            ->get(['id', 'nama']);

        $yearBills = (clone $baseQuery)
            ->where('tahun', $year)
            ->get(['bulan', 'nominal', 'status']);

        $monthlyOverview = collect(self::MONTHS)->map(function (string $name, int $month) use ($yearBills) {
            $bills = $yearBills->where('bulan', $month);
            $total = (float) $bills->sum('nominal');
            $paid = (float) $bills->where('status', 'lunas')->sum('nominal');

            return [
                'number' => $month,
                'name' => $name,
                'bill_count' => $bills->count(),
                'total' => $total,
                'paid' => $paid,
                'outstanding' => max(0, $total - $paid),
                'progress' => $total > 0 ? (int) round(($paid / $total) * 100) : 0,
                'complete' => $bills->isNotEmpty() && $paid >= $total,
            ];
        });

        $summary = [
            'total' => (float) $yearBills->sum('nominal'),
            'paid' => (float) $yearBills->where('status', 'lunas')->sum('nominal'),
            'outstanding' => (float) $yearBills->where('status', 'belum_bayar')->sum('nominal'),
            'paid_count' => $yearBills->where('status', 'lunas')->count(),
            'unpaid_count' => $yearBills->where('status', 'belum_bayar')->count(),
        ];
        $summary['progress'] = $summary['total'] > 0
            ? (int) round(($summary['paid'] / $summary['total']) * 100)
            : 0;

        $bills = (clone $baseQuery)
            ->with('jenisIuran:id,nama')
            ->where('tahun', $year)
            ->when($filters['bulan'] ?? null, fn ($query, $month) => $query->where('bulan', $month))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['jenis'] ?? null, fn ($query, $type) => $query->where('jenis_iuran_id', $type))
            ->orderByDesc('bulan')
            ->orderBy('jenis_iuran_id')
            ->paginate(12)
            ->withQueryString();

        return view('resident-payments.index', compact(
            'member',
            'filters',
            'year',
            'months',
            'availableYears',
            'types',
            'monthlyOverview',
            'summary',
            'bills',
        ));
    }
}
