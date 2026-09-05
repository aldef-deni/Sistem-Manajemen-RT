<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\SafeUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfilApiController extends Controller
{
    public function perbarui(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user->update($data);

        return response()->json([
            'pesan' => 'Profil diperbarui.',
            'user'  => AuthController::profil($user->fresh()),
        ]);
    }

    public function gantiPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'password_lama' => 'required|string',
            'password'      => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($data['password_lama'], $user->password)) {
            throw ValidationException::withMessages([
                'password_lama' => ['Password lama salah.'],
            ]);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        // Sesi perangkat lain dicabut supaya password lama tidak dipakai lagi.
        $sekarang = $request->user()->currentAccessToken();
        $user->tokens()->where('id', '!=', $sekarang->id)->delete();

        return response()->json(['pesan' => 'Password berhasil diganti.']);
    }

    public function gantiFoto(Request $request): JsonResponse
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();
        $lama = $user->foto;

        $path = SafeUpload::store($request->file('foto'), 'profil', 'foto_' . $user->id, SafeUpload::IMAGE);
        SafeUpload::delete($lama);
        $user->update(['foto' => $path]);

        return response()->json([
            'pesan'    => 'Foto profil diperbarui.',
            'foto_url' => url($path),
        ]);
    }
}
