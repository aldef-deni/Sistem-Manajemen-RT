@extends('layouts.app')

@section('title', 'Detail Arisan')

@section('content')
@php
    $peserta      = $arisan->peserta;
    $sudahDapat   = $peserta->filter(fn ($p) => (bool) $p->pivot->sudah_dapat);
    $belumDapat   = $peserta->reject(fn ($p) => (bool) $p->pivot->sudah_dapat);
    $terkumpul    = $peserta->count() * (float) $arisan->nominal_iuran;
    $statusWarna  = match ($arisan->status) {
        'aktif'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'selesai'    => 'bg-slate-100 text-slate-600 border-slate-200',
        'dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
        default      => 'bg-amber-50 text-amber-700 border-amber-200',
    };
    $sudahIkut = $peserta->pluck('id')->all();
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
                <span class="text-slate-700 font-medium">Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $arisan->nama }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('arisan.edit', $arisan) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Ubah
            </a>
            <a href="{{ route('arisan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
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
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Iuran per Periode</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">Rp {{ number_format($arisan->nominal_iuran, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ ucfirst($arisan->periode) }}</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Peserta</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">{{ $peserta->count() }}</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ $sudahDapat->count() }} sudah dapat giliran</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Terkumpul per Periode</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">Rp {{ number_format($terkumpul, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Bila semua peserta membayar</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Status</div>
            <div class="mt-1.5">
                <span class="inline-flex px-2.5 py-1 rounded-full border text-xs font-semibold {{ $statusWarna }}">{{ ucfirst($arisan->status) }}</span>
            </div>
            <div class="text-xs text-slate-500 mt-1.5">Mulai {{ optional($arisan->tanggal_mulai)->format('d M Y') ?? '—' }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Daftar peserta --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h2 class="font-semibold text-slate-800">Daftar Peserta</h2>
                @if ($arisan->status === 'aktif' && $belumDapat->isNotEmpty())
                    <form action="{{ route('arisan.undian', $arisan) }}" method="POST"
                          onsubmit="return confirm('Undi pemenang dari {{ $belumDapat->count() }} peserta yang belum dapat giliran?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Undi Pemenang
                        </button>
                    </form>
                @endif
            </div>

            @if ($peserta->isEmpty())
                <div class="px-5 py-12 text-center">
                    <p class="text-sm text-slate-500">Belum ada peserta. Tambahkan warga lewat panel di samping untuk memulai arisan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-5 py-3 text-left font-medium w-16">Urutan</th>
                                <th class="px-5 py-3 text-left font-medium">Nama Peserta</th>
                                <th class="px-5 py-3 text-left font-medium">No. KK</th>
                                <th class="px-5 py-3 text-left font-medium">Giliran</th>
                                <th class="px-5 py-3 text-right font-medium w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($peserta->sortBy(fn ($p) => $p->pivot->urutan) as $orang)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-3 text-slate-500 tabular-nums">{{ $orang->pivot->urutan }}</td>
                                    <td class="px-5 py-3 font-medium text-slate-800">{{ $orang->nama_lengkap }}</td>
                                    <td class="px-5 py-3 text-slate-500 tabular-nums">{{ optional($orang->kartuKeluarga)->no_kk ?? '—' }}</td>
                                    <td class="px-5 py-3">
                                        @if ($orang->pivot->sudah_dapat)
                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                                                Sudah &middot; {{ $orang->pivot->tanggal_dapat ? \Illuminate\Support\Carbon::parse($orang->pivot->tanggal_dapat)->format('d M Y') : '—' }}
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200 text-xs font-semibold">Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <form action="{{ route('arisan.peserta.hapus', [$arisan, $orang->id]) }}" method="POST"
                                              onsubmit="return confirm('Keluarkan {{ $orang->nama_lengkap }} dari arisan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Keluarkan peserta">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Panel samping --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <h2 class="font-semibold text-slate-800 mb-3">Tambah Peserta</h2>
                <form action="{{ route('arisan.peserta.tambah', $arisan) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="anggota_keluarga_id" class="block text-sm font-medium text-slate-700 mb-1.5">Warga</label>
                        <select id="anggota_keluarga_id" name="anggota_keluarga_id" required
                                class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                            <option value="">— Pilih warga —</option>
                            @foreach ($wargas as $warga)
                                @continue(in_array($warga->id, $sudahIkut, true))
                                <option value="{{ $warga->id }}">{{ $warga->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors">Tambahkan</button>
                </form>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <h2 class="font-semibold text-slate-800 mb-3">Rincian</h2>
                <dl class="space-y-2.5 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Periode</dt>
                        <dd class="font-medium text-slate-800">{{ ucfirst($arisan->periode) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Mode undian</dt>
                        <dd class="font-medium text-slate-800">{{ ucfirst($arisan->mode_undian) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Pemenang / pertemuan</dt>
                        <dd class="font-medium text-slate-800 tabular-nums">{{ $arisan->jumlah_pemenang_per_pertemuan ?? 1 }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Rekening kas</dt>
                        <dd class="font-medium text-slate-800 text-right">
                            {{ optional($arisan->rekening)->nama ?? 'Tidak dikaitkan' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Belum dapat giliran</dt>
                        <dd class="font-medium text-slate-800 tabular-nums">{{ $belumDapat->count() }} orang</dd>
                    </div>
                </dl>

                @if (filled($arisan->keterangan))
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <div class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-1.5">Keterangan</div>
                        <p class="text-sm text-slate-600 whitespace-pre-line">{{ $arisan->keterangan }}</p>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <h2 class="font-semibold text-slate-800 mb-1">Hapus Arisan</h2>
                <p class="text-sm text-slate-500 mb-3">Seluruh data peserta dan giliran ikut terhapus dan tidak bisa dikembalikan.</p>
                <form action="{{ route('arisan.destroy', $arisan) }}" method="POST"
                      onsubmit="return confirm('Hapus arisan {{ $arisan->nama }} beserta seluruh pesertanya?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors">Hapus Arisan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
