<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\Arisan;
use App\Models\ArisanIuran;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Pencatatan iuran arisan: satu baris untuk satu peserta pada satu periode.
 */
class ArisanIuranTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Arisan $arisan;
    /** @var \Illuminate\Support\Collection<int,AnggotaKeluarga> */
    private $peserta;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = User::where('role', 'admin')->firstOrFail();

        $this->arisan = Arisan::create([
            'nama'                          => 'ARISAN UJI IURAN',
            'nominal_iuran'                 => 100000,
            'periode'                       => 'bulanan',
            'tanggal_mulai'                 => now()->subMonths(2)->startOfMonth()->toDateString(),
            'mode_undian'                   => 'otomatis',
            'jumlah_pemenang_per_pertemuan' => 1,
            'status'                        => 'aktif',
        ]);

        $this->peserta = AnggotaKeluarga::orderBy('id')->limit(3)->get();
        $this->assertCount(3, $this->peserta, 'Seeder harus menyediakan minimal tiga warga.');

        foreach ($this->peserta as $i => $orang) {
            $this->arisan->peserta()->attach($orang->id, ['urutan' => $i + 1, 'sudah_dapat' => false]);
        }
    }

    public function test_jumlah_periode_mengikuti_jumlah_peserta(): void
    {
        $this->assertSame(3, $this->arisan->jumlahPeriode(), 'Tiga peserta, satu pemenang per pertemuan.');

        $this->arisan->update(['jumlah_pemenang_per_pertemuan' => 2]);
        $this->assertSame(2, $this->arisan->fresh()->jumlahPeriode(), 'Dua pemenang per pertemuan membagi dua jumlah periode.');
    }

    public function test_periode_berjalan_dihitung_dari_tanggal_mulai(): void
    {
        // Mulai dua bulan lalu, arisan bulanan, jadi sekarang periode ke-3.
        $this->assertSame(3, $this->arisan->periodeSaatIni());

        $mingguan = Arisan::create([
            'nama'          => 'ARISAN MINGGUAN',
            'nominal_iuran' => 50000,
            'periode'       => 'mingguan',
            'tanggal_mulai' => now()->subWeeks(2)->toDateString(),
            'mode_undian'   => 'manual',
            'jumlah_pemenang_per_pertemuan' => 1,
            'status'        => 'aktif',
        ]);
        $mingguan->peserta()->attach($this->peserta->pluck('id')->all());

        $this->assertSame(3, $mingguan->periodeSaatIni(), 'Dua minggu berjalan berarti periode ke-3.');
    }

    public function test_catat_iuran_satu_peserta(): void
    {
        $orang = $this->peserta->first();

        $this->actingAs($this->admin)->post(route('arisan.iuran.bayar', $this->arisan), [
            'anggota_keluarga_id' => $orang->id,
            'periode_ke'          => 1,
        ])->assertRedirect();

        $baris = ArisanIuran::where('arisan_id', $this->arisan->id)->firstOrFail();

        $this->assertSame($orang->id, $baris->anggota_keluarga_id);
        $this->assertSame(1, $baris->periode_ke);
        $this->assertSame(100000.0, (float) $baris->nominal, 'Nominal jatuh ke iuran baku arisan.');
        $this->assertSame('tunai', $baris->metode);
        $this->assertSame($this->admin->id, $baris->dicatat_oleh, 'Pencatat harus tersimpan untuk jejak audit.');
        $this->assertTrue($baris->tanggal_bayar->isToday());
    }

    public function test_catat_iuran_rinci_dengan_nominal_dan_metode_sendiri(): void
    {
        $orang = $this->peserta->get(1);

        $this->actingAs($this->admin)->post(route('arisan.iuran.bayar', $this->arisan), [
            'anggota_keluarga_id' => $orang->id,
            'periode_ke'          => 2,
            'nominal'             => 75000,
            'tanggal_bayar'       => now()->subDays(3)->toDateString(),
            'metode'              => 'transfer',
            'keterangan'          => 'dititipkan ke bendahara',
        ])->assertRedirect();

        $baris = ArisanIuran::where('anggota_keluarga_id', $orang->id)->firstOrFail();

        $this->assertSame(75000.0, (float) $baris->nominal);
        $this->assertSame('transfer', $baris->metode);
        $this->assertSame('dititipkan ke bendahara', $baris->keterangan);
        $this->assertSame(now()->subDays(3)->toDateString(), $baris->tanggal_bayar->toDateString());
    }

    public function test_iuran_ganda_pada_periode_yang_sama_ditolak(): void
    {
        $orang = $this->peserta->first();
        $kirim = ['anggota_keluarga_id' => $orang->id, 'periode_ke' => 1];

        $this->actingAs($this->admin)->post(route('arisan.iuran.bayar', $this->arisan), $kirim);
        $this->actingAs($this->admin)->post(route('arisan.iuran.bayar', $this->arisan), $kirim)
            ->assertSessionHas('error');

        $this->assertSame(1, ArisanIuran::where('arisan_id', $this->arisan->id)->count());
    }

    public function test_bukan_peserta_tidak_bisa_dicatat(): void
    {
        $luar = AnggotaKeluarga::whereNotIn('id', $this->peserta->pluck('id'))->firstOrFail();

        $this->actingAs($this->admin)->post(route('arisan.iuran.bayar', $this->arisan), [
            'anggota_keluarga_id' => $luar->id,
            'periode_ke'          => 1,
        ])->assertSessionHas('error');

        $this->assertSame(0, ArisanIuran::count());
    }

    public function test_arisan_selesai_menolak_pencatatan(): void
    {
        $this->arisan->update(['status' => 'selesai']);

        $this->actingAs($this->admin)->post(route('arisan.iuran.bayar', $this->arisan), [
            'anggota_keluarga_id' => $this->peserta->first()->id,
            'periode_ke'          => 1,
        ])->assertSessionHas('error');

        $this->assertSame(0, ArisanIuran::count());
    }

    public function test_pencatatan_massal_hanya_mengisi_yang_belum_bayar(): void
    {
        // Satu peserta sudah membayar lebih dulu dengan nominal berbeda.
        $this->actingAs($this->admin)->post(route('arisan.iuran.bayar', $this->arisan), [
            'anggota_keluarga_id' => $this->peserta->first()->id,
            'periode_ke'          => 1,
            'nominal'             => 60000,
        ]);

        $this->actingAs($this->admin)->post(route('arisan.iuran.massal', $this->arisan), [
            'periode_ke' => 1,
        ])->assertRedirect();

        $baris = ArisanIuran::where('periode_ke', 1)->get();

        $this->assertCount(3, $baris, 'Ketiga peserta harus punya catatan.');
        $this->assertSame(60000.0, (float) $baris->firstWhere('anggota_keluarga_id', $this->peserta->first()->id)->nominal,
            'Catatan yang sudah ada tidak boleh tertimpa.');

        // Dijalankan lagi tidak boleh menggandakan.
        $this->actingAs($this->admin)->post(route('arisan.iuran.massal', $this->arisan), ['periode_ke' => 1])
            ->assertSessionHas('error');

        $this->assertSame(3, ArisanIuran::where('periode_ke', 1)->count());
    }

    public function test_batalkan_catatan_iuran(): void
    {
        $this->actingAs($this->admin)->post(route('arisan.iuran.massal', $this->arisan), ['periode_ke' => 1]);

        $baris = ArisanIuran::firstOrFail();

        $this->actingAs($this->admin)
            ->delete(route('arisan.iuran.hapus', [$this->arisan, $baris]))
            ->assertRedirect();

        $this->assertNull(ArisanIuran::find($baris->id));
        $this->assertSame(2, ArisanIuran::count());
    }

    public function test_catatan_milik_arisan_lain_tidak_bisa_dihapus(): void
    {
        $lain = Arisan::create([
            'nama' => 'ARISAN LAIN', 'nominal_iuran' => 50000, 'periode' => 'bulanan',
            'tanggal_mulai' => now()->toDateString(), 'mode_undian' => 'manual',
            'jumlah_pemenang_per_pertemuan' => 1, 'status' => 'aktif',
        ]);
        $lain->peserta()->attach($this->peserta->first()->id, ['urutan' => 1]);

        $this->actingAs($this->admin)->post(route('arisan.iuran.bayar', $lain), [
            'anggota_keluarga_id' => $this->peserta->first()->id,
            'periode_ke'          => 1,
        ]);

        $baris = ArisanIuran::where('arisan_id', $lain->id)->firstOrFail();

        $this->actingAs($this->admin)
            ->delete(route('arisan.iuran.hapus', [$this->arisan, $baris]))
            ->assertNotFound();

        $this->assertNotNull(ArisanIuran::find($baris->id));
    }

    public function test_total_terkumpul_dan_target(): void
    {
        $this->actingAs($this->admin)->post(route('arisan.iuran.massal', $this->arisan), ['periode_ke' => 1]);
        $this->actingAs($this->admin)->post(route('arisan.iuran.massal', $this->arisan), ['periode_ke' => 2]);

        $this->assertSame(600000.0, $this->arisan->totalTerkumpul(), '3 peserta × 2 periode × Rp 100.000');
        $this->assertSame(900000.0, $this->arisan->targetTerkumpul(), '3 peserta × 3 periode × Rp 100.000');
    }

    public function test_mengeluarkan_peserta_ikut_menghapus_catatan_iurannya(): void
    {
        $this->actingAs($this->admin)->post(route('arisan.iuran.massal', $this->arisan), ['periode_ke' => 1]);
        $this->assertSame(3, ArisanIuran::count());

        $keluar = $this->peserta->first();

        $this->actingAs($this->admin)
            ->delete(route('arisan.peserta.hapus', [$this->arisan, $keluar->id]))
            ->assertRedirect();

        $this->assertSame(0, ArisanIuran::where('anggota_keluarga_id', $keluar->id)->count(),
            'Catatan iuran peserta yang dikeluarkan harus ikut terhapus.');
        $this->assertSame(2, ArisanIuran::count());
    }

    public function test_menghapus_arisan_ikut_menghapus_catatan_iuran(): void
    {
        $this->actingAs($this->admin)->post(route('arisan.iuran.massal', $this->arisan), ['periode_ke' => 1]);

        $this->actingAs($this->admin)->delete(route('arisan.destroy', $this->arisan))->assertRedirect();

        $this->assertSame(0, ArisanIuran::where('arisan_id', $this->arisan->id)->count());
    }

    public function test_halaman_detail_dan_riwayat_menampilkan_iuran(): void
    {
        $this->actingAs($this->admin)->post(route('arisan.iuran.massal', $this->arisan), ['periode_ke' => 1]);

        $this->actingAs($this->admin)
            ->get(route('arisan.show', $this->arisan) . '?periode=1')
            ->assertOk()
            ->assertSee('Lunas')
            ->assertSee($this->peserta->first()->nama_lengkap);

        $this->actingAs($this->admin)
            ->get(route('arisan.iuran.riwayat', $this->arisan))
            ->assertOk()
            ->assertSee('Riwayat Iuran')
            ->assertSee('Rp 300.000');
    }

    public function test_warga_tidak_boleh_mencatat_iuran(): void
    {
        $warga = User::where('role', 'warga')->firstOrFail();

        $this->actingAs($warga)->get(route('arisan.iuran.riwayat', $this->arisan))->assertForbidden();
        $this->actingAs($warga)->post(route('arisan.iuran.bayar', $this->arisan), [
            'anggota_keluarga_id' => $this->peserta->first()->id,
            'periode_ke'          => 1,
        ])->assertForbidden();

        $this->assertSame(0, ArisanIuran::count());
    }
}
