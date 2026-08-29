@extends('layouts.app')

@section('title', $title ?? 'Halaman')
@section('page-title', $title ?? 'Halaman')
@section('page-subtitle', $section ?? '')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-md mx-auto">
        {{-- Icon --}}
        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200/50 flex items-center justify-center">
            <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
        </div>

        <h2 class="text-xl font-bold text-slate-800 mb-2">{{ $title ?? 'Halaman' }}</h2>
        <p class="text-sm text-slate-500 mb-2">Bagian <span class="font-medium text-blue-600">{{ $section ?? '' }}</span></p>
        <p class="text-sm text-slate-400 mb-8">
            Fitur ini sedang dalam pengembangan dan akan segera tersedia. Silakan cek kembali nanti.
        </p>

        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        {{-- Progress indicator --}}
        <div class="mt-10 p-4 bg-slate-50 rounded-xl border border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500">Progress Pengembangan</span>
                <span class="text-xs font-bold text-blue-600">0%</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-1.5">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 rounded-full" style="width: 0%"></div>
            </div>
        </div>
    </div>
</div>
@endsection
