<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ResidentRegistrationApiController extends Controller
{
    private const CACHE_PREFIX = 'resident-mobile-registration:';

    private const TOKEN_LIFETIME_MINUTES = 15;

    public function verify(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nik' => ['required', 'digits:16'],
            'no_kk' => ['required', 'digits:16'],
        ], [
            'nik.digits' => 'NIK harus terdiri dari 16 digit.',
            'no_kk.digits' => 'Nomor KK harus terdiri dari 16 digit.',
        ]);

        $members = AnggotaKeluarga::query()
            ->with('kartuKeluarga:id,no_kk')
            ->kepalaKeluarga()
            ->where('nik', $data['nik'])
            ->whereHas('kartuKeluarga', fn ($query) => $query->where('no_kk', $data['no_kk']))
            ->limit(2)
            ->get();

        if ($members->count() !== 1) {
            throw ValidationException::withMessages([
                'identity' => 'NIK dan Nomor KK tidak cocok dengan data Kepala Keluarga yang terdaftar. Silakan hubungi pengurus RT.',
            ]);
        }

        $member = $members->first();
        if (User::query()->where('anggota_keluarga_id', $member->id)->exists()) {
            throw ValidationException::withMessages([
                'identity' => 'Data Kepala Keluarga tersebut sudah memiliki akun. Silakan masuk atau hubungi pengurus RT bila lupa akun.',
            ]);
        }

        $token = Str::random(64);
        Cache::put(self::CACHE_PREFIX.$token, ['member_id' => $member->id], now()->addMinutes(self::TOKEN_LIFETIME_MINUTES));

        return response()->json([
            'token_verifikasi' => $token,
            'nama' => $member->nama_lengkap,
            'berlaku_menit' => self::TOKEN_LIFETIME_MINUTES,
            'pesan' => 'Identitas Kepala Keluarga berhasil diverifikasi.',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token_verifikasi' => ['required', 'string', 'size:64'],
            'username' => ['required', 'string', 'min:4', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $verification = Cache::get(self::CACHE_PREFIX.$data['token_verifikasi']);
        if (! is_array($verification) || empty($verification['member_id'])) {
            throw ValidationException::withMessages([
                'identity' => 'Verifikasi identitas telah berakhir. Silakan ulangi verifikasi NIK dan Nomor KK.',
            ]);
        }

        try {
            $user = DB::transaction(function () use ($verification, $data): ?User {
                $member = AnggotaKeluarga::query()->lockForUpdate()->find($verification['member_id']);
                if (! $member || ! $member->isKepalaKeluarga() || User::query()->where('anggota_keluarga_id', $member->id)->exists()) {
                    return null;
                }

                return User::create([
                    'name' => $member->nama_lengkap,
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'no_hp' => ! empty($data['no_hp']) ? $data['no_hp'] : $member->no_hp,
                    'role' => 'warga',
                    'anggota_keluarga_id' => $member->id,
                    'password' => Hash::make($data['password']),
                ]);
            });
        } catch (QueryException $exception) {
            if (! User::query()->where('anggota_keluarga_id', $verification['member_id'])->exists()) {
                throw $exception;
            }
            $user = null;
        }

        Cache::forget(self::CACHE_PREFIX.$data['token_verifikasi']);

        if (! $user) {
            throw ValidationException::withMessages([
                'identity' => 'Data Kepala Keluarga tersebut baru saja dipakai untuk akun lain. Silakan masuk atau hubungi pengurus RT.',
            ]);
        }

        Notification::send(
            User::query()->whereIn('role', ['admin', 'ketua'])->get(),
            new SystemNotification(
                category: 'resident',
                title: 'Pendaftaran warga baru',
                message: $user->name.' berhasil mendaftar mandiri sebagai Warga.',
                routeName: 'akun.index',
                tone: 'emerald',
                context: ['user_id' => $user->id],
            )
        );

        return response()->json([
            'pesan' => 'Akun berhasil dibuat. Silakan masuk menggunakan username dan password Anda.',
            'username' => $user->username,
        ], 201);
    }
}
