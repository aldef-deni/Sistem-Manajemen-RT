@extends('layouts.app')

@section('title', 'Buat Akun')
@section('page-title', 'Kelola Akun')
@section('page-subtitle', 'Buat Akun Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Buat Akun Baru</h2>
            <p class="text-xs text-slate-400 mt-1">Buat akun untuk Ketua RT, Pengurus RT, atau Warga</p>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
            <p class="font-semibold mb-1">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('akun.store') }}">
        @csrf

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100/50 border-b border-blue-100">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <h3 class="text-sm font-bold text-blue-800">Informasi Akun</h3>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama pengguna"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Nama dipakai untuk login</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" required maxlength="50" placeholder="cth: budi.rt"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Email juga bisa dipakai untuk login</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">No HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" maxlength="20" placeholder="08xxxxxx"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Role <span class="text-red-500">*</span></label>
                        <select name="role" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih role...</option>
                            <option value="ketua" {{ old('role') === 'ketua' ? 'selected' : '' }}>Ketua RT</option>
                            <option value="pengurus" {{ old('role') === 'pengurus' ? 'selected' : '' }}>Pengurus RT</option>
                            <option value="warga" {{ old('role') === 'warga' ? 'selected' : '' }}>Warga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required minlength="6" placeholder="Ulangi password"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                </div>

                {{-- Role Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="p-3 rounded-lg bg-green-50 border border-green-200">
                        <p class="text-xs font-bold text-green-700">Ketua RT</p>
                        <p class="text-[11px] text-green-600 mt-0.5">Bisa membuat akun & mengelola CRUD kependudukan</p>
                    </div>
                    <div class="p-3 rounded-lg bg-amber-50 border border-amber-200">
                        <p class="text-xs font-bold text-amber-600">Pengurus RT</p>
                        <p class="text-[11px] text-amber-600 mt-0.5">Akses baca data kependudukan & fitur lainnya</p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                        <p class="text-xs font-bold text-slate-600">Warga</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Akses terbatas sesuai fitur warga</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('akun.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Akun
            </button>
        </div>
    </form>
</div>
@endsection