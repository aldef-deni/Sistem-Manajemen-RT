<?php

namespace App\Http\Controllers;

use App\Models\SubscribePayment;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Support\SubscriptionAccess;
use App\Support\SubscriptionPaymentMethods;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class SubscribePaymentController extends Controller
{
    public function qris(string $paymentMethod, SubscriptionPaymentMethods $paymentMethods): StreamedResponse
    {
        $qrisPath = $this->qrisPath($paymentMethod, $paymentMethods);

        $extension = pathinfo($qrisPath, PATHINFO_EXTENSION);

        return Storage::disk('local')->response(
            $qrisPath,
            'qris-subscribe-'.$paymentMethod.'.'.$extension,
            ['Content-Disposition' => 'inline']
        );
    }

    public function downloadQris(string $paymentMethod, SubscriptionPaymentMethods $paymentMethods): StreamedResponse
    {
        $qrisPath = $this->qrisPath($paymentMethod, $paymentMethods);
        $extension = pathinfo($qrisPath, PATHINFO_EXTENSION);

        return Storage::disk('local')->download(
            $qrisPath,
            'qris-subscribe-'.$paymentMethod.'.'.$extension
        );
    }

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
                || $status['payment_methods'] === [],
            422,
            'Konfigurasi pembayaran subscribe belum lengkap.'
        );

        if (count($status['payment_methods']) === 1 && ! $request->filled('payment_method_id')) {
            $request->merge(['payment_method_id' => $status['payment_methods'][0]['id']]);
        }

        $validated = $request->validate([
            'payment_method_id' => ['required', 'string', 'max:40'],
            'sender_bank' => ['required', 'string', 'max:100'],
            'sender_account_name' => ['required', 'string', 'max:150'],
            'paid_at' => ['required', 'date', 'before_or_equal:today'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'payment_method_id.required' => 'Pilih Bank atau E-Wallet tujuan pembayaran.',
            'sender_bank.required' => 'Bank atau E-Wallet pengirim wajib diisi.',
            'sender_account_name.required' => 'Nama pemilik rekening pengirim wajib diisi.',
            'paid_at.required' => 'Tanggal pembayaran wajib diisi.',
            'paid_at.before_or_equal' => 'Tanggal pembayaran tidak boleh melebihi hari ini.',
            'proof.required' => 'Bukti pembayaran wajib diunggah.',
            'proof.mimes' => 'Bukti pembayaran harus berupa JPG, PNG, WEBP, atau PDF.',
            'proof.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
        ]);

        $destination = collect($status['payment_methods'])
            ->firstWhere('id', $validated['payment_method_id']);

        if (! $destination) {
            throw ValidationException::withMessages([
                'payment_method_id' => 'Tujuan pembayaran tidak tersedia atau telah berubah. Silakan pilih kembali.',
            ]);
        }

        $file = $request->file('proof');
        $extension = strtolower((string) $file->guessExtension());
        $extension = $extension === 'jpeg' ? 'jpg' : $extension;
        $filename = 'subscribe_'.$user->id.'_'.now()->format('YmdHis').'_'.bin2hex(random_bytes(6)).'.'.$extension;
        $proofPath = $file->storeAs('subscribe-proofs', $filename, 'local');

        abort_unless($proofPath, 500, 'Bukti pembayaran gagal disimpan.');

        try {
            $payment = DB::transaction(function () use ($user, $status, $destination, $validated, $proofPath): SubscribePayment {
                abort_if(
                    SubscribePayment::query()
                        ->where('user_id', $user->id)
                        ->where('status', SubscribePayment::STATUS_PENDING)
                        ->lockForUpdate()
                        ->exists(),
                    422,
                    'Pembayaran Anda sedang menunggu verifikasi Administrator.'
                );

                return SubscribePayment::create([
                    'user_id' => $user->id,
                    'amount' => $status['price'],
                    'destination_type' => $destination['type'],
                    'destination_bank' => $destination['provider'],
                    'destination_account_number' => $destination['account_number'],
                    'destination_account_name' => $destination['account_name'],
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

        Notification::send(
            User::query()->where('role', 'admin')->get(),
            new SystemNotification(
                category: 'subscribe',
                title: 'Pembayaran subscribe baru',
                message: $user->name.' mengirim bukti pembayaran dan menunggu verifikasi.',
                routeName: 'subscribe.verifications.index',
                routeParams: ['status' => 'pending'],
                tone: 'amber',
                context: ['payment_id' => $payment->id],
            )
        );

        return redirect()
            ->route('subscribe.payment.index')
            ->with('success', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi Administrator.');
    }

    private function qrisPath(string $paymentMethod, SubscriptionPaymentMethods $paymentMethods): string
    {
        $method = collect($paymentMethods->all())->firstWhere('id', $paymentMethod);
        $qrisPath = $method['qris_path'] ?? null;

        abort_unless(
            is_string($qrisPath)
                && str_starts_with($qrisPath, 'subscribe-qris/')
                && Storage::disk('local')->exists($qrisPath),
            404
        );

        return $qrisPath;
    }
}
