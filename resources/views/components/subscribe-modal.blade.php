@php
    $modalState = $subscriptionStatus['state'];
    $modalPending = $subscriptionStatus['pending_payment'];
    $modalLatest = $subscriptionStatus['latest_payment'];
@endphp

<div id="subscribe-required-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="subscribe-modal-title">
    <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" data-subscribe-close></div>
    <div class="relative w-full max-w-lg overflow-hidden rounded-3xl border border-white/20 bg-white shadow-2xl">
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 px-6 pb-8 pt-7 text-white">
            <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-blue-400/20 blur-2xl"></div>
            <button type="button" data-subscribe-close class="absolute right-4 top-4 rounded-full bg-white/10 p-2 text-white/70 transition hover:bg-white/20 hover:text-white" aria-label="Tutup modal">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="relative">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 shadow-lg shadow-orange-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h2 id="subscribe-modal-title" class="text-2xl font-extrabold">
                    @if($modalState === 'pending')
                        Pembayaran sedang diverifikasi
                    @elseif($modalState === 'rejected')
                        Pembayaran perlu diperbaiki
                    @elseif($modalState === 'expired')
                        Masa subscribe telah berakhir
                    @else
                        Aktifkan akses lengkap Anda
                    @endif
                </h2>
                <p class="mt-2 text-sm leading-6 text-blue-100/80">
                    Menu Keuangan, Kegiatan & Info, serta Aspirasi & Partisipasi memerlukan subscribe aktif.
                </p>
            </div>
        </div>

        <div class="space-y-5 p-6">
            @if($modalState === 'rejected' && $modalLatest?->rejection_reason)
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                    <p class="text-xs font-bold uppercase tracking-wide text-red-500">Alasan penolakan</p>
                    <p class="mt-1 text-sm text-red-700">{{ $modalLatest->rejection_reason }}</p>
                </div>
            @elseif($modalState === 'pending')
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Bukti yang dikirim pada {{ $modalPending->created_at->translatedFormat('d F Y, H:i') }} WIB sedang menunggu keputusan Administrator.
                </div>
            @endif

            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Biaya</p>
                    <p class="mt-1 text-xl font-extrabold text-slate-900">Rp {{ number_format($subscriptionStatus['price'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Masa aktif</p>
                    <p class="mt-1 text-xl font-extrabold text-slate-900">30 hari</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" data-subscribe-close class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Nanti</button>
                <a href="{{ route('subscribe.payment.index') }}" class="flex-1 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3 text-center text-sm font-bold text-white shadow-lg shadow-blue-500/20 transition hover:shadow-blue-500/40">
                    {{ $modalState === 'pending' ? 'Lihat Status' : 'Bayar Subscribe' }}
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const modal = document.getElementById('subscribe-required-modal');
        if (! modal) return;

        window.openSubscribeModal = function () {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        };

        window.closeSubscribeModal = function () {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        modal.querySelectorAll('[data-subscribe-close]').forEach(function (element) {
            element.addEventListener('click', window.closeSubscribeModal);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') window.closeSubscribeModal();
        });

        @if(session('show_subscribe_modal') || (request()->routeIs('dashboard') && in_array($modalState, ['required', 'expired', 'rejected'], true)))
            window.openSubscribeModal();
        @endif
    })();
</script>
@endpush
