<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Sistem Manajemen RT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('styles')
</head>
<body class="bg-slate-50 antialiased">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main Content --}}
        <div class="flex min-h-screen min-w-0 flex-1 flex-col">
            {{-- Top Header --}}
            <header class="top-header sticky top-0 z-40 flex items-center justify-between gap-3 px-4 py-3 sm:px-6">
                <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                    {{-- Mobile menu toggle --}}
                    <button onclick="document.getElementById('sidebar').classList.toggle('open')" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <h1 class="truncate text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                        <p class="hidden truncate text-xs text-slate-400 sm:block">@yield('page-subtitle', '')</p>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-2 sm:gap-4">
                    {{-- Pusat notifikasi untuk seluruh role --}}
                    <div id="notificationMenu" class="relative">
                        <button id="notificationToggle" type="button" title="Notifikasi"
                                aria-label="Buka notifikasi" aria-expanded="false" aria-controls="notificationDropdown"
                                class="relative grid h-10 w-10 place-items-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(($notificationUnreadCount ?? 0) > 0)
                                <span class="absolute -right-0.5 -top-0.5 flex min-w-5 items-center justify-center rounded-full border-2 border-white bg-rose-500 px-1 py-0.5 text-[9px] font-extrabold leading-3 text-white shadow">
                                    {{ $notificationUnreadCount > 99 ? '99+' : $notificationUnreadCount }}
                                </span>
                            @endif
                        </button>

                        <div id="notificationDropdown" class="absolute right-0 top-12 z-50 hidden w-[calc(100vw-2rem)] max-w-[380px] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/15 sm:w-[380px]">
                            <div class="flex items-center justify-between gap-3 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-blue-50/60 px-4 py-3.5">
                                <div>
                                    <h2 class="text-sm font-extrabold text-slate-900">Notifikasi</h2>
                                    <p class="text-[11px] text-slate-500">
                                        {{ ($notificationUnreadCount ?? 0) > 0 ? $notificationUnreadCount.' belum dibaca' : 'Semua sudah dibaca' }}
                                    </p>
                                </div>
                                @if(($notificationUnreadCount ?? 0) > 0)
                                    <form method="POST" action="{{ route('notifications.read-all') }}">
                                        @csrf
                                        <button type="submit" class="rounded-lg px-2.5 py-1.5 text-[11px] font-bold text-blue-600 transition hover:bg-blue-100">Tandai dibaca</button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-[420px] overflow-y-auto">
                                @forelse(($notificationItems ?? collect()) as $notification)
                                    @php
                                        $notificationData = $notification->data;
                                        $notificationCategory = $notificationData['category'] ?? 'system';
                                        $notificationTone = $notificationData['tone'] ?? 'blue';
                                        $notificationIconClasses = match ($notificationTone) {
                                            'emerald' => 'bg-emerald-100 text-emerald-600',
                                            'amber' => 'bg-amber-100 text-amber-600',
                                            'rose' => 'bg-rose-100 text-rose-600',
                                            'violet' => 'bg-violet-100 text-violet-600',
                                            default => 'bg-blue-100 text-blue-600',
                                        };
                                    @endphp
                                    <a href="{{ route('notifications.open', $notification->id) }}"
                                       class="group flex gap-3 border-b border-slate-100 px-4 py-3.5 no-underline transition hover:bg-slate-50 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50/45' }}">
                                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $notificationIconClasses }}">
                                            @if($notificationCategory === 'chat')
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72A7.49 7.49 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            @elseif($notificationCategory === 'subscribe' || $notificationCategory === 'payment')
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            @elseif($notificationCategory === 'announcement')
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                            @elseif($notificationCategory === 'complaint')
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
                                            @else
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="flex items-start justify-between gap-2">
                                                <span class="line-clamp-1 text-xs font-bold text-slate-800">{{ $notificationData['title'] ?? 'Notifikasi baru' }}</span>
                                                @unless($notification->read_at)<span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-blue-600"></span>@endunless
                                            </span>
                                            <span class="mt-0.5 line-clamp-2 text-[11px] leading-4 text-slate-500">{{ $notificationData['message'] ?? '' }}</span>
                                            <span class="mt-1.5 block text-[10px] font-medium text-slate-400">{{ $notification->created_at->locale('id')->diffForHumans() }}</span>
                                        </span>
                                    </a>
                                @empty
                                    <div class="flex flex-col items-center px-6 py-10 text-center">
                                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0"/></svg>
                                        </span>
                                        <p class="mt-3 text-sm font-bold text-slate-700">Belum ada notifikasi</p>
                                        <p class="mt-1 text-xs text-slate-400">Aktivitas terbaru akan muncul di sini.</p>
                                    </div>
                                @endforelse
                            </div>

                            <a href="{{ route('notifications.index') }}" class="flex items-center justify-center gap-2 bg-slate-50 px-4 py-3 text-xs font-bold text-blue-600 no-underline transition hover:bg-blue-50">
                                Lihat semua notifikasi
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- User dropdown --}}
                    <div class="flex items-center gap-2 border-l border-slate-200 pl-2 sm:gap-3 sm:pl-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-slate-700">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-xs text-slate-400">{{ auth()->user()->role_label }}</p>
                        </div>
                        @php($headerAvatarUrl = auth()->user()->foto_url)
                        <div class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-sm font-semibold text-white shadow-md shadow-blue-500/20">
                            @if($headerAvatarUrl)
                                <img src="{{ $headerAvatarUrl }}?v={{ auth()->user()->updated_at?->timestamp }}"
                                     alt="Foto profil {{ auth()->user()->name }}"
                                     data-header-avatar
                                     class="h-full w-full object-cover">
                            @else
                                {{ auth()->user()->initial }}
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="page-content flex-1 px-4 py-5 sm:px-5">
                @yield('content')
            </main>
        </div>
    </div>

    @if(($subscriptionStatus['locked'] ?? false))
        @include('components.subscribe-modal', ['subscriptionStatus' => $subscriptionStatus])
    @endif

    <script>
        (() => {
            const menu = document.getElementById('notificationMenu');
            const toggle = document.getElementById('notificationToggle');
            const dropdown = document.getElementById('notificationDropdown');
            if (!menu || !toggle || !dropdown) return;

            const close = () => {
                dropdown.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            };

            toggle.addEventListener('click', (event) => {
                event.stopPropagation();
                const willOpen = dropdown.classList.contains('hidden');
                dropdown.classList.toggle('hidden');
                toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            });
            document.addEventListener('click', (event) => {
                if (!menu.contains(event.target)) close();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') close();
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
