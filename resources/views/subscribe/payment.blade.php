@extends('layouts.app')

@section('title', 'Pembayaran Subscribe')
@section('page-title', 'Pembayaran Subscribe')
@section('page-subtitle', 'Aktifkan akses fitur Sistem Manajemen RT')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('dashboard') }}" class="font-medium text-blue-600 hover:underline">Dashboard</a>
                <span>/</span>
                <span>Pembayaran Subscribe</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Subscribe Sistem RT</h1>
            <p class="mt-1 text-sm text-slate-500">Satu pembayaran memberikan akses penuh selama 30 hari setelah disetujui.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold
            {{ $status['state'] === 'active' ? 'bg-emerald-100 text-emerald-700' : ($status['state'] === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
            <span class="h-2 w-2 rounded-full {{ $status['state'] === 'active' ? 'bg-emerald-500' : ($status['state'] === 'pending' ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
            {{ $status['state'] === 'active' ? 'Subscribe Aktif' : ($status['state'] === 'pending' ? 'Menunggu Verifikasi' : 'Belum Aktif') }}
        </span>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    @if($status['state'] === 'active')
        <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-700 p-6 text-white shadow-xl shadow-emerald-500/20">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-100">Akses aktif</p>
                    <h2 class="mt-2 text-2xl font-extrabold">Semua fitur telah terbuka</h2>
                    <p class="mt-2 text-sm text-emerald-50/80">Berlaku sampai {{ $status['active_payment']->ends_at->translatedFormat('d F Y, H:i') }} WIB.</p>
                </div>
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/15">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
        </div>
    @elseif($status['state'] === 'pending')
        <div class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-6">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-500/20">
                    <svg class="h-6 w-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-amber-900">Menunggu verifikasi Administrator</h2>
                    <p class="mt-1 text-sm leading-6 text-amber-700">Bukti pembayaran telah diterima pada {{ $status['pending_payment']->created_at->translatedFormat('d F Y, H:i') }} WIB. Anda akan memperoleh akses selama 30 hari setelah pembayaran disetujui.</p>
                </div>
            </div>
        </div>
    @else
        @if($status['state'] === 'rejected')
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wide text-red-500">Pembayaran sebelumnya ditolak</p>
                <p class="mt-2 text-sm font-medium text-red-800">{{ $status['latest_payment']->rejection_reason }}</p>
                <p class="mt-1 text-xs text-red-600">Perbaiki data atau bukti pembayaran, lalu kirim ulang formulir di bawah.</p>
            </div>
        @elseif($status['state'] === 'expired')
            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-5 text-sm text-orange-800">
                Masa subscribe sebelumnya telah berakhir. Lakukan pembayaran baru untuk mengaktifkan akses 30 hari berikutnya.
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[0.85fr_1.15fr]">
            <aside class="space-y-4">
                <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 p-6 text-white shadow-xl">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-200">Paket 30 hari</p>
                    <p class="mt-3 text-3xl font-black">Rp {{ number_format($status['price'], 0, ',', '.') }}</p>
                    <p class="mt-2 text-sm leading-6 text-blue-100/75">Akses menu Keuangan, Kegiatan & Info, serta Aspirasi & Partisipasi.</p>
                    <div class="mt-6 border-t border-white/10 pt-5">
                        <p class="text-xs uppercase tracking-wide text-blue-200/70">Transfer ke</p>
                        <p class="mt-2 text-lg font-extrabold">{{ $status['bank'] }}</p>
                        <p class="mt-1 font-mono text-xl tracking-wider text-white">{{ $status['account_number'] }}</p>
                        <p class="mt-1 text-sm text-blue-100/80">a.n. {{ $status['account_name'] }}</p>
                    </div>
                </div>
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-xs leading-5 text-blue-700">
                    Pastikan nominal dan tujuan rekening sesuai. Bukti disimpan secara privat dan hanya dapat dilihat Administrator.
                </div>
            </aside>

            <form action="{{ route('subscribe.payment.store') }}" method="POST" enctype="multipart/form-data" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                <div class="mb-6">
                    <h2 class="text-lg font-extrabold text-slate-900">Konfirmasi Pembayaran</h2>
                    <p class="mt-1 text-sm text-slate-500">Lengkapi data transfer dan unggah bukti yang jelas.</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="sender_bank" class="mb-2 block text-sm font-semibold text-slate-700">Bank Pengirim</label>
                        <input id="sender_bank" name="sender_bank" type="text" maxlength="100" required value="{{ old('sender_bank') }}" placeholder="Contoh: BRI"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label for="sender_account_name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Pemilik Rekening</label>
                        <input id="sender_account_name" name="sender_account_name" type="text" maxlength="150" required value="{{ old('sender_account_name') }}" placeholder="Nama rekening pengirim"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="paid_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Pembayaran</label>
                        <input id="paid_at" name="paid_at" type="date" max="{{ now()->toDateString() }}" required value="{{ old('paid_at', now()->toDateString()) }}"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="proof" class="mb-2 block text-sm font-semibold text-slate-700">Bukti Pembayaran</label>
                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center transition hover:border-blue-400 hover:bg-blue-50/50">
                            <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5.002 5.002 0 0115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="mt-3 text-sm font-bold text-slate-700">Pilih JPG, PNG, WEBP, atau PDF</span>
                            <span id="proof-file-name" class="mt-1 text-xs text-slate-400">Maksimal 5 MB</span>
                            <input id="proof" name="proof" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" required class="sr-only">
                        </label>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="notes" class="mb-2 block text-sm font-semibold text-slate-700">Catatan <span class="font-normal text-slate-400">(opsional)</span></label>
                        <textarea id="notes" name="notes" rows="3" maxlength="1000" placeholder="Informasi tambahan untuk Administrator"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-blue-500/20 transition hover:shadow-blue-500/40">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Kirim Bukti Pembayaran
                </button>
            </form>
        </div>
    @endif

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-bold text-slate-800">Riwayat Pembayaran</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr><th class="px-5 py-3">Dikirim</th><th class="px-5 py-3">Nominal</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Masa Aktif</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-5 py-4 text-slate-600">{{ $payment->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $payment->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($payment->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ $payment->status_label }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $payment->ends_at ? $payment->starts_at->format('d/m/Y').' – '.$payment->ends_at->format('d/m/Y') : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-400">Belum ada riwayat pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('proof')?.addEventListener('change', function () {
        const label = document.getElementById('proof-file-name');
        if (label && this.files.length) label.textContent = this.files[0].name;
    });
</script>
@endpush
