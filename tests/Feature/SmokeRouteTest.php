<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route as RouteInstance;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Membuka setiap halaman GET sebagai tiga peran dan menuntut tidak ada yang
 * balas 5xx.
 *
 * Uji inilah yang akan menangkap kelas kesalahan yang lolos ke produksi pada
 * audit 5 September 2026: view yang belum dibuat, variabel salah ketik,
 * argumen controller yang tidak terkirim, dan fungsi PHP yang tidak tersedia.
 */
class SmokeRouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public static function peranProvider(): array
    {
        return [
            'administrator' => ['admin'],
            'ketua RT'      => ['ketua'],
            'pengurus RT'   => ['pengurus'],
            'warga'         => ['warga'],
        ];
    }

    /**
     * @dataProvider peranProvider
     */
    public function test_tidak_ada_halaman_yang_error_untuk_peran(string $peran): void
    {
        $user = User::where('role', $peran)->firstOrFail();
        $gagal = [];

        foreach ($this->halamanGet() as $nama => $uri) {
            $status = $this->actingAs($user)->get($uri)->getStatusCode();

            if ($status >= 500) {
                $gagal[] = sprintf('%s (%s) -> %d', $uri, $nama, $status);
            }
        }

        $this->assertSame([], $gagal, "Halaman berikut balas 5xx sebagai {$peran}:\n" . implode("\n", $gagal));
    }

    public function test_halaman_tamu_dapat_dibuka(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/')->assertRedirect(route('login'));
    }

    /**
     * Seluruh route GET, dengan parameter diisi id 1 — seeder selalu
     * menyediakan setidaknya satu baris untuk tiap tabel utama.
     *
     * @return array<string,string>
     */
    private function halamanGet(): array
    {
        $halaman = [];

        /** @var RouteInstance $route */
        foreach (Route::getRoutes() as $route) {
            if (! in_array('GET', $route->methods(), true)) {
                continue;
            }

            $uri = $route->uri();

            if (str_starts_with($uri, '_') || in_array($uri, ['up', 'login', '/'], true)) {
                continue;
            }

            $halaman[$route->getName() ?? $uri] = '/' . preg_replace('/\{[^}]+\}/', '1', $uri);
        }

        return $halaman;
    }
}
