<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    private function authorizeManageAkun(): void
    {
        if (! auth()->user()?->canManageAkun()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeManageAkun();
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $totalAkun    = User::count();
        $totalKetua   = User::where('role', 'ketua')->count();
        $totalPengurus = User::where('role', 'pengurus')->count();

        return view('akun.index', compact('users', 'totalAkun', 'totalKetua', 'totalPengurus'));
    }

    public function create()
    {
        $this->authorizeManageAkun();
        return view('akun.create');
    }

    public function store(Request $request)
    {
        $this->authorizeManageAkun();
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|max:100|unique:users,email',
            'no_hp'    => 'nullable|string|max:20',
            'role'     => 'required|in:ketua,pengurus,warga',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'no_hp'    => $validated['no_hp'] ?? null,
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dibuat!');
    }

    public function edit(User $akun)
    {
        $this->authorizeManageAkun();
        return view('akun.edit', compact('akun'));
    }

    public function update(Request $request, User $akun)
    {
        $this->authorizeManageAkun();

        // Akun Administrator hanya boleh diubah oleh dirinya sendiri. Tanpa
        // penjagaan ini seorang Ketua RT bisa mengganti password Administrator
        // lalu masuk sebagai Administrator.
        if ($akun->role === 'admin' && $akun->id !== auth()->id()) {
            return back()->with('error', 'Akun Administrator hanya dapat diubah oleh pemiliknya sendiri.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $akun->id,
            'email'    => 'required|email|max:100|unique:users,email,' . $akun->id,
            'no_hp'    => 'nullable|string|max:20',
            'role'     => 'required|in:ketua,pengurus,warga',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $akun->update([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'no_hp'    => $validated['no_hp'] ?? null,
            // Peran admin tidak ikut diubah — daftar pilihan peran memang
            // tidak memuat 'admin', jadi menyimpannya akan menurunkan peran.
            'role'     => $akun->role === 'admin' ? 'admin' : $validated['role'],
        ]);

        if (! empty($validated['password'])) {
            $akun->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui!');
    }

    public function destroy(User $akun)
    {
        $this->authorizeManageAkun();
        if ($akun->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        if ($akun->role === 'admin') {
            return back()->with('error', 'Akun Administrator tidak dapat dihapus.');
        }
        $akun->delete();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus!');
    }
}