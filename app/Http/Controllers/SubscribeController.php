<?php

namespace App\Http\Controllers;

use App\Models\SettingRT;
use App\Support\SubscriptionPaymentMethods;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class SubscribeController extends Controller
{
    /**
     * Administrator sengaja tidak termasuk karena tidak pernah dikenai subscribe.
     *
     * @var array<string, array{label: string, deskripsi: string}>
     */
    private const SUBSCRIBE_ROLES = [
        'ketua' => [
            'label' => 'Ketua RT',
            'deskripsi' => 'Terapkan subscribe untuk akun dengan role Ketua RT.',
        ],
        'pengurus' => [
            'label' => 'Pengurus RT',
            'deskripsi' => 'Terapkan subscribe untuk akun pengelola operasional RT.',
        ],
        'warga' => [
            'label' => 'Warga',
            'deskripsi' => 'Terapkan subscribe untuk seluruh akun warga.',
        ],
    ];

    public function index(SubscriptionPaymentMethods $paymentMethods): View
    {
        $rolesTersimpan = json_decode(SettingRT::get('subscribe_roles', '[]'), true);
        $rolesTersimpan = is_array($rolesTersimpan) ? $rolesTersimpan : [];

        $subscribe = [
            'status' => SettingRT::get('subscribe_enabled', '0') === '1' ? 'aktif' : 'nonaktif',
            'roles' => array_values(array_intersect(array_keys(self::SUBSCRIBE_ROLES), $rolesTersimpan)),
            'harga' => SettingRT::get('subscribe_price', ''),
            'payment_methods' => $paymentMethods->all(),
        ];

        $roleOptions = self::SUBSCRIBE_ROLES;

        return view('subscribe.index', compact('subscribe', 'roleOptions'));
    }

    public function update(Request $request, SubscriptionPaymentMethods $paymentMethods): RedirectResponse
    {
        // Tetap menerima format satu rekening dari form/klien versi lama.
        if (! $request->has('payment_methods')
            && $request->filled('bank')
            && $request->filled('nomor_rekening')
            && $request->filled('nama_rekening')) {
            $request->merge([
                'payment_methods' => [[
                    'type' => 'bank',
                    'provider' => $request->input('bank'),
                    'account_number' => $request->input('nomor_rekening'),
                    'account_name' => $request->input('nama_rekening'),
                ]],
            ]);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
            'roles' => ['nullable', 'required_if:status,aktif', 'array', 'min:1'],
            'roles.*' => ['string', 'distinct', Rule::in(array_keys(self::SUBSCRIBE_ROLES))],
            'harga' => ['nullable', 'required_if:status,aktif', 'integer', 'min:1', 'max:999999999999'],
            'payment_methods' => ['nullable', 'required_if:status,aktif', 'array', 'min:1', 'max:10'],
            'payment_methods.*.type' => ['required_if:status,aktif', 'string', Rule::in(SubscriptionPaymentMethods::TYPES)],
            'payment_methods.*.provider' => ['nullable', 'required_if:status,aktif', 'string', 'max:100'],
            'payment_methods.*.account_number' => ['nullable', 'required_if:status,aktif', 'string', 'max:50', 'regex:/^[0-9+ .-]+$/'],
            'payment_methods.*.account_name' => ['nullable', 'required_if:status,aktif', 'string', 'max:150'],
            'payment_methods.*.existing_id' => ['nullable', 'string', 'max:40', 'regex:/^pm_[a-f0-9]{24}$/'],
            'payment_methods.*.qris' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072', 'dimensions:min_width=200,min_height=200,max_width=4000,max_height=4000'],
            'payment_methods.*.remove_qris' => ['nullable', 'boolean'],
        ], [
            'roles.required_if' => 'Pilih minimal satu role saat fitur subscribe diaktifkan.',
            'roles.min' => 'Pilih minimal satu role yang menggunakan subscribe.',
            'roles.*.in' => 'Role yang dipilih tidak diizinkan menggunakan pengaturan subscribe.',
            'roles.*.distinct' => 'Role subscribe tidak boleh dipilih lebih dari satu kali.',
            'harga.required_if' => 'Harga subscribe wajib diisi saat fitur diaktifkan.',
            'harga.integer' => 'Harga subscribe harus berupa angka bulat.',
            'harga.min' => 'Harga subscribe minimal Rp1.',
            'payment_methods.required_if' => 'Tambahkan minimal satu Bank atau E-Wallet saat fitur subscribe diaktifkan.',
            'payment_methods.min' => 'Tambahkan minimal satu tujuan pembayaran.',
            'payment_methods.max' => 'Tujuan pembayaran maksimal 10 metode.',
            'payment_methods.*.type.required' => 'Jenis tujuan pembayaran wajib dipilih.',
            'payment_methods.*.type.in' => 'Jenis tujuan pembayaran harus Bank atau E-Wallet.',
            'payment_methods.*.provider.required' => 'Nama Bank atau E-Wallet wajib diisi.',
            'payment_methods.*.account_number.required' => 'Nomor rekening atau E-Wallet wajib diisi.',
            'payment_methods.*.account_number.regex' => 'Nomor pembayaran hanya boleh berisi angka, tanda tambah, spasi, titik, atau tanda hubung.',
            'payment_methods.*.account_name.required' => 'Nama pemilik akun pembayaran wajib diisi.',
            'payment_methods.*.qris.image' => 'QRIS harus berupa file gambar.',
            'payment_methods.*.qris.mimes' => 'QRIS harus berformat JPG, PNG, atau WEBP.',
            'payment_methods.*.qris.max' => 'Ukuran gambar QRIS maksimal 3 MB.',
            'payment_methods.*.qris.dimensions' => 'Ukuran gambar QRIS minimal 200×200 dan maksimal 4000×4000 piksel.',
        ]);

        $currentPaymentMethods = $paymentMethods->all();
        $currentMethodsById = collect($currentPaymentMethods)->keyBy('id');
        $submittedMethods = array_key_exists('payment_methods', $validated)
            ? ($validated['payment_methods'] ?? [])
            : null;
        $methodRows = [];

        if (is_array($submittedMethods)) {
            foreach ($submittedMethods as $index => $submittedMethod) {
                $normalized = $paymentMethods->normalize([$submittedMethod])[0] ?? null;

                if ($normalized) {
                    $methodRows[] = [
                        'index' => $index,
                        'input' => $submittedMethod,
                        'method' => $normalized,
                    ];
                }
            }
        }

        $normalizedPaymentMethods = $submittedMethods === null
            ? $currentPaymentMethods
            : array_column($methodRows, 'method');
        $uniqueDestinations = array_unique(array_map(
            fn (array $method): string => strtolower($method['type'].'|'.$method['provider'].'|'.preg_replace('/\s+/', '', $method['account_number'])),
            $normalizedPaymentMethods
        ));

        if (count($uniqueDestinations) !== count($normalizedPaymentMethods)) {
            throw ValidationException::withMessages([
                'payment_methods' => 'Tujuan pembayaran yang sama tidak boleh ditambahkan lebih dari satu kali.',
            ]);
        }

        $oldQrisPaths = array_values(array_filter(array_column($currentPaymentMethods, 'qris_path')));
        $newQrisPaths = [];

        try {
            if ($submittedMethods !== null) {
                foreach ($methodRows as $position => $row) {
                    $existingId = (string) ($row['input']['existing_id'] ?? $row['method']['id']);
                    $existingMethod = $currentMethodsById->get($existingId);
                    $upload = $request->file('payment_methods.'.$row['index'].'.qris');

                    if ($upload instanceof UploadedFile) {
                        $extension = strtolower((string) $upload->guessExtension());
                        $extension = $extension === 'jpeg' ? 'jpg' : $extension;
                        $filename = 'qris_'.now()->format('YmdHis').'_'.bin2hex(random_bytes(6)).'.'.$extension;
                        $qrisPath = $upload->storeAs('subscribe-qris', $filename, 'local');

                        if (! $qrisPath) {
                            throw new RuntimeException('Gambar QRIS gagal disimpan.');
                        }

                        $newQrisPaths[] = $qrisPath;
                        $normalizedPaymentMethods[$position]['qris_path'] = $qrisPath;
                    } elseif (! filter_var($row['input']['remove_qris'] ?? false, FILTER_VALIDATE_BOOL)) {
                        $normalizedPaymentMethods[$position]['qris_path'] = $existingMethod['qris_path'] ?? null;
                    }
                }
            }

            DB::transaction(function () use ($validated, $normalizedPaymentMethods): void {
                SettingRT::set(
                    'subscribe_enabled',
                    $validated['status'] === 'aktif' ? '1' : '0',
                    'Status fitur subscribe warga'
                );
                SettingRT::set(
                    'subscribe_roles',
                    json_encode(array_values($validated['roles'] ?? []), JSON_THROW_ON_ERROR),
                    'Daftar role yang dikenai subscribe (Administrator selalu dikecualikan)'
                );
                SettingRT::set('subscribe_price', $validated['harga'] ?? null, 'Harga subscribe warga');
                SettingRT::set(
                    'subscribe_payment_methods',
                    json_encode($normalizedPaymentMethods, JSON_THROW_ON_ERROR),
                    'Daftar Bank dan E-Wallet tujuan pembayaran subscribe'
                );

                // Rekening pertama tetap disalin ke key lama untuk kompatibilitas versi sebelumnya.
                $primary = $normalizedPaymentMethods[0] ?? null;
                SettingRT::set('subscribe_payment_type', $primary['type'] ?? null, 'Jenis tujuan pembayaran subscribe utama');
                SettingRT::set('subscribe_bank', $primary['provider'] ?? null, 'Penyedia pembayaran subscribe utama');
                SettingRT::set('subscribe_account_number', $primary['account_number'] ?? null, 'Nomor akun pembayaran subscribe utama');
                SettingRT::set('subscribe_account_name', $primary['account_name'] ?? null, 'Nama pemilik akun pembayaran subscribe utama');
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($newQrisPaths);

            throw $exception;
        }

        $activeQrisPaths = array_values(array_filter(array_column($normalizedPaymentMethods, 'qris_path')));
        Storage::disk('local')->delete(array_values(array_diff($oldQrisPaths, $activeQrisPaths)));

        return redirect()
            ->route('subscribe.index')
            ->with('success', 'Pengaturan subscribe berhasil disimpan.');
    }
}
