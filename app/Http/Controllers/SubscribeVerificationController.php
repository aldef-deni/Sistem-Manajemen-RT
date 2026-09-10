<?php

namespace App\Http\Controllers;

use App\Models\SubscribePayment;
use App\Notifications\SystemNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscribeVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $filter = in_array($request->query('status'), ['pending', 'approved', 'rejected', 'all'], true)
            ? $request->query('status')
            : 'pending';

        $payments = SubscribePayment::query()
            ->with(['user', 'verifier'])
            ->when($filter !== 'all', fn ($query) => $query->where('status', $filter))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'pending' => SubscribePayment::where('status', SubscribePayment::STATUS_PENDING)->count(),
            'approved' => SubscribePayment::where('status', SubscribePayment::STATUS_APPROVED)->count(),
            'rejected' => SubscribePayment::where('status', SubscribePayment::STATUS_REJECTED)->count(),
        ];

        return view('subscribe.verifications', compact('payments', 'counts', 'filter'));
    }

    public function approve(Request $request, SubscribePayment $payment): RedirectResponse
    {
        $payment = DB::transaction(function () use ($request, $payment): SubscribePayment {
            $payment = SubscribePayment::query()->lockForUpdate()->findOrFail($payment->id);

            abort_unless(
                $payment->status === SubscribePayment::STATUS_PENDING,
                422,
                'Pembayaran ini sudah diverifikasi.'
            );

            $startsAt = now();

            $payment->update([
                'status' => SubscribePayment::STATUS_APPROVED,
                'pending_key' => null,
                'rejection_reason' => null,
                'verified_by' => $request->user()->id,
                'verified_at' => $startsAt,
                'starts_at' => $startsAt,
                'ends_at' => $startsAt->copy()->addDays(30),
            ]);

            return $payment;
        });

        $payment->user?->notify(new SystemNotification(
            category: 'subscribe',
            title: 'Pembayaran subscribe disetujui',
            message: 'Akses subscribe Anda aktif selama 30 hari.',
            routeName: 'subscribe.payment.index',
            tone: 'emerald',
            context: ['payment_id' => $payment->id],
        ));

        return back()->with('success', 'Pembayaran disetujui. Subscribe pengguna aktif selama 30 hari.');
    }

    public function reject(Request $request, SubscribePayment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $payment = DB::transaction(function () use ($request, $payment, $validated): SubscribePayment {
            $payment = SubscribePayment::query()->lockForUpdate()->findOrFail($payment->id);

            abort_unless(
                $payment->status === SubscribePayment::STATUS_PENDING,
                422,
                'Pembayaran ini sudah diverifikasi.'
            );

            $payment->update([
                'status' => SubscribePayment::STATUS_REJECTED,
                'pending_key' => null,
                'rejection_reason' => $validated['rejection_reason'],
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
                'starts_at' => null,
                'ends_at' => null,
            ]);

            return $payment;
        });

        $payment->user?->notify(new SystemNotification(
            category: 'subscribe',
            title: 'Pembayaran subscribe ditolak',
            message: 'Pembayaran perlu dikirim ulang. Alasan: '.$validated['rejection_reason'],
            routeName: 'subscribe.payment.index',
            tone: 'rose',
            context: ['payment_id' => $payment->id],
        ));

        return back()->with('success', 'Pembayaran ditolak dan pengguna dapat mengirim bukti baru.');
    }

    public function proof(SubscribePayment $payment): StreamedResponse
    {
        abort_unless(
            str_starts_with($payment->proof_path, 'subscribe-proofs/')
                && Storage::disk('local')->exists($payment->proof_path),
            404
        );

        $extension = pathinfo($payment->proof_path, PATHINFO_EXTENSION);

        return Storage::disk('local')->response(
            $payment->proof_path,
            'bukti-subscribe-'.$payment->id.'.'.$extension,
            ['Content-Disposition' => 'inline']
        );
    }
}
