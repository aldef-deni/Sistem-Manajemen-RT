@extends('layouts.guest')

@section('title', 'Buat Akun Warga')

@section('content')
<div class="min-h-screen px-4 py-8 sm:px-6 lg:py-12">
    <div class="mx-auto max-w-5xl overflow-hidden rounded-3xl border border-white/70 bg-white shadow-2xl shadow-blue-900/10">
        <div class="grid lg:grid-cols-[0.9fr_1.1fr]">
            <aside class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-emerald-950 to-emerald-700 p-7 text-white sm:p-10">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-cyan-400/20 blur-3xl"></div>
                <div class="relative flex h-full flex-col">
                    <a href="{{ route('register.resident.identity') }}" class="inline-flex w-fit items-center gap-2 text-xs font-semibold text-emerald-100 hover:text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Verifikasi ulang
                    </a>
                    <img src="{{ asset('images/aldef-landscape.png') }}" alt="Aldef Tech" class="mt-10 h-14 w-auto self-start">
                    <p class="mt-8 text-xs font-bold uppercase tracking-[0.2em] text-emerald-200">Identitas Terverifikasi</p>
                    <h1 class="mt-3 text-3xl font-extrabold leading-tight">{{ $member->nama_lengkap }}</h1>
                    <div class="mt-5 space-y-3 rounded-2xl border border-white/10 bg-white/10 p-5 text-sm backdrop-blur">
                        <div class="flex items-center justify-between gap-4"><span class="text-emerald-100">NIK</span><span class="font-semibold tracking-wider">••••••••••••{{ substr($member->nik, -4) }}</span></div>
                        <div class="flex items-center justify-between gap-4"><span class="text-emerald-100">Nomor KK</span><span class="font-semibold tracking-wider">••••••••••••{{ substr($member->kartuKeluarga->no_kk, -4) }}</span></div>
                        <div class="flex items-center justify-between gap-4"><span class="text-emerald-100">Status</span><span class="rounded-full bg-emerald-300/20 px-2.5 py-1 text-xs font-bold text-emerald-100">Kepala Keluarga</span></div>
                    </div>
                    <div class="mt-6 flex items-start gap-3 text-xs leading-5 text-emerald-100">
                        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <p>Akun akan otomatis memiliki role Warga dan langsung terhubung ke data ini.</p>
                    </div>
                </div>
            </aside>

            <main class="p-7 sm:p-10 lg:p-12">
                <div class="mx-auto max-w-lg">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-600">Langkah 2 dari 2</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900">Buat akun login</h2>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Verifikasi berlaku selama 15 menit. Gunakan username dan password yang mudah Anda ingat.</p>

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                            <p class="font-bold">Periksa kembali data akun:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.resident.store') }}" class="mt-7 space-y-4">
                        @csrf
                        <div>
                            <label for="username" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Username</label>
                            <input id="username" name="username" value="{{ old('username') }}" required minlength="4" maxlength="50" autocomplete="username" placeholder="Contoh: budi.rt" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                            </div>
                            <div>
                                <label for="no_hp" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">No. HP / WhatsApp</label>
                                <input id="no_hp" name="no_hp" value="{{ old('no_hp', $member->no_hp) }}" maxlength="20" autocomplete="tel" placeholder="08xxxxxxxxxx" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="password" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Password</label>
                                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                            </div>
                            <div>
                                <label for="password_confirmation" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Ulangi Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" placeholder="Ketik ulang password" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                            </div>
                        </div>
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:from-emerald-700 hover:to-teal-700">
                            Buat Akun Warga
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection
