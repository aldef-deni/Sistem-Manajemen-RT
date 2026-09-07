<?php

namespace Tests\Feature;

use App\Models\Pengaduan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Menjaga tiga celah yang ditemukan pada audit 5 September 2026 tetap tertutup.
 */
class HakAksesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function sebagai(string $peran): User
    {
        return User::where('role', $peran)->firstOrFail();
    }

    /** Warga tidak boleh menyentuh keuangan, pengaturan, maupun kelola akun. */
    public function test_warga_ditolak_dari_halaman_pengurus(): void
    {
        $warga = $this->sebagai('warga');

        $terlarang = [
            '/kas-rt',
            '/kas-rt/pemasukan',
            '/kas-rt/pengeluaran',
            '/tabungan',
            '/pinjaman',
            '/iuran-warga',
            '/data-warga',
            '/pengaturan',
            '/pengaturan/kelola-pengurus',
            '/bantuan-sosial/tambah-penerima',
            '/akun',
        ];

        foreach ($terlarang as $uri) {
            $this->actingAs($warga)->get($uri)->assertForbidden();
        }
    }

    /** Pengurus mengelola operasional, tapi bukan pengaturan dan akun. */
    public function test_pengurus_ditolak_dari_pengaturan_dan_akun(): void
    {
        $pengurus = $this->sebagai('pengurus');

        $this->actingAs($pengurus)->get('/kas-rt')->assertOk();
        $this->actingAs($pengurus)->get('/pengaturan')->assertForbidden();
        $this->actingAs($pengurus)->get('/akun')->assertForbidden();
    }

    /** Ketua RT mengelola operasional, pengaturan, akun, dan password. */
    public function test_ketua_dapat_mengelola_rt_dan_akun(): void
    {
        $ketua = $this->sebagai('ketua');

        $this->actingAs($ketua)->get('/kas-rt')->assertOk();
        $this->actingAs($ketua)->get('/pengaturan')->assertOk();
        $this->actingAs($ketua)->get('/pengaturan/tata-tertib')->assertOk();
        $this->actingAs($ketua)->get('/pengaturan/kelola-pengurus')->assertOk();
        $this->actingAs($ketua)->get('/akun')->assertOk();
        $this->actingAs($ketua)->get('/akun/create')
            ->assertOk()
            ->assertSee('Perubahan role dilakukan oleh Administrator');

        $this->actingAs($ketua)->get('/dashboard')
            ->assertOk()
            ->assertSee('Ketua RT')
            ->assertSee('Kelola Akun');
    }

    /** Ketua RT dapat membuat akun Warga dan mengganti password tanpa mengubah role. */
    public function test_ketua_mengelola_akun_tanpa_hak_mengubah_role(): void
    {
        $ketua = $this->sebagai('ketua');

        $this->actingAs($ketua)->post(route('akun.store'), [
            'name' => 'Warga Baru',
            'username' => 'warga-baru-ketua',
            'email' => 'warga.baru.ketua@sistemrt.test',
            'password' => 'rahasia-baru',
            'password_confirmation' => 'rahasia-baru',
        ])->assertRedirect(route('akun.index'));

        $akun = User::where('username', 'warga-baru-ketua')->firstOrFail();
        $this->assertSame('warga', $akun->role);

        $this->actingAs($ketua)->put(route('akun.update', $akun), [
            'name' => $akun->name,
            'username' => $akun->username,
            'email' => $akun->email,
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
        ])->assertRedirect(route('akun.index'));

        $this->assertTrue(Hash::check('password-baru', $akun->fresh()->password));
        $this->assertSame('warga', $akun->fresh()->role);

        $this->actingAs($ketua)->put(route('akun.update', $akun), [
            'name' => $akun->name,
            'username' => $akun->username,
            'email' => $akun->email,
            'role' => 'pengurus',
        ])->assertSessionHasErrors('role');

        $this->assertSame('warga', $akun->fresh()->role);
    }

    /** Administrator memiliki akses teknis akun serta seluruh tata kelola RT. */
    public function test_administrator_dapat_mengelola_akun_dan_pengaturan_rt(): void
    {
        $admin = $this->sebagai('admin');

        $this->actingAs($admin)->get('/akun')->assertOk();
        $this->actingAs($admin)->get('/akun/create')->assertOk();
        $this->actingAs($admin)->get('/pengaturan')->assertOk();
        $this->actingAs($admin)->get('/dashboard')->assertSee('Kelola Akun');
    }

    /** Ketua RT tidak boleh mengganti password atau menurunkan peran Administrator. */
    public function test_ketua_tidak_dapat_mengambil_alih_akun_administrator(): void
    {
        $ketua = $this->sebagai('ketua');
        $admin = $this->sebagai('admin');

        $this->actingAs($ketua)->put(route('akun.update', $admin), [
            'name'                  => 'Administrator',
            'username'              => $admin->username,
            'email'                 => $admin->email,
            'role'                  => 'warga',
            'password'              => 'sandi-rampasan',
            'password_confirmation' => 'sandi-rampasan',
        ])->assertForbidden();

        $admin->refresh();

        $this->assertSame('admin', $admin->role, 'Peran Administrator ikut berubah.');
        $this->assertFalse(
            auth()->validate(['email' => $admin->email, 'password' => 'sandi-rampasan']),
            'Password Administrator berhasil diganti oleh Ketua RT.'
        );
    }

    /** Lampiran berekstensi .php tidak boleh pernah mendarat di dalam public/. */
    public function test_lampiran_pengaduan_menolak_berkas_php(): void
    {
        $warga = $this->sebagai('warga');

        $response = $this->actingAs($warga)->post(route('pengaduan.store'), [
            'judul'         => 'Uji lampiran berbahaya',
            'kategori'      => 'Lainnya',
            'isi_pengaduan' => 'Berkas ini seharusnya ditolak.',
            'privasi'       => 'publik',
            'lampiran'      => UploadedFile::fake()->createWithContent('x.php', '<?php echo "halo";'),
        ]);

        $response->assertSessionHasErrors('lampiran');
        $this->assertSame(0, Pengaduan::where('judul', 'Uji lampiran berbahaya')->count());
        $this->assertSame([], glob(public_path('uploads/pengaduan/*.php')) ?: []);
    }

    /** Lampiran gambar yang wajar tetap harus diterima. */
    public function test_lampiran_pengaduan_menerima_gambar(): void
    {
        $warga = $this->sebagai('warga');

        $this->actingAs($warga)->post(route('pengaduan.store'), [
            'judul'         => 'Lampu jalan mati',
            'kategori'      => 'Fasilitas Umum',
            'isi_pengaduan' => 'Lampu depan gang 3 sudah dua minggu mati.',
            'privasi'       => 'publik',
            'lampiran'      => UploadedFile::fake()->image('bukti.jpg'),
        ])->assertRedirect(route('pengaduan.index'));

        $pengaduan = Pengaduan::where('judul', 'Lampu jalan mati')->firstOrFail();

        $this->assertStringStartsWith('uploads/pengaduan/', $pengaduan->lampiran);
        $this->assertStringEndsWith('.jpg', $pengaduan->lampiran);

        @unlink(public_path($pengaduan->lampiran));
    }
}
