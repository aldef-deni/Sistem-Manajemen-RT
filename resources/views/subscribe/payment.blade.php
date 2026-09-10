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
                    <div class="mt-3 inline-flex flex-wrap items-center gap-x-2 gap-y-1 rounded-xl border border-amber-200 bg-white/70 px-3 py-2 text-xs text-amber-800">
                        <span class="font-bold">Tujuan:</span>
                        <span>{{ $status['pending_payment']->destination_type_label }} {{ $status['pending_payment']->destination_bank }}</span>
                        <span class="font-mono font-bold">{{ $status['pending_payment']->destination_account_number }}</span>
                    </div>
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
                        <p class="text-xs uppercase tracking-wide text-blue-200/70">Pilihan pembayaran</p>
                        <div class="mt-2 flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m4 0h4M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <div>
                                <p class="text-lg font-extrabold">{{ count($status['payment_methods']) }} metode tersedia</p>
                                <p class="text-xs text-blue-100/70">Pilih Bank atau E-Wallet di formulir</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-xs leading-5 text-blue-700">
                    Pastikan nominal dan tujuan pembayaran sesuai. Bukti disimpan secara privat dan hanya dapat dilihat Administrator.
                </div>
            </aside>

            <form action="{{ route('subscribe.payment.store') }}" method="POST" enctype="multipart/form-data" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                <div class="mb-6">
                    <h2 class="text-lg font-extrabold text-slate-900">Konfirmasi Pembayaran</h2>
                    <p class="mt-1 text-sm text-slate-500">Lengkapi data transfer dan unggah bukti yang jelas.</p>
                </div>

                <fieldset class="mb-6">
                    <legend class="mb-3 text-sm font-bold text-slate-800">Pilih Tujuan Pembayaran</legend>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach($status['payment_methods'] as $method)
                            <div class="subscription-payment-choice">
                                <input id="payment-method-{{ $loop->index }}" type="radio" name="payment_method_id" value="{{ $method['id'] }}" required class="peer sr-only"
                                    {{ old('payment_method_id', $loop->first ? $method['id'] : '') === $method['id'] ? 'checked' : '' }}>
                                <label for="payment-method-{{ $loop->index }}" class="subscription-payment-card relative block h-full cursor-pointer rounded-2xl border-2 border-slate-200 bg-white p-4 transition hover:border-blue-300 hover:shadow-sm peer-checked:border-blue-600 peer-checked:bg-blue-50/60 peer-checked:ring-2 peer-checked:ring-blue-100">
                                    <span class="flex items-center justify-between gap-2">
                                        <span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider {{ $method['type'] === 'ewallet' ? 'bg-violet-100 text-violet-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $method['type'] === 'ewallet' ? 'E-Wallet' : 'Bank' }}
                                        </span>
                                        <span class="subscription-payment-check flex h-5 w-5 items-center justify-center rounded-full border-2 border-slate-300 bg-white text-transparent transition">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                    </span>
                                    <span class="mt-3 block text-base font-extrabold text-slate-900">{{ $method['provider'] }}</span>
                                    <span class="mt-1 block font-mono text-sm font-bold tracking-wide text-slate-700">{{ $method['account_number'] }}</span>
                                    <span class="mt-1 block text-xs text-slate-500">a.n. {{ $method['account_name'] }}</span>
                                    @if($method['qris_path'])
                                        <span class="mt-4 grid gap-3 rounded-xl border border-violet-100 bg-white p-3 sm:grid-cols-[96px_1fr] sm:items-center">
                                            <img src="{{ route('subscribe.payment.qris', ['paymentMethod' => $method['id']]) }}" alt="QRIS {{ $method['provider'] }}" class="mx-auto h-24 w-24 rounded-lg border border-slate-100 bg-white object-contain p-1 shadow-sm">
                                            <span class="text-center sm:text-left">
                                                <span class="inline-flex rounded-full bg-violet-100 px-2 py-1 text-[9px] font-black uppercase tracking-wider text-violet-700">QRIS tersedia</span>
                                                <span class="mt-1.5 block text-xs font-bold text-slate-700">Scan untuk membayar lebih cepat</span>
                                                <span class="mt-1 block text-[10px] leading-4 text-slate-400">Pastikan nama tujuan sesuai sebelum melanjutkan pembayaran.</span>
                                            </span>
                                        </span>
                                    @endif
                                </label>
                                @if($method['qris_path'])
                                    <button type="button" data-qris-open data-qris-src="{{ route('subscribe.payment.qris', ['paymentMethod' => $method['id']]) }}" data-qris-provider="{{ $method['provider'] }}"
                                        class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-violet-50 px-3 py-2 text-xs font-bold text-violet-700 transition hover:bg-violet-100">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4h4m8 0h4v4m0 8v4h-4M8 20H4v-4M8 8h8v8H8V8z"/></svg>
                                        Perbesar QRIS
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @error('payment_method_id') <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                </fieldset>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="sender_bank" class="mb-2 block text-sm font-semibold text-slate-700">Bank / E-Wallet Pengirim</label>
                        <input id="sender_bank" name="sender_bank" type="text" maxlength="100" required value="{{ old('sender_bank') }}" placeholder="Contoh: BRI atau DANA"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label for="sender_account_name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Pemilik Akun Pengirim</label>
                        <input id="sender_account_name" name="sender_account_name" type="text" maxlength="150" required value="{{ old('sender_account_name') }}" placeholder="Nama rekening atau akun pengirim"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="paid_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Pembayaran</label>
                        <input id="paid_at" name="paid_at" type="date" max="{{ now()->toDateString() }}" required value="{{ old('paid_at', now()->toDateString()) }}"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="proof" class="mb-2 block text-sm font-semibold text-slate-700">Bukti Pembayaran</label>
                        <label id="proof-dropzone" class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center transition hover:border-blue-400 hover:bg-blue-50/50">
                            <span id="proof-empty-state" class="flex flex-col items-center">
                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5.002 5.002 0 0115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span class="mt-3 text-sm font-bold text-slate-700">Pilih JPG, PNG, WEBP, atau PDF</span>
                                <span id="proof-file-name" class="mt-1 text-xs text-slate-400">Maksimal 5 MB</span>
                            </span>

                            <span id="proof-preview" class="hidden w-full" aria-live="polite">
                                <span class="relative mx-auto block max-w-sm overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                    <img id="proof-preview-image" src="" alt="Preview bukti pembayaran" class="hidden h-48 w-full bg-slate-100 object-contain p-2">
                                    <span id="proof-preview-pdf" class="hidden h-48 flex-col items-center justify-center bg-gradient-to-br from-red-50 to-white text-red-600">
                                        <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-100">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13H7V3zm7 0v6h5M10 14h4m-4 3h4"/></svg>
                                        </span>
                                        <span class="mt-3 text-sm font-extrabold">Dokumen PDF</span>
                                    </span>
                                    <span class="absolute left-3 top-3 rounded-full bg-slate-950/75 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white">Preview</span>
                                </span>

                                <span class="mx-auto mt-3 flex max-w-sm items-center justify-between gap-3 rounded-xl bg-blue-50 px-3 py-2.5 text-left">
                                    <span class="min-w-0">
                                        <span id="proof-preview-name" class="block truncate text-sm font-bold text-slate-800"></span>
                                        <span id="proof-preview-meta" class="mt-0.5 block text-xs text-slate-500"></span>
                                    </span>
                                    <span class="shrink-0 text-xs font-bold text-blue-600">Ganti file</span>
                                </span>
                            </span>
                            <input id="proof" name="proof" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" required class="sr-only">
                        </label>
                        <p id="proof-preview-error" role="alert" class="mt-2 hidden text-xs font-semibold text-red-600"></p>
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
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr><th class="px-5 py-3">Dikirim</th><th class="px-5 py-3">Nominal</th><th class="px-5 py-3">Tujuan</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Masa Aktif</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-5 py-4 text-slate-600">{{ $payment->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-700">{{ $payment->destination_type_label }} {{ $payment->destination_bank }}</p>
                                <p class="mt-0.5 font-mono text-xs text-slate-500">{{ $payment->destination_account_number }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $payment->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($payment->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ $payment->status_label }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $payment->ends_at ? $payment->starts_at->format('d/m/Y').' – '.$payment->ends_at->format('d/m/Y') : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Belum ada riwayat pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<div id="qris-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="qris-modal-title">
    <button type="button" data-qris-close class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" aria-label="Tutup QRIS"></button>
    <div class="relative z-10 w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-violet-600">Scan Pembayaran</p>
                <h2 id="qris-modal-title" class="mt-1 text-lg font-extrabold text-slate-900">QRIS</h2>
            </div>
            <button type="button" data-qris-close class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200" aria-label="Tutup">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="bg-gradient-to-br from-violet-50 via-white to-blue-50 p-6">
            <div class="mx-auto aspect-square max-w-xs overflow-hidden rounded-3xl border border-white bg-white p-3 shadow-xl shadow-violet-200/50">
                <img id="qris-modal-image" src="" alt="QRIS pembayaran subscribe" class="h-full w-full object-contain">
            </div>
            <p class="mt-4 text-center text-xs leading-5 text-slate-500">Periksa kembali nama dan nominal tujuan pada aplikasi pembayaran sebelum menyelesaikan transaksi.</p>
        </div>
    </div>
</div>

<style>
    .subscription-payment-choice > input:focus-visible + .subscription-payment-card {
        outline: 2px solid #2563eb;
        outline-offset: 2px;
    }
    .subscription-payment-choice > input:checked + .subscription-payment-card .subscription-payment-check {
        border-color: #2563eb;
        background: #2563eb;
        color: #fff;
    }
</style>
@endsection

@push('scripts')
<script>
    (() => {
        const input = document.getElementById('proof');
        if (!input) return;

        const dropzone = document.getElementById('proof-dropzone');
        const emptyState = document.getElementById('proof-empty-state');
        const preview = document.getElementById('proof-preview');
        const previewImage = document.getElementById('proof-preview-image');
        const previewPdf = document.getElementById('proof-preview-pdf');
        const previewName = document.getElementById('proof-preview-name');
        const previewMeta = document.getElementById('proof-preview-meta');
        const error = document.getElementById('proof-preview-error');
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
        const maximumSize = 5 * 1024 * 1024;
        let previewUrl = null;

        const revokePreviewUrl = () => {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }
        };

        const formatFileSize = (bytes) => {
            if (bytes < 1024) return `${bytes} B`;
            if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
            return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
        };

        const resetPreview = () => {
            previewImage.onload = null;
            previewImage.onerror = null;
            revokePreviewUrl();
            previewImage.removeAttribute('src');
            previewImage.classList.add('hidden');
            previewPdf.classList.add('hidden');
            previewPdf.classList.remove('flex');
            preview.classList.add('hidden');
            emptyState.classList.remove('hidden');
            dropzone.classList.remove('border-blue-400', 'bg-blue-50/50');
            dropzone.removeAttribute('aria-invalid');
        };

        const showError = (message) => {
            error.textContent = message;
            error.classList.remove('hidden');
            dropzone.setAttribute('aria-invalid', 'true');
        };

        input.addEventListener('change', () => {
            resetPreview();
            error.textContent = '';
            error.classList.add('hidden');

            const file = input.files?.[0];
            if (!file) return;

            const extension = file.name.split('.').pop()?.toLowerCase() ?? '';
            const isAllowedType = allowedTypes.includes(file.type) || (!file.type && allowedExtensions.includes(extension));

            if (!isAllowedType || !allowedExtensions.includes(extension)) {
                input.value = '';
                showError('Format file tidak didukung. Gunakan JPG, PNG, WEBP, atau PDF.');
                return;
            }

            if (file.size > maximumSize) {
                input.value = '';
                showError('Ukuran file melebihi batas maksimal 5 MB.');
                return;
            }

            const isPdf = file.type === 'application/pdf' || extension === 'pdf';
            const typeLabel = isPdf ? 'PDF' : extension.toUpperCase();

            emptyState.classList.add('hidden');
            preview.classList.remove('hidden');
            previewName.textContent = file.name;
            previewMeta.textContent = `${typeLabel} • ${formatFileSize(file.size)}`;
            dropzone.classList.add('border-blue-400', 'bg-blue-50/50');

            if (isPdf) {
                previewPdf.classList.remove('hidden');
                previewPdf.classList.add('flex');
                return;
            }

            const currentPreviewUrl = URL.createObjectURL(file);
            previewUrl = currentPreviewUrl;
            previewImage.src = currentPreviewUrl;
            previewImage.classList.remove('hidden');
            previewImage.onload = () => {
                if (previewUrl !== currentPreviewUrl) return;
                previewImage.onload = null;
                previewImage.onerror = null;
            };
            previewImage.onerror = () => {
                if (previewUrl !== currentPreviewUrl) return;
                input.value = '';
                resetPreview();
                showError('Gambar tidak dapat ditampilkan. Pilih file gambar lain yang valid.');
            };
        });

        window.addEventListener('beforeunload', revokePreviewUrl);
    })();

    (() => {
        const modal = document.getElementById('qris-modal');
        const image = document.getElementById('qris-modal-image');
        const title = document.getElementById('qris-modal-title');
        if (! modal || ! image || ! title) return;

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            image.removeAttribute('src');
            document.body.classList.remove('overflow-hidden');
        };

        document.querySelectorAll('[data-qris-open]').forEach((button) => {
            button.addEventListener('click', () => {
                image.src = button.dataset.qrisSrc;
                title.textContent = `QRIS ${button.dataset.qrisProvider}`;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
                modal.querySelector('[data-qris-close]')?.focus();
            });
        });

        modal.querySelectorAll('[data-qris-close]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && ! modal.classList.contains('hidden')) closeModal();
        });
    })();
</script>
@endpush
