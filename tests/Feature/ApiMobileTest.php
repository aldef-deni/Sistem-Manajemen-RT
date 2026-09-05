<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\Pengaduan;
use App\Models\Polling;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * API yang dipakai aplikasi mobile. Seluruh balasan harus berbentuk JSON,
 * memakai token Sanctum, dan tidak pernah membocorkan data milik warga lain.
 */
class ApiMobileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function masuk(string $username, string $password = 'password'): string
    {
        // Password seeder acak, jadi disetel ulang untuk keperluan uji.
        $user = User::where('username', $username)->firstOrFail();
        $user->update(['password' => bcrypt($password)]);

        $res = $this->postJson('/api/login', [
            'username'    => $username,
            'password'    => $password,
            'device_name' => 'uji-android',
        ])->assertOk();

        return $res->json('token');
    }

    private function sebagai(string $username): array
    {
        return ['Authorization' => 'Bearer ' . $this->masuk($username)];
    }

    /**
     * Guard menyimpan pengguna hasil request sebelumnya di dalam container,
     * jadi harus dilupakan bila satu uji berpindah akun atau mencabut token.
     */
    private function lupakanGuard(): void
    {
        $this->app['auth']->forgetGuards();
    }

    public function test_login_mengembalikan_token_dan_profil(): void
    {
        $res = $this->postJson('/api/login', [
            'username'    => 'admin',
            'password'    => $this->siapkanPassword('admin'),
            'device_name' => 'uji-android',
        ])->assertOk();

        $this->assertNotEmpty($res->json('token'));
        $res->assertJsonPath('user.username', 'admin')
            ->assertJsonPath('user.peran', 'admin')
            ->assertJsonPath('user.pengurus', true);
    }

    private function siapkanPassword(string $username, string $password = 'password'): string
    {
        User::where('username', $username)->firstOrFail()->update(['password' => bcrypt($password)]);

        return $password;
    }

    public function test_login_salah_ditolak(): void
    {
        $this->postJson('/api/login', [
            'username' => 'admin',
            'password' => 'jelas-salah',
        ])->assertStatus(422)->assertJsonValidationErrors('username');
    }

    public function test_endpoint_tanpa_token_ditolak(): void
    {
        foreach (['/api/beranda', '/api/pengumuman', '/api/saya', '/api/polling'] as $uri) {
            $this->getJson($uri)->assertUnauthorized();
        }
    }

    public function test_beranda_pengurus_memuat_angka_kas(): void
    {
        $res = $this->getJson('/api/beranda', $this->sebagai('admin'))->assertOk();

        $kunci = collect($res->json('ringkasan'))->pluck('kunci');

        $this->assertTrue($kunci->contains('saldo_kas'));
        $this->assertTrue($kunci->contains('pengaduan_baru'));
        $this->assertIsString($res->json('sapaan'));
    }

    public function test_beranda_warga_tidak_memuat_angka_kas(): void
    {
        $res = $this->getJson('/api/beranda', $this->sebagai('warga'))->assertOk();

        $kunci = collect($res->json('ringkasan'))->pluck('kunci');

        $this->assertFalse($kunci->contains('saldo_kas'), 'Warga tidak boleh melihat saldo kas RT.');
        $this->assertTrue($kunci->contains('iuran_saya'));
    }

    public function test_daftar_dan_detail_pengumuman(): void
    {
        $token = $this->sebagai('warga');

        $res = $this->getJson('/api/pengumuman', $token)->assertOk()
            ->assertJsonStructure(['data', 'halaman' => ['saat_ini', 'terakhir', 'total']]);

        $id = $res->json('data.0.id');
        $this->assertNotNull($id, 'Seeder harus menyediakan pengumuman terbit.');

        $this->getJson('/api/pengumuman/' . $id, $token)->assertOk()
            ->assertJsonStructure(['id', 'judul', 'isi_teks', 'tanggal']);
    }

    public function test_jadwal_struktur_dan_umkm_dapat_dibaca_warga(): void
    {
        $token = $this->sebagai('warga');

        $this->getJson('/api/jadwal', $token)->assertOk()->assertJsonStructure(['data', 'halaman']);
        $this->getJson('/api/struktur-rt', $token)->assertOk()->assertJsonStructure(['pengurus']);
        $this->getJson('/api/umkm', $token)->assertOk()->assertJsonStructure(['data', 'halaman']);
        $this->getJson('/api/kegiatan', $token)->assertOk()->assertJsonStructure(['data', 'halaman']);
    }

    public function test_iuran_saya_menjelaskan_bila_akun_belum_ditautkan(): void
    {
        $res = $this->getJson('/api/iuran-saya', $this->sebagai('warga'))->assertOk();

        $this->assertFalse($res->json('tertaut'));
        $this->assertStringContainsString('belum ditautkan', $res->json('pesan'));
    }

    public function test_iuran_saya_menampilkan_tagihan_setelah_akun_ditautkan(): void
    {
        $warga = User::where('username', 'warga')->firstOrFail();
        $warga->update(['anggota_keluarga_id' => AnggotaKeluarga::value('id')]);

        $res = $this->getJson('/api/iuran-saya', $this->sebagai('warga'))->assertOk();

        $this->assertTrue($res->json('tertaut'));
        $this->assertIsArray($res->json('data'));
        $this->assertArrayHasKey('belum_lunas', $res->json('ringkasan'));
    }

    public function test_kirim_pengaduan_beserta_lampiran(): void
    {
        $res = $this->postJson('/api/pengaduan', [
            'judul'         => 'Saluran air tersumbat',
            'kategori'      => 'Kebersihan',
            'isi_pengaduan' => 'Selokan depan gang 2 meluap tiap hujan.',
            'privasi'       => 'publik',
            'lampiran'      => UploadedFile::fake()->image('foto.jpg'),
        ], $this->sebagai('warga'))->assertCreated();

        $this->assertStringStartsWith('ADU-', $res->json('kode_tiket'));

        $pengaduan = Pengaduan::where('judul', 'Saluran air tersumbat')->firstOrFail();
        $this->assertStringStartsWith('uploads/pengaduan/', $pengaduan->lampiran);
        @unlink(public_path($pengaduan->lampiran));
    }

    public function test_lampiran_php_ditolak_juga_lewat_api(): void
    {
        $this->postJson('/api/pengaduan', [
            'judul'         => 'Uji berkas berbahaya',
            'kategori'      => 'Lainnya',
            'isi_pengaduan' => 'Harus ditolak.',
            'privasi'       => 'publik',
            'lampiran'      => UploadedFile::fake()->createWithContent('x.php', '<?php echo 1;'),
        ], $this->sebagai('warga'))->assertStatus(422)->assertJsonValidationErrors('lampiran');

        $this->assertSame(0, Pengaduan::where('judul', 'Uji berkas berbahaya')->count());
    }

    public function test_warga_tidak_bisa_membuka_pengaduan_privat_orang_lain(): void
    {
        $lain = User::where('username', 'ketua')->firstOrFail();

        $privat = Pengaduan::create([
            'user_id'       => $lain->id,
            'kode_tiket'    => 'ADU-UJI-0001',
            'judul'         => 'Rahasia tetangga',
            'kategori'      => 'Lainnya',
            'isi_pengaduan' => 'Hanya untuk pengurus.',
            'privasi'       => 'privat',
        ]);

        $tokenWarga = $this->sebagai('warga');
        $tokenAdmin = $this->sebagai('admin');

        $this->lupakanGuard();
        $this->getJson('/api/pengaduan/' . $privat->id, $tokenWarga)->assertForbidden();

        $this->lupakanGuard();
        $this->getJson('/api/pengaduan/' . $privat->id, $tokenAdmin)->assertOk();
    }

    public function test_polling_dan_pemungutan_suara(): void
    {
        $polling = Polling::create([
            'user_id'         => User::where('username', 'admin')->value('id'),
            'judul'           => 'Jam ronda malam',
            'deskripsi'       => 'Pilih jam mulai ronda.',
            'opsi'            => ['20.00', '21.00'],
            'status'          => 'aktif',
            'tanggal_mulai'   => now()->toDateString(),
            'izinkan_ganti'   => true,
            'tampilkan_hasil' => true,
            'jumlah_suara'    => 0,
        ]);

        $token = $this->sebagai('warga');

        $this->postJson("/api/polling/{$polling->id}/pilih", ['pilihan' => '20.00'], $token)->assertOk();

        $res = $this->getJson('/api/polling', $token)->assertOk();
        $baris = collect($res->json('data'))->firstWhere('id', $polling->id);

        $this->assertSame('20.00', $baris['pilihan_saya']);
        $this->assertSame(1, $baris['jumlah_suara']);

        // Mengganti pilihan tidak boleh menambah jumlah suara.
        $this->postJson("/api/polling/{$polling->id}/pilih", ['pilihan' => '21.00'], $token)->assertOk();
        $this->assertSame(1, $polling->fresh()->jumlah_suara);

        // Opsi di luar daftar ditolak.
        $this->postJson("/api/polling/{$polling->id}/pilih", ['pilihan' => '23.00'], $token)
            ->assertStatus(422);
    }

    public function test_perbarui_profil_dan_ganti_password(): void
    {
        $token = $this->sebagai('warga');

        $this->putJson('/api/saya', [
            'name'  => 'Warga Baru',
            'email' => 'warga@sistemrt.com',
            'no_hp' => '081200000123',
        ], $token)->assertOk()->assertJsonPath('user.nama', 'Warga Baru');

        $this->putJson('/api/saya/password', [
            'password_lama'         => 'salah',
            'password'              => 'rahasiabaru',
            'password_confirmation' => 'rahasiabaru',
        ], $token)->assertStatus(422)->assertJsonValidationErrors('password_lama');

        $this->putJson('/api/saya/password', [
            'password_lama'         => 'password',
            'password'              => 'rahasiabaru',
            'password_confirmation' => 'rahasiabaru',
        ], $token)->assertOk();
    }

    public function test_logout_mencabut_token(): void
    {
        $token = $this->sebagai('warga');

        $this->postJson('/api/logout', [], $token)->assertOk();

        $this->lupakanGuard();
        $this->getJson('/api/saya', $token)->assertUnauthorized();
    }
}
