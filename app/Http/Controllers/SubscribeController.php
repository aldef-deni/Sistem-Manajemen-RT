<?php

namespace App\Http\Controllers;

use App\Models\SettingRT;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

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

    public function index(): View
    {
        $rolesTersimpan = json_decode(SettingRT::get('subscribe_roles', '[]'), true);
        $rolesTersimpan = is_array($rolesTersimpan) ? $rolesTersimpan : [];

        $subscribe = [
            'status' => SettingRT::get('subscribe_enabled', '0') === '1' ? 'aktif' : 'nonaktif',
            'roles' => array_values(array_intersect(array_keys(self::SUBSCRIBE_ROLES), $rolesTersimpan)),
            'harga' => SettingRT::get('subscribe_price', ''),
            'bank' => SettingRT::get('subscribe_bank', ''),
            'nomor_rekening' => SettingRT::get('subscribe_account_number', ''),
            'nama_rekening' => SettingRT::get('subscribe_account_name', ''),
        ];

        $roleOptions = self::SUBSCRIBE_ROLES;

        return view('subscribe.index', compact('subscribe', 'roleOptions'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
            'roles' => ['nullable', 'required_if:status,aktif', 'array', 'min:1'],
            'roles.*' => ['string', 'distinct', Rule::in(array_keys(self::SUBSCRIBE_ROLES))],
            'harga' => ['nullable', 'required_if:status,aktif', 'integer', 'min:1', 'max:999999999999'],
            'bank' => ['nullable', 'required_if:status,aktif', 'string', 'max:100'],
            'nomor_rekening' => ['nullable', 'required_if:status,aktif', 'string', 'max:50', 'regex:/^[0-9 .-]+$/'],
            'nama_rekening' => ['nullable', 'required_if:status,aktif', 'string', 'max:150'],
        ], [
            'roles.required_if' => 'Pilih minimal satu role saat fitur subscribe diaktifkan.',
            'roles.min' => 'Pilih minimal satu role yang menggunakan subscribe.',
            'roles.*.in' => 'Role yang dipilih tidak diizinkan menggunakan pengaturan subscribe.',
            'roles.*.distinct' => 'Role subscribe tidak boleh dipilih lebih dari satu kali.',
            'harga.required_if' => 'Harga subscribe wajib diisi saat fitur diaktifkan.',
            'harga.integer' => 'Harga subscribe harus berupa angka bulat.',
            'harga.min' => 'Harga subscribe minimal Rp1.',
            'bank.required_if' => 'Nama bank wajib diisi saat fitur diaktifkan.',
            'nomor_rekening.required_if' => 'Nomor rekening wajib diisi saat fitur diaktifkan.',
            'nomor_rekening.regex' => 'Nomor rekening hanya boleh berisi angka, spasi, titik, atau tanda hubung.',
            'nama_rekening.required_if' => 'Nama pemilik rekening wajib diisi saat fitur diaktifkan.',
        ]);

        DB::transaction(function () use ($validated): void {
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
            SettingRT::set('subscribe_bank', $validated['bank'] ?? null, 'Bank tujuan pembayaran subscribe');
            SettingRT::set('subscribe_account_number', $validated['nomor_rekening'] ?? null, 'Nomor rekening pembayaran subscribe');
            SettingRT::set('subscribe_account_name', $validated['nama_rekening'] ?? null, 'Nama pemilik rekening pembayaran subscribe');
        });

        return redirect()
            ->route('subscribe.index')
            ->with('success', 'Pengaturan subscribe berhasil disimpan.');
    }
}
