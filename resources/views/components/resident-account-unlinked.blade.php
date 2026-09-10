<div class="overflow-hidden rounded-3xl border border-amber-200 bg-white shadow-sm">
    <div class="bg-gradient-to-r from-amber-50 via-orange-50 to-white px-6 py-8 sm:px-10 sm:py-10">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 ring-8 ring-amber-50">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 4.5h.008v.008H12V16.5z"/>
                </svg>
            </div>
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-700">Data warga belum terhubung</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900 sm:text-2xl">Riwayat keuangan belum dapat ditampilkan</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Akun Anda belum ditautkan ke data kependudukan. Hubungi Administrator atau Ketua RT agar akun
                    <span class="font-semibold text-slate-800">{{ auth()->user()->username }}</span> dihubungkan melalui menu Kelola Akun.
                </p>
            </div>
        </div>
    </div>
    <div class="grid gap-px bg-slate-200 sm:grid-cols-3">
        <div class="bg-white px-6 py-4">
            <p class="text-xs font-semibold text-slate-400">1. HUBUNGI PENGURUS</p>
            <p class="mt-1 text-sm font-medium text-slate-700">Sampaikan username akun Anda.</p>
        </div>
        <div class="bg-white px-6 py-4">
            <p class="text-xs font-semibold text-slate-400">2. TAUTKAN DATA</p>
            <p class="mt-1 text-sm font-medium text-slate-700">Pengurus memilih data warga yang sesuai.</p>
        </div>
        <div class="bg-white px-6 py-4">
            <p class="text-xs font-semibold text-slate-400">3. BUKA KEMBALI</p>
            <p class="mt-1 text-sm font-medium text-slate-700">Informasi pribadi akan tampil otomatis.</p>
        </div>
    </div>
</div>
