@extends('layouts.app')

@section('title', 'Kas RT')

@section('content')
<div class="space-y-5">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Kas RT</h1>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Kas RT</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('kas-rt.pemasukan') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-semibold text-white transition-all shadow-md" style="background: linear-gradient(135deg, #10b981, #059669);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Pemasukan
            </a>
            <a href="{{ route('kas-rt.pengeluaran') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-semibold text-white transition-all shadow-md" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                Pengeluaran
            </a>
            <button class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all border-2" style="border-color: #14b8a6; color: #0d9488;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Master Kas
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stat Cards --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
        {{-- Pemasukan --}}
        <div style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(16,185,129,0.3);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.8125rem; font-weight: 500; opacity: 0.85; margin: 0;">Pemasukan Bulan Ini</p>
                    <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">Rp {{ number_format($pemasukanBulan, 0, ',', '.') }}</p>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 0.75rem; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
            </div>
        </div>

        {{-- Pengeluaran --}}
        <div style="background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(239,68,68,0.3);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.8125rem; font-weight: 500; opacity: 0.85; margin: 0;">Pengeluaran Bulan Ini</p>
                    <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">Rp {{ number_format($pengeluaranBulan, 0, ',', '.') }}</p>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 0.75rem; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
            </div>
        </div>

        {{-- Saldo --}}
        <div style="background: linear-gradient(135deg, #14b8a6, #0d9488); border-radius: 1rem; padding: 1.25rem; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(20,184,166,0.3);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.8125rem; font-weight: 500; opacity: 0.85; margin: 0;">Saldo Kas Saat Ini</p>
                    <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">Rp {{ number_format($saldoTotal, 0, ',', '.') }}</p>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 0.75rem; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Saldo per Rekening --}}
    <div style="background: white; border-radius: 1rem; border: 1px solid #e2e8f0; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
            <div style="width: 32px; height: 32px; border-radius: 0.5rem; background: #f0fdfa; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 16px; height: 16px; color: #0d9488;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
            </div>
            <h3 style="font-weight: 700; color: #1e293b; font-size: 0.9375rem;">Saldo per Rekening / Kas</h3>
        </div>
        <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.75rem;">
            @php
            $rekeningStyles = [
                'TAB-BRI' => ['bg' => 'linear-gradient(135deg, #eff6ff, #dbeafe)', 'border' => '#bfdbfe', 'badgeBg' => '#dbeafe', 'badgeColor' => '#1d4ed8', 'iconBg' => '#dbeafe'],
                'BRI' => ['bg' => 'linear-gradient(135deg, #f0fdfa, #ccfbf1)', 'border' => '#99f6e4', 'badgeBg' => '#ccfbf1', 'badgeColor' => '#0d9488', 'iconBg' => '#ccfbf1'],
                'BCA' => ['bg' => 'linear-gradient(135deg, #f5f3ff, #ede9fe)', 'border' => '#ddd6fe', 'badgeBg' => '#ede9fe', 'badgeColor' => '#7c3aed', 'iconBg' => '#ede9fe'],
                'MANDIRI' => ['bg' => 'linear-gradient(135deg, #eef2ff, #e0e7ff)', 'border' => '#c7d2fe', 'badgeBg' => '#e0e7ff', 'badgeColor' => '#4f46e5', 'iconBg' => '#e0e7ff'],
                'TUNAI' => ['bg' => 'linear-gradient(135deg, #ecfdf5, #d1fae5)', 'border' => '#a7f3d0', 'badgeBg' => '#d1fae5', 'badgeColor' => '#059669', 'iconBg' => '#d1fae5'],
                'DANA' => ['bg' => 'linear-gradient(135deg, #ecfeff, #cffafe)', 'border' => '#a5f3fc', 'badgeBg' => '#cffafe', 'badgeColor' => '#0891b2', 'iconBg' => '#cffafe'],
            ];
            @endphp

            @foreach($rekenings as $rk)
            @php $s = $rekeningStyles[$rk->nama] ?? ['bg' => '#f8fafc', 'border' => '#e2e8f0', 'badgeBg' => '#f1f5f9', 'badgeColor' => '#475569', 'iconBg' => '#f1f5f9']; @endphp
            <div style="background: {{ $s['bg'] }}; border: 1px solid {{ $s['border'] }}; border-radius: 0.75rem; padding: 0.875rem; text-align: center;">
                <div style="width: 40px; height: 40px; border-radius: 0.5rem; background: {{ $s['iconBg'] }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;">
                    @if($rk->jenis === 'TABUNGAN')
                    <svg style="width: 20px; height: 20px; color: {{ $s['badgeColor'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    @elseif($rk->jenis === 'TUNAI')
                    <svg style="width: 20px; height: 20px; color: {{ $s['badgeColor'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    @elseif($rk->jenis === 'E-WALLET')
                    <svg style="width: 20px; height: 20px; color: {{ $s['badgeColor'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    @else
                    <svg style="width: 20px; height: 20px; color: {{ $s['badgeColor'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                    @endif
                </div>
                <span style="display: inline-block; padding: 2px 8px; font-size: 10px; font-weight: 700; background: {{ $s['badgeBg'] }}; color: {{ $s['badgeColor'] }}; border-radius: 9999px; margin-bottom: 0.25rem;">{{ $rk->nama }}</span>
                <p style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-top: 0.25rem;">Rp {{ number_format($rk->saldo, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Riwayat Transaksi --}}
    <div style="background: white; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden;">
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 32px; height: 32px; border-radius: 0.5rem; background: #f0fdfa; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 16px; height: 16px; color: #0d9488;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 style="font-weight: 700; color: #1e293b; font-size: 0.9375rem;">Riwayat Transaksi</h3>
            </div>
            <button style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; border: 1px solid #99f6e4; color: #0d9488; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; background: white; cursor: pointer;">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan
            </button>
        </div>

        {{-- Filter --}}
        <div style="padding: 0.75rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
            <form method="GET" action="{{ route('kas-rt.index') }}" style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Jenis:</label>
                    <select name="jenis" style="font-size: 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.375rem 0.75rem; background: white;">
                        <option value="">Semua</option>
                        <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Bulan:</label>
                    <select name="bulan" style="font-size: 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.375rem 0.75rem; background: white;">
                        <option value="">Semua</option>
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Tahun:</label>
                    <select name="tahun" style="font-size: 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.375rem 0.75rem; background: white;">
                        <option value="">Semua</option>
                        @foreach($years as $y)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 180px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari transaksi..." style="width: 100%; font-size: 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.375rem 0.75rem;">
                </div>
                <button type="submit" style="padding: 0.375rem 1rem; background: #14b8a6; color: white; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.375rem;">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">No</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Tanggal</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Jenis</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Kategori</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Kas</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Keterangan</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Pemasukan</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Pengeluaran</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Petugas</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #64748b; font-weight: 500;">{{ $transaksi->firstItem() + $loop->index }}</td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #334155; font-weight: 500; white-space: nowrap;">{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            @if($t->jenis === 'masuk')
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #ecfdf5; color: #047857; border-radius: 9999px;">
                                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                Masuk
                            </span>
                            @else
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #fef2f2; color: #b91c1c; border-radius: 9999px;">
                                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                Keluar
                            </span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #475569;">{{ $t->kategori }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; font-size: 0.625rem; font-weight: 700; background: #eff6ff; color: #1d4ed8; border-radius: 9999px; border: 1px solid #bfdbfe;">
                                {{ $t->rekening->nama }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #64748b; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $t->keterangan ?? '-' }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            @if($t->jenis === 'masuk')
                            <span style="font-size: 0.875rem; font-weight: 700; color: #059669;">Rp {{ number_format($t->nominal, 0, ',', '.') }}</span>
                            @else
                            <span style="font-size: 0.875rem; color: #cbd5e1;">-</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            @if($t->jenis === 'keluar')
                            <span style="font-size: 0.875rem; font-weight: 700; color: #dc2626;">Rp {{ number_format($t->nominal, 0, ',', '.') }}</span>
                            @else
                            <span style="font-size: 0.875rem; color: #cbd5e1;">-</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #475569;">{{ $t->user->name ?? '-' }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <form action="{{ route('kas-rt.destroy', $t) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus transaksi ini? Saldo akan dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" style="padding: 6px; color: #f87171; background: none; border: none; border-radius: 0.5rem; cursor: pointer;">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="padding: 3rem 1rem; text-align: center;">
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <div style="width: 64px; height: 64px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin-bottom: 0.75rem;">
                                    <svg style="width: 32px; height: 32px; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p style="font-size: 0.875rem; font-weight: 600; color: #64748b;">Belum ada transaksi</p>
                                <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Mulai catat pemasukan atau pengeluaran kas RT</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksi->hasPages())
        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #f1f5f9;">
            {{ $transaksi->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
