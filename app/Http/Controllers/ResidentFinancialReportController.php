<?php

namespace App\Http\Controllers;

use App\Models\ArisanIuran;
use App\Models\IuranWarga;
use App\Models\Pinjaman;
use App\Models\Tabungan;
use App\Models\TabunganTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ResidentFinancialReportController extends Controller
{
    private const CATEGORIES = [
        'iuran' => 'Iuran RT',
        'tabungan' => 'Tabungan',
        'arisan' => 'Arisan',
        'pinjaman' => 'Pinjaman',
    ];

    public function index(Request $request)
    {
        $filters = $request->validate([
            'periode' => ['nullable', 'in:bulan,tahun,rentang,semua'],
            'bulan' => ['nullable', 'date_format:Y-m'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'kategori' => ['nullable', 'in:iuran,tabungan,arisan,pinjaman'],
        ]);

        $user = $request->user()->loadMissing('anggotaKeluarga.kartuKeluarga');
        $member = $user->anggotaKeluarga;
        $filters = $this->normalizedFilters($filters);
        $categories = self::CATEGORIES;

        if (! $member) {
            return view('resident-finance.index', [
                'member' => null,
                'filters' => $filters,
                'categories' => $categories,
            ]);
        }

        $allEntries = $this->entriesFor((int) $member->id);
        $entries = $this->applyFilters($allEntries, $filters);

        $summaryEntries = $entries->where('counted', true);
        $summary = [
            'in' => (float) $summaryEntries->where('direction', 'in')->sum('amount'),
            'out' => (float) $summaryEntries->where('direction', 'out')->sum('amount'),
            'count' => $entries->count(),
            'savings' => (float) Tabungan::where('anggota_keluarga_id', $member->id)
                ->where('status', 'aktif')
                ->sum('saldo'),
            'loan_remaining' => (float) Pinjaman::where('anggota_keluarga_id', $member->id)
                ->whereIn('status', ['aktif', 'macet'])
                ->sum('sisa_pinjaman'),
        ];

        $categoryFilters = $filters;
        unset($categoryFilters['kategori']);
        $categoryEntries = $this->applyFilters($allEntries, $categoryFilters)->where('counted', true);

        $categorySummary = collect(self::CATEGORIES)->map(function (string $label, string $key) use ($categoryEntries) {
            $items = $categoryEntries->where('category', $key);

            return [
                'key' => $key,
                'label' => $label,
                'in' => (float) $items->where('direction', 'in')->sum('amount'),
                'out' => (float) $items->where('direction', 'out')->sum('amount'),
                'count' => $items->count(),
            ];
        });

        $transactions = $this->paginate($entries, 15, $request);

        return view('resident-finance.index', compact(
            'member',
            'filters',
            'categories',
            'summary',
            'categorySummary',
            'transactions',
        ));
    }

    /** @param array<string, mixed> $filters */
    private function normalizedFilters(array $filters): array
    {
        $period = $filters['periode'] ?? 'tahun';
        $filters['periode'] = $period;
        $filters['tahun'] = (int) ($filters['tahun'] ?? now()->year);
        $filters['bulan'] = $filters['bulan'] ?? now()->format('Y-m');

        if ($period === 'rentang') {
            $filters['tanggal_mulai'] = $filters['tanggal_mulai'] ?? now()->startOfMonth()->toDateString();
            $filters['tanggal_selesai'] = $filters['tanggal_selesai'] ?? now()->toDateString();
        }

        return $filters;
    }

    private function entriesFor(int $memberId): Collection
    {
        $iuran = IuranWarga::query()
            ->with('jenisIuran:id,nama')
            ->where('anggota_keluarga_id', $memberId)
            ->where('status', 'lunas')
            ->whereNotNull('tanggal_bayar')
            ->get()
            ->map(fn (IuranWarga $item) => $this->entry(
                'iuran-'.$item->id,
                $item->tanggal_bayar,
                'iuran',
                'Pembayaran '.($item->jenisIuran?->nama ?? 'Iuran RT'),
                $item->periode,
                'out',
                (float) $item->nominal,
                'Lunas',
                'confirmed',
                $item->catatan,
            ));

        $savings = TabunganTransaksi::query()
            ->with('tabungan:id,no_rekening,jenis_tabungan')
            ->whereHas('tabungan', fn ($query) => $query->where('anggota_keluarga_id', $memberId))
            ->get()
            ->map(fn (TabunganTransaksi $item) => $this->entry(
                'tabungan-'.$item->id,
                $item->created_at,
                'tabungan',
                $item->jenis === 'setoran' ? 'Setoran Tabungan' : 'Penarikan Tabungan',
                'Rek. '.($item->tabungan?->no_rekening ?? '-'),
                $item->jenis === 'penarikan' ? 'in' : 'out',
                (float) $item->nominal,
                match ($item->status) {
                    'dikonfirmasi' => 'Dikonfirmasi',
                    'ditolak' => 'Ditolak',
                    default => 'Menunggu',
                },
                $item->status === 'dikonfirmasi' ? 'confirmed' : $item->status,
                $item->keterangan,
                $item->status === 'dikonfirmasi',
            ));

        $arisan = ArisanIuran::query()
            ->with('arisan:id,nama')
            ->where('anggota_keluarga_id', $memberId)
            ->get()
            ->map(fn (ArisanIuran $item) => $this->entry(
                'arisan-'.$item->id,
                $item->tanggal_bayar,
                'arisan',
                'Iuran '.($item->arisan?->nama ?? 'Arisan'),
                'Periode ke-'.$item->periode_ke.' · '.ucfirst($item->metode),
                'out',
                (float) $item->nominal,
                'Lunas',
                'confirmed',
                $item->keterangan,
            ));

        $loans = Pinjaman::query()
            ->with('jenis:id,nama')
            ->where('anggota_keluarga_id', $memberId)
            ->whereIn('status', ['disetujui', 'aktif', 'lunas', 'macet'])
            ->get()
            ->map(fn (Pinjaman $item) => $this->entry(
                'pinjaman-'.$item->id,
                $item->tanggal_mulai ?? $item->created_at,
                'pinjaman',
                'Pencairan '.($item->jenis?->nama ?? 'Pinjaman'),
                $item->keperluan,
                'in',
                (float) $item->nominal,
                ucfirst($item->status),
                'confirmed',
                $item->catatan,
            ));

        return collect()
            ->concat($iuran)
            ->concat($savings)
            ->concat($arisan)
            ->concat($loans)
            ->sortByDesc(fn (array $entry) => $entry['date']->getTimestamp())
            ->values();
    }

    private function entry(
        string $id,
        mixed $date,
        string $category,
        string $title,
        string $detail,
        string $direction,
        float $amount,
        string $status,
        string $statusKey,
        ?string $note = null,
        bool $counted = true,
    ): array {
        return compact(
            'id',
            'category',
            'title',
            'detail',
            'direction',
            'amount',
            'status',
            'statusKey',
            'note',
            'counted',
        ) + ['date' => Carbon::parse($date)];
    }

    /** @param array<string, mixed> $filters */
    private function applyFilters(Collection $entries, array $filters): Collection
    {
        $entries = $entries->when(
            $filters['kategori'] ?? null,
            fn (Collection $items, string $category) => $items->where('category', $category),
        );

        return match ($filters['periode']) {
            'bulan' => $entries->filter(fn (array $entry) => $entry['date']->format('Y-m') === $filters['bulan']),
            'tahun' => $entries->filter(fn (array $entry) => (int) $entry['date']->year === (int) $filters['tahun']),
            'rentang' => $entries->filter(function (array $entry) use ($filters) {
                $date = $entry['date']->toDateString();

                return $date >= $filters['tanggal_mulai'] && $date <= $filters['tanggal_selesai'];
            }),
            default => $entries,
        };
    }

    private function paginate(Collection $items, int $perPage, Request $request): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );
    }
}
