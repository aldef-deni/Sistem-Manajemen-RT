<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\Arisan;
use App\Models\Barang;
use App\Models\JenisIuran;
use App\Models\JenisPinjaman;
use App\Models\KartuKeluarga;
use App\Models\RekeningKas;
use App\Models\Tabungan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menjalankan buat → ubah → hapus pada setiap modul, memastikan formulir,
 * kolom, dan aksi CRUD-nya benar-benar bekerja — bukan sekadar halamannya
 * terbuka.
 */
class CrudModulTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = User::where('role', 'admin')->firstOrFail();
    }

    private function wargaId(): int
    {
        return AnggotaKeluarga::query()->value('id');
    }

    private function rekeningId(): int
    {
        return RekeningKas::query()->value('id');
    }

    public function test_pengumuman(): void
    {
        $this->actingAs($this->admin)->post(route('pengumuman.store'), [
            'judul' => 'Kerja Bakti Minggu Pagi',
            'kategori' => 'Kegiatan',
            'target' => 'Semua Warga',
            'isi' => 'Kerja bakti dimulai pukul 07.00 di pos ronda.',
            'tanggal_publish' => now()->toDateString(),
            'status' => 'publish',
        ])->assertRedirect();

        $id = \App\Models\Pengumuman::where('judul', 'Kerja Bakti Minggu Pagi')->value('id');
        $this->assertNotNull($id, 'Pengumuman tidak tersimpan.');

        $this->actingAs($this->admin)->get(route('pengumuman.show', $id))->assertOk();
        $this->actingAs($this->admin)->get(route('pengumuman.edit', $id))->assertOk();

        $this->actingAs($this->admin)->put(route('pengumuman.update', $id), [
            'judul' => 'Kerja Bakti Diundur',
            'kategori' => 'Kegiatan',
            'target' => 'Semua Warga',
            'isi' => 'Diundur menjadi minggu depan.',
            'tanggal_publish' => now()->toDateString(),
            'status' => 'publish',
        ])->assertRedirect();

        $this->assertSame('Kerja Bakti Diundur', \App\Models\Pengumuman::find($id)->judul);

        $this->actingAs($this->admin)->delete(route('pengumuman.destroy', $id))->assertRedirect();
        $this->assertNull(\App\Models\Pengumuman::find($id));
    }

    public function test_visitor(): void
    {
        $this->actingAs($this->admin)->post(route('visitor.store'), [
            'tipe_kunjungan' => 'singkat',
            'nama_tamu' => 'Budi Santoso',
            'no_hp' => '081200000001',
            'tujuan_blok' => 'Blok C No. 5',
            'kepentingan' => ['Silaturahmi'],
            'deskripsi_kepentingan' => 'Menjenguk keluarga.',
        ])->assertRedirect();

        $visitor = \App\Models\Visitor::where('nama_tamu', 'Budi Santoso')->firstOrFail();

        $this->actingAs($this->admin)->get(route('visitor.show', $visitor))->assertOk();
        $this->actingAs($this->admin)->get(route('visitor.edit', $visitor))->assertOk();
        $this->actingAs($this->admin)->patch(route('visitor.checkout', $visitor))->assertRedirect();
        $this->actingAs($this->admin)->delete(route('visitor.destroy', $visitor))->assertRedirect();

        $this->assertNull(\App\Models\Visitor::find($visitor->id));
    }

    public function test_surat(): void
    {
        $this->actingAs($this->admin)->post(route('surat.store'), [
            'anggota_keluarga_id' => $this->wargaId(),
            'jenis_surat' => 'Surat Keterangan Domisili',
            'keperluan' => 'Melengkapi berkas pendaftaran sekolah.',
        ])->assertRedirect();

        $surat = \App\Models\Surat::latest('id')->firstOrFail();

        $this->actingAs($this->admin)->get(route('surat.show', $surat))->assertOk();
        $this->actingAs($this->admin)->patch(route('surat.status', $surat), ['status' => 'selesai'])->assertRedirect();
        $this->actingAs($this->admin)->delete(route('surat.destroy', $surat))->assertRedirect();

        $this->assertNull(\App\Models\Surat::find($surat->id));
    }

    public function test_barang_dan_peminjaman(): void
    {
        $this->actingAs($this->admin)->post(route('barang.store'), [
            'kode_barang' => 'INV-UJI-001',
            'nama_barang' => 'Kursi Plastik',
            'kategori' => 'Perlengkapan',
            'kondisi' => 'Baik',
            'jumlah' => 20,
            'satuan' => 'buah',
            'lokasi' => 'Gudang RT',
        ])->assertRedirect();

        $barang = Barang::where('kode_barang', 'INV-UJI-001')->firstOrFail();

        $this->actingAs($this->admin)->get(route('barang.show', $barang))->assertOk();
        $this->actingAs($this->admin)->put(route('barang.update', $barang), [
            'kode_barang' => 'INV-UJI-001',
            'nama_barang' => 'Kursi Plastik Merah',
            'kategori' => 'Perlengkapan',
            'kondisi' => 'Baik',
            'jumlah' => 25,
            'satuan' => 'buah',
        ])->assertRedirect();

        $this->assertSame('KURSI PLASTIK MERAH', $barang->fresh()->nama_barang, 'Nama barang disimpan dalam huruf kapital.');

        // Peminjaman memakai barang di atas
        $this->actingAs($this->admin)->post(route('peminjaman.store'), [
            'kode_peminjaman' => 'PJM-UJI-001',
            'barang_id' => $barang->id,
            'jumlah_pinjam' => 2,
            'kondisi_saat_pinjam' => 'Baik',
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_rencana_kembali' => now()->addDays(3)->toDateString(),
            'nama_peminjam' => 'Panitia 17 Agustus',
        ])->assertRedirect();

        $pinjam = \App\Models\PeminjamanBarang::where('kode_peminjaman', 'PJM-UJI-001')->firstOrFail();

        $this->actingAs($this->admin)->get(route('peminjaman.show', $pinjam))->assertOk();
        $this->actingAs($this->admin)->patch(route('peminjaman.kembalikan', $pinjam), [
            'kondisi_saat_kembali' => 'Baik',
            'tanggal_kembali' => now()->toDateString(),
        ])->assertRedirect();
        $this->actingAs($this->admin)->delete(route('peminjaman.destroy', $pinjam))->assertRedirect();
        $this->actingAs($this->admin)->delete(route('barang.destroy', $barang))->assertRedirect();

        // Barang tidak dihapus permanen, hanya ditandai — riwayat peminjaman
        // yang sudah ada tetap punya rujukan.
        $this->assertSame('dihapus', $barang->fresh()->status);
    }

    public function test_rencana_pembelian(): void
    {
        $this->actingAs($this->admin)->post(route('barang.rencana.store'), [
            'kode_rencana' => 'RCN-UJI-001',
            'nama_barang' => 'Tenda Lipat',
            'kategori' => 'Perlengkapan',
            'jumlah' => 2,
            'satuan' => 'unit',
            'prioritas' => 'sedang',
            'estimasi_harga' => 1500000,
            'tanggal_rencana' => now()->addMonth()->toDateString(),
        ])->assertRedirect();

        $rencana = \App\Models\RencanaPembelian::where('kode_rencana', 'RCN-UJI-001')->firstOrFail();

        $this->actingAs($this->admin)->patch(route('barang.rencana.update-status', $rencana), ['status' => 'disetujui'])->assertRedirect();
        $this->actingAs($this->admin)->delete(route('barang.rencana.destroy', $rencana))->assertRedirect();

        $this->assertNull(\App\Models\RencanaPembelian::find($rencana->id));
    }

    public function test_kas_rt(): void
    {
        $rekening = RekeningKas::findOrFail($this->rekeningId());
        $saldoAwal = (float) $rekening->saldo;

        $this->actingAs($this->admin)->post(route('kas-rt.store-pemasukan'), [
            'tanggal' => now()->toDateString(),
            'kategori' => 'Iuran Warga',
            'rekening_kas_id' => $rekening->id,
            'nominal' => 250000,
            'keterangan' => 'Uji pemasukan',
        ])->assertRedirect();

        $this->assertSame($saldoAwal + 250000, (float) $rekening->fresh()->saldo, 'Saldo tidak bertambah setelah pemasukan.');

        $this->actingAs($this->admin)->post(route('kas-rt.store-pengeluaran'), [
            'tanggal' => now()->toDateString(),
            'kategori' => 'Kebersihan',
            'rekening_kas_id' => $rekening->id,
            'nominal' => 100000,
            'keterangan' => 'Uji pengeluaran',
        ])->assertRedirect();

        $this->assertSame($saldoAwal + 150000, (float) $rekening->fresh()->saldo, 'Saldo tidak berkurang setelah pengeluaran.');

        $transaksi = \App\Models\TransaksiKas::where('keterangan', 'Uji pengeluaran')->firstOrFail();
        $this->actingAs($this->admin)->delete(route('kas-rt.destroy', $transaksi))->assertRedirect();
    }

    public function test_tabungan_setoran_dan_penarikan(): void
    {
        $warga = $this->wargaId();
        $saldoAwal = (float) (Tabungan::where('anggota_keluarga_id', $warga)->value('saldo') ?? 0);

        $this->actingAs($this->admin)->post(route('tabungan.store-setoran'), [
            'anggota_keluarga_id' => $warga,
            'rekening_kas_id' => $this->rekeningId(),
            'jenis_tabungan' => 'sukarela',
            'nominal' => 500000,
            'keterangan' => 'Setoran awal uji',
        ])->assertRedirect();

        $tabungan = Tabungan::where('anggota_keluarga_id', $warga)->firstOrFail();
        $this->assertSame($saldoAwal + 500000, (float) $tabungan->saldo, 'Setoran tidak menambah saldo.');

        $this->actingAs($this->admin)->get(route('tabungan.show', $tabungan))->assertOk();

        $this->actingAs($this->admin)->post(route('tabungan.store-penarikan'), [
            'anggota_keluarga_id' => $warga,
            'nominal' => 200000,
            'keterangan' => 'Penarikan uji',
        ])->assertRedirect();

        $this->assertSame($saldoAwal + 300000, (float) $tabungan->fresh()->saldo, 'Penarikan tidak mengurangi saldo.');

        // Penarikan melebihi saldo harus ditolak, bukan membuat saldo minus.
        $this->actingAs($this->admin)->post(route('tabungan.store-penarikan'), [
            'anggota_keluarga_id' => $warga,
            'nominal' => 99000000,
            'keterangan' => 'Melebihi saldo',
        ]);

        $this->assertSame($saldoAwal + 300000, (float) $tabungan->fresh()->saldo, 'Saldo berubah padahal penarikan melebihi saldo.');
    }

    public function test_iuran_warga(): void
    {
        $jenis = JenisIuran::query()->value('id');
        $this->assertNotNull($jenis, 'Seeder tidak menyediakan jenis iuran.');

        $this->actingAs($this->admin)->post(route('iuran-warga.store'), [
            'anggota_keluarga_id' => $this->wargaId(),
            'jenis_iuran_id' => $jenis,
            'bulan' => 6,
            'tahun' => 2026,
            'nominal' => 50000,
            'catatan' => 'Uji iuran',
        ])->assertRedirect();

        $iuran = \App\Models\IuranWarga::where('catatan', 'Uji iuran')->firstOrFail();

        $this->actingAs($this->admin)->get(route('iuran-warga.edit', $iuran))->assertOk();
        $this->actingAs($this->admin)->patch(route('iuran-warga.bayar', $iuran))->assertRedirect();
        $this->actingAs($this->admin)->delete(route('iuran-warga.destroy', $iuran))->assertRedirect();

        $this->assertNull(\App\Models\IuranWarga::find($iuran->id));
    }

    public function test_kartu_keluarga(): void
    {
        $this->actingAs($this->admin)->post(route('kartu-keluarga.store'), [
            'no_kk' => '33150199999999',
            'alamat' => 'Jl. Uji Coba No. 1',
            'rt' => '003',
            'rw' => '005',
            'anggota' => [[
                'nik' => '3315019999999901',
                'nama_lengkap' => 'Warga Uji',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-01-01',
                'status_hubungan' => 'Kepala Keluarga',
            ]],
        ])->assertSessionHasErrors('no_kk'); // panjang no_kk wajib 20 karakter

        $this->actingAs($this->admin)->post(route('kartu-keluarga.store'), [
            'no_kk' => '33150199999999999999',
            'alamat' => 'Jl. Uji Coba No. 1',
            'rt' => '003',
            'rw' => '005',
            'anggota' => [[
                'nik' => '3315019999999901',
                'nama_lengkap' => 'Warga Uji',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-01-01',
                'status_hubungan' => 'Kepala Keluarga',
            ]],
        ])->assertRedirect();

        $kk = KartuKeluarga::where('no_kk', '33150199999999999999')->firstOrFail();

        $this->assertSame(1, $kk->anggota()->count());
        $this->actingAs($this->admin)->get(route('kartu-keluarga.show', $kk))->assertOk();
        $this->actingAs($this->admin)->delete(route('kartu-keluarga.destroy', $kk))->assertRedirect();
        $this->assertNull(KartuKeluarga::find($kk->id));
    }

    public function test_arisan_lengkap(): void
    {
        $this->actingAs($this->admin)->post(route('arisan.store'), [
            'nama' => 'Arisan Uji RT',
            'nominal_iuran' => 100000,
            'periode' => 'bulanan',
            'tanggal_mulai' => now()->toDateString(),
            'mode_undian' => 'otomatis',
            'pendamping_per_periode' => 1,
        ])->assertRedirect();

        $arisan = Arisan::where('nama', 'ARISAN UJI RT')->firstOrFail();

        // Kedua tampilan ini sebelumnya tidak ada dan membuat halaman 500.
        $this->actingAs($this->admin)->get(route('arisan.show', $arisan))->assertOk();
        $this->actingAs($this->admin)->get(route('arisan.edit', $arisan))->assertOk();

        $this->actingAs($this->admin)->post(route('arisan.peserta.tambah', $arisan), [
            'anggota_keluarga_id' => $this->wargaId(),
        ])->assertRedirect();

        $this->assertSame(1, $arisan->peserta()->count());

        $this->actingAs($this->admin)->post(route('arisan.undian', $arisan))->assertRedirect();
        $this->assertSame(1, $arisan->peserta()->wherePivot('sudah_dapat', true)->count());

        $this->actingAs($this->admin)->put(route('arisan.update', $arisan), [
            'nama' => 'Arisan Uji Diubah',
            'nominal_iuran' => 150000,
            'periode' => 'bulanan',
            'tanggal_mulai' => now()->toDateString(),
            'mode_undian' => 'manual',
            'jumlah_pemenang_per_pertemuan' => 2,
            'status' => 'aktif',
        ])->assertRedirect();

        $this->assertSame('ARISAN UJI DIUBAH', $arisan->fresh()->nama);

        $this->actingAs($this->admin)->delete(route('arisan.peserta.hapus', [$arisan, $this->wargaId()]))->assertRedirect();
        $this->actingAs($this->admin)->delete(route('arisan.destroy', $arisan))->assertRedirect();
        $this->assertNull(Arisan::find($arisan->id));
    }

    public function test_polling_dan_pemungutan_suara(): void
    {
        $this->actingAs($this->admin)->post(route('polling.store'), [
            'judul' => 'Warna Cat Pos Ronda',
            'tanggal_mulai' => now()->toDateString(),
            'opsi' => ['Hijau', 'Biru'],
            'izinkan_ganti' => 1,
        ])->assertRedirect();

        $polling = \App\Models\Polling::where('judul', 'Warna Cat Pos Ronda')->firstOrFail();
        $warga = User::where('role', 'warga')->firstOrFail();

        $this->actingAs($warga)->post(route('polling.vote', $polling), ['pilihan' => 'Hijau'])->assertRedirect();
        $this->assertSame(1, $polling->fresh()->jumlah_suara);

        // Ganti pilihan tidak boleh menambah jumlah suara.
        $this->actingAs($warga)->post(route('polling.vote', $polling), ['pilihan' => 'Biru'])->assertRedirect();
        $this->assertSame(1, $polling->fresh()->jumlah_suara, 'Suara terhitung ganda saat mengganti pilihan.');

        $this->actingAs($this->admin)->get(route('polling.show', $polling))->assertOk();
        $this->actingAs($this->admin)->patch(route('polling.close', $polling))->assertRedirect();
        $this->actingAs($this->admin)->delete(route('polling.destroy', $polling))->assertRedirect();
    }

    public function test_umkm(): void
    {
        $this->actingAs($this->admin)->post(route('umkm.store'), [
            'nama_usaha' => 'Warung Bu Sri',
            'kategori' => 'Kuliner',
            'deskripsi_usaha' => 'Menjual nasi uduk dan gorengan setiap pagi.',
            'no_telepon' => '081200000002',
        ])->assertRedirect();

        $umkm = \App\Models\UMKM::where('nama_usaha', 'Warung Bu Sri')->firstOrFail();

        $this->actingAs($this->admin)->get(route('umkm.edit', $umkm))->assertOk();
        $this->actingAs($this->admin)->put(route('umkm.update', $umkm), [
            'nama_usaha' => 'Warung Bu Sri Jaya',
            'kategori' => 'Kuliner',
            'deskripsi_usaha' => 'Menambah menu ayam geprek.',
        ])->assertRedirect();

        $this->assertSame('Warung Bu Sri Jaya', $umkm->fresh()->nama_usaha);

        $this->actingAs($this->admin)->delete(route('umkm.destroy', $umkm))->assertRedirect();
        $this->assertNull(\App\Models\UMKM::find($umkm->id));
    }

    public function test_jadwal_dan_kegiatan_dan_notulen(): void
    {
        // Jadwal kegiatan
        $this->actingAs($this->admin)->post(route('jadwal-kegiatan.store'), [
            'nama_kegiatan' => 'Ronda Malam Blok C',
            'kategori' => 'Keamanan',
            'jenis_jadwal' => 'rutin',
            'tanggal_mulai' => now()->toDateString(),
            'status' => 'aktif',
        ])->assertRedirect();

        $jadwal = \App\Models\JadwalKegiatan::where('nama_kegiatan', 'Ronda Malam Blok C')->firstOrFail();
        $this->actingAs($this->admin)->get(route('jadwal-kegiatan.show', $jadwal))->assertOk();
        $this->actingAs($this->admin)->delete(route('jadwal-kegiatan.destroy', $jadwal))->assertRedirect();

        // Kegiatan RT — halaman detailnya dulu 500 karena ternary bersarang.
        $this->actingAs($this->admin)->post(route('kegiatan-rt.store'), [
            'judul' => 'Peringatan HUT RI',
            'artikel' => 'Lomba dimulai pukul 08.00 di lapangan.',
            'kategori' => 'Sosial',
            'status' => 'publish',
            'tanggal_mulai' => now()->toDateString(),
        ])->assertRedirect();

        $kegiatan = \App\Models\KegiatanRT::where('judul', 'Peringatan HUT RI')->firstOrFail();
        $this->actingAs($this->admin)->get(route('kegiatan-rt.show', $kegiatan))->assertOk();
        $this->actingAs($this->admin)->delete(route('kegiatan-rt.destroy', $kegiatan))->assertRedirect();

        // Notulen rapat — halaman detailnya dulu 500 karena variabel salah ketik.
        $this->actingAs($this->admin)->post(route('notulen-rapat.store'), [
            'judul_rapat' => 'Rapat Bulanan Pengurus',
            'tanggal' => now()->toDateString(),
            'waktu_mulai' => '19:00',
            'waktu_selesai' => '21:00',
            'tempat' => 'Balai RT',
            'moderator' => 'Ketua RT',
            'notulis' => 'Sekretaris',
            'status' => 'final',
        ])->assertRedirect();

        $notulen = \App\Models\NotulenRapat::where('judul_rapat', 'Rapat Bulanan Pengurus')->firstOrFail();
        $this->actingAs($this->admin)->get(route('notulen-rapat.show', $notulen))->assertOk();
        $this->actingAs($this->admin)->delete(route('notulen-rapat.destroy', $notulen))->assertRedirect();
    }

    public function test_pinjaman(): void
    {
        $this->actingAs($this->admin)->post(route('pinjaman.jenis.store'), [
            'nama' => 'Pinjaman Darurat',
            'bunga_persen' => 1,
            'denda_persen' => 2,
            'tenor_bulan' => 6,
            'status' => 'aktif',
        ])->assertRedirect();

        $jenis = JenisPinjaman::where('nama', 'Pinjaman Darurat')->firstOrFail();

        $this->actingAs($this->admin)->post(route('pinjaman.store-ajukan'), [
            'anggota_keluarga_id' => $this->wargaId(),
            'jenis_pinjaman_id' => $jenis->id,
            'nominal' => 1000000,
            'tenor_bulan' => 6,
            'keperluan' => 'Biaya berobat.',
        ])->assertRedirect();

        $this->assertSame(1, \App\Models\Pinjaman::where('jenis_pinjaman_id', $jenis->id)->count());

        $this->actingAs($this->admin)->put(route('pinjaman.jenis.update', $jenis), [
            'nama' => 'Pinjaman Darurat Warga',
            'bunga_persen' => 1,
            'denda_persen' => 2,
            'tenor_bulan' => 12,
            'status' => 'aktif',
        ])->assertRedirect();

        $this->assertSame('Pinjaman Darurat Warga', $jenis->fresh()->nama);
    }

    public function test_bantuan_sosial(): void
    {
        $this->actingAs($this->admin)->post(route('bantuan-sosial.store-penerima'), [
            'anggota_keluarga_id' => $this->wargaId(),
            'jenis_bantuan' => ['PKH'],
            'tahun' => 2026,
            'status' => 'aktif',
            'keterangan' => 'Uji penerima',
        ])->assertRedirect();

        $penerima = \App\Models\PenerimaBantuan::where('keterangan', 'Uji penerima')->firstOrFail();

        $this->actingAs($this->admin)->get(route('bantuan-sosial.show', $penerima))->assertOk();
        $this->actingAs($this->admin)->get(route('bantuan-sosial.edit', $penerima))->assertOk();
        $this->actingAs($this->admin)->delete(route('bantuan-sosial.destroy', $penerima))->assertRedirect();

        $this->assertNull(\App\Models\PenerimaBantuan::find($penerima->id));
    }

    public function test_kelola_akun(): void
    {
        $this->actingAs($this->admin)->post(route('akun.store'), [
            'name' => 'Sekretaris RT',
            'username' => 'sekretaris',
            'email' => 'sekretaris@sistemrt.test',
            'role' => 'pengurus',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect();

        $akun = User::where('username', 'sekretaris')->firstOrFail();

        $this->actingAs($this->admin)->get(route('akun.edit', $akun))->assertOk();
        $this->actingAs($this->admin)->put(route('akun.update', $akun), [
            'name' => 'Sekretaris RT 05',
            'username' => 'sekretaris',
            'email' => 'sekretaris@sistemrt.test',
            'role' => 'warga',
        ])->assertRedirect();

        $this->assertSame('warga', $akun->fresh()->role);

        $this->actingAs($this->admin)->delete(route('akun.destroy', $akun))->assertRedirect();
        $this->assertNull(User::find($akun->id));
    }

    public function test_profil_dan_ganti_password(): void
    {
        $this->actingAs($this->admin)->put(route('profil.update'), [
            'name' => 'Administrator RT',
            'username' => $this->admin->username,
            'email' => $this->admin->email,
            'no_hp' => '081200000009',
        ])->assertRedirect();

        $this->assertSame('Administrator RT', $this->admin->fresh()->name);
    }
}
