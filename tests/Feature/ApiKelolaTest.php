<?php

namespace Tests\Feature;

use App\Models\IuranWarga;
use App\Models\Pengaduan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Bagian aplikasi khusus pengurus. Yang dijaga di sini bukan hanya bahwa
 * endpointnya bekerja, tapi bahwa warga benar-benar tidak bisa menyentuhnya.
 */
class ApiKelolaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function sebagai(string $username): array
    {
        $this->app['auth']->forgetGuards();

        $user = User::where('username', $username)->firstOrFail();
        $user->update(['password' => Hash::make('password')]);

        $token = $this->postJson('/api/login', [
            'username'    => $username,
            'password'    => 'password',
            'device_name' => 'uji',
        ])->assertOk()->json('token');

        $this->app['auth']->forgetGuards();

        return ['Authorization' => 'Bearer ' . $token];
    }

    public static function endpointKelola(): array
    {
        return [
            'ringkasan' => ['GET', '/api/kelola/ringkasan'],
            'warga'     => ['GET', '/api/kelola/warga'],
            'kas'       => ['GET', '/api/kelola/kas'],
            'iuran'     => ['GET', '/api/kelola/iuran'],
        ];
    }

    /**
     * @dataProvider endpointKelola
     */
    public function test_warga_ditolak_dari_seluruh_endpoint_kelola(string $metode, string $uri): void
    {
        $this->json($metode, $uri, [], $this->sebagai('warga'))->assertForbidden();
    }

    /**
     * @dataProvider endpointKelola
     */
    public function test_pengurus_diterima_di_endpoint_kelola(string $metode, string $uri): void
    {
        $this->json($metode, $uri, [], $this->sebagai('pengurus'))->assertOk();
    }

    public function test_ringkasan_memuat_kas_warga_iuran_dan_pengaduan(): void
    {
        $this->getJson('/api/kelola/ringkasan', $this->sebagai('pengurus'))
            ->assertOk()
            ->assertJsonStructure([
                'kas'       => ['saldo', 'masuk', 'keluar', 'bulan'],
                'warga'     => ['jiwa', 'kk'],
                'iuran'     => ['belum_lunas', 'total_tunggak'],
                'pengaduan' => ['baru', 'diproses'],
            ]);
    }

    public function test_daftar_warga_dapat_dicari(): void
    {
        $token = $this->sebagai('pengurus');

        $semua = $this->getJson('/api/kelola/warga', $token)->assertOk();
        $nama  = $semua->json('data.0.nama');
        $this->assertNotNull($nama, 'Seeder harus menyediakan data warga.');

        $hasil = $this->getJson('/api/kelola/warga?cari=' . urlencode(substr($nama, 0, 4)), $token)->assertOk();
        $this->assertGreaterThan(0, count($hasil->json('data')));
    }

    public function test_kas_menampilkan_saldo_dan_transaksi(): void
    {
        $this->getJson('/api/kelola/kas', $this->sebagai('pengurus'))
            ->assertOk()
            ->assertJsonStructure(['saldo_total', 'rekening', 'transaksi']);
    }

    public function test_menandai_iuran_lunas(): void
    {
        $tagihan = IuranWarga::where('status', '!=', 'lunas')->first();

        if (! $tagihan) {
            $tagihan = IuranWarga::create([
                'anggota_keluarga_id' => \App\Models\AnggotaKeluarga::value('id'),
                'jenis_iuran_id'      => \App\Models\JenisIuran::value('id'),
                'bulan'               => 5,
                'tahun'               => 2026,
                'nominal'             => 50000,
                'status'              => 'belum',
            ]);
        }

        $token = $this->sebagai('pengurus');

        $this->patchJson("/api/kelola/iuran/{$tagihan->id}/lunas", [], $token)->assertOk();
        $this->assertSame('lunas', $tagihan->fresh()->status);

        // Menandai dua kali harus ditolak, bukan diam-diam menimpa.
        $this->patchJson("/api/kelola/iuran/{$tagihan->id}/lunas", [], $token)->assertStatus(422);
    }

    public function test_pengurus_menindaklanjuti_pengaduan(): void
    {
        $pengaduan = Pengaduan::create([
            'user_id'       => User::where('username', 'warga')->value('id'),
            'kode_tiket'    => 'ADU-UJI-9001',
            'judul'         => 'Sampah menumpuk',
            'kategori'      => 'Kebersihan',
            'isi_pengaduan' => 'Sudah tiga hari tidak diangkut.',
            'privasi'       => 'publik',
            'status'        => 'baru',
        ]);

        $token = $this->sebagai('pengurus');

        $this->patchJson("/api/kelola/pengaduan/{$pengaduan->id}/status", ['status' => 'diproses'], $token)->assertOk();
        $this->assertSame('diproses', $pengaduan->fresh()->status);

        $this->postJson("/api/kelola/pengaduan/{$pengaduan->id}/balas", ['pesan' => 'Petugas kebersihan sudah dijadwalkan besok.'], $token)
            ->assertCreated();

        $this->assertSame(1, $pengaduan->replies()->count());
    }

    public function test_warga_tidak_bisa_menindaklanjuti_pengaduan(): void
    {
        $pengaduan = Pengaduan::create([
            'user_id'       => User::where('username', 'warga')->value('id'),
            'kode_tiket'    => 'ADU-UJI-9002',
            'judul'         => 'Uji akses',
            'kategori'      => 'Lainnya',
            'isi_pengaduan' => 'Warga tidak boleh mengubah statusnya sendiri.',
            'privasi'       => 'publik',
            'status'        => 'baru',
        ]);

        $token = $this->sebagai('warga');

        $this->patchJson("/api/kelola/pengaduan/{$pengaduan->id}/status", ['status' => 'selesai'], $token)->assertForbidden();
        $this->assertSame('baru', $pengaduan->fresh()->status);
    }

    /* ------------------------------------------------------- akun: admin & ketua */

    public function test_pengurus_ditolak_dari_kelola_akun(): void
    {
        $this->getJson('/api/kelola/akun', $this->sebagai('pengurus'))->assertForbidden();
    }

    public function test_ketua_dapat_melihat_dan_mengubah_peran(): void
    {
        $target = User::create([
            'name'     => 'Sekretaris Uji',
            'username' => 'sekretaris-uji',
            'email'    => 'sekretaris.uji@sistemrt.test',
            'password' => Hash::make('rahasia123'),
            'role'     => 'warga',
        ]);

        $token = $this->sebagai('ketua');

        $this->getJson('/api/kelola/akun', $token)->assertOk()->assertJsonStructure(['data', 'halaman']);

        $this->patchJson("/api/kelola/akun/{$target->id}/peran", ['peran' => 'pengurus'], $token)->assertOk();
        $this->assertSame('pengurus', $target->fresh()->role);
    }

    public function test_peran_administrator_tidak_dapat_diubah_dari_aplikasi(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $this->patchJson("/api/kelola/akun/{$admin->id}/peran", ['peran' => 'warga'], $this->sebagai('ketua'))
            ->assertStatus(422);

        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_ketua_tidak_dapat_mengatur_ulang_password_administrator(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $sebelum = $admin->password;

        $this->patchJson("/api/kelola/akun/{$admin->id}/reset-password", [], $this->sebagai('ketua'))
            ->assertStatus(422);

        $this->assertSame($sebelum, $admin->fresh()->password);
    }

    public function test_reset_password_akun_biasa_mencabut_tokennya(): void
    {
        $target = User::where('username', 'pengurus')->firstOrFail();
        $tokenTarget = $this->sebagai('pengurus');

        $hasil = $this->patchJson("/api/kelola/akun/{$target->id}/reset-password", [], $this->sebagai('ketua'))
            ->assertOk();

        $this->assertNotEmpty($hasil->json('password'));

        // Token lama harus mati supaya password lama benar-benar tidak berlaku.
        $this->app['auth']->forgetGuards();
        $this->getJson('/api/saya', $tokenTarget)->assertUnauthorized();
    }
}
