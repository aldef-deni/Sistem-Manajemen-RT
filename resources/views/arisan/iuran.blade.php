@extends('layouts.app')

@section('title', 'Riwayat Iuran Arisan')

@section('content')
@php
    $peserta = $arisan->peserta->sortBy(fn ($p) => $p->pivot->urutan);
    $nominalBaku = (float) $arisan->nominal_iuran;
@endphp

<div class="space-y-4">
    {{-- Breadcrumb --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('arisan.index') }}" class="text-teal-600 hover:underline font-medium">Arisan RT</a>
                <span>/</span>
                <a href="{{ route('arisan.show', $arisan) }}" class="text-teal-600 hover:underline font-medium">{{ $arisan->nama }}</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Riwayat Iuran</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Iuran</h1>
        </div>
        <a href="{{ route('arisan.show', $arisan) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Terkumpul</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">Rp {{ number_format($ringkasan['terkumpul'], 0, ',', '.') }}</div>
            <div class="mt-2 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full bg-teal-500 rounded-full" style="width: {{ min(100, $ringkasan['persen']) }}%"></div>
            </div>
            <div class="text-xs text-slate-500 mt-1">{{ $ringkasan['persen'] }}% dari target</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Target Penuh</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">Rp {{ number_format($ringkasan['target'], 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ $ringkasan['jumlah_peserta'] }} peserta × {{ $ringkasan['jumlah_periode'] }} periode</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Catatan Iuran</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">{{ $ringkasan['catatan_iuran'] }}</div>
            <div class="text-xs text-slate-500 mt-0.5">dari {{ $ringkasan['jumlah_peserta'] * $ringkasan['jumlah_periode'] }} yang diharapkan</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Periode Berjalan</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">{{ $ringkasan['periode_berjalan'] }}</div>
            <div class="text-xs text-slate-500 mt-0.5">dari {{ $ringkasan['jumlah_periode'] }} periode</div>
        </div>
    </div>

    {{-- Matriks --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-slate-800">Peserta terhadap Periode</h2>
                <p class="text-sm text-slate-500 mt-0.5">Klik sel kosong untuk mencatat lunas, klik sel lunas untuk membatalkannya.</p>
            </div>
            <div class="flex items-center gap-4 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-500"></span> Lunas</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-400"></span> Nominal berbeda</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded border border-slate-300 bg-white"></span> Belum</span>
            </div>
        </div>

        @if ($peserta->isEmpty())
            <div class="px-5 py-12 text-center">
                <p class="text-sm text-slate-500">Belum ada peserta pada arisan ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="text-sm border-collapse">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium sticky left-0 bg-slate-50 z-10 min-w-[200px] border-r border-slate-200">Peserta</th>
                            @foreach ($periodeList as $ke)
                                <th class="px-2 py-3 text-center font-medium min-w-[52px]" title="{{ $arisan->labelPeriode($ke) }}">{{ $ke }}</th>
                            @endforeach
                            <th class="px-4 py-3 text-right font-medium min-w-[120px] border-l border-slate-200">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($peserta as $orang)
                            @php
                                $baris = $matriks->get($orang->id) ?? collect();
                                $total = $baris->sum(fn ($i) => (float) $i->nominal);
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2.5 sticky left-0 bg-white z-10 border-r border-slate-200">
                                    <div class="font-medium text-slate-800">{{ $orang->pivot->urutan }}. {{ $orang->nama_lengkap }}</div>
                                    @if ($orang->pivot->sudah_dapat)
                                        <div class="text-xs text-amber-700">sudah dapat giliran</div>
                                    @endif
                                </td>

                                @foreach ($periodeList as $ke)
                                    @php $bayar = $baris->get($ke); @endphp
                                    <td class="px-1 py-1.5 text-center">
                                        @if ($bayar)
                                            <form action="{{ route('arisan.iuran.hapus', [$arisan, $bayar]) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Batalkan iuran {{ $orang->nama_lengkap }} periode ke-{{ $ke }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-9 h-8 rounded {{ (float) $bayar->nominal == $nominalBaku ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-amber-400 hover:bg-amber-500' }} text-white text-xs font-semibold transition-colors"
                                                        title="{{ $bayar->tanggal_bayar->format('d M Y') }} &middot; Rp {{ number_format($bayar->nominal, 0, ',', '.') }} &middot; {{ ucfirst($bayar->metode) }}">
                                                    &check;
                                                </button>
                                            </form>
                                        @elseif ($arisan->status === 'aktif')
                                            <form action="{{ route('arisan.iuran.bayar', $arisan) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="anggota_keluarga_id" value="{{ $orang->id }}">
                                                <input type="hidden" name="periode_ke" value="{{ $ke }}">
                                                <button type="submit"
                                                        class="w-9 h-8 rounded border border-slate-300 bg-white hover:border-teal-500 hover:bg-teal-50 text-slate-400 hover:text-teal-600 text-xs transition-colors"
                                                        title="Catat lunas {{ $arisan->labelPeriode($ke) }}">+</button>
                                            </form>
                                        @else
                                            <span class="inline-block w-9 h-8 rounded border border-slate-200 bg-slate-50"></span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="px-4 py-2.5 text-right tabular-nums font-medium text-slate-800 border-l border-slate-200">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 text-slate-600">
                        <tr>
                            <td class="px-4 py-3 font-semibold sticky left-0 bg-slate-50 z-10 border-r border-slate-200">Lunas per periode</td>
                            @foreach ($periodeList as $ke)
                                @php $lunas = $matriks->filter(fn ($b) => $b->has($ke))->count(); @endphp
                                <td class="px-2 py-3 text-center tabular-nums {{ $lunas === $peserta->count() ? 'text-emerald-600 font-semibold' : '' }}">
                                    {{ $lunas }}
                                </td>
                            @endforeach
                            <td class="px-4 py-3 text-right tabular-nums font-bold text-slate-800 border-l border-slate-200">
                                Rp {{ number_format($ringkasan['terkumpul'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    {{-- Catatan terakhir --}}
    @php
        $terakhir = $arisan->iuran()->with(['peserta', 'pencatat'])->latest('id')->limit(15)->get();
    @endphp
    @if ($terakhir->isNotEmpty())
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="font-semibold text-slate-800">15 Catatan Terakhir</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium">Peserta</th>
                            <th class="px-5 py-3 text-left font-medium">Periode</th>
                            <th class="px-5 py-3 text-right font-medium">Nominal</th>
                            <th class="px-5 py-3 text-left font-medium">Tanggal</th>
                            <th class="px-5 py-3 text-left font-medium">Metode</th>
                            <th class="px-5 py-3 text-left font-medium">Dicatat oleh</th>
                            <th class="px-5 py-3 text-left font-medium">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($terakhir as $baris)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 font-medium text-slate-800">{{ optional($baris->peserta)->nama_lengkap ?? '—' }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $arisan->labelPeriode($baris->periode_ke) }}</td>
                                <td class="px-5 py-3 text-right tabular-nums text-slate-800">Rp {{ number_format($baris->nominal, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-slate-600 tabular-nums">{{ $baris->tanggal_bayar->format('d M Y') }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ ucfirst($baris->metode) }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ optional($baris->pencatat)->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $baris->keterangan ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
