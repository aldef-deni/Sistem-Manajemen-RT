@extends('layouts.app')

@section('title', 'Pengaturan Subscribe')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Subscribe</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan Subscribe</h1>
            <p class="mt-1 text-sm text-slate-500">Atur status dan informasi pembayaran subscribe untuk warga.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <div class="font-semibold mb-1">Pengaturan belum dapat disimpan:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('subscribe.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Status Subscribe</h2>
                        <p class="text-xs text-slate-500">Pilih apakah fitur subscribe warga sedang digunakan.</p>
                    </div>
                </div>
            </div>

            <div class="p-5">
                @php($status = old('status', $subscribe['status']))
                <div class="subscribe-status-grid" role="radiogroup" aria-label="Status fitur subscribe">
                    <label class="subscribe-status-option subscribe-status-active">
                        <input type="radio" name="status" value="aktif" {{ $status === 'aktif' ? 'checked' : '' }}>
                        <span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Aktif
                        </span>
                    </label>
                    <label class="subscribe-status-option subscribe-status-inactive">
                        <input type="radio" name="status" value="nonaktif" {{ $status === 'nonaktif' ? 'checked' : '' }}>
                        <span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tidak Aktif
                        </span>
                    </label>
                </div>
                <p class="mt-3 text-xs text-slate-500">Saat diaktifkan, seluruh informasi pembayaran di bawah wajib diisi.</p>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m4 0h4M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Informasi Pembayaran</h2>
                        <p class="text-xs text-slate-500">Data yang akan digunakan sebagai tujuan pembayaran warga.</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 p-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="harga" class="mb-2 block text-sm font-semibold text-slate-700">Harga Subscribe</label>
                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-100">
                        <span class="flex items-center border-r border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-500">Rp</span>
                        <input id="harga" name="harga" type="number" inputmode="numeric" min="1" max="999999999999" step="1"
                            value="{{ old('harga', $subscribe['harga']) }}" placeholder="Contoh: 25000"
                            class="w-full border-0 px-4 py-3 text-sm text-slate-800 outline-none focus:ring-0">
                    </div>
                    @error('harga') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="bank" class="mb-2 block text-sm font-semibold text-slate-700">Bank</label>
                    <input id="bank" name="bank" type="text" maxlength="100"
                        value="{{ old('bank', $subscribe['bank']) }}" placeholder="Contoh: Bank BCA"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    @error('bank') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nomor_rekening" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Rekening Pembayaran</label>
                    <input id="nomor_rekening" name="nomor_rekening" type="text" inputmode="numeric" maxlength="50"
                        value="{{ old('nomor_rekening', $subscribe['nomor_rekening']) }}" placeholder="Contoh: 1234567890"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    @error('nomor_rekening') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="nama_rekening" class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap Pemilik Rekening</label>
                    <input id="nama_rekening" name="nama_rekening" type="text" maxlength="150"
                        value="{{ old('nama_rekening', $subscribe['nama_rekening']) }}" placeholder="Nama sesuai rekening bank"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    @error('nama_rekening') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition hover:shadow-emerald-500/40">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<style>
    .subscribe-status-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        max-width: 520px;
    }
    .subscribe-status-option { cursor: pointer; }
    .subscribe-status-option input { position: absolute; opacity: 0; pointer-events: none; }
    .subscribe-status-option span {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 46px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
        transition: all .15s ease;
    }
    .subscribe-status-option svg { width: 18px; height: 18px; }
    .subscribe-status-option input:focus-visible + span { outline: 2px solid #10b981; outline-offset: 2px; }
    .subscribe-status-active input:checked + span { border-color: #10b981; background: #ecfdf5; color: #047857; }
    .subscribe-status-inactive input:checked + span { border-color: #f87171; background: #fef2f2; color: #b91c1c; }
    @media (max-width: 520px) {
        .subscribe-status-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
