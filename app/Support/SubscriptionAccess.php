<?php

namespace App\Support;

use App\Models\SettingRT;
use App\Models\SubscribePayment;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionAccess
{
    /** @var list<string> */
    public const ALLOWED_ROLES = ['ketua', 'pengurus', 'warga'];

    /** @var list<string> */
    private const PROTECTED_ROUTE_PREFIXES = [
        'iuran-warga.',
        'kas-rt.',
        'tabungan.',
        'pinjaman.',
        'arisan.',
        'pengumuman.',
        'kalender.',
        'jadwal-kegiatan.',
        'kegiatan-rt.',
        'notulen-rapat.',
        'struktur-rt.',
        'pengaduan.',
        'polling.',
    ];

    /** @var list<string> */
    private const PROTECTED_ROUTE_NAMES = ['pembayaran', 'laporan-keuangan'];

    /** @var list<string> */
    private const PROTECTED_API_PATHS = [
        'api/pengumuman*',
        'api/kegiatan*',
        'api/jadwal*',
        'api/struktur-rt*',
        'api/iuran-saya*',
        'api/pengaduan*',
        'api/polling*',
        'api/kelola/ringkasan*',
        'api/kelola/kas*',
        'api/kelola/iuran*',
        'api/kelola/pengaduan*',
    ];

    public function __construct(private readonly SubscriptionPaymentMethods $paymentMethods) {}

    public function enabled(): bool
    {
        return SettingRT::get('subscribe_enabled', '0') === '1';
    }

    /** @return list<string> */
    public function roles(): array
    {
        $roles = json_decode(SettingRT::get('subscribe_roles', '[]'), true);

        if (! is_array($roles)) {
            return [];
        }

        return array_values(array_intersect(self::ALLOWED_ROLES, $roles));
    }

    public function appliesTo(User $user): bool
    {
        return $user->role !== 'admin'
            && $this->enabled()
            && in_array($user->role, $this->roles(), true);
    }

    public function activePayment(User $user): ?SubscribePayment
    {
        return SubscribePayment::query()
            ->where('user_id', $user->id)
            ->where('status', SubscribePayment::STATUS_APPROVED)
            ->whereNotNull('starts_at')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->latest('ends_at')
            ->first();
    }

    public function pendingPayment(User $user): ?SubscribePayment
    {
        return SubscribePayment::query()
            ->where('user_id', $user->id)
            ->where('status', SubscribePayment::STATUS_PENDING)
            ->latest('id')
            ->first();
    }

    /**
     * @return array{
     *   applies: bool,
     *   locked: bool,
     *   state: string,
     *   active_payment: SubscribePayment|null,
     *   pending_payment: SubscribePayment|null,
     *   latest_payment: SubscribePayment|null,
     *   price: int,
     *   payment_methods: list<array{id: string, type: string, provider: string, account_number: string, account_name: string, qris_path: string|null}>,
     *   bank: string,
     *   account_number: string,
     *   account_name: string
     * }
     */
    public function status(User $user): array
    {
        $applies = $this->appliesTo($user);
        $activePayment = $applies ? $this->activePayment($user) : null;
        $pendingPayment = $applies && ! $activePayment ? $this->pendingPayment($user) : null;
        $latestPayment = $applies
            ? SubscribePayment::query()->where('user_id', $user->id)->latest('id')->first()
            : null;

        $state = match (true) {
            ! $applies => 'not_applicable',
            $activePayment !== null => 'active',
            $pendingPayment !== null => 'pending',
            $latestPayment?->status === SubscribePayment::STATUS_REJECTED => 'rejected',
            $latestPayment?->status === SubscribePayment::STATUS_APPROVED => 'expired',
            default => 'required',
        };
        $paymentMethods = $this->paymentMethods->all();
        $primaryPaymentMethod = $paymentMethods[0] ?? null;

        return [
            'applies' => $applies,
            'locked' => $applies && $activePayment === null,
            'state' => $state,
            'active_payment' => $activePayment,
            'pending_payment' => $pendingPayment,
            'latest_payment' => $latestPayment,
            'price' => (int) SettingRT::get('subscribe_price', 0),
            'payment_methods' => $paymentMethods,
            // Dipertahankan untuk kompatibilitas kode lama selama transisi multi-rekening.
            'bank' => $primaryPaymentMethod['provider'] ?? '',
            'account_number' => $primaryPaymentMethod['account_number'] ?? '',
            'account_name' => $primaryPaymentMethod['account_name'] ?? '',
        ];
    }

    public function protects(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if ($routeName && in_array($routeName, self::PROTECTED_ROUTE_NAMES, true)) {
            return true;
        }

        if ($routeName) {
            foreach (self::PROTECTED_ROUTE_PREFIXES as $prefix) {
                if (str_starts_with($routeName, $prefix)) {
                    return true;
                }
            }
        }

        foreach (self::PROTECTED_API_PATHS as $path) {
            if ($request->is($path)) {
                return true;
            }
        }

        return false;
    }
}
