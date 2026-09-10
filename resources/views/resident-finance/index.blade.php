@extends('layouts.app')

@section('title', 'Laporan Keuangan Saya')
@section('page-title', 'Laporan Keuangan')
@section('page-subtitle', 'Seluruh transaksi keuangan pribadi Anda')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-violet-600">
                <a href="{{ route('dashboard') }}" class="hover:text-violet-700">Dashboard</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Keuangan</span>
            </div>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Laporan Keuangan Saya</h1>
            <p class="mt-1 text-sm text-slate-500">Satu riwayat untuk iuran RT, tabungan, arisan, dan pinjaman milik Anda.</p>
        </div>
        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-violet-100 bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Data pribadi · subscribe tidak termasuk
        </div>
    </div>

    @if (! $member)
        <x-resident-account-unlinked />
    @else
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-violet-950 to-indigo-700 px-6 py-7 text-white shadow-xl shadow-violet-950/10 sm:px-8">
            <div class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-fuchsia-400/20 blur-3xl"></div>
            <div class="absolute -bottom-24 left-1/3 h-48 w-48 rounded-full bg-indigo-300/20 blur-3xl"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-violet-200">Buku Keuangan Pribadi</p>
                    <h2 class="mt-2 text-2xl font-bold">{{ $member->nama_lengkap }}</h2>
                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-xs text-violet-100">
                        <span>NIK {{ $member->nik }}</span>
                        <span>KK {{ $member->kartuKeluarga?->no_kk ?? '-' }}</span>
                    </div>
                </div>
                <div class="grid w-full grid-cols-2 gap-2 sm:max-w-md">
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-violet-200">Saldo Tabungan</p>
                        <p class="mt-2 text-lg font-bold">Rp {{ number_format($summary['savings'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-violet-200">Sisa Pinjaman</p>
                        <p class="mt-2 text-lg font-bold">Rp {{ number_format($summary['loan_remaining'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div class="shrink-0">
                    <h2 class="text-base font-bold text-slate-900">Filter Laporan</h2>
                    <p class="mt-1 text-xs text-slate-400">Atur periode dan jenis transaksi.</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="{{ route('laporan-keuangan', ['periode' => 'bulan', 'bulan' => now()->format('Y-m')]) }}" class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:border-violet-200 hover:bg-violet-50 hover:text-violet-700">Bulan ini</a>
                        <a href="{{ route('laporan-keuangan', ['periode' => 'tahun', 'tahun' => now()->year]) }}" class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:border-violet-200 hover:bg-violet-50 hover:text-violet-700">Tahun ini</a>
                        <a href="{{ route('laporan-keuangan', ['periode' => 'semua']) }}" class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:border-violet-200 hover:bg-violet-50 hover:text-violet-700">Semua waktu</a>
                    </div>
                </div>
                <form method="GET" action="{{ route('laporan-keuangan') }}" id="report-filter" class="grid flex-1 gap-3 sm:grid-cols-2 lg:grid-cols-6 xl:max-w-4xl">
                    <div>
                        <label for="periode" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Periode</label>
                        <select id="periode" name="periode" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                            <option value="bulan" @selected($filters['periode'] === 'bulan')>Per bulan</option>
                            <option value="tahun" @selected($filters['periode'] === 'tahun')>Per tahun</option>
                            <option value="rentang" @selected($filters['periode'] === 'rentang')>Rentang tanggal</option>
                            <option value="semua" @selected($filters['periode'] === 'semua')>Semua waktu</option>
                        </select>
                    </div>
                    <div data-period-field="bulan">
                        <label for="bulan" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Bulan</label>
                        <input id="bulan" type="month" name="bulan" value="{{ $filters['bulan'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    </div>
                    <div data-period-field="tahun">
                        <label for="tahun" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Tahun</label>
                        <input id="tahun" type="number" name="tahun" min="2000" max="2100" value="{{ $filters['tahun'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    </div>
                    <div data-period-field="rentang">
                        <label for="tanggal_mulai" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Dari</label>
                        <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    </div>
                    <div data-period-field="rentang">
                        <label for="tanggal_selesai" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Sampai</label>
                        <input id="tanggal_selesai" type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    </div>
                    <div>
                        <label for="kategori" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Kategori</label>
                        <select id="kategori" name="kategori" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                            <option value="">Semua transaksi</option>
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}" @selected(($filters['kategori'] ?? '') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button class="flex-1 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Terapkan</button>
                        <a href="{{ route('laporan-keuangan') }}" title="Reset filter" class="flex h-[42px] items-center justify-center rounded-lg border border-slate-200 px-3 text-slate-500 hover:bg-slate-50"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></a>
                    </div>
                </form>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm">
                <div class="flex items-center justify-between"><p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Dana Diterima</p><span class="rounded-xl bg-emerald-100 p-2 text-emerald-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5 5m0 0l5-5m-5 5V4M5 20h14"/></svg></span></div>
                <p class="mt-4 text-2xl font-bold text-emerald-800">Rp {{ number_format($summary['in'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-emerald-600/70">Penarikan tabungan & pencairan pinjaman</p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-gradient-to-br from-rose-50 to-white p-5 shadow-sm">
                <div class="flex items-center justify-between"><p class="text-xs font-bold uppercase tracking-wider text-rose-600">Dana Dibayarkan</p><span class="rounded-xl bg-rose-100 p-2 text-rose-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5-5m0 0l-5 5m5-5v12M5 4h14"/></svg></span></div>
                <p class="mt-4 text-2xl font-bold text-rose-800">Rp {{ number_format($summary['out'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-rose-600/70">Iuran, arisan & setoran tabungan</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Saldo Tabungan</p><span class="rounded-xl bg-blue-50 p-2 text-blue-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></span></div>
                <p class="mt-4 text-2xl font-bold text-slate-900">Rp {{ number_format($summary['savings'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-400">Saldo akun tabungan aktif saat ini</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Jumlah Transaksi</p><span class="rounded-xl bg-violet-50 p-2 text-violet-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg></span></div>
                <p class="mt-4 text-2xl font-bold text-slate-900">{{ number_format($summary['count']) }}</p>
                <p class="mt-1 text-xs text-slate-400">Sesuai filter laporan aktif</p>
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-4">
            @php
                $categoryStyles = [
                    'iuran' => ['bg-blue-50 text-blue-700 border-blue-100', 'bg-blue-500'],
                    'tabungan' => ['bg-emerald-50 text-emerald-700 border-emerald-100', 'bg-emerald-500'],
                    'arisan' => ['bg-amber-50 text-amber-700 border-amber-100', 'bg-amber-500'],
                    'pinjaman' => ['bg-violet-50 text-violet-700 border-violet-100', 'bg-violet-500'],
                ];
            @endphp
            @foreach ($categorySummary as $category)
                @php $style = $categoryStyles[$category['key']]; @endphp
                <a href="{{ route('laporan-keuangan', array_merge(request()->except(['page', 'kategori']), ['kategori' => $category['key']])) }}" class="rounded-2xl border bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md {{ ($filters['kategori'] ?? '') === $category['key'] ? 'border-violet-400 ring-2 ring-violet-500/10' : 'border-slate-200' }}">
                    <div class="flex items-center justify-between">
                        <span class="rounded-lg border px-2.5 py-1 text-[11px] font-bold {{ $style[0] }}">{{ $category['label'] }}</span>
                        <span class="text-xs font-semibold text-slate-400">{{ $category['count'] }} transaksi</span>
                    </div>
                    <div class="mt-4 flex items-end justify-between gap-3">
                        <div><p class="text-[10px] uppercase tracking-wider text-slate-400">Masuk</p><p class="mt-1 text-sm font-bold text-emerald-700">Rp {{ number_format($category['in'], 0, ',', '.') }}</p></div>
                        <div class="text-right"><p class="text-[10px] uppercase tracking-wider text-slate-400">Keluar</p><p class="mt-1 text-sm font-bold text-rose-700">Rp {{ number_format($category['out'], 0, ',', '.') }}</p></div>
                    </div>
                </a>
            @endforeach
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Riwayat Transaksi</h2>
                    <p class="mt-1 text-xs text-slate-400">Urutan terbaru berdasarkan tanggal transaksi.</p>
                </div>
                <span class="hidden rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500 sm:inline-flex">{{ $summary['count'] }} data</span>
            </div>

            @if ($transactions->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <h3 class="mt-4 text-sm font-bold text-slate-700">Belum ada transaksi pada periode ini</h3>
                    <p class="mt-1 text-xs text-slate-400">Ubah periode atau kategori untuk melihat riwayat lainnya.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($transactions as $transaction)
                        @php
                            $style = $categoryStyles[$transaction['category']];
                            $statusStyle = match ($transaction['statusKey']) {
                                'confirmed' => 'bg-emerald-50 text-emerald-700',
                                'ditolak' => 'bg-rose-50 text-rose-700',
                                default => 'bg-amber-50 text-amber-700',
                            };
                        @endphp
                        <article class="px-5 py-4 transition hover:bg-slate-50/70 sm:px-6">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $style[0] }}">
                                    @if ($transaction['category'] === 'iuran')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    @elseif ($transaction['category'] === 'tabungan')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    @elseif ($transaction['category'] === 'arisan')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14zM9 7a3 3 0 116 0 3 3 0 01-6 0z"/></svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="truncate text-sm font-bold text-slate-800">{{ $transaction['title'] }}</h3>
                                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $statusStyle }}">{{ $transaction['status'] }}</span>
                                            </div>
                                            <p class="mt-1 truncate text-xs text-slate-500">{{ $transaction['detail'] }}</p>
                                            <p class="mt-1.5 text-[11px] text-slate-400">{{ $transaction['date']->translatedFormat('d F Y') }} · {{ $categories[$transaction['category']] }}</p>
                                            @if ($transaction['note'])<p class="mt-2 text-xs italic text-slate-400">“{{ $transaction['note'] }}”</p>@endif
                                        </div>
                                        <div class="shrink-0 text-left sm:text-right">
                                            <p class="text-base font-bold {{ ! $transaction['counted'] ? 'text-slate-400 line-through' : ($transaction['direction'] === 'in' ? 'text-emerald-700' : 'text-rose-700') }}">
                                                {{ $transaction['direction'] === 'in' ? '+' : '−' }} Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                                            </p>
                                            <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">{{ $transaction['direction'] === 'in' ? 'Dana diterima' : 'Dana dibayarkan' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                @if ($transactions->hasPages())
                    <div class="border-t border-slate-100 px-5 py-4">{{ $transactions->links() }}</div>
                @endif
            @endif
        </section>

        <div class="flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50/70 p-4 text-xs leading-5 text-blue-800">
            <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p>Laporan ini hanya membaca transaksi yang terhubung ke data warga Anda. Pembayaran subscribe dan transaksi milik warga lain tidak masuk ke perhitungan.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const periodSelect = document.getElementById('periode');
    if (!periodSelect) return;

    const updatePeriodFields = function () {
        document.querySelectorAll('[data-period-field]').forEach(function (field) {
            const visible = field.dataset.periodField === periodSelect.value;
            field.classList.toggle('hidden', !visible);
            field.querySelectorAll('input').forEach(function (input) {
                input.disabled = !visible;
            });
        });
    };

    periodSelect.addEventListener('change', updatePeriodFields);
    updatePeriodFields();
});
</script>
@endpush
