@extends('layouts.app')

@section('title', 'Detail Arisan')

@section('content')
@php
    $peserta     = $arisan->peserta;
    $sudahDapat  = $peserta->filter(fn ($p) => (bool) $p->pivot->sudah_dapat);
    $belumDapat  = $peserta->reject(fn ($p) => (bool) $p->pivot->sudah_dapat);
    $statusWarna = match ($arisan->status) {
        'aktif'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'selesai'    => 'bg-slate-100 text-slate-600 border-slate-200',
        'dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
        default      => 'bg-amber-50 text-amber-700 border-amber-200',
    };
    $sudahIkut     = $peserta->pluck('id')->all();
    $jumlahPeriode = max(1, $ringkasan['jumlah_periode']);
    $lunasPeriode  = $iuranPeriode->count();
    $belumPeriode  = max(0, $peserta->count() - $lunasPeriode);
    $terkumpulPeriode = $iuranPeriode->sum(fn ($i) => (float) $i->nominal);
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
            <a href="{{ route('arisan.iuran.riwayat', $arisan) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                Riwayat Iuran
            </a>
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
    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Iuran per Periode</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">Rp {{ number_format($arisan->nominal_iuran, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ ucfirst($arisan->periode) }} &middot; {{ $jumlahPeriode }} periode</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Peserta</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">{{ $peserta->count() }}</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ $sudahDapat->count() }} sudah dapat giliran</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Iuran Terkumpul</div>
            <div class="mt-1 text-xl font-bold text-slate-800 tabular-nums">Rp {{ number_format($ringkasan['terkumpul'], 0, ',', '.') }}</div>
            <div class="mt-2 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full bg-teal-500 rounded-full" style="width: {{ min(100, $ringkasan['persen']) }}%"></div>
            </div>
            <div class="text-xs text-slate-500 mt-1">{{ $ringkasan['persen'] }}% dari Rp {{ number_format($ringkasan['target'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Status</div>
            <div class="mt-1.5">
                <span class="inline-flex px-2.5 py-1 rounded-full border text-xs font-semibold {{ $statusWarna }}">{{ ucfirst($arisan->status) }}</span>
            </div>
            <div class="text-xs text-slate-500 mt-1.5">Mulai {{ optional($arisan->tanggal_mulai)->format('d M Y') ?? '—' }}</div>
        </div>
    </div>

    {{-- Pencatatan iuran periode --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-semibold text-slate-800">Iuran {{ $arisan->labelPeriode($periodeAktif) }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">
                        {{ $lunasPeriode }} lunas &middot; {{ $belumPeriode }} belum &middot;
                        terkumpul Rp {{ number_format($terkumpulPeriode, 0, ',', '.') }}
                        @if ($periodeAktif === $ringkasan['periode_berjalan'])
                            <span class="ml-1 inline-flex px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 border border-teal-200 text-xs font-semibold">periode berjalan</span>
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Pindah periode --}}
                    <form method="GET" action="{{ route('arisan.show', $arisan) }}" class="flex items-center gap-2">
                        <label for="periode" class="text-sm text-slate-500">Periode</label>
                        <select id="periode" name="periode" onchange="this.form.submit()"
                                class="px-3 py-2 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                            @for ($i = 1; $i <= $jumlahPeriode; $i++)
                                <option value="{{ $i }}" @selected($i === $periodeAktif)>{{ $arisan->labelPeriode($i) }}</option>
                            @endfor
                        </select>
                    </form>

                    @if ($arisan->status === 'aktif' && $belumPeriode > 0)
                        <form action="{{ route('arisan.iuran.massal', $arisan) }}" method="POST"
                              onsubmit="return confirm('Catat lunas {{ $belumPeriode }} peserta yang belum membayar pada periode ini?')">
                            @csrf
                            <input type="hidden" name="periode_ke" value="{{ $periodeAktif }}">
                            <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Lunasi Semua
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if ($peserta->isEmpty())
            <div class="px-5 py-12 text-center">
                <p class="text-sm text-slate-500">Belum ada peserta. Tambahkan warga lewat panel di bawah untuk memulai arisan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium w-16">No.</th>
                            <th class="px-5 py-3 text-left font-medium">Peserta</th>
                            <th class="px-5 py-3 text-left font-medium">Giliran</th>
                            <th class="px-5 py-3 text-left font-medium">Iuran Periode Ini</th>
                            <th class="px-5 py-3 text-right font-medium w-44">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($peserta->sortBy(fn ($p) => $p->pivot->urutan) as $orang)
                            @php $bayar = $iuranPeriode->get($orang->id); @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-500 tabular-nums">{{ $orang->pivot->urutan }}</td>
                                <td class="px-5 py-3">
                                    <div class="font-medium text-slate-800">{{ $orang->nama_lengkap }}</div>
                                    <div class="text-xs text-slate-500 tabular-nums">{{ optional($orang->kartuKeluarga)->no_kk ?? '—' }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($orang->pivot->sudah_dapat)
                                        <span class="inline-flex px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold">
                                            Sudah &middot; {{ $orang->pivot->tanggal_dapat ? \Illuminate\Support\Carbon::parse($orang->pivot->tanggal_dapat)->format('d M Y') : '—' }}
                                        </span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200 text-xs font-semibold">Belum</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($bayar)
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">Lunas</span>
                                            <span class="text-slate-600 tabular-nums">Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            {{ $bayar->tanggal_bayar->format('d M Y') }} &middot; {{ ucfirst($bayar->metode) }}
                                        </div>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full bg-red-50 text-red-700 border border-red-200 text-xs font-semibold">Belum bayar</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if ($bayar)
                                            <form action="{{ route('arisan.iuran.hapus', [$arisan, $bayar]) }}" method="POST"
                                                  onsubmit="return confirm('Batalkan catatan iuran {{ $orang->nama_lengkap }} untuk periode ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batalkan</button>
                                            </form>
                                        @elseif ($arisan->status === 'aktif')
                                            <form action="{{ route('arisan.iuran.bayar', $arisan) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="anggota_keluarga_id" value="{{ $orang->id }}">
                                                <input type="hidden" name="periode_ke" value="{{ $periodeAktif }}">
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-teal-600 hover:bg-teal-700 transition-colors">Catat Lunas</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('arisan.peserta.hapus', [$arisan, $orang->id]) }}" method="POST"
                                              onsubmit="return confirm('Keluarkan {{ $orang->nama_lengkap }} dari arisan ini? Seluruh catatan iurannya ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Keluarkan peserta">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Catat iuran rinci --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
            <h2 class="font-semibold text-slate-800 mb-1">Catat Iuran Rinci</h2>
            <p class="text-sm text-slate-500 mb-4">Gunakan bila nominalnya berbeda dari iuran baku, dibayar di tanggal lain, atau lewat transfer.</p>

            <form action="{{ route('arisan.iuran.bayar', $arisan) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label for="rinci_anggota" class="block text-sm font-medium text-slate-700 mb-1.5">Peserta <span class="text-red-500">*</span></label>
                    <select id="rinci_anggota" name="anggota_keluarga_id" required
                            class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                        <option value="">— Pilih peserta —</option>
                        @foreach ($peserta->sortBy(fn ($p) => $p->pivot->urutan) as $orang)
                            <option value="{{ $orang->id }}">{{ $orang->pivot->urutan }}. {{ $orang->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="rinci_periode" class="block text-sm font-medium text-slate-700 mb-1.5">Periode <span class="text-red-500">*</span></label>
                    <select id="rinci_periode" name="periode_ke" required
                            class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                        @for ($i = 1; $i <= $jumlahPeriode; $i++)
                            <option value="{{ $i }}" @selected($i === $periodeAktif)>{{ $arisan->labelPeriode($i) }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="rinci_nominal" class="block text-sm font-medium text-slate-700 mb-1.5">Nominal (Rp)</label>
                    <input type="number" id="rinci_nominal" name="nominal" min="0" step="1000"
                           value="{{ (int) $arisan->nominal_iuran }}"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                </div>

                <div>
                    <label for="rinci_tanggal" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Bayar</label>
                    <input type="date" id="rinci_tanggal" name="tanggal_bayar" value="{{ now()->toDateString() }}"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                </div>

                <div>
                    <label for="rinci_metode" class="block text-sm font-medium text-slate-700 mb-1.5">Metode</label>
                    <select id="rinci_metode" name="metode"
                            class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>

                <div>
                    <label for="rinci_ket" class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan</label>
                    <input type="text" id="rinci_ket" name="keterangan" maxlength="255" placeholder="mis. dititipkan ke bendahara"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" @disabled($arisan->status !== 'aktif' || $peserta->isEmpty())
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors disabled:bg-slate-300 disabled:cursor-not-allowed">
                        Simpan Catatan Iuran
                    </button>
                </div>
            </form>
        </div>

        {{-- Panel samping --}}
        <div class="space-y-4">
            @if ($arisan->status === 'aktif' && $belumDapat->isNotEmpty())
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <h2 class="font-semibold text-slate-800 mb-1">Undian Giliran</h2>
                    <p class="text-sm text-slate-500 mb-3">{{ $belumDapat->count() }} peserta belum kebagian giliran.</p>
                    <form action="{{ route('arisan.undian', $arisan) }}" method="POST"
                          onsubmit="return confirm('Undi pemenang dari {{ $belumDapat->count() }} peserta yang belum dapat giliran?')">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Undi Pemenang
                        </button>
                    </form>
                </div>
            @endif

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
                        <dd class="font-medium text-slate-800 text-right">{{ optional($arisan->rekening)->nama ?? 'Tidak dikaitkan' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Catatan iuran</dt>
                        <dd class="font-medium text-slate-800 tabular-nums">{{ $ringkasan['catatan_iuran'] }} baris</dd>
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
                <p class="text-sm text-slate-500 mb-3">Seluruh peserta, giliran, dan catatan iuran ikut terhapus dan tidak bisa dikembalikan.</p>
                <form action="{{ route('arisan.destroy', $arisan) }}" method="POST"
                      onsubmit="return confirm('Hapus arisan {{ $arisan->nama }} beserta seluruh peserta dan catatan iurannya?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors">Hapus Arisan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
