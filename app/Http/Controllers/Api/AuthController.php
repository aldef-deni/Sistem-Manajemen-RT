<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Masuk dan keluar untuk aplikasi mobile.
 *
 * Memakai token Sanctum, bukan sesi — aplikasi tidak menyimpan cookie.
 */
class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username'    => 'required|string',
            'password'    => 'required|string',
            'device_name' => 'nullable|string|max:100',
        ]);

        // Sama seperti login web: boleh username, email, atau nama lengkap.
        $user = User::where('username', $data['username'])
            ->orWhere('email', $data['username'])
            ->orWhere('name', $data['username'])
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        // Satu perangkat satu token: masuk ulang mencabut token lama perangkat itu.
        $perangkat = $data['device_name'] ?? 'perangkat';
        $user->tokens()->where('name', $perangkat)->delete();

        $token = $user->createToken($perangkat)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $this->profil($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['pesan' => 'Berhasil keluar.']);
    }

    public function saya(Request $request): JsonResponse
    {
        return response()->json(['user' => $this->profil($request->user())]);
    }

    /**
     * Bentuk profil yang dipakai seluruh endpoint, supaya aplikasi tidak
     * menebak-nebak bidang mana yang tersedia.
     */
    public static function profil(User $user): array
    {
        $user->loadMissing('anggotaKeluarga.kartuKeluarga');
        $warga = $user->anggotaKeluarga;

        return [
            'id'       => $user->id,
            'nama'     => $user->name,
            'username' => $user->username,
            'email'    => $user->email,
            'no_hp'    => $user->no_hp,
            'peran'    => $user->role,
            'peran_label' => match ($user->role) {
                'admin'    => 'Administrator',
                'ketua'    => 'Ketua RT',
                'pengurus' => 'Pengurus RT',
                default    => 'Warga',
            },
            'foto_url' => $user->foto ? url($user->foto) : null,
            'pengurus' => in_array($user->role, ['admin', 'ketua', 'pengurus'], true),
            'warga'    => $warga ? [
                'id'      => $warga->id,
                'nama'    => $warga->nama_lengkap,
                'nik'     => $warga->nik,
                'no_kk'   => optional($warga->kartuKeluarga)->no_kk,
                'alamat'  => optional($warga->kartuKeluarga)->alamat,
            ] : null,
        ];
    }
}
