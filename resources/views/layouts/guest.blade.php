<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') — Sistem Manajemen RT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        /* Plate untuk logo Aldef pada latar gelap. Putih pucat dengan sedikit
           kehangatan supaya tidak menyilaukan, dan cincin tipis agar tepinya
           tetap terbaca di atas gradasi biru. */
        .brand-plate {
            display: inline-flex;
            align-items: center;
            padding: 0.6rem 0.95rem;
            border-radius: 0.85rem;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 10px 26px rgba(3, 20, 60, 0.28), inset 0 0 0 1px rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body class="login-bg antialiased">
    @yield('content')
</body>
</html>
