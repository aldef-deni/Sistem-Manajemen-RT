<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\Arisan;
use App\Models\ArisanIuran;
use App\Models\IuranWarga;
use App\Models\JenisIuran;
use App\Models\JenisPinjaman;
use App\Models\KartuKeluarga;
use App\Models\Pinjaman;
use App\Models\SettingRT;
use App\Models\SubscribePayment;
use App\Models\Tabungan;
use App\Models\TabunganTransaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentFinanceTest extends TestCase
{
    use RefreshDatabase;

    private AnggotaKeluarga $member;

    private AnggotaKeluarga $otherMember;

    private User $resident;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-09-10 10:00:00');

        $this->member = $this->member('Satu', '1111111111111111');
        $this->otherMember = $this->member('Dua', '2222222222222222');

        $this->resident = User::factory()->create([
            'name' => 'Warga Satu',
            'username' => 'warga-satu',
            'role' => 'warga',
            'anggota_keluarga_id' => $this->member->id,
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_pembayaran_hanya_menampilkan_iuran_warga_yang_sedang_masuk(): void
    {
        $type = JenisIuran::create([
            'nama' => 'Kebersihan Lingkungan',
            'nominal_default' => 125000,
            'is_active' => true,
        ]);
        $otherType = JenisIuran::create([
            'nama' => 'Rahasia Warga Dua',
            'nominal_default' => 987654,
            'is_active' => true,
        ]);

        IuranWarga::create([
            'anggota_keluarga_id' => $this->member->id,
            'jenis_iuran_id' => $type->id,
            'bulan' => 9,
            'tahun' => 2026,
            'nominal' => 125000,
            'status' => 'lunas',
            'tanggal_bayar' => '2026-09-05',
        ]);
        IuranWarga::create([
            'anggota_keluarga_id' => $this->member->id,
            'jenis_iuran_id' => $type->id,
            'bulan' => 8,
            'tahun' => 2026,
            'nominal' => 125000,
            'status' => 'belum_bayar',
        ]);
        IuranWarga::create([
            'anggota_keluarga_id' => $this->otherMember->id,
            'jenis_iuran_id' => $otherType->id,
            'bulan' => 9,
            'tahun' => 2026,
            'nominal' => 987654,
            'status' => 'lunas',
            'tanggal_bayar' => '2026-09-04',
        ]);

        $this->actingAs($this->resident)
            ->get(route('pembayaran', ['tahun' => 2026]))
            ->assertOk()
            ->assertSee('Pembayaran Iuran Saya')
            ->assertSee('Kebersihan Lingkungan')
            ->assertSee('Rp 125.000')
            ->assertSee('Belum dibayar')
            ->assertDontSee('Rahasia Warga Dua')
            ->assertDontSee('987.654');
    }

    public function test_filter_pembayaran_bulan_dan_status_bekerja(): void
    {
        $type = JenisIuran::create([
            'nama' => 'Iuran Bulanan',
            'nominal_default' => 100000,
            'is_active' => true,
        ]);

        IuranWarga::create([
            'anggota_keluarga_id' => $this->member->id,
            'jenis_iuran_id' => $type->id,
            'bulan' => 8,
            'tahun' => 2026,
            'nominal' => 111000,
            'status' => 'lunas',
            'tanggal_bayar' => '2026-08-05',
            'catatan' => 'CATATAN-AGUSTUS-TIDAK-TAMPIL',
        ]);
        IuranWarga::create([
            'anggota_keluarga_id' => $this->member->id,
            'jenis_iuran_id' => $type->id,
            'bulan' => 9,
            'tahun' => 2026,
            'nominal' => 222000,
            'status' => 'belum_bayar',
        ]);

        $this->actingAs($this->resident)
            ->get(route('pembayaran', [
                'tahun' => 2026,
                'bulan' => 9,
                'status' => 'belum_bayar',
            ]))
            ->assertOk()
            ->assertSee('Rp 222.000')
            ->assertDontSee('CATATAN-AGUSTUS-TIDAK-TAMPIL');
    }

    public function test_laporan_menggabungkan_transaksi_pribadi_tanpa_subscribe_atau_data_warga_lain(): void
    {
        $type = JenisIuran::create([
            'nama' => 'Iuran Keamanan Saya',
            'nominal_default' => 70000,
            'is_active' => true,
        ]);
        IuranWarga::create([
            'anggota_keluarga_id' => $this->member->id,
            'jenis_iuran_id' => $type->id,
            'bulan' => 9,
            'tahun' => 2026,
            'nominal' => 70000,
            'status' => 'lunas',
            'tanggal_bayar' => '2026-09-02',
        ]);

        $savings = Tabungan::create([
            'anggota_keluarga_id' => $this->member->id,
            'no_rekening' => 'TAB-WARGA-SATU',
            'jenis_tabungan' => 'sukarela',
            'saldo' => 325000,
            'status' => 'aktif',
        ]);
        TabunganTransaksi::create([
            'tabungan_id' => $savings->id,
            'jenis' => 'setoran',
            'nominal' => 125000,
            'saldo_sebelum' => 200000,
            'saldo_sesudah' => 325000,
            'keterangan' => 'Setoran pribadi September',
            'status' => 'dikonfirmasi',
        ]);

        $arisan = Arisan::create([
            'nama' => 'Arisan Melati',
            'nominal_iuran' => 80000,
            'periode' => 'bulanan',
            'tanggal_mulai' => '2026-09-01',
            'mode_undian' => 'manual',
            'jumlah_pemenang_per_pertemuan' => 1,
            'status' => 'aktif',
        ]);
        ArisanIuran::create([
            'arisan_id' => $arisan->id,
            'anggota_keluarga_id' => $this->member->id,
            'periode_ke' => 1,
            'nominal' => 80000,
            'tanggal_bayar' => '2026-09-03',
            'metode' => 'tunai',
        ]);

        $loanType = JenisPinjaman::create([
            'nama' => 'Pinjaman Usaha',
            'tenor_bulan' => 12,
            'status' => 'aktif',
        ]);
        Pinjaman::create([
            'anggota_keluarga_id' => $this->member->id,
            'jenis_pinjaman_id' => $loanType->id,
            'nominal' => 1500000,
            'tenor_bulan' => 12,
            'keperluan' => 'Modal usaha pribadi',
            'status' => 'aktif',
            'tanggal_mulai' => '2026-09-04',
            'sisa_pinjaman' => 1250000,
        ]);

        $otherSavings = Tabungan::create([
            'anggota_keluarga_id' => $this->otherMember->id,
            'no_rekening' => 'TAB-RAHASIA-DUA',
            'jenis_tabungan' => 'sukarela',
            'saldo' => 987654,
            'status' => 'aktif',
        ]);
        TabunganTransaksi::create([
            'tabungan_id' => $otherSavings->id,
            'jenis' => 'setoran',
            'nominal' => 987654,
            'saldo_sebelum' => 0,
            'saldo_sesudah' => 987654,
            'keterangan' => 'TRANSAKSI-RAHASIA-WARGA-DUA',
            'status' => 'dikonfirmasi',
        ]);

        SubscribePayment::create([
            'user_id' => $this->resident->id,
            'amount' => 99887766,
            'destination_bank' => 'Bank RT',
            'destination_account_number' => '123',
            'destination_account_name' => 'RT',
            'sender_bank' => 'Bank Warga',
            'sender_account_name' => 'Warga Satu',
            'paid_at' => '2026-09-05',
            'proof_path' => 'subscribe/rahasia.jpg',
            'status' => 'approved',
            'starts_at' => '2026-09-05 00:00:00',
            'ends_at' => '2026-10-05 00:00:00',
        ]);

        $this->actingAs($this->resident)
            ->get(route('laporan-keuangan', ['periode' => 'tahun', 'tahun' => 2026]))
            ->assertOk()
            ->assertSee('Pembayaran Iuran Keamanan Saya')
            ->assertSee('Setoran Tabungan')
            ->assertSee('Iuran Arisan Melati')
            ->assertSee('Pencairan Pinjaman Usaha')
            ->assertSee('Rp 325.000')
            ->assertSee('Rp 1.250.000')
            ->assertDontSee('TRANSAKSI-RAHASIA-WARGA-DUA')
            ->assertDontSee('TAB-RAHASIA-DUA')
            ->assertDontSee('99.887.766')
            ->assertDontSee('subscribe/rahasia.jpg');
    }

    public function test_laporan_dapat_difilter_berdasarkan_kategori(): void
    {
        $type = JenisIuran::create([
            'nama' => 'Iuran Terfilter',
            'nominal_default' => 76000,
            'is_active' => true,
        ]);
        IuranWarga::create([
            'anggota_keluarga_id' => $this->member->id,
            'jenis_iuran_id' => $type->id,
            'bulan' => 9,
            'tahun' => 2026,
            'nominal' => 76000,
            'status' => 'lunas',
            'tanggal_bayar' => '2026-09-02',
        ]);
        $savings = Tabungan::create([
            'anggota_keluarga_id' => $this->member->id,
            'no_rekening' => 'TAB-FILTER',
            'jenis_tabungan' => 'sukarela',
            'saldo' => 88000,
            'status' => 'aktif',
        ]);
        TabunganTransaksi::create([
            'tabungan_id' => $savings->id,
            'jenis' => 'setoran',
            'nominal' => 88000,
            'saldo_sebelum' => 0,
            'saldo_sesudah' => 88000,
            'keterangan' => 'TABUNGAN-TERFILTER-KELUAR',
            'status' => 'dikonfirmasi',
        ]);

        $this->actingAs($this->resident)
            ->get(route('laporan-keuangan', [
                'periode' => 'bulan',
                'bulan' => '2026-09',
                'kategori' => 'iuran',
            ]))
            ->assertOk()
            ->assertSee('Iuran Terfilter')
            ->assertDontSee('TABUNGAN-TERFILTER-KELUAR');
    }

    public function test_akun_tanpa_data_warga_mendapat_petunjuk_tanpa_data_keuangan(): void
    {
        $unlinked = User::factory()->create([
            'username' => 'belum-terhubung',
            'role' => 'warga',
            'anggota_keluarga_id' => null,
        ]);

        foreach (['pembayaran', 'laporan-keuangan'] as $routeName) {
            $this->actingAs($unlinked)
                ->get(route($routeName))
                ->assertOk()
                ->assertSee('Data warga belum terhubung')
                ->assertSee('belum-terhubung');
        }
    }

    public function test_halaman_keuangan_pribadi_hanya_bisa_diakses_role_warga(): void
    {
        foreach (['admin', 'ketua', 'pengurus'] as $role) {
            $user = User::factory()->create([
                'username' => 'akses-'.$role,
                'role' => $role,
            ]);

            $this->actingAs($user)->get(route('pembayaran'))->assertForbidden();
            $this->actingAs($user)->get(route('laporan-keuangan'))->assertForbidden();
        }
    }

    public function test_halaman_keuangan_pribadi_tetap_terkunci_saat_subscribe_warga_belum_aktif(): void
    {
        SettingRT::set('subscribe_enabled', '1');
        SettingRT::set('subscribe_roles', json_encode(['warga'], JSON_THROW_ON_ERROR));

        foreach (['pembayaran', 'laporan-keuangan'] as $routeName) {
            $this->actingAs($this->resident)
                ->get(route($routeName))
                ->assertRedirect(route('dashboard'))
                ->assertSessionHas('show_subscribe_modal', true);
        }
    }

    public function test_data_warga_yang_sama_tidak_bisa_ditautkan_ke_dua_akun(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-link',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->post(route('akun.store'), [
                'name' => 'Akun Duplikat',
                'username' => 'akun-duplikat',
                'email' => 'duplikat@example.test',
                'role' => 'warga',
                'anggota_keluarga_id' => $this->member->id,
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertSessionHasErrors('anggota_keluarga_id');
    }

    private function member(string $suffix, string $nik): AnggotaKeluarga
    {
        $family = KartuKeluarga::create([
            'no_kk' => 'KK-'.$nik,
            'rt' => '001',
            'rw' => '002',
            'alamat' => 'Jalan Pengujian '.$suffix,
        ]);

        return AnggotaKeluarga::create([
            'kartu_keluarga_id' => $family->id,
            'nik' => $nik,
            'nama_lengkap' => 'Warga '.$suffix,
            'jenis_kelamin' => 'L',
            'status_hubungan' => 'Kepala Keluarga',
            'domisili' => 'Tetap',
            'role' => 'Warga',
        ]);
    }
}
