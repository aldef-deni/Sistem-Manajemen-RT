@extends('layouts.app')

@section('title', 'Semua Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Semua kabar dan aktivitas terbaru untuk akun Anda')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('dashboard') }}" class="font-semibold text-blue-600 hover:underline">Dashboard</a>
                <span>/</span>
                <span>Notifikasi</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Pusat Notifikasi</h1>
            <p class="mt-1 text-sm text-slate-500">Pilih notifikasi untuk langsung membuka halaman terkait.</p>
        </div>

        @if(auth()->user()->unreadNotifications()->exists())
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-700 transition hover:bg-blue-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 4 4L18 6m-6 10 2 2 6-7"/></svg>
                    Tandai semua dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/70 px-4 py-3.5 sm:px-5">
            <div class="flex items-center gap-2">
                <a href="{{ route('notifications.index') }}" class="rounded-full px-4 py-2 text-xs font-bold no-underline transition {{ $filter === 'all' ? 'bg-slate-900 text-white shadow' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-100' }}">Semua</a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="rounded-full px-4 py-2 text-xs font-bold no-underline transition {{ $filter === 'unread' ? 'bg-blue-600 text-white shadow' : 'border border-slate-200 bg-white text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}">Belum dibaca</a>
            </div>
            <p class="text-xs font-medium text-slate-400">{{ $notifications->total() }} notifikasi</p>
        </div>

        <div>
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $category = $data['category'] ?? 'system';
                    $tone = $data['tone'] ?? 'blue';
                    $iconClasses = match ($tone) {
                        'emerald' => 'bg-emerald-100 text-emerald-600 ring-emerald-200',
                        'amber' => 'bg-amber-100 text-amber-600 ring-amber-200',
                        'rose' => 'bg-rose-100 text-rose-600 ring-rose-200',
                        'violet' => 'bg-violet-100 text-violet-600 ring-violet-200',
                        default => 'bg-blue-100 text-blue-600 ring-blue-200',
                    };
                @endphp
                <a href="{{ route('notifications.open', $notification->id) }}"
                   class="group flex items-start gap-4 border-b border-slate-100 px-4 py-4 no-underline transition last:border-b-0 hover:bg-slate-50 sm:px-5 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50/40' }}">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl ring-1 {{ $iconClasses }}">
                        @if($category === 'chat')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72A7.49 7.49 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        @elseif($category === 'subscribe' || $category === 'payment')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        @elseif($category === 'announcement')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        @elseif($category === 'complaint')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
                        @else
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="flex items-start justify-between gap-3">
                            <span class="text-sm font-extrabold text-slate-800">{{ $data['title'] ?? 'Notifikasi baru' }}</span>
                            @unless($notification->read_at)
                                <span class="mt-1 flex shrink-0 items-center gap-1.5 rounded-full bg-blue-100 px-2 py-1 text-[9px] font-extrabold uppercase tracking-wide text-blue-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span> Baru
                                </span>
                            @endunless
                        </span>
                        <span class="mt-1 block text-sm leading-5 text-slate-600">{{ $data['message'] ?? '' }}</span>
                        <span class="mt-2 flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $notification->created_at->locale('id')->diffForHumans() }}
                        </span>
                    </span>
                    <svg class="mt-4 h-5 w-5 shrink-0 text-slate-300 transition group-hover:translate-x-1 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/></svg>
                </a>
            @empty
                <div class="flex flex-col items-center px-6 py-20 text-center">
                    <span class="grid h-20 w-20 place-items-center rounded-3xl bg-slate-100 text-slate-400">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0"/></svg>
                    </span>
                    <h3 class="mt-5 text-base font-extrabold text-slate-800">{{ $filter === 'unread' ? 'Tidak ada notifikasi baru' : 'Belum ada notifikasi' }}</h3>
                    <p class="mt-1 max-w-sm text-sm leading-6 text-slate-500">{{ $filter === 'unread' ? 'Semua notifikasi sudah Anda baca.' : 'Kabar terbaru dari aktivitas Sistem RT akan tersimpan di halaman ini.' }}</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="border-t border-slate-100 bg-slate-50 px-4 py-4 sm:px-5">{{ $notifications->links() }}</div>
        @endif
    </div>
</div>
@endsection
