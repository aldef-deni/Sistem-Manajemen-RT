@extends('layouts.app')

@section('title', 'Verifikasi Subscribe')
@section('page-title', 'Verifikasi Subscribe')
@section('page-subtitle', 'Tinjau pembayaran dan aktifkan akses pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('dashboard') }}" class="font-medium text-blue-600 hover:underline">Dashboard</a><span>/</span><span>Verifikasi Subscribe</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Pembayaran Subscribe</h1>
            <p class="mt-1 text-sm text-slate-500">Setiap persetujuan mengaktifkan akun selama 30 hari sejak diverifikasi.</p>
        </div>
        <a href="{{ route('subscribe.index') }}" class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm hover:bg-slate-50">Pengaturan Subscribe</a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
    @endif

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-amber-600">Menunggu</p><p class="mt-2 text-3xl font-black text-amber-900">{{ $counts['pending'] }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50 p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-emerald-600">Disetujui</p><p class="mt-2 text-3xl font-black text-emerald-900">{{ $counts['approved'] }}</p>
        </div>
        <div class="rounded-2xl border border-red-200 bg-gradient-to-br from-red-50 to-rose-50 p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-red-600">Ditolak</p><p class="mt-2 text-3xl font-black text-red-900">{{ $counts['rejected'] }}</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $value => $label)
            <a href="{{ route('subscribe.verifications.index', ['status' => $value]) }}" class="rounded-full px-4 py-2 text-xs font-bold transition {{ $filter === $value ? 'bg-slate-900 text-white shadow' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">{{ $label }}</a>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1080px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Pengguna</th><th class="px-5 py-3">Pembayaran</th><th class="px-5 py-3">Bukti</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        <tr class="align-top hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900">{{ $payment->user->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $payment->user->username }} · {{ $payment->user->role_label }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $payment->sender_bank }} · a.n. {{ $payment->sender_account_name }}</p>
                                <p class="mt-1 text-xs text-slate-400">Transfer {{ $payment->paid_at->format('d/m/Y') }} · dikirim {{ $payment->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('subscribe.verifications.proof', $payment) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat Bukti
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $payment->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($payment->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ $payment->status_label }}</span>
                                @if($payment->ends_at)<p class="mt-2 text-xs text-slate-500">s.d. {{ $payment->ends_at->translatedFormat('d M Y, H:i') }}</p>@endif
                                @if($payment->rejection_reason)<p class="mt-2 max-w-xs text-xs text-red-600">{{ $payment->rejection_reason }}</p>@endif
                            </td>
                            <td class="px-5 py-4">
                                @if($payment->status === 'pending')
                                    <div class="space-y-2">
                                        <form action="{{ route('subscribe.verifications.approve', $payment) }}" method="POST" onsubmit="return confirm('Aktifkan subscribe pengguna ini selama 30 hari?')">
                                            @csrf @method('PATCH')
                                            <button class="w-full rounded-lg bg-emerald-600 px-3 py-2 text-xs font-bold text-white hover:bg-emerald-700">Aktifkan 30 Hari</button>
                                        </form>
                                        <form action="{{ route('subscribe.verifications.reject', $payment) }}" method="POST" class="space-y-2">
                                            @csrf @method('PATCH')
                                            <input name="rejection_reason" type="text" maxlength="500" required placeholder="Alasan penolakan"
                                                class="w-56 rounded-lg border border-slate-200 px-3 py-2 text-xs outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100">
                                            <button class="w-full rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100">Tolak Pembayaran</button>
                                        </form>
                                    </div>
                                @else
                                    <p class="text-xs text-slate-400">Diverifikasi {{ $payment->verified_at?->diffForHumans() ?? '—' }}<br>{{ $payment->verifier?->name }}</p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-14 text-center text-slate-400">Tidak ada pembayaran pada filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">{{ $payments->links() }}</div>
        @endif
    </section>
</div>
@endsection
