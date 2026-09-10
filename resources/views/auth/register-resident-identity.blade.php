@extends('layouts.guest')

@section('title', 'Verifikasi Data Warga')

@section('content')
<div class="min-h-screen px-4 py-8 sm:px-6 lg:py-12">
    <div class="mx-auto max-w-5xl overflow-hidden rounded-3xl border border-white/70 bg-white shadow-2xl shadow-blue-900/10">
        <div class="grid lg:grid-cols-[0.9fr_1.1fr]">
            <aside class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-blue-700 p-7 text-white sm:p-10">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-cyan-400/20 blur-3xl"></div>
                <div class="relative flex h-full flex-col">
                    <a href="{{ route('login') }}" class="inline-flex w-fit items-center gap-2 text-xs font-semibold text-blue-100 hover:text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali ke login
                    </a>
                    <img src="{{ asset('images/aldef-landscape.png') }}" alt="Aldef Tech" class="mt-10 h-14 w-auto self-start">
                    <p class="mt-8 text-xs font-bold uppercase tracking-[0.2em] text-blue-200">Pendaftaran Mandiri</p>
                    <h1 class="mt-3 text-3xl font-extrabold leading-tight">Akun Warga untuk Kepala Keluarga</h1>
                    <p class="mt-4 text-sm leading-6 text-blue-100">Gunakan NIK dan Nomor KK yang sudah dicatat pengurus RT. Sistem hanya mengizinkan satu akun Warga untuk setiap Kepala Keluarga.</p>

                    <div class="mt-8 space-y-3">
                        <div class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-cyan-300 text-xs font-bold text-blue-950">1</span>
                            <div><p class="text-sm font-bold">Verifikasi identitas</p><p class="mt-1 text-xs text-blue-100">Cocokkan NIK dan Nomor KK.</p></div>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/5 p-4">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white/15 text-xs font-bold">2</span>
                            <div><p class="text-sm font-bold">Buat akun</p><p class="mt-1 text-xs text-blue-100">Tentukan username dan password pribadi.</p></div>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="p-7 sm:p-10 lg:p-12">
                <div class="mx-auto max-w-lg">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Langkah 1 dari 2</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900">Verifikasi data Anda</h2>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </span>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Masukkan identitas persis seperti data yang telah didaftarkan oleh pengurus RT.</p>

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                            <div class="flex gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 4.5h.008v.008H12V16.5z"/></svg>
                                <div><p class="font-bold">Data belum dapat diverifikasi</p><p class="mt-1 leading-5">{{ $errors->first() }}</p></div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.resident.verify') }}" class="mt-7 space-y-5">
                        @csrf
                        <div>
                            <label for="nik" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">NIK Kepala Keluarga</label>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a3 3 0 11-6 0 3 3 0 016 0zm-9 14v-1a6 6 0 0112 0v1M5 3h14a2 2 0 012 2v16H3V5a2 2 0 012-2z"/></svg>
                                <input id="nik" name="nik" type="text" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" required autocomplete="off" placeholder="16 digit NIK" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm tracking-wider text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                            </div>
                        </div>
                        <div>
                            <label for="no_kk" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Nomor Kartu Keluarga</label>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                                <input id="no_kk" name="no_kk" type="text" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" required autocomplete="off" placeholder="16 digit Nomor KK" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm tracking-wider text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                            </div>
                        </div>
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/20 transition hover:from-blue-700 hover:to-blue-800">
                            Verifikasi Data
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </form>

                    <div class="mt-6 flex items-start gap-3 rounded-xl bg-slate-50 p-4 text-xs leading-5 text-slate-500">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p>NIK dan Nomor KK hanya dipakai untuk mencocokkan data. Nilainya tidak disimpan dalam sesi pendaftaran dan tidak akan ditampilkan kembali.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection
