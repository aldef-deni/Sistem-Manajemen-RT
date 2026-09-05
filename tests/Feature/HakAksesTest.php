<?php

namespace Tests\Feature;

use App\Models\Pengaduan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        ]);

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
