@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle')
    Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }}!
@endsection

@section('content')
<div class="space-y-6">
    {{-- Welcome & Info Row --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }}! 👋</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data warga dan keuangan RT Anda dengan mudah.</p>
        </div>
        <div class="info-box flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-800">RT 001 / RW 002</p>
                <p class="text-xs text-blue-600/70">Jl. Merdeka No. 10, Kel. Sukamaju, Kec. Menteng</p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        {{-- Total Warga --}}
        <div class="stat-card stat-card-blue">
            <p class="text-xs font-medium text-blue-100 uppercase tracking-wider">Total Warga</p>
            <p class="text-2xl font-extrabold mt-1">125</p>
            <p class="text-[11px] text-blue-200/70 mt-0.5">Terdaftar aktif</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>

        {{-- Iuran Bulanan --}}
        <div class="stat-card stat-card-green">
            <p class="text-xs font-medium text-green-100 uppercase tracking-wider">Iuran Bulanan</p>
            <p class="text-2xl font-extrabold mt-1">Rp 0</p>
            <p class="text-[11px] text-green-200/70 mt-0.5">Belum ada data</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
            </div>
        </div>

        {{-- Tagihan --}}
        <div class="stat-card stat-card-orange">
            <p class="text-xs font-medium text-orange-100 uppercase tracking-wider">Tagihan</p>
            <p class="text-2xl font-extrabold mt-1">Rp 1.050.000</p>
            <p class="text-[11px] text-orange-200/70 mt-0.5">Belum terbayar</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            </div>
        </div>

        {{-- Saldo Kas --}}
        <div class="stat-card stat-card-purple">
            <p class="text-xs font-medium text-purple-100 uppercase tracking-wider">Saldo Kas</p>
            <p class="text-2xl font-extrabold mt-1">Rp 300.000</p>
            <p class="text-[11px] text-purple-200/70 mt-0.5">Saldo saat ini</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
        </div>

        {{-- Total Transaksi --}}
        <div class="stat-card stat-card-yellow">
            <p class="text-xs font-medium text-yellow-100 uppercase tracking-wider">Total Transaksi</p>
            <p class="text-2xl font-extrabold mt-1">Rp 50.000</p>
            <p class="text-[11px] text-yellow-200/70 mt-0.5">Transaksi aktif</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
        </div>

        {{-- Pinjaman Aktif --}}
        <div class="stat-card stat-card-indigo">
            <p class="text-xs font-medium text-indigo-100 uppercase tracking-wider">Pinjaman Aktif</p>
            <p class="text-2xl font-extrabold mt-1">Rp 0</p>
            <p class="text-[11px] text-indigo-200/70 mt-0.5">Tidak ada pinjaman</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        {{-- Iuran CH3 RT --}}
        <div class="stat-card stat-card-emerald">
            <p class="text-xs font-medium text-emerald-100 uppercase tracking-wider">Iuran CH3 RT</p>
            <p class="text-2xl font-extrabold mt-1">0</p>
            <p class="text-[11px] text-emerald-200/70 mt-0.5">Belum terbayar</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        {{-- Total Unpaid --}}
        <div class="stat-card stat-card-red">
            <p class="text-xs font-medium text-red-100 uppercase tracking-wider">Total Unpaid</p>
            <p class="text-2xl font-extrabold mt-1">0</p>
            <p class="text-[11px] text-red-200/70 mt-0.5">Tunggakan</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- Jumlah Orang --}}
        <div class="stat-card stat-card-pink">
            <p class="text-xs font-medium text-pink-100 uppercase tracking-wider">Jumlah Orang</p>
            <p class="text-2xl font-extrabold mt-1">0</p>
            <p class="text-[11px] text-pink-200/70 mt-0.5">Data belum tersedia</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        {{-- Total Qurban --}}
        <div class="stat-card stat-card-teal">
            <p class="text-xs font-medium text-teal-100 uppercase tracking-wider">Total Qurban Peserta</p>
            <p class="text-2xl font-extrabold mt-1">Rp 0</p>
            <p class="text-[11px] text-teal-200/70 mt-0.5">Belum ada peserta</p>
            <div class="absolute top-3 right-3 opacity-20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Bottom Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pembayaran Baru Pending --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                    <h3 class="text-sm font-semibold text-slate-700">Pembayaran Baru Pending</h3>
                </div>
                <span class="text-xs text-slate-400">Menunggu Konfirmasi</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                <div>
                    <p class="text-2xl font-bold text-slate-800">0</p>
                    <p class="text-xs text-slate-400">Transaksi menunggu konfirmasi</p>
                </div>
                <a href="{{ route('pembayaran') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Lihat Detail
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Pembayaran Iuran Terakhir --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-400"></div>
                    <h3 class="text-sm font-semibold text-slate-700">Pembayaran Iuran Terakhir</h3>
                </div>
            </div>
            <div class="flex items-center justify-center p-4 bg-slate-50 rounded-lg min-h-[80px]">
                <p class="text-sm text-slate-400 italic">Belum ada pembayaran</p>
            </div>
        </div>
    </div>
</div>
@endsection
