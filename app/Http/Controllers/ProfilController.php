<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    /**
     * Menampilkan halaman Profil Saya.
     */
    public function index()
    {
        $user = auth()->user();

        // Cek keterhubungan dengan data warga (berdasarkan nomor HP)
        $warga = null;
        if ($user->no_hp) {
            $suffix = substr(preg_replace('/[^0-9]/', '', $user->no_hp), -9);
            $warga = AnggotaKeluarga::whereNotNull('no_hp')
                ->get()
                ->first(fn ($a) => str_ends_with(preg_replace('/[^0-9]/', '', $a->no_hp), $suffix));
        }

        return view('profil.index', compact('user', 'warga'));
    }

    /**
     * Simpan perubahan informasi profil.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Upload / ganti foto profil.
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();
        $file = $request->file('foto');
        $filename = 'foto_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profil'), $filename);

        // Hapus foto lama jika ada
        if ($user->foto && file_exists(public_path($user->foto))) {
            @unlink(public_path($user->foto));
        }

        $user->update(['foto' => 'uploads/profil/' . $filename]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Hapus foto profil.
     */
    public function hapusFoto()
    {
        $user = auth()->user();

        if ($user->foto && file_exists(public_path($user->foto))) {
            @unlink(public_path($user->foto));
        }
        $user->update(['foto' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }

    /**
     * Ganti password akun.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal 6 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}