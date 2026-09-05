@extends('layouts.app')

@section('title', 'Ubah Arisan')

@section('content')
<div class="space-y-4">
    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('arisan.index') }}" class="text-teal-600 hover:underline font-medium">Arisan RT</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Ubah</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Ubah Arisan</h1>
        </div>
        <a href="{{ route('arisan.show', $arisan) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('arisan.update', $arisan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="rounded-xl p-6 mb-6" style="background: linear-gradient(135deg, #14b8a6, #0d9488, #0f766e);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">{{ $arisan->nama }}</h2>
                    <p class="text-sm text-white/80">{{ $arisan->peserta->count() }} peserta terdaftar</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Arisan <span class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" required maxlength="255"
                           value="{{ old('nama', $arisan->nama) }}"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                </div>

                <div>
                    <label for="nominal_iuran" class="block text-sm font-medium text-slate-700 mb-1.5">Nominal Iuran (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="nominal_iuran" name="nominal_iuran" required min="1000" step="1000"
                           value="{{ old('nominal_iuran', (int) $arisan->nominal_iuran) }}"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                </div>

                <div>
                    <label for="periode" class="block text-sm font-medium text-slate-700 mb-1.5">Periode <span class="text-red-500">*</span></label>
                    <select id="periode" name="periode" required
                            class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                        @foreach (['mingguan' => 'Mingguan', 'bulanan' => 'Bulanan'] as $nilai => $label)
                            <option value="{{ $nilai }}" @selected(old('periode', $arisan->periode) === $nilai)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" required
                           value="{{ old('tanggal_mulai', optional($arisan->tanggal_mulai)->format('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                </div>

                <div>
                    <label for="mode_undian" class="block text-sm font-medium text-slate-700 mb-1.5">Mode Undian <span class="text-red-500">*</span></label>
                    <select id="mode_undian" name="mode_undian" required
                            class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                        @foreach (['otomatis' => 'Otomatis (diundi sistem)', 'manual' => 'Manual (ditentukan pengurus)'] as $nilai => $label)
                            <option value="{{ $nilai }}" @selected(old('mode_undian', $arisan->mode_undian) === $nilai)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jumlah_pemenang_per_pertemuan" class="block text-sm font-medium text-slate-700 mb-1.5">Pemenang per Pertemuan <span class="text-red-500">*</span></label>
                    <input type="number" id="jumlah_pemenang_per_pertemuan" name="jumlah_pemenang_per_pertemuan" required min="1"
                           value="{{ old('jumlah_pemenang_per_pertemuan', $arisan->jumlah_pemenang_per_pertemuan ?? 1) }}"
                           class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select id="status" name="status" required
                            class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                        @foreach (['aktif' => 'Aktif', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $nilai => $label)
                            <option value="{{ $nilai }}" @selected(old('status', $arisan->status) === $nilai)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="rekening_kas_id" class="block text-sm font-medium text-slate-700 mb-1.5">Rekening Kas</label>
                    <select id="rekening_kas_id" name="rekening_kas_id"
                            class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">
                        <option value="">— Tidak dikaitkan —</option>
                        @foreach ($rekenings as $rekening)
                            <option value="{{ $rekening->id }}" @selected((string) old('rekening_kas_id', $arisan->rekening_kas_id) === (string) $rekening->id)>
                                {{ $rekening->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none">{{ old('keterangan', $arisan->keterangan) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('arisan.show', $arisan) }}" class="px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
