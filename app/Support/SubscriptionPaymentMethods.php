<?php

namespace App\Support;

use App\Models\SettingRT;

class SubscriptionPaymentMethods
{
    public const TYPE_BANK = 'bank';

    public const TYPE_EWALLET = 'ewallet';

    /** @var list<string> */
    public const TYPES = [self::TYPE_BANK, self::TYPE_EWALLET];

    /**
     * @return list<array{id: string, type: string, provider: string, account_number: string, account_name: string, qris_path: string|null}>
     */
    public function all(): array
    {
        $stored = json_decode((string) SettingRT::get('subscribe_payment_methods', '[]'), true);

        if (is_array($stored)) {
            $methods = $this->normalize($stored);

            if ($methods !== []) {
                return $methods;
            }
        }

        $legacy = [
            'type' => (string) SettingRT::get('subscribe_payment_type', self::TYPE_BANK),
            'provider' => (string) SettingRT::get('subscribe_bank', ''),
            'account_number' => (string) SettingRT::get('subscribe_account_number', ''),
            'account_name' => (string) SettingRT::get('subscribe_account_name', ''),
        ];

        return $this->isComplete($legacy) ? $this->normalize([$legacy]) : [];
    }

    /**
     * @param  array<int, mixed>  $methods
     * @return list<array{id: string, type: string, provider: string, account_number: string, account_name: string, qris_path: string|null}>
     */
    public function normalize(array $methods): array
    {
        $normalized = [];

        foreach ($methods as $method) {
            if (! is_array($method)) {
                continue;
            }

            $item = [
                'type' => in_array(($method['type'] ?? ''), self::TYPES, true)
                    ? $method['type']
                    : self::TYPE_BANK,
                'provider' => trim((string) ($method['provider'] ?? '')),
                'account_number' => trim((string) ($method['account_number'] ?? '')),
                'account_name' => trim((string) ($method['account_name'] ?? '')),
                'qris_path' => $this->safeQrisPath($method['qris_path'] ?? null),
            ];

            if (! $this->isComplete($item)) {
                continue;
            }

            $item['id'] = $this->id($item);
            $normalized[] = $item;
        }

        return array_values($normalized);
    }

    /**
     * @param  array{type: string, provider: string, account_number: string, account_name: string}  $method
     */
    public function id(array $method): string
    {
        $identity = implode('|', [
            strtolower(trim($method['type'])),
            strtolower(trim($method['provider'])),
            preg_replace('/\s+/', '', trim($method['account_number'])),
            strtolower(trim($method['account_name'])),
        ]);

        return 'pm_'.substr(hash('sha256', $identity), 0, 24);
    }

    public static function typeLabel(string $type): string
    {
        return $type === self::TYPE_EWALLET ? 'E-Wallet' : 'Bank';
    }

    private function safeQrisPath(mixed $path): ?string
    {
        if (! is_string($path)
            || ! str_starts_with($path, 'subscribe-qris/')
            || str_contains($path, '..')
            || preg_match('/\Asubscribe-qris\/[A-Za-z0-9._-]+\z/', $path) !== 1) {
            return null;
        }

        return $path;
    }

    /** @param array<string, mixed> $method */
    private function isComplete(array $method): bool
    {
        return trim((string) ($method['provider'] ?? '')) !== ''
            && trim((string) ($method['account_number'] ?? '')) !== ''
            && trim((string) ($method['account_name'] ?? '')) !== '';
    }
}
