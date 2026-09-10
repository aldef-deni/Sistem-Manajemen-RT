<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscribePayment;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Support\SubscriptionAccess;
use App\Support\SubscriptionPaymentMethods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class SubscribeApiController extends Controller
{
    public function index(Request $request, SubscriptionAccess $subscription): JsonResponse
    {
        $status = $subscription->status($request->user());
        $methods = collect($status['payment_methods'])->map(function (array $method): array {
            return [
                ...$method,
                'qris_url' => $method['qris_path'] ? url('/api/subscribe/qris/'.$method['id']) : null,
            ];
        })->values();

        $payments = SubscribePayment::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn (SubscribePayment $item): array => $this->paymentPayload($item));

        return response()->json([
            'status' => [
                'berlaku' => $status['applies'],
                'terkunci' => $status['locked'],
                'state' => $status['state'],
                'harga' => $status['price'],
                'mulai' => $status['active_payment']?->starts_at?->toIso8601String(),
                'berakhir' => $status['active_payment']?->ends_at?->toIso8601String(),
                'alasan_penolakan' => $status['latest_payment']?->rejection_reason,
            ],
            'metode_pembayaran' => $methods,
            'riwayat' => $payments,
        ]);
    }

    public function store(Request $request, SubscriptionAccess $subscription): JsonResponse
    {
        $user = $request->user();
        $status = $subscription->status($user);
        abort_unless($status['applies'], 403, 'Akun Anda tidak memerlukan subscribe.');

        if ($status['active_payment']) {
            throw ValidationException::withMessages(['payment' => 'Subscribe Anda masih aktif.']);
        }
        if ($status['pending_payment']) {
            throw ValidationException::withMessages(['payment' => 'Pembayaran sedang menunggu verifikasi Administrator.']);
        }
        abort_if($status['price'] < 1 || $status['payment_methods'] === [], 422, 'Konfigurasi pembayaran subscribe belum lengkap.');

        if (count($status['payment_methods']) === 1 && ! $request->filled('payment_method_id')) {
            $request->merge(['payment_method_id' => $status['payment_methods'][0]['id']]);
        }

        $data = $request->validate([
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

        $destination = collect($status['payment_methods'])->firstWhere('id', $data['payment_method_id']);
        if (! $destination) {
            throw ValidationException::withMessages(['payment_method_id' => 'Tujuan pembayaran tidak tersedia.']);
        }

        $file = $request->file('proof');
        $extension = strtolower((string) $file->guessExtension());
        $extension = $extension === 'jpeg' ? 'jpg' : $extension;
        $filename = 'subscribe_'.$user->id.'_'.now()->format('YmdHis').'_'.bin2hex(random_bytes(6)).'.'.$extension;
        $proofPath = $file->storeAs('subscribe-proofs', $filename, 'local');
        abort_unless($proofPath, 500, 'Bukti pembayaran gagal disimpan.');

        try {
            $payment = DB::transaction(function () use ($user, $status, $destination, $data, $proofPath): SubscribePayment {
                abort_if(
                    SubscribePayment::query()->where('user_id', $user->id)
                        ->where('status', SubscribePayment::STATUS_PENDING)->lockForUpdate()->exists(),
                    422,
                    'Pembayaran sedang menunggu verifikasi Administrator.'
                );

                return SubscribePayment::create([
                    'user_id' => $user->id,
                    'amount' => $status['price'],
                    'destination_type' => $destination['type'],
                    'destination_bank' => $destination['provider'],
                    'destination_account_number' => $destination['account_number'],
                    'destination_account_name' => $destination['account_name'],
                    'sender_bank' => $data['sender_bank'],
                    'sender_account_name' => $data['sender_account_name'],
                    'paid_at' => $data['paid_at'],
                    'proof_path' => $proofPath,
                    'notes' => $data['notes'] ?? null,
                    'status' => SubscribePayment::STATUS_PENDING,
                    'pending_key' => 'user:'.$user->id,
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($proofPath);
            throw $exception;
        }

        Notification::send(User::query()->where('role', 'admin')->get(), new SystemNotification(
            category: 'subscribe',
            title: 'Pembayaran subscribe baru',
            message: $user->name.' mengirim bukti pembayaran dan menunggu verifikasi.',
            routeName: 'subscribe.verifications.index',
            routeParams: ['status' => 'pending'],
            tone: 'amber',
            context: ['payment_id' => $payment->id],
        ));

        return response()->json([
            'pesan' => 'Bukti pembayaran berhasil dikirim dan menunggu verifikasi Administrator.',
            'data' => $this->paymentPayload($payment),
        ], 201);
    }

    public function qris(string $paymentMethod, SubscriptionPaymentMethods $paymentMethods): StreamedResponse
    {
        $method = collect($paymentMethods->all())->firstWhere('id', $paymentMethod);
        $path = $method['qris_path'] ?? null;
        abort_unless(is_string($path) && str_starts_with($path, 'subscribe-qris/') && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, 'qris-subscribe-'.basename($path), ['Content-Disposition' => 'inline']);
    }

    public function verifications(Request $request): JsonResponse
    {
        $filter = in_array($request->query('status'), ['pending', 'approved', 'rejected', 'all'], true)
            ? $request->query('status')
            : 'pending';
        $items = SubscribePayment::query()->with(['user', 'verifier'])
            ->when($filter !== 'all', fn ($query) => $query->where('status', $filter))
            ->latest('id')->paginate(20);

        return response()->json([
            'jumlah' => [
                'pending' => SubscribePayment::where('status', SubscribePayment::STATUS_PENDING)->count(),
                'approved' => SubscribePayment::where('status', SubscribePayment::STATUS_APPROVED)->count(),
                'rejected' => SubscribePayment::where('status', SubscribePayment::STATUS_REJECTED)->count(),
            ],
            'data' => collect($items->items())->map(fn (SubscribePayment $item): array => $this->paymentPayload($item, true))->values(),
            'halaman' => ['saat_ini' => $items->currentPage(), 'terakhir' => $items->lastPage(), 'total' => $items->total()],
        ]);
    }

    public function proof(SubscribePayment $payment): StreamedResponse
    {
        abort_unless(str_starts_with($payment->proof_path, 'subscribe-proofs/') && Storage::disk('local')->exists($payment->proof_path), 404);

        return Storage::disk('local')->response(
            $payment->proof_path,
            'bukti-subscribe-'.$payment->id.'.'.pathinfo($payment->proof_path, PATHINFO_EXTENSION),
            ['Content-Disposition' => 'inline']
        );
    }

    public function approve(Request $request, SubscribePayment $payment): JsonResponse
    {
        $payment = DB::transaction(function () use ($request, $payment): SubscribePayment {
            $payment = SubscribePayment::query()->lockForUpdate()->findOrFail($payment->id);
            abort_unless($payment->status === SubscribePayment::STATUS_PENDING, 422, 'Pembayaran ini sudah diverifikasi.');
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
            category: 'subscribe', title: 'Pembayaran subscribe disetujui',
            message: 'Akses subscribe Anda aktif selama 30 hari.', routeName: 'subscribe.payment.index',
            tone: 'emerald', context: ['payment_id' => $payment->id],
        ));

        return response()->json(['pesan' => 'Pembayaran disetujui. Subscribe aktif selama 30 hari.']);
    }

    public function reject(Request $request, SubscribePayment $payment): JsonResponse
    {
        $data = $request->validate(['rejection_reason' => ['required', 'string', 'max:500']], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);
        $payment = DB::transaction(function () use ($request, $payment, $data): SubscribePayment {
            $payment = SubscribePayment::query()->lockForUpdate()->findOrFail($payment->id);
            abort_unless($payment->status === SubscribePayment::STATUS_PENDING, 422, 'Pembayaran ini sudah diverifikasi.');
            $payment->update([
                'status' => SubscribePayment::STATUS_REJECTED,
                'pending_key' => null,
                'rejection_reason' => $data['rejection_reason'],
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
                'starts_at' => null,
                'ends_at' => null,
            ]);

            return $payment;
        });
        $payment->user?->notify(new SystemNotification(
            category: 'subscribe', title: 'Pembayaran subscribe ditolak',
            message: 'Pembayaran perlu dikirim ulang. Alasan: '.$data['rejection_reason'],
            routeName: 'subscribe.payment.index', tone: 'rose', context: ['payment_id' => $payment->id],
        ));

        return response()->json(['pesan' => 'Pembayaran ditolak dan pengguna dapat mengirim bukti baru.']);
    }

    private function paymentPayload(SubscribePayment $item, bool $withUser = false): array
    {
        return [
            'id' => $item->id,
            'jumlah' => (int) $item->amount,
            'tujuan_tipe' => $item->destination_type,
            'tujuan_provider' => $item->destination_bank,
            'tujuan_nomor' => $item->destination_account_number,
            'tujuan_nama' => $item->destination_account_name,
            'pengirim_provider' => $item->sender_bank,
            'pengirim_nama' => $item->sender_account_name,
            'tanggal_bayar' => $item->paid_at?->toDateString(),
            'catatan' => $item->notes,
            'status' => $item->status,
            'status_label' => $item->status_label,
            'alasan_penolakan' => $item->rejection_reason,
            'mulai' => $item->starts_at?->toIso8601String(),
            'berakhir' => $item->ends_at?->toIso8601String(),
            'dibuat_pada' => $item->created_at?->toIso8601String(),
            'bukti_url' => $withUser ? url('/api/subscribe/verifikasi/'.$item->id.'/bukti') : null,
            'pengguna' => $withUser ? [
                'id' => $item->user?->id,
                'nama' => $item->user?->name,
                'peran' => $item->user?->role_label,
            ] : null,
        ];
    }
}
