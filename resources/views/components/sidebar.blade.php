@php
    $currentRoute = request()->route()->getName();

    $menu = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>',
        ],
        [
            'group' => 'Kependudukan',
            'items' => [
                ['label' => 'Data Warga', 'route' => 'data-warga', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'],
                ['label' => 'Kartu Keluarga', 'route' => 'kartu-keluarga.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>'],
                ['label' => 'Pemilih Pemilu', 'route' => 'pemilih-pemilu', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>'],
            ],
        ],
        [
            'group' => 'Keuangan',
            'items' => [
                ['label' => 'Iuran Warga', 'route' => 'iuran-warga.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>'],
                ['label' => 'Kas RT', 'route' => 'kas-rt.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>'],
                ['label' => 'Tabungan', 'route' => 'tabungan.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'],
                ['label' => 'Pinjaman', 'route' => 'pinjaman.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>'],
                ['label' => 'Arisan RT', 'route' => 'arisan.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14zM9 7a3 3 0 116 0 3 3 0 01-6 0z"/></svg>'],
                ['label' => 'Pembayaran', 'route' => 'pembayaran', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>'],
                ['label' => 'Laporan Keuangan', 'route' => 'laporan-keuangan', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>'],
            ],
        ],
        [
            'group' => 'Inventaris',
            'items' => [
                ['label' => 'Data Barang', 'route' => 'barang.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'],
                ['label' => 'Peminjaman', 'route' => 'peminjaman.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>'],
            ],
        ],
        [
            'group' => 'Layanan Warga',
            'items' => [
                ['label' => 'UMKM', 'route' => 'umkm.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'],
                ['label' => 'Bantuan Sosial', 'route' => 'bantuan-sosial.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>'],
                ['label' => 'E-Visitor', 'route' => 'visitor.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>'],
                ['label' => 'Surat Menyurat', 'route' => 'surat.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'],
            ],
        ],
        [
            'group' => 'Kegiatan & Info',
            'items' => [
                ['label' => 'Pengumuman', 'route' => 'pengumuman.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>'],
                ['label' => 'Kalender', 'route' => 'kalender.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'],
                ['label' => 'Jadwal Kegiatan', 'route' => 'jadwal-kegiatan.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>'],
                ['label' => 'Kegiatan RT', 'route' => 'kegiatan-rt.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'],
                ['label' => 'Notulen Rapat', 'route' => 'notulen-rapat.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'],
                ['label' => 'Struktur RT', 'route' => 'struktur-rt.show', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'],
            ],
        ],
        [
            'group' => 'Aspirasi & Partisipasi',
            'items' => [
                ['label' => 'Pengaduan', 'route' => 'pengaduan.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>'],
                ['label' => 'Polling Warga', 'route' => 'polling.index', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>'],
            ],
        ],
        [
            'group' => 'Pengaturan',
            'items' => [
                ['label' => 'Profil Saya', 'route' => 'profil-saya', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'],
                [
                    'label' => 'Umum',
                    'route' => 'pengaturan',
                    'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                    'children' => [
                        ['label' => 'Tata Tertib', 'route' => 'pengaturan.tata-tertib', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'],
                        ['label' => 'Kelola Pengurus', 'route' => 'pengaturan.kelola-pengurus', 'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14zM9 7a3 3 0 116 0 3 3 0 01-6 0z"/></svg>'],
                    ],
                ],
            ],
        ],
    ];

    // Kelola Akun — hanya Administrator & Ketua RT yang melihat menu ini
    if (auth()->user()->canManageAkun()) {
        foreach ($menu as &$group) {
            if (($group['group'] ?? null) === 'Pengaturan') {
                $group['items'][] = [
                    'label' => 'Kelola Akun',
                    'route' => 'akun.index',
                    'icon' => '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>',
                ];
            }
        }
        unset($group);
    }

    /*
     * Sebuah item menu dianggap AKTIF bila:
     *  1. nama route-nya sama persis dengan route halaman saat ini, ATAU
     *  2. item bertipe resource (.index) dan sedang berada di salah satu
     *     halaman turunannya (mis. iuran-warga.index aktif saat di iuran-warga.edit).
     * Item biasa tanpa titik (mis. 'pengaturan') HANYA aktif pada halaman persisnya —
     * sehingga 'pengaturan', 'pengaturan.tata-tertib' dan 'pengaturan.kelola-pengurus'
     * tidak pernah menyala bersamaan.
     */
    function isActive($itemRoute, $currentRoute) {
        if ($itemRoute === $currentRoute) return true;
        if (! str_contains($itemRoute, '.')) return false;
        if (str_ends_with($itemRoute, '.index')) {
            return explode('.', $itemRoute)[0] === explode('.', $currentRoute)[0];
        }
        return false;
    }

    // Cek apakah salah satu item (termasuk sub-menu di dalamnya) sedang aktif.
    function anyItemActive($items, $currentRoute) {
        foreach ($items as $item) {
            $routes = [$item['route']];
            foreach ($item['children'] ?? [] as $child) {
                $routes[] = $child['route'];
            }
            foreach ($routes as $r) {
                if (isActive($r, $currentRoute)) return true;
            }
        }
        return false;
    }

    // Sub-menu induk (mis. Umum) terbuka bila route-nya sendiri atau anaknya aktif.
    function subMenuOpen($sub, $currentRoute) {
        if (isActive($sub['route'], $currentRoute)) return true;
        foreach ($sub['children'] ?? [] as $child) {
            if (isActive($child['route'], $currentRoute)) return true;
        }
        return false;
    }
@endphp

<style>
    /* Sub-menu bertingkat (Pengaturan > Umum > Tata Tertib / Kelola Pengurus) */
    .sidebar .menu-sub-head { display: flex; align-items: center; margin: 1px 0.375rem; border-radius: 0.5rem; }
    .sidebar .menu-sub-head > a.menu-item { flex: 1; min-width: 0; margin: 0; }
    .sidebar .menu-sub-toggle {
        display: flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; margin-right: 2px; border: none; border-radius: 6px;
        background: transparent; color: #64748b; cursor: pointer; flex-shrink: 0; transition: all 0.15s ease;
    }
    .sidebar .menu-sub-toggle:hover { color: #e2e8f0; background: rgba(255,255,255,0.06); }
    .sidebar .menu-sub-toggle svg { width: 14px; height: 14px; transition: transform 0.2s ease; }
    .sidebar .menu-sub.open .menu-sub-toggle svg { transform: rotate(90deg); }
    .sidebar .menu-sub-items {
        max-height: 0; overflow: hidden; transition: max-height 0.25s ease;
        margin: 0 0 2px 0.875rem; border-left: 1px solid rgba(148,163,184,0.2); padding-left: 2px;
    }
    .sidebar .menu-sub.open .menu-sub-items { max-height: 220px; }
    .sidebar .menu-sub-items .menu-item { margin-left: 0.375rem; margin-right: 0.375rem; padding-left: 0.5rem; }
    .sidebar .menu-sub-items .menu-item svg { width: 15px; height: 15px; }
</style>

<aside class="sidebar flex flex-col" id="sidebar">
    <div class="px-5 py-5 border-b border-white/5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>
                </svg>
            </div>
            <div>
                <span class="text-white font-bold text-sm tracking-wide">SISTEM RT</span>
                <p class="text-[10px] text-slate-500 leading-none mt-0.5">Manajemen RT</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto py-3 px-2" id="sidebar-menu">
        @foreach($menu as $item)
            @if(isset($item['label']))
                <a href="{{ route($item['route']) }}"
                   class="menu-item {{ isActive($item['route'], $currentRoute) ? 'active' : '' }}">
                    {!! $item['icon'] !!}
                    <span>{{ $item['label'] }}</span>
                </a>
            @elseif(isset($item['group']))
                <div class="menu-group {{ anyItemActive($item['items'], $currentRoute) ? 'open' : '' }}" data-group>
                    <div class="menu-group-label" onclick="this.parentElement.classList.toggle('open')">
                        <span>{{ $item['group'] }}</span>
                        <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div class="menu-group-items">
                        @foreach($item['items'] as $sub)
                            @if(isset($sub['children']))
                                <div class="menu-sub {{ subMenuOpen($sub, $currentRoute) ? 'open' : '' }}">
                                    <div class="menu-sub-head">
                                        <a href="{{ route($sub['route']) }}"
                                           class="menu-item {{ isActive($sub['route'], $currentRoute) ? 'active' : '' }}">
                                            {!! $sub['icon'] !!}
                                            <span>{{ $sub['label'] }}</span>
                                        </a>
                                        <button type="button" class="menu-sub-toggle" title="Buka sub menu {{ $sub['label'] }}"
                                            onclick="this.closest('.menu-sub').classList.toggle('open')">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="menu-sub-items">
                                        @foreach($sub['children'] as $child)
                                            <a href="{{ route($child['route']) }}"
                                               class="menu-item {{ isActive($child['route'], $currentRoute) ? 'active' : '' }}">
                                                {!! $child['icon'] !!}
                                                <span>{{ $child['label'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ route($sub['route']) }}"
                                   class="menu-item {{ isActive($sub['route'], $currentRoute) ? 'active' : '' }}">
                                    {!! $sub['icon'] !!}
                                    <span>{{ $sub['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>

    <div class="border-t border-white/5 p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu-item w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
