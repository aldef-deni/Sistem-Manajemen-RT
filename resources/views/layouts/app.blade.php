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
        <div class="flex-1 flex flex-col min-h-screen ml-0 md:ml-[250px]">
            {{-- Top Header --}}
            <header class="top-header sticky top-0 z-40 px-6 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- Mobile menu toggle --}}
                    <button onclick="document.getElementById('sidebar').classList.toggle('open')" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-xs text-slate-400">@yield('page-subtitle', '')</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Notification bell --}}
                    <button class="relative p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {{-- User dropdown --}}
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-slate-700">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-xs text-slate-400">Admin</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-semibold text-sm shadow-md shadow-blue-500/20">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 px-5 py-5 page-content">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
