<?php

namespace App\Http\Controllers;

use App\Models\SubscribePayment;
use App\Support\SubscriptionAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class SubscribePaymentController extends Controller
{
    public function index(Request $request, SubscriptionAccess $subscription): View|RedirectResponse
    {
        $user = $request->user();
        $status = $subscription->status($user);

        if (! $status['applies']) {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Akun Anda tidak memerlukan subscribe.');
        }

        $payments = SubscribePayment::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        return view('subscribe.payment', compact('status', 'payments'));
    }

    public function store(Request $request, SubscriptionAccess $subscription): RedirectResponse
    {
        $user = $request->user();
        $status = $subscription->status($user);

        abort_unless($status['applies'], 403, 'Akun Anda tidak memerlukan subscribe.');

        if ($status['active_payment']) {
            return back()->withErrors(['payment' => 'Subscribe Anda masih aktif. Pembayaran baru belum diperlukan.']);
        }

        if ($status['pending_payment']) {
            return back()->withErrors(['payment' => 'Pembayaran Anda sedang menunggu verifikasi Administrator.']);
        }

        abort_if(
            $status['price'] < 1
                || $status['bank'] === ''
                || $status['account_number'] === ''
                || $status['account_name'] === '',
            422,
            'Konfigurasi pembayaran subscribe belum lengkap.'
        );

        $validated = $request->validate([
            'sender_bank' => ['required', 'string', 'max:100'],
            'sender_account_name' => ['required', 'string', 'max:150'],
            'paid_at' => ['required', 'date', 'before_or_equal:today'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'sender_bank.required' => 'Bank pengirim wajib diisi.',
            'sender_account_name.required' => 'Nama pemilik rekening pengirim wajib diisi.',
            'paid_at.required' => 'Tanggal pembayaran wajib diisi.',
            'paid_at.before_or_equal' => 'Tanggal pembayaran tidak boleh melebihi hari ini.',
            'proof.required' => 'Bukti pembayaran wajib diunggah.',
            'proof.mimes' => 'Bukti pembayaran harus berupa JPG, PNG, WEBP, atau PDF.',
            'proof.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
        ]);

        $file = $request->file('proof');
        $extension = strtolower((string) $file->guessExtension());
        $extension = $extension === 'jpeg' ? 'jpg' : $extension;
        $filename = 'subscribe_'.$user->id.'_'.now()->format('YmdHis').'_'.bin2hex(random_bytes(6)).'.'.$extension;
        $proofPath = $file->storeAs('subscribe-proofs', $filename, 'local');

        abort_unless($proofPath, 500, 'Bukti pembayaran gagal disimpan.');

        try {
            DB::transaction(function () use ($user, $status, $validated, $proofPath): void {
                abort_if(
                    SubscribePayment::query()
                        ->where('user_id', $user->id)
                        ->where('status', SubscribePayment::STATUS_PENDING)
                        ->lockForUpdate()
                        ->exists(),
                    422,
                    'Pembayaran Anda sedang menunggu verifikasi Administrator.'
                );

                SubscribePayment::create([
                    'user_id' => $user->id,
                    'amount' => $status['price'],
                    'destination_bank' => $status['bank'],
                    'destination_account_number' => $status['account_number'],
                    'destination_account_name' => $status['account_name'],
                    'sender_bank' => $validated['sender_bank'],
                    'sender_account_name' => $validated['sender_account_name'],
                    'paid_at' => $validated['paid_at'],
                    'proof_path' => $proofPath,
                    'notes' => $validated['notes'] ?? null,
                    'status' => SubscribePayment::STATUS_PENDING,
                    'pending_key' => 'user:'.$user->id,
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($proofPath);
            throw $exception;
        }

        return redirect()
            ->route('subscribe.payment.index')
            ->with('success', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi Administrator.');
    }
}
