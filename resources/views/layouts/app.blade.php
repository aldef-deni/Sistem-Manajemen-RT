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
                    {{-- Notifikasi pembayaran subscribe untuk Administrator --}}
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('subscribe.verifications.index') }}" title="Pembayaran subscribe menunggu verifikasi"
                           class="relative rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(($subscriptionPendingCount ?? 0) > 0)
                                <span class="absolute -right-1 -top-1 flex min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-bold text-white shadow">
                                    {{ $subscriptionPendingCount > 99 ? '99+' : $subscriptionPendingCount }}
                                </span>
                            @endif
                        </a>
                    @endif

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

    @stack('scripts')
</body>
</html>
