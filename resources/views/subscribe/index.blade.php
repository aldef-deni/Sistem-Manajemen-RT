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
            <p class="mt-1 text-sm text-slate-500">Atur status, role pengguna, dan informasi pembayaran subscribe.</p>
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

    <form action="{{ route('subscribe.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                @php $status = old('status', $subscribe['status']); @endphp
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
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14zM9 7a3 3 0 116 0 3 3 0 01-6 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Role Pengguna Subscribe</h2>
                        <p class="text-xs text-slate-500">Pilih satu, beberapa, atau semua role yang wajib menggunakan subscribe.</p>
                    </div>
                </div>
            </div>

            @php
                $selectedRoles = old('roles', $subscribe['roles']);
                $selectedRoles = is_array($selectedRoles) ? $selectedRoles : [];
                $semuaRoleDipilih = count(array_intersect(array_keys($roleOptions), $selectedRoles)) === count($roleOptions);
            @endphp

            <div class="p-5">
                <label class="subscribe-select-all">
                    <input id="subscribe-semua-role" type="checkbox" {{ $semuaRoleDipilih ? 'checked' : '' }}>
                    <span>
                        <span class="subscribe-checkbox-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>
                            <strong>Pilih semua role</strong>
                            <small>Ketua RT, Pengurus RT, dan Warga</small>
                        </span>
                    </span>
                </label>

                <div class="subscribe-role-grid">
                    @foreach($roleOptions as $role => $option)
                        <label class="subscribe-role-option">
                            <input class="subscribe-role-input" type="checkbox" name="roles[]" value="{{ $role }}"
                                {{ in_array($role, $selectedRoles, true) ? 'checked' : '' }}>
                            <span>
                                <span class="subscribe-checkbox-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span>
                                    <strong>{{ $option['label'] }}</strong>
                                    <small>{{ $option['deskripsi'] }}</small>
                                </span>
                            </span>
                        </label>
                    @endforeach
                </div>

                @error('roles') <p class="mt-3 text-xs text-red-600">{{ $message }}</p> @enderror
                @error('roles.*') <p class="mt-3 text-xs text-red-600">{{ $message }}</p> @enderror

                <div class="mt-4 flex items-start gap-2 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-xs text-blue-700">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Role Administrator selalu bebas subscribe dan tidak dapat dipilih.</span>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m4 0h4M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800">Informasi Pembayaran</h2>
                            <p class="text-xs text-slate-500">Tambahkan satu atau beberapa tujuan Bank dan E-Wallet.</p>
                        </div>
                    </div>
                    <span id="payment-method-count" class="inline-flex w-fit items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700"></span>
                </div>
            </div>

            @php
                $paymentMethodValues = old('payment_methods', $subscribe['payment_methods']);
                $paymentMethodValues = is_array($paymentMethodValues) ? array_values($paymentMethodValues) : [];
                $storedPaymentMethodsById = collect($subscribe['payment_methods'])->keyBy('id');
                if ($paymentMethodValues === []) {
                    $paymentMethodValues = [['type' => 'bank', 'provider' => '', 'account_number' => '', 'account_name' => '']];
                }
            @endphp

            <div class="space-y-5 p-5">
                <div>
                    <label for="harga" class="mb-2 block text-sm font-semibold text-slate-700">Harga Subscribe</label>
                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-100">
                        <span class="flex items-center border-r border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-500">Rp</span>
                        <input id="harga" name="harga" type="number" inputmode="numeric" min="1" max="999999999999" step="1"
                            value="{{ old('harga', $subscribe['harga']) }}" placeholder="Contoh: 25000"
                            class="w-full border-0 px-4 py-3 text-sm text-slate-800 outline-none focus:ring-0">
                    </div>
                    @error('harga') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                @error('payment_methods')
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-semibold text-red-700">{{ $message }}</div>
                @enderror

                <div id="payment-method-list" class="space-y-4">
                    @foreach($paymentMethodValues as $index => $method)
                        @php
                            $methodType = ($method['type'] ?? 'bank') === 'ewallet' ? 'ewallet' : 'bank';
                            $existingMethodId = $method['existing_id'] ?? $method['id'] ?? null;
                            $storedMethod = $existingMethodId ? $storedPaymentMethodsById->get($existingMethodId) : null;
                            $qrisPath = $method['qris_path'] ?? $storedMethod['qris_path'] ?? null;
                        @endphp
                        <article class="subscription-payment-method overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/70" data-payment-method>
                            @if($existingMethodId)
                                <input type="hidden" name="payment_methods[{{ $index }}][existing_id]" value="{{ $existingMethodId }}">
                            @endif
                            <div class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="payment-method-number flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-xs font-black text-white">{{ $index + 1 }}</span>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Tujuan Pembayaran</p>
                                        <p class="payment-method-summary text-xs text-slate-400">{{ $methodType === 'ewallet' ? 'E-Wallet' : 'Bank' }}</p>
                                    </div>
                                </div>
                                <button type="button" class="remove-payment-method inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Hapus
                                </button>
                            </div>

                            <div class="grid gap-4 p-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis Pembayaran</label>
                                    <select name="payment_methods[{{ $index }}][type]" class="payment-method-type w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                        <option value="bank" {{ $methodType === 'bank' ? 'selected' : '' }}>Bank</option>
                                        <option value="ewallet" {{ $methodType === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                                    </select>
                                    @error("payment_methods.$index.type") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="payment-provider-label mb-2 block text-sm font-semibold text-slate-700">{{ $methodType === 'ewallet' ? 'Nama E-Wallet' : 'Nama Bank' }}</label>
                                    <input name="payment_methods[{{ $index }}][provider]" type="text" maxlength="100" value="{{ $method['provider'] ?? '' }}"
                                        placeholder="{{ $methodType === 'ewallet' ? 'Contoh: DANA' : 'Contoh: BCA' }}" class="payment-provider-input w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                    @error("payment_methods.$index.provider") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="payment-number-label mb-2 block text-sm font-semibold text-slate-700">{{ $methodType === 'ewallet' ? 'Nomor E-Wallet' : 'Nomor Rekening' }}</label>
                                    <input name="payment_methods[{{ $index }}][account_number]" type="text" inputmode="tel" maxlength="50" value="{{ $method['account_number'] ?? '' }}"
                                        placeholder="{{ $methodType === 'ewallet' ? 'Contoh: 081234567890' : 'Contoh: 1234567890' }}" class="payment-number-input w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-mono text-sm tracking-wide text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                    @error("payment_methods.$index.account_number") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Pemilik Akun</label>
                                    <input name="payment_methods[{{ $index }}][account_name]" type="text" maxlength="150" value="{{ $method['account_name'] ?? '' }}"
                                        placeholder="Nama sesuai rekening atau akun" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                    @error("payment_methods.$index.account_name") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2 rounded-2xl border border-dashed border-violet-200 bg-white p-4" data-qris-area data-has-existing-qris="{{ $qrisPath ? '1' : '0' }}">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                        <div class="relative flex h-32 w-full shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 sm:w-32">
                                            <img @if($qrisPath) src="{{ route('subscribe.payment.qris', ['paymentMethod' => $existingMethodId]) }}" @endif alt="Preview QRIS {{ $method['provider'] ?? '' }}" class="payment-qris-preview h-full w-full object-contain p-2 {{ $qrisPath ? '' : 'hidden' }}">
                                            <div class="payment-qris-placeholder flex flex-col items-center text-slate-400 {{ $qrisPath ? 'hidden' : '' }}">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm11 0h2m3 0v2m-6 4h2m2-3h2v3h-3"/></svg>
                                                <span class="mt-2 text-[10px] font-bold uppercase tracking-wider">Belum Ada QRIS</span>
                                            </div>
                                            <span class="payment-qris-badge absolute left-2 top-2 rounded-full bg-violet-600 px-2 py-1 text-[9px] font-black uppercase tracking-wider text-white {{ $qrisPath ? '' : 'hidden' }}">QRIS</span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-bold text-slate-800">Gambar QRIS <span class="font-normal text-slate-400">(opsional)</span></p>
                                            <p class="payment-qris-state mt-1 text-xs leading-5 text-slate-500">{{ $qrisPath ? 'QRIS tersimpan dan akan ditampilkan kepada warga.' : 'Unggah QRIS agar warga dapat memilih transfer atau memindai kode.' }}</p>
                                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                                <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12L8 8m4-4 4 4"/></svg>
                                                    <span class="payment-qris-upload-label">{{ $qrisPath ? 'Ganti QRIS' : 'Upload QRIS' }}</span>
                                                    <input type="file" name="payment_methods[{{ $index }}][qris]" accept=".jpg,.jpeg,.png,.webp" class="payment-qris-input sr-only">
                                                </label>
                                                <label class="payment-qris-remove-control inline-flex cursor-pointer items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-xs font-bold text-red-600 {{ $qrisPath ? '' : 'hidden' }}">
                                                    <input type="checkbox" name="payment_methods[{{ $index }}][remove_qris]" value="1" class="payment-qris-remove rounded border-red-300 text-red-600 focus:ring-red-500" {{ old("payment_methods.$index.remove_qris") ? 'checked' : '' }}>
                                                    Hapus QRIS
                                                </label>
                                            </div>
                                            <p class="payment-qris-error mt-2 hidden text-xs font-semibold text-red-600"></p>
                                            <p class="mt-2 text-[11px] text-slate-400">JPG, PNG, atau WEBP · maksimal 3 MB · minimal 200×200 piksel.</p>
                                            @error("payment_methods.$index.qris") <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <button id="add-payment-method" type="button" class="flex w-full items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-blue-200 bg-blue-50/50 px-4 py-4 text-sm font-bold text-blue-700 transition hover:border-blue-400 hover:bg-blue-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Bank atau E-Wallet
                </button>
                <p class="text-center text-xs text-slate-400">Maksimal 10 tujuan pembayaran. Warga dapat memilih salah satunya saat membayar.</p>
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

<template id="payment-method-template">
    <article class="subscription-payment-method overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/70" data-payment-method>
        <div class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3">
            <div class="flex items-center gap-3">
                <span class="payment-method-number flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-xs font-black text-white"></span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Tujuan Pembayaran</p>
                    <p class="payment-method-summary text-xs text-slate-400">Bank</p>
                </div>
            </div>
            <button type="button" class="remove-payment-method inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Hapus
            </button>
        </div>
        <div class="grid gap-4 p-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis Pembayaran</label>
                <select name="payment_methods[__INDEX__][type]" class="payment-method-type w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="bank">Bank</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>
            <div>
                <label class="payment-provider-label mb-2 block text-sm font-semibold text-slate-700">Nama Bank</label>
                <input name="payment_methods[__INDEX__][provider]" type="text" maxlength="100" placeholder="Contoh: BCA" class="payment-provider-input w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>
            <div>
                <label class="payment-number-label mb-2 block text-sm font-semibold text-slate-700">Nomor Rekening</label>
                <input name="payment_methods[__INDEX__][account_number]" type="text" inputmode="tel" maxlength="50" placeholder="Contoh: 1234567890" class="payment-number-input w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-mono text-sm tracking-wide text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Pemilik Akun</label>
                <input name="payment_methods[__INDEX__][account_name]" type="text" maxlength="150" placeholder="Nama sesuai rekening atau akun" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>
            <div class="md:col-span-2 rounded-2xl border border-dashed border-violet-200 bg-white p-4" data-qris-area data-has-existing-qris="0">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div class="relative flex h-32 w-full shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 sm:w-32">
                        <img alt="Preview QRIS" class="payment-qris-preview hidden h-full w-full object-contain p-2">
                        <div class="payment-qris-placeholder flex flex-col items-center text-slate-400">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm11 0h2m3 0v2m-6 4h2m2-3h2v3h-3"/></svg>
                            <span class="mt-2 text-[10px] font-bold uppercase tracking-wider">Belum Ada QRIS</span>
                        </div>
                        <span class="payment-qris-badge absolute left-2 top-2 hidden rounded-full bg-violet-600 px-2 py-1 text-[9px] font-black uppercase tracking-wider text-white">QRIS</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-800">Gambar QRIS <span class="font-normal text-slate-400">(opsional)</span></p>
                        <p class="payment-qris-state mt-1 text-xs leading-5 text-slate-500">Unggah QRIS agar warga dapat memilih transfer atau memindai kode.</p>
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12L8 8m4-4 4 4"/></svg>
                                <span class="payment-qris-upload-label">Upload QRIS</span>
                                <input type="file" name="payment_methods[__INDEX__][qris]" accept=".jpg,.jpeg,.png,.webp" class="payment-qris-input sr-only">
                            </label>
                            <label class="payment-qris-remove-control hidden cursor-pointer items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-xs font-bold text-red-600">
                                <input type="checkbox" name="payment_methods[__INDEX__][remove_qris]" value="1" class="payment-qris-remove rounded border-red-300 text-red-600 focus:ring-red-500">
                                Hapus QRIS
                            </label>
                        </div>
                        <p class="payment-qris-error mt-2 hidden text-xs font-semibold text-red-600"></p>
                        <p class="mt-2 text-[11px] text-slate-400">JPG, PNG, atau WEBP · maksimal 3 MB · minimal 200×200 piksel.</p>
                    </div>
                </div>
            </div>
        </div>
    </article>
</template>

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
    .subscribe-select-all,
    .subscribe-role-option { display: block; cursor: pointer; }
    .subscribe-select-all > input,
    .subscribe-role-option > input { position: absolute; opacity: 0; pointer-events: none; }
    .subscribe-select-all > span,
    .subscribe-role-option > span {
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 13px 14px;
        transition: all .15s ease;
    }
    .subscribe-select-all > span { border-style: dashed; background: #f8fafc; }
    .subscribe-select-all strong,
    .subscribe-role-option strong { display: block; color: #334155; font-size: 14px; }
    .subscribe-select-all small,
    .subscribe-role-option small { display: block; margin-top: 2px; color: #64748b; font-size: 11px; line-height: 1.4; }
    .subscribe-role-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 12px;
    }
    .subscribe-checkbox-icon {
        display: flex;
        width: 22px;
        height: 22px;
        flex: 0 0 22px;
        align-items: center;
        justify-content: center;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #fff;
        color: transparent;
    }
    .subscribe-checkbox-icon svg { width: 14px; height: 14px; }
    .subscribe-select-all > input:checked + span,
    .subscribe-role-option > input:checked + span { border-color: #8b5cf6; background: #f5f3ff; }
    .subscribe-select-all > input:checked + span .subscribe-checkbox-icon,
    .subscribe-role-option > input:checked + span .subscribe-checkbox-icon { border-color: #8b5cf6; background: #8b5cf6; color: #fff; }
    .subscribe-select-all > input:focus-visible + span,
    .subscribe-role-option > input:focus-visible + span { outline: 2px solid #8b5cf6; outline-offset: 2px; }
    @media (max-width: 520px) {
        .subscribe-status-grid { grid-template-columns: 1fr; }
        .subscribe-role-grid { grid-template-columns: 1fr; }
    }
    @media (min-width: 521px) and (max-width: 900px) {
        .subscribe-role-grid { grid-template-columns: 1fr; }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const semuaRole = document.getElementById('subscribe-semua-role');
        const pilihanRole = Array.from(document.querySelectorAll('.subscribe-role-input'));

        if (semuaRole && pilihanRole.length > 0) {
            const sinkronkanPilihSemua = function () {
                const jumlahDipilih = pilihanRole.filter(function (input) { return input.checked; }).length;
                semuaRole.checked = jumlahDipilih === pilihanRole.length;
                semuaRole.indeterminate = jumlahDipilih > 0 && jumlahDipilih < pilihanRole.length;
            };

            semuaRole.addEventListener('change', function () {
                pilihanRole.forEach(function (input) { input.checked = semuaRole.checked; });
                semuaRole.indeterminate = false;
            });

            pilihanRole.forEach(function (input) {
                input.addEventListener('change', sinkronkanPilihSemua);
            });

            sinkronkanPilihSemua();
        }

        const methodList = document.getElementById('payment-method-list');
        const methodTemplate = document.getElementById('payment-method-template');
        const addMethodButton = document.getElementById('add-payment-method');
        const methodCount = document.getElementById('payment-method-count');
        let nextMethodIndex = methodList?.querySelectorAll('[data-payment-method]').length ?? 0;

        if (! methodList || ! methodTemplate || ! addMethodButton || ! methodCount) return;

        const updateMethodLabels = function (card) {
            const isEwallet = card.querySelector('.payment-method-type')?.value === 'ewallet';
            const providerLabel = card.querySelector('.payment-provider-label');
            const providerInput = card.querySelector('.payment-provider-input');
            const numberLabel = card.querySelector('.payment-number-label');
            const numberInput = card.querySelector('.payment-number-input');
            const summary = card.querySelector('.payment-method-summary');

            if (providerLabel) providerLabel.textContent = isEwallet ? 'Nama E-Wallet' : 'Nama Bank';
            if (providerInput) providerInput.placeholder = isEwallet ? 'Contoh: DANA' : 'Contoh: BCA';
            if (numberLabel) numberLabel.textContent = isEwallet ? 'Nomor E-Wallet' : 'Nomor Rekening';
            if (numberInput) numberInput.placeholder = isEwallet ? 'Contoh: 081234567890' : 'Contoh: 1234567890';
            if (summary) summary.textContent = isEwallet ? 'E-Wallet' : 'Bank';
        };

        const revokeQrisPreview = function (card) {
            const qrisArea = card.querySelector('[data-qris-area]');
            if (qrisArea?.dataset.previewUrl) {
                URL.revokeObjectURL(qrisArea.dataset.previewUrl);
                delete qrisArea.dataset.previewUrl;
            }
        };

        const updateQrisRemovalState = function (card) {
            const qrisArea = card.querySelector('[data-qris-area]');
            const removeInput = card.querySelector('.payment-qris-remove');
            const preview = card.querySelector('.payment-qris-preview');
            const state = card.querySelector('.payment-qris-state');
            if (! qrisArea || ! removeInput || ! preview || ! state) return;

            preview.classList.toggle('opacity-30', removeInput.checked);
            if (removeInput.checked) {
                state.textContent = 'QRIS akan dihapus saat pengaturan disimpan.';
                state.classList.add('font-semibold', 'text-red-600');
            } else {
                state.textContent = qrisArea.dataset.previewUrl
                    ? 'QRIS baru siap diupload. Pastikan gambar dapat dipindai dengan jelas.'
                    : (qrisArea.dataset.hasExistingQris === '1'
                        ? 'QRIS tersimpan dan akan ditampilkan kepada warga.'
                        : 'Unggah QRIS agar warga dapat memilih transfer atau memindai kode.');
                state.classList.remove('font-semibold', 'text-red-600');
            }
        };

        const previewQris = function (input) {
            const card = input.closest('[data-payment-method]');
            const qrisArea = card?.querySelector('[data-qris-area]');
            const preview = card?.querySelector('.payment-qris-preview');
            const placeholder = card?.querySelector('.payment-qris-placeholder');
            const badge = card?.querySelector('.payment-qris-badge');
            const uploadLabel = card?.querySelector('.payment-qris-upload-label');
            const error = card?.querySelector('.payment-qris-error');
            const removeInput = card?.querySelector('.payment-qris-remove');
            const file = input.files?.[0];

            if (! card || ! qrisArea || ! preview || ! placeholder || ! badge || ! uploadLabel || ! error || ! file) return;

            error.textContent = '';
            error.classList.add('hidden');
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            if (! allowedTypes.includes(file.type)) {
                input.value = '';
                error.textContent = 'QRIS harus berformat JPG, PNG, atau WEBP.';
                error.classList.remove('hidden');
                return;
            }

            if (file.size > 3 * 1024 * 1024) {
                input.value = '';
                error.textContent = 'Ukuran gambar QRIS maksimal 3 MB.';
                error.classList.remove('hidden');
                return;
            }

            revokeQrisPreview(card);
            const previewUrl = URL.createObjectURL(file);
            qrisArea.dataset.previewUrl = previewUrl;
            preview.src = previewUrl;
            preview.classList.remove('hidden', 'opacity-30');
            placeholder.classList.add('hidden');
            badge.classList.remove('hidden');
            uploadLabel.textContent = 'Ganti QRIS';
            if (removeInput) removeInput.checked = false;
            updateQrisRemovalState(card);
        };

        const refreshMethods = function () {
            const cards = Array.from(methodList.querySelectorAll('[data-payment-method]'));
            cards.forEach(function (card, index) {
                const number = card.querySelector('.payment-method-number');
                const removeButton = card.querySelector('.remove-payment-method');
                if (number) number.textContent = String(index + 1);
                if (removeButton) removeButton.classList.toggle('hidden', cards.length === 1);
                updateMethodLabels(card);
                updateQrisRemovalState(card);
            });

            methodCount.textContent = `${cards.length} metode tersedia`;
            addMethodButton.disabled = cards.length >= 10;
            addMethodButton.classList.toggle('hidden', cards.length >= 10);
        };

        addMethodButton.addEventListener('click', function () {
            if (methodList.querySelectorAll('[data-payment-method]').length >= 10) return;

            const html = methodTemplate.innerHTML.replaceAll('__INDEX__', String(nextMethodIndex++));
            methodList.insertAdjacentHTML('beforeend', html);
            refreshMethods();
            methodList.lastElementChild?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        methodList.addEventListener('click', function (event) {
            const removeButton = event.target.closest('.remove-payment-method');
            if (! removeButton || methodList.querySelectorAll('[data-payment-method]').length <= 1) return;
            const card = removeButton.closest('[data-payment-method]');
            if (card) {
                revokeQrisPreview(card);
                card.remove();
            }
            refreshMethods();
        });

        methodList.addEventListener('change', function (event) {
            const card = event.target.closest('[data-payment-method]');
            if (! card) return;

            if (event.target.matches('.payment-method-type')) updateMethodLabels(card);
            if (event.target.matches('.payment-qris-input')) previewQris(event.target);
            if (event.target.matches('.payment-qris-remove')) updateQrisRemovalState(card);
        });

        window.addEventListener('beforeunload', function () {
            methodList.querySelectorAll('[data-payment-method]').forEach(revokeQrisPreview);
        });

        refreshMethods();
    });
</script>
@endpush
@endsection
