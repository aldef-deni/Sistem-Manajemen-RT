@extends('layouts.app')

@section('title', 'Pendapatan Subscribe')
@section('page-title', 'Pendapatan Subscribe')
@section('page-subtitle', 'Pantau pemasukan subscribe yang sudah diverifikasi')

@section('content')
@php
    $exportParams = request()->only(['period', 'month', 'start_date', 'end_date']);
    $currentMonth = now()->format('Y-m');
    $previousMonth = now()->subMonth()->format('Y-m');
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <div class="mb-1 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('dashboard') }}" class="font-medium text-blue-600 hover:underline">Dashboard</a>
                <span>/</span>
                <a href="{{ route('subscribe.index') }}" class="font-medium text-blue-600 hover:underline">Subscribe</a>
                <span>/</span>
                <span>Pendapatan</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Pendapatan Subscribe</h1>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">Laporan hanya menghitung pembayaran yang telah disetujui, berdasarkan tanggal verifikasi administrator.</p>
        </div>

        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
            <a href="{{ route('subscribe.verifications.index') }}" class="col-span-2 inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 sm:col-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Verifikasi
            </a>
            <a href="{{ route('subscribe.income.export.pdf', $exportParams) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5 hover:bg-rose-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
                Export PDF
            </a>
            <a href="{{ route('subscribe.income.export.excel', $exportParams) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5 hover:bg-emerald-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-blue-50/60 px-5 py-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="font-bold text-slate-800">Filter Laporan</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Periode aktif: <span class="font-bold text-blue-700">{{ $filters['label'] }}</span></p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs font-bold">
                    <a href="{{ route('subscribe.income.index', ['period' => 'month', 'month' => $currentMonth]) }}" class="rounded-full border px-3 py-1.5 {{ $filters['period'] === 'month' && $filters['month'] === $currentMonth ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-blue-300' }}">Bulan Ini</a>
                    <a href="{{ route('subscribe.income.index', ['period' => 'month', 'month' => $previousMonth]) }}" class="rounded-full border px-3 py-1.5 {{ $filters['period'] === 'month' && $filters['month'] === $previousMonth ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-blue-300' }}">Bulan Lalu</a>
                    <a href="{{ route('subscribe.income.index', ['period' => 'all']) }}" class="rounded-full border px-3 py-1.5 {{ $filters['period'] === 'all' ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-blue-300' }}">Semua Waktu</a>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('subscribe.income.index') }}" class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-12" id="income-filter-form">
            <div class="xl:col-span-3">
                <label for="period" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Jenis Periode</label>
                <select id="period" name="period" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    <option value="month" @selected($filters['period'] === 'month')>Berdasarkan Bulan</option>
                    <option value="range" @selected($filters['period'] === 'range')>Rentang Tanggal</option>
                    <option value="all" @selected($filters['period'] === 'all')>Semua Waktu</option>
                </select>
            </div>

            <div class="xl:col-span-3" data-month-field>
                <label for="month" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Pilih Bulan</label>
                <input id="month" name="month" type="month" value="{{ $filters['month'] }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            </div>

            <div class="xl:col-span-3" data-range-field>
                <label for="start_date" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Tanggal Awal</label>
                <input id="start_date" name="start_date" type="date" value="{{ $filters['start_date'] }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            </div>

            <div class="xl:col-span-3" data-range-field>
                <label for="end_date" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Tanggal Akhir</label>
                <input id="end_date" name="end_date" type="date" value="{{ $filters['end_date'] }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            </div>

            <div class="flex items-end gap-2 md:col-span-2 xl:col-span-12 xl:justify-end">
                <a href="{{ route('subscribe.income.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50">Reset</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-slate-200 hover:bg-slate-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.667V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.333L3.2 4.6A1 1 0 013 4z"/></svg>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </section>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 p-5 text-white shadow-lg shadow-blue-200">
            <div class="absolute -right-6 -top-7 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="relative">
                <div class="flex items-center justify-between"><p class="text-xs font-bold uppercase tracking-wider text-blue-100">Pendapatan Periode</p><span class="rounded-lg bg-white/15 p-2"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v-1M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg></span></div>
                <p class="mt-5 text-2xl font-black tracking-tight">Rp {{ number_format($summary['total'], 0, ',', '.') }}</p>
                <p class="mt-1 truncate text-xs text-blue-100">{{ $filters['label'] }}</p>
            </div>
        </article>

        <article class="relative overflow-hidden rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50 p-5 shadow-sm">
            <div class="flex items-center justify-between"><p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Transaksi Disetujui</p><span class="rounded-lg bg-emerald-100 p-2 text-emerald-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span></div>
            <p class="mt-5 text-3xl font-black text-emerald-950">{{ number_format($summary['transactions'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-emerald-700">Dari {{ number_format($summary['subscribers'], 0, ',', '.') }} pengguna unik</p>
        </article>

        <article class="relative overflow-hidden rounded-2xl border border-violet-200 bg-gradient-to-br from-violet-50 to-fuchsia-50 p-5 shadow-sm">
            <div class="flex items-center justify-between"><p class="text-xs font-bold uppercase tracking-wider text-violet-700">Rata-rata Transaksi</p><span class="rounded-lg bg-violet-100 p-2 text-violet-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 10l12-3M5 19a4 4 0 100-8 4 4 0 000 8zm12-3a4 4 0 100-8 4 4 0 000 8z"/></svg></span></div>
            <p class="mt-5 text-2xl font-black text-violet-950">Rp {{ number_format($summary['average'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-violet-700">Nilai rata-rata pembayaran</p>
        </article>

        <article class="relative overflow-hidden rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-5 shadow-sm">
            <div class="flex items-center justify-between"><p class="text-xs font-bold uppercase tracking-wider text-amber-700">Total Keseluruhan</p><span class="rounded-lg bg-amber-100 p-2 text-amber-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V3a1 1 0 011-1h7a1 1 0 011 1v7a1 1 0 01-1 1h-8zm0 2h8a1 1 0 011 1v7a1 1 0 01-1 1h-7a1 1 0 01-1-1v-8zM3 2h5a1 1 0 011 1v18a1 1 0 01-1 1H3a1 1 0 01-1-1V3a1 1 0 011-1z"/></svg></span></div>
            <p class="mt-5 text-2xl font-black text-amber-950">Rp {{ number_format($summary['all_time'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-amber-700">Akumulasi semua pendapatan</p>
        </article>
    </div>

    <div class="grid gap-5 xl:grid-cols-3">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <div class="flex items-start justify-between">
                <div><h2 class="font-bold text-slate-900">Tren 6 Bulan</h2><p class="mt-0.5 text-xs text-slate-500">Pendapatan berdasarkan tanggal verifikasi</p></div>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">Bulanan</span>
            </div>
            <div class="mt-6 flex h-52 items-end gap-3 sm:gap-5">
                @foreach($trend as $point)
                    @php $barHeight = $point['total'] > 0 ? max(10, round(($point['total'] / $trendMaximum) * 100)) : 3; @endphp
                    <div class="group flex h-full min-w-0 flex-1 flex-col items-center justify-end gap-2">
                        <div class="pointer-events-none rounded-lg bg-slate-900 px-2 py-1 text-[10px] font-bold text-white opacity-0 shadow transition group-hover:opacity-100">Rp {{ number_format($point['total'], 0, ',', '.') }}</div>
                        <div class="relative flex h-36 w-full max-w-12 items-end overflow-hidden rounded-t-xl bg-slate-100">
                            <div class="w-full rounded-t-xl bg-gradient-to-t from-blue-700 to-cyan-400 transition-all duration-500 group-hover:from-blue-600 group-hover:to-cyan-300" style="height: {{ $barHeight }}%"></div>
                        </div>
                        <div class="text-center"><p class="text-xs font-bold text-slate-700">{{ $point['label'] }}</p><p class="text-[10px] text-slate-400">{{ $point['year'] }}</p></div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div><h2 class="font-bold text-slate-900">Tujuan Pembayaran</h2><p class="mt-0.5 text-xs text-slate-500">Distribusi pemasukan per Bank dan E-Wallet</p></div>
            <div class="mt-5 space-y-4">
                @forelse($bankBreakdown as $bank)
                    @php $percentage = $summary['total'] > 0 ? round(($bank->total / $summary['total']) * 100) : 0; @endphp
                    <div>
                        <div class="mb-2 flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-slate-800">{{ $bank->destination_bank }}</p>
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-bold uppercase text-slate-500">{{ $bank->destination_type === 'ewallet' ? 'E-Wallet' : 'Bank' }}</span>
                                </div>
                                <p class="text-xs text-slate-400">{{ $bank->transaction_count }} transaksi</p>
                            </div>
                            <p class="text-right text-sm font-black text-slate-900">Rp {{ number_format($bank->total, 0, ',', '.') }}</p>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400" style="width: {{ $percentage }}%"></div></div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-200 py-10 text-center">
                        <svg class="mx-auto h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h2m4 0h4M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <p class="mt-2 text-xs text-slate-400">Belum ada pendapatan pada periode ini.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div><h2 class="font-bold text-slate-900">Rincian Pendapatan</h2><p class="mt-0.5 text-xs text-slate-500">{{ $payments->total() }} transaksi ditemukan untuk {{ $filters['label'] }}</p></div>
            <div class="rounded-xl bg-emerald-50 px-4 py-2 text-sm font-black text-emerald-700">Total: Rp {{ number_format($summary['total'], 0, ',', '.') }}</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1180px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Tanggal Verifikasi</th>
                        <th class="px-5 py-3">Pengguna</th>
                        <th class="px-5 py-3">Pembayaran</th>
                        <th class="px-5 py-3">Periode Subscribe</th>
                        <th class="px-5 py-3">Diverifikasi Oleh</th>
                        <th class="px-5 py-3 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        <tr class="align-top transition hover:bg-blue-50/30">
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-800">{{ $payment->verified_at->format('d/m/Y') }}</p>
                                <p class="mt-0.5 text-xs text-slate-400">{{ $payment->verified_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-700 text-xs font-black text-white">{{ strtoupper(substr($payment->user?->name ?? 'P', 0, 1)) }}</span>
                                    <div><p class="font-bold text-slate-900">{{ $payment->user?->name ?? 'Pengguna dihapus' }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $payment->user?->username ?? '-' }} · {{ $payment->user?->role_label ?? '-' }}</p></div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-700">{{ $payment->sender_bank }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">a.n. {{ $payment->sender_account_name }}</p>
                                <p class="mt-1 text-[11px] text-slate-400">Transfer {{ $payment->paid_at->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-700">{{ $payment->starts_at?->format('d/m/Y') ?? '-' }} <span class="mx-1 text-slate-300">→</span> {{ $payment->ends_at?->format('d/m/Y') ?? '-' }}</p>
                                <span class="mt-1.5 inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700">30 Hari</span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-700">{{ $payment->verifier?->name ?? '-' }}</p>
                                <p class="mt-0.5 text-xs text-slate-400">Administrator</p>
                            </td>
                            <td class="px-5 py-4 text-right"><p class="text-base font-black text-emerald-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p><span class="mt-1 inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-700">Lunas</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-16 text-center"><svg class="mx-auto h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 5L5 19M5 5h14v14H5V5z"/></svg><p class="mt-3 font-semibold text-slate-500">Belum ada pendapatan</p><p class="mt-1 text-xs text-slate-400">Coba pilih periode lain atau verifikasi pembayaran yang masih menunggu.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">{{ $payments->links() }}</div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const period = document.getElementById('period');
        const monthFields = document.querySelectorAll('[data-month-field]');
        const rangeFields = document.querySelectorAll('[data-range-field]');

        const syncPeriodFields = () => {
            monthFields.forEach(field => field.classList.toggle('hidden', period.value !== 'month'));
            rangeFields.forEach(field => field.classList.toggle('hidden', period.value !== 'range'));
        };

        period.addEventListener('change', syncPeriodFields);
        syncPeriodFields();
    })();
</script>
@endpush
