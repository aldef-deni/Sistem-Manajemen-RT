<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi rute berdasarkan peran pengguna.
 *
 * Dipakai sebagai `role:admin,ketua` pada grup rute. Tanpa middleware ini
 * seluruh area aplikasi hanya terlindung oleh `auth`, sehingga warga biasa
 * bisa membuka pengaturan, kas RT, dan data keuangan seluruh warga.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
