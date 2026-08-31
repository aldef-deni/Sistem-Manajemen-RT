@extends('layouts.app')

@section('title', 'Kalender')

@push('styles')
<style>
.calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
.calendar-cell { min-height: 90px; position: relative; border-right: 1px solid #f1f5f9; padding: 6px; }
.calendar-cell:last-child { border-right: none; }
.calendar-cell:hover { background: rgba(248, 250, 252, 0.5); }
.calendar-cell.today { background: linear-gradient(135deg, #f0fdf4, #ecfdf5); box-shadow: inset 0 0 0 2px #2dd4bf; }
.calendar-cell.weekend { background: rgba(248, 250, 252, 0.3); }
.calendar-cell.empty { background: rgba(248, 250, 252, 0.2); }
.pasaran-badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
.pasaran-legi { background: #10b981; color: white; }
.pasaran-pahing { background: #f97316; color: white; }
.pasaran-pon { background: #3b82f6; color: white; }
.pasaran-wage { background: #ef4444; color: white; }
.pasaran-kliwon { background: #6366f1; color: white; }
.day-header { padding: 10px 4px; text-align: center; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; }
.day-header.minggu { color: #ef4444; background: rgba(254, 226, 226, 0.3); }
.day-header.sabtu { color: #2563eb; background: rgba(219, 234, 254, 0.3); }
.day-header.normal { color: #475569; background: #f8fafc; }
.today-circle { width: 28px; height: 28px; background: #14b8a6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; box-shadow: 0 2px 4px rgba(20,184,166,0.3); }
.weekend-text { color: #ef4444; }
.normal-text { color: #334155; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Kalender</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Kalender</h1>
                <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Calendar Navigation Header --}}
    <div style="background: linear-gradient(135deg, #14b8a6, #10b981, #059669); border-radius: 16px; padding: 20px; box-shadow: 0 8px 25px rgba(16,185,129,0.3);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ route('kalender.index', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
               style="width: 44px; height: 44px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: background 0.2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div style="text-align:center;">
                <h2 style="font-size:24px; font-weight:800; color:white; margin:0;">
                    📅 {{ $monthName }} {{ $year }}
                </h2>
                <p style="color:rgba(255,255,255,0.8); font-size:14px; margin-top:4px;">Suro {{ $tahunJawa }} — Klik tanggal untuk jadwal</p>
            </div>
            <a href="{{ route('kalender.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
               style="width: 44px; height: 44px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: background 0.2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- Legend --}}
    <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; padding:12px 16px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:12px 20px; font-size:12px; font-weight:600;">
            <span style="display:flex; align-items:center; gap:6px; color:#64748b;">
                <span style="width:12px;height:12px;border-radius:50%;background:#10b981;display:inline-block;"></span> Legi
            </span>
            <span style="display:flex; align-items:center; gap:6px; color:#64748b;">
                <span style="width:12px;height:12px;border-radius:50%;background:#f97316;display:inline-block;"></span> Pahing
            </span>
            <span style="display:flex; align-items:center; gap:6px; color:#64748b;">
                <span style="width:12px;height:12px;border-radius:50%;background:#3b82f6;display:inline-block;"></span> Pon
            </span>
            <span style="display:flex; align-items:center; gap:6px; color:#64748b;">
                <span style="width:12px;height:12px;border-radius:50%;background:#ef4444;display:inline-block;"></span> Wage
            </span>
            <span style="display:flex; align-items:center; gap:6px; color:#64748b;">
                <span style="width:12px;height:12px;border-radius:50%;background:#6366f1;display:inline-block;"></span> Kliwon
            </span>
            <span style="width:1px;height:16px;background:#cbd5e1;"></span>
            <span style="display:flex; align-items:center; gap:6px; color:#64748b;">
                <span style="width:12px;height:12px;border-radius:50%;background:#cbd5e1;border:1px solid #94a3b8;display:inline-block;"></span> Hari Libur
            </span>
            <span style="display:flex; align-items:center; gap:6px; color:#64748b;">
                <span style="width:12px;height:12px;border-radius:50%;background:#fb923c;border:2px solid #f97316;display:inline-block;"></span> Kegiatan RT
            </span>
            <span style="display:flex; align-items:center; gap:6px; color:#64748b;">
                <span style="width:12px;height:12px;border-radius:50%;background:#2dd4bf;border:2px solid #14b8a6;display:inline-block;"></span> Jadwal Saya
            </span>
        </div>
    </div>

    {{-- Calendar Grid --}}
    <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        {{-- Day Headers --}}
        <div style="display:grid; grid-template-columns:repeat(7,1fr);">
            <div style="padding:10px 4px; text-align:center; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#ef4444; background:rgba(254,226,226,0.3); border-bottom:1px solid #e2e8f0;">Minggu</div>
            <div style="padding:10px 4px; text-align:center; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; background:#f8fafc; border-bottom:1px solid #e2e8f0;">Senin</div>
            <div style="padding:10px 4px; text-align:center; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; background:#f8fafc; border-bottom:1px solid #e2e8f0;">Selasa</div>
            <div style="padding:10px 4px; text-align:center; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; background:#f8fafc; border-bottom:1px solid #e2e8f0;">Rabu</div>
            <div style="padding:10px 4px; text-align:center; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; background:#f8fafc; border-bottom:1px solid #e2e8f0;">Kamis</div>
            <div style="padding:10px 4px; text-align:center; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; background:#f8fafc; border-bottom:1px solid #e2e8f0;">Jumat</div>
            <div style="padding:10px 4px; text-align:center; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#2563eb; background:rgba(219,234,254,0.3); border-bottom:1px solid #e2e8f0;">Sabtu</div>
        </div>

        {{-- Calendar Weeks --}}
        @foreach($calendar as $weekIndex => $week)
            <div style="display:grid; grid-template-columns:repeat(7,1fr); border-top: 1px solid #f1f5f9;">
                @foreach($week as $dayData)
                    @if($dayData)
                        @php
                            $isToday = $dayData['is_today'];
                            $isWeekend = $dayData['is_weekend'];
                            $pasaran = $dayData['hari_pasaran'];
                            $pasaranClass = 'pasaran-' . strtolower($pasaran);
                        @endphp
                        <div style="min-height:90px; padding:6px; border-right:1px solid #f1f5f9; cursor:pointer;{{ $isToday ? 'background:linear-gradient(135deg,#f0fdf4,#ecfdf5);box-shadow:inset 0 0 0 2px #2dd4bf;' : '' }}{{ $isWeekend && !$isToday ? 'background:rgba(248,250,252,0.3);' : '' }}" title="{{ $pasaran }}, {{ $dayData['day'] }} {{ $monthName }} {{ $year }}">
                            {{-- Tanggal --}}
                            <div style="margin-bottom:4px;">
                                @if($isToday)
                                    <div class="today-circle">{{ $dayData['day'] }}</div>
                                @else
                                    <span style="font-size:13px; font-weight:700; {{ $isWeekend ? 'color:#ef4444;' : 'color:#334155;' }}">
                                        {{ $dayData['day'] }}
                                    </span>
                                @endif
                            </div>

                            {{-- Pasaran Badge --}}
                            @php
                                $badgeColors = [
                                    'Legi' => 'background:#10b981;color:white;',
                                    'Pahing' => 'background:#f97316;color:white;',
                                    'Pon' => 'background:#3b82f6;color:white;',
                                    'Wage' => 'background:#ef4444;color:white;',
                                    'Kliwon' => 'background:#6366f1;color:white;',
                                ];
                                $badgeStyle = $badgeColors[$pasaran] ?? 'background:#94a3b8;color:white;';
                            @endphp
                            <span style="display:inline-flex;align-items:center;padding:2px 8px;border-radius:9999px;font-size:10px;font-weight:700;box-shadow:0 1px 2px rgba(0,0,0,0.1);{{ $badgeStyle }}">{{ $pasaran }}</span>

                            {{-- Tahun Jawa --}}
                            <p style="font-size:10px; color:#94a3b8; margin-top:3px; font-weight:500;">
                                {{ $year }}/{{ $dayData['day'] }}
                            </p>
                        </div>
                    @else
                        <div style="min-height:90px; padding:6px; background:rgba(248,250,252,0.2);"></div>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- Quick Month Jump --}}
    <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; padding:12px 16px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Lompat ke Bulan:</span>
            <div style="display:flex; flex-wrap:wrap; gap:6px;">
                @foreach([1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'] as $m => $label)
                    <a href="{{ route('kalender.index', ['month' => $m, 'year' => $year]) }}"
                       style="padding:4px 12px; border-radius:8px; font-size:11px; font-weight:700; text-decoration:none;
                       {{ $m == $month ? 'background:#14b8a6; color:white; box-shadow:0 2px 4px rgba(20,184,166,0.3);' : 'background:#f1f5f9; color:#475569;' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
