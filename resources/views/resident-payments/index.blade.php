@extends('layouts.app')

@section('title', 'Pembayaran Iuran Saya')
@section('page-title', 'Pembayaran')
@section('page-subtitle', 'Status iuran RT pribadi per bulan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-blue-600">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-700">Dashboard</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Keuangan</span>
            </div>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Pembayaran Iuran Saya</h1>
            <p class="mt-1 text-sm text-slate-500">Pantau iuran RT yang sudah lunas dan masih perlu dibayar setiap bulan.</p>
        </div>
        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Khusus iuran RT, bukan subscribe
        </div>
    </div>

    @if (! $member)
        <x-resident-account-unlinked />
    @else
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-blue-950 to-blue-700 px-6 py-7 text-white shadow-xl shadow-blue-950/10 sm:px-8">
            <div class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-cyan-400/20 blur-3xl"></div>
            <div class="absolute -bottom-24 left-1/3 h-48 w-48 rounded-full bg-blue-300/20 blur-3xl"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-200">Ringkasan Tahun {{ $year }}</p>
                    <h2 class="mt-2 text-2xl font-bold">{{ $member->nama_lengkap }}</h2>
                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-xs text-blue-100">
                        <span>NIK {{ $member->nik }}</span>
                        <span>KK {{ $member->kartuKeluarga?->no_kk ?? '-' }}</span>
                    </div>
                </div>
                <div class="w-full rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur sm:max-w-sm">
                    <div class="flex items-center justify-between text-xs text-blue-100">
                        <span>Progres pelunasan</span>
                        <span class="font-bold text-white">{{ $summary['progress'] }}%</span>
                    </div>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-white/15">
                        <div class="h-full rounded-full bg-gradient-to-r from-cyan-300 to-emerald-300" style="width: {{ $summary['progress'] }}%"></div>
                    </div>
                    <p class="mt-3 text-xs text-blue-100">{{ $summary['paid_count'] }} tagihan lunas · {{ $summary['unpaid_count'] }} belum dibayar</p>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Iuran</p>
                    <span class="rounded-xl bg-blue-50 p-2 text-blue-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m4-3v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg></span>
                </div>
                <p class="mt-4 text-2xl font-bold text-slate-900">Rp {{ number_format($summary['total'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-400">Seluruh tagihan pada {{ $year }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Sudah Dibayar</p>
                    <span class="rounded-xl bg-emerald-100 p-2 text-emerald-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                </div>
                <p class="mt-4 text-2xl font-bold text-emerald-800">Rp {{ number_format($summary['paid'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-emerald-600/70">{{ $summary['paid_count'] }} tagihan telah lunas</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm sm:col-span-2 xl:col-span-1">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Belum Dibayar</p>
                    <span class="rounded-xl bg-amber-100 p-2 text-amber-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                </div>
                <p class="mt-4 text-2xl font-bold text-amber-800">Rp {{ number_format($summary['outstanding'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-amber-600/70">{{ $summary['unpaid_count'] }} tagihan menunggu pembayaran</p>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Kalender Pembayaran {{ $year }}</h2>
                    <p class="mt-1 text-xs text-slate-400">Pilih bulan untuk melihat rincian tagihan.</p>
                </div>
                <form method="GET" action="{{ route('pembayaran') }}" class="flex items-center gap-2">
                    <label for="tahun-ringkas" class="text-xs font-semibold text-slate-500">Tahun</label>
                    <select id="tahun-ringkas" name="tahun" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        @foreach ($availableYears as $availableYear)
                            <option value="{{ $availableYear }}" @selected($year === $availableYear)>{{ $availableYear }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                @foreach ($monthlyOverview as $month)
                    @php
                        $selected = (int) ($filters['bulan'] ?? 0) === $month['number'];
                        $monthQuery = array_merge(request()->except(['page', 'bulan']), ['tahun' => $year, 'bulan' => $month['number']]);
                    @endphp
                    <a href="{{ route('pembayaran', $monthQuery) }}" class="group rounded-2xl border p-3.5 transition-all hover:-translate-y-0.5 hover:shadow-md {{ $selected ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500/10' : ($month['complete'] ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-white hover:border-blue-200') }}">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-sm font-bold {{ $selected ? 'text-blue-700' : 'text-slate-800' }}">{{ $month['name'] }}</p>
                                <p class="mt-0.5 text-[10px] text-slate-400">{{ $month['bill_count'] }} tagihan</p>
                            </div>
                            @if ($month['complete'])
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-700"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></span>
                            @elseif ($month['outstanding'] > 0)
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-400 ring-4 ring-amber-100"></span>
                            @else
                                <span class="h-2.5 w-2.5 rounded-full bg-slate-200"></span>
                            @endif
                        </div>
                        <p class="mt-4 text-xs font-semibold {{ $month['outstanding'] > 0 ? 'text-amber-700' : ($month['complete'] ? 'text-emerald-700' : 'text-slate-400') }}">
                            @if ($month['outstanding'] > 0)
                                Sisa Rp {{ number_format($month['outstanding'], 0, ',', '.') }}
                            @elseif ($month['complete'])
                                Lunas
                            @else
                                Belum ada tagihan
                            @endif
                        </p>
                        <div class="mt-2 h-1 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full {{ $month['complete'] ? 'bg-emerald-500' : 'bg-blue-500' }}" style="width: {{ $month['progress'] }}%"></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 p-5 sm:p-6">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Rincian Tagihan</h2>
                        <p class="mt-1 text-xs text-slate-400">Hasil difilter tanpa mencampurkan pembayaran subscribe.</p>
                    </div>
                    <form method="GET" action="{{ route('pembayaran') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                        <select name="tahun" aria-label="Tahun" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            @foreach ($availableYears as $availableYear)
                                <option value="{{ $availableYear }}" @selected($year === $availableYear)>{{ $availableYear }}</option>
                            @endforeach
                        </select>
                        <select name="bulan" aria-label="Bulan" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua bulan</option>
                            @foreach ($months as $number => $name)
                                <option value="{{ $number }}" @selected((int) ($filters['bulan'] ?? 0) === $number)>{{ $name }}</option>
                            @endforeach
                        </select>
                        <select name="jenis" aria-label="Jenis iuran" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua jenis iuran</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" @selected((int) ($filters['jenis'] ?? 0) === $type->id)>{{ $type->nama }}</option>
                            @endforeach
                        </select>
                        <select name="status" aria-label="Status" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua status</option>
                            <option value="lunas" @selected(($filters['status'] ?? '') === 'lunas')>Lunas</option>
                            <option value="belum_bayar" @selected(($filters['status'] ?? '') === 'belum_bayar')>Belum dibayar</option>
                        </select>
                        <div class="flex gap-2">
                            <button class="flex-1 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Terapkan</button>
                            <a href="{{ route('pembayaran', ['tahun' => $year]) }}" title="Reset filter" class="flex items-center justify-center rounded-lg border border-slate-200 px-3 text-slate-500 hover:bg-slate-50"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></a>
                        </div>
                    </form>
                </div>
            </div>

            @if ($bills->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14l6-6m4-3v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg></div>
                    <h3 class="mt-4 text-sm font-bold text-slate-700">Tidak ada tagihan pada filter ini</h3>
                    <p class="mt-1 text-xs text-slate-400">Coba pilih bulan, jenis iuran, atau status lainnya.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100 md:hidden">
                    @foreach ($bills as $bill)
                        <article class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $bill->jenisIuran?->nama ?? 'Iuran RT' }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $bill->periode }}</p>
                                </div>
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $bill->status === 'lunas' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $bill->status === 'lunas' ? 'Lunas' : 'Belum dibayar' }}</span>
                            </div>
                            <div class="mt-4 flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Nominal</p>
                                    <p class="mt-1 text-base font-bold text-slate-900">{{ $bill->nominal_formatted }}</p>
                                </div>
                                <p class="text-right text-xs text-slate-500">{{ $bill->tanggal_bayar ? 'Dibayar ' . $bill->tanggal_bayar->translatedFormat('d M Y') : 'Belum ada tanggal bayar' }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-full">
                        <thead class="bg-slate-50/80">
                            <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                <th class="px-6 py-3.5">Jenis Iuran</th>
                                <th class="px-6 py-3.5">Periode</th>
                                <th class="px-6 py-3.5">Nominal</th>
                                <th class="px-6 py-3.5">Tanggal Bayar</th>
                                <th class="px-6 py-3.5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($bills as $bill)
                                <tr class="transition hover:bg-slate-50/70">
                                    <td class="px-6 py-4"><p class="text-sm font-semibold text-slate-800">{{ $bill->jenisIuran?->nama ?? 'Iuran RT' }}</p>@if($bill->catatan)<p class="mt-0.5 max-w-xs truncate text-xs text-slate-400">{{ $bill->catatan }}</p>@endif</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $bill->periode }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900">{{ $bill->nominal_formatted }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $bill->tanggal_bayar?->translatedFormat('d M Y') ?? '—' }}</td>
                                    <td class="px-6 py-4 text-right"><span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold {{ $bill->status === 'lunas' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $bill->status === 'lunas' ? 'Lunas' : 'Belum dibayar' }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($bills->hasPages())
                    <div class="border-t border-slate-100 px-5 py-4">{{ $bills->links() }}</div>
                @endif
            @endif
        </section>
    @endif
</div>
@endsection
