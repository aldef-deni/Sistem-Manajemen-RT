<?php

namespace App\Http\Controllers;

use App\Models\SubscribePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscribeIncomeController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->filters($request);
        $query = $this->revenueQuery($filters);

        $total = (int) (clone $query)->sum('amount');
        $transactionCount = (clone $query)->count();
        $subscriberCount = (clone $query)->distinct('user_id')->count('user_id');

        $summary = [
            'total' => $total,
            'transactions' => $transactionCount,
            'subscribers' => $subscriberCount,
            'average' => $transactionCount > 0 ? (int) round($total / $transactionCount) : 0,
            'all_time' => (int) SubscribePayment::query()
                ->where('status', SubscribePayment::STATUS_APPROVED)
                ->whereNotNull('verified_at')
                ->sum('amount'),
        ];

        $payments = (clone $query)
            ->with(['user', 'verifier'])
            ->latest('verified_at')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $bankBreakdown = (clone $query)
            ->selectRaw('destination_type, destination_bank, COUNT(*) as transaction_count, SUM(amount) as total')
            ->groupBy('destination_type', 'destination_bank')
            ->orderByDesc('total')
            ->get();

        $trend = $this->sixMonthTrend();
        $trendMaximum = max(1, (int) $trend->max('total'));

        return view('subscribe.income.index', compact(
            'payments',
            'filters',
            'summary',
            'bankBreakdown',
            'trend',
            'trendMaximum'
        ));
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $this->filters($request);
        $payments = $this->revenueQuery($filters)
            ->with(['user', 'verifier'])
            ->latest('verified_at')
            ->latest('id')
            ->get();

        $total = (int) $payments->sum('amount');

        return Pdf::loadView('subscribe.income.pdf', [
            'payments' => $payments,
            'filters' => $filters,
            'total' => $total,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape')->download($this->fileName($filters, 'pdf'));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $filters = $this->filters($request);
        $payments = $this->revenueQuery($filters)
            ->with(['user', 'verifier'])
            ->latest('verified_at')
            ->latest('id')
            ->get();

        $spreadsheet = $this->makeSpreadsheet($payments, $filters);

        return response()->streamDownload(
            function () use ($spreadsheet): void {
                (new Xlsx($spreadsheet))->save('php://output');
                $spreadsheet->disconnectWorksheets();
            },
            $this->fileName($filters, 'xlsx'),
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    /**
     * @return array{period:string,month:string,start_date:?string,end_date:?string,start:?CarbonImmutable,end:?CarbonImmutable,label:string}
     */
    private function filters(Request $request): array
    {
        $today = CarbonImmutable::now();
        $input = [
            'period' => $request->query('period', 'month'),
            'month' => $request->query('month', $today->format('Y-m')),
            'start_date' => $request->query('start_date'),
            'end_date' => $request->query('end_date'),
        ];

        $validated = Validator::make($input, [
            'period' => ['required', 'in:month,range,all'],
            'month' => ['required_if:period,month', 'nullable', 'date_format:Y-m'],
            'start_date' => ['required_if:period,range', 'nullable', 'date'],
            'end_date' => ['required_if:period,range', 'nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'period.in' => 'Jenis periode tidak valid.',
            'month.required_if' => 'Bulan laporan wajib dipilih.',
            'month.date_format' => 'Format bulan laporan tidak valid.',
            'start_date.required_if' => 'Tanggal awal wajib diisi.',
            'end_date.required_if' => 'Tanggal akhir wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal akhir tidak boleh sebelum tanggal awal.',
        ])->validate();

        $start = null;
        $end = null;

        if ($validated['period'] === 'month') {
            $start = CarbonImmutable::createFromFormat('Y-m-d', $validated['month'].'-01')->startOfDay();
            $end = $start->endOfMonth()->endOfDay();
            $label = $start->locale('id')->translatedFormat('F Y');
        } elseif ($validated['period'] === 'range') {
            $start = CarbonImmutable::parse($validated['start_date'])->startOfDay();
            $end = CarbonImmutable::parse($validated['end_date'])->endOfDay();
            $label = $start->locale('id')->translatedFormat('d M Y').' - '.$end->locale('id')->translatedFormat('d M Y');
        } else {
            $label = 'Semua waktu';
        }

        return [
            'period' => $validated['period'],
            'month' => $validated['month'] ?? $today->format('Y-m'),
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'start' => $start,
            'end' => $end,
            'label' => $label,
        ];
    }

    /** @param array{start:?CarbonImmutable,end:?CarbonImmutable} $filters */
    private function revenueQuery(array $filters): Builder
    {
        return SubscribePayment::query()
            ->where('status', SubscribePayment::STATUS_APPROVED)
            ->whereNotNull('verified_at')
            ->when(
                $filters['start'] && $filters['end'],
                fn (Builder $query) => $query->whereBetween('verified_at', [$filters['start'], $filters['end']])
            );
    }

    /** @return Collection<int, array{label:string,year:string,total:int}> */
    private function sixMonthTrend(): Collection
    {
        return collect(range(5, 0))->map(function (int $monthsAgo): array {
            $month = CarbonImmutable::now()->startOfMonth()->subMonths($monthsAgo);

            return [
                'label' => $month->locale('id')->translatedFormat('M'),
                'year' => $month->format('Y'),
                'total' => (int) SubscribePayment::query()
                    ->where('status', SubscribePayment::STATUS_APPROVED)
                    ->whereBetween('verified_at', [$month->startOfDay(), $month->endOfMonth()->endOfDay()])
                    ->sum('amount'),
            ];
        });
    }

    /**
     * @param  EloquentCollection<int, SubscribePayment>  $payments
     * @param  array{period:string,month:string,start_date:?string,end_date:?string,label:string}  $filters
     */
    private function makeSpreadsheet(EloquentCollection $payments, array $filters): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getProperties()
            ->setCreator(config('app.name'))
            ->setTitle('Pendapatan Subscribe - '.$filters['label'])
            ->setSubject('Laporan pendapatan subscribe yang telah disetujui');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pendapatan Subscribe');
        $sheet->mergeCells('A1:J1')->setCellValue('A1', 'LAPORAN PENDAPATAN SUBSCRIBE');
        $sheet->mergeCells('A2:J2')->setCellValue('A2', 'Periode: '.$filters['label']);
        $sheet->mergeCells('A3:J3')->setCellValue('A3', 'Dibuat: '.now()->format('d/m/Y H:i').' WIB');

        $headers = ['No', 'Tanggal Verifikasi', 'Nama Pengguna', 'Username', 'Role', 'Bank Pengirim', 'Nama Pengirim', 'Tanggal Transfer', 'Periode Aktif', 'Jumlah (Rp)'];
        foreach ($headers as $columnIndex => $header) {
            $sheet->setCellValue([$columnIndex + 1, 5], $header);
        }

        $row = 6;
        foreach ($payments as $index => $payment) {
            $sheet->setCellValue([1, $row], $index + 1);
            $sheet->setCellValue([2, $row], $payment->verified_at?->format('d/m/Y H:i'));
            $sheet->setCellValueExplicit([3, $row], $payment->user?->name ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit([4, $row], $payment->user?->username ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValue([5, $row], $payment->user?->role_label ?? '-');
            $sheet->setCellValueExplicit([6, $row], $payment->sender_bank, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit([7, $row], $payment->sender_account_name, DataType::TYPE_STRING);
            $sheet->setCellValue([8, $row], $payment->paid_at?->format('d/m/Y'));
            $sheet->setCellValue([9, $row], ($payment->starts_at?->format('d/m/Y') ?? '-').' - '.($payment->ends_at?->format('d/m/Y') ?? '-'));
            $sheet->setCellValue([10, $row], (int) $payment->amount);
            $row++;
        }

        $totalRow = $row;
        $sheet->mergeCells("A{$totalRow}:I{$totalRow}")->setCellValue("A{$totalRow}", 'TOTAL PENDAPATAN');
        $sheet->setCellValue("J{$totalRow}", (int) $payments->sum('amount'));

        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle('A2:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:J5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle("A5:J{$totalRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('DDE3EA'));
        $sheet->getStyle("A{$totalRow}:J{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCFCE7']],
        ]);
        $sheet->getStyle("J6:J{$totalRow}")->getNumberFormat()->setFormatCode('[$Rp-id-ID] #,##0');
        $sheet->getStyle("A6:J{$totalRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        $sheet->getStyle("A{$totalRow}:I{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->freezePane('A6');

        if ($payments->isNotEmpty()) {
            $sheet->setAutoFilter('A5:J'.($totalRow - 1));
        }

        foreach (['A' => 7, 'B' => 20, 'C' => 24, 'D' => 18, 'E' => 16, 'F' => 20, 'G' => 24, 'H' => 18, 'I' => 25, 'J' => 18] as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        return $spreadsheet;
    }

    private function fileName(array $filters, string $extension): string
    {
        $period = match ($filters['period']) {
            'month' => $filters['month'],
            'range' => $filters['start_date'].'_'.$filters['end_date'],
            default => 'semua-waktu',
        };

        return 'pendapatan-subscribe-'.$period.'.'.$extension;
    }
}
