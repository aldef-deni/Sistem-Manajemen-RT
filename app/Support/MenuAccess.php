<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Menentukan apakah pengguna saat ini boleh membuka sebuah route.
 *
 * Jawabannya dibaca langsung dari middleware `role:` pada route yang
 * bersangkutan, bukan dari daftar terpisah — jadi menu sidebar selalu
 * mengikuti aturan akses yang sesungguhnya dan tidak bisa melenceng.
 */
class MenuAccess
{
    /** @var array<string,bool> */
    private static array $cache = [];

    public static function boleh(?string $routeName): bool
    {
        if (! $routeName) {
            return true;
        }

        if (array_key_exists($routeName, self::$cache)) {
            return self::$cache[$routeName];
        }

        $route = Route::getRoutes()->getByName($routeName);

        if (! $route) {
            return self::$cache[$routeName] = false;
        }

        $peran = auth()->user()?->role;

        foreach ($route->gatherMiddleware() as $middleware) {
            if (is_string($middleware) && str_starts_with($middleware, 'role:')) {
                $diizinkan = explode(',', substr($middleware, 5));

                return self::$cache[$routeName] = in_array($peran, $diizinkan, true);
            }
        }

        return self::$cache[$routeName] = true;
    }
}
