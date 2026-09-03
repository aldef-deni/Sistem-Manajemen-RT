<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\IuranWargaController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\PemilihPemiluController;
use App\Http\Controllers\KasRTController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ArisanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RencanaPembelianController;
use App\Http\Controllers\PeminjamanBarangController;
use App\Http\Controllers\UMKMController;
use App\Http\Controllers\BantuanSosialController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\JadwalKegiatanController;
use App\Http\Controllers\NotulenRapatController;
use App\Http\Controllers\StrukturRTController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PollingController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Login)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Warga
    Route::get('/data-warga', [DataWargaController::class, 'index'])->name('data-warga');

    // Pemilih Pemilu
    Route::get('/pemilih-pemilu', [PemilihPemiluController::class, 'index'])->name('pemilih-pemilu');

    // Kartu Keluarga CRUD
    Route::resource('kartu-keluarga', KartuKeluargaController::class);

    // Iuran Warga CRUD
    Route::resource('iuran-warga', IuranWargaController::class)->except(['show']);
    Route::patch('iuran-warga/{iuran_warga}/bayar', [IuranWargaController::class, 'bayar'])->name('iuran-warga.bayar');
    Route::post('iuran-warga/bayar-massal', [IuranWargaController::class, 'bayarMassal'])->name('iuran-warga.bayar-massal');

    // Kas RT
    Route::get('kas-rt', [KasRTController::class, 'index'])->name('kas-rt.index');
    Route::get('kas-rt/pemasukan', [KasRTController::class, 'pemasukan'])->name('kas-rt.pemasukan');
    Route::post('kas-rt/pemasukan', [KasRTController::class, 'storePemasukan'])->name('kas-rt.store-pemasukan');
    Route::get('kas-rt/pengeluaran', [KasRTController::class, 'pengeluaran'])->name('kas-rt.pengeluaran');
    Route::post('kas-rt/pengeluaran', [KasRTController::class, 'storePengeluaran'])->name('kas-rt.store-pengeluaran');
    Route::delete('kas-rt/{transaksi}', [KasRTController::class, 'destroy'])->name('kas-rt.destroy');

    // Tabungan
    Route::get('tabungan', [TabunganController::class, 'index'])->name('tabungan.index');
    Route::get('tabungan/setoran', [TabunganController::class, 'setoran'])->name('tabungan.setoran');
    Route::post('tabungan/setoran', [TabunganController::class, 'storeSetoran'])->name('tabungan.store-setoran');
    Route::get('tabungan/penarikan', [TabunganController::class, 'penarikan'])->name('tabungan.penarikan');
    Route::post('tabungan/penarikan', [TabunganController::class, 'storePenarikan'])->name('tabungan.store-penarikan');
    Route::get('tabungan/get-saldo', [TabunganController::class, 'getSaldo'])->name('tabungan.get-saldo');
    Route::get('tabungan/{tabungan}', [TabunganController::class, 'show'])->name('tabungan.show');

    // Pinjaman
    Route::get('pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index');
    Route::get('pinjaman/jenis', [PinjamanController::class, 'jenisIndex'])->name('pinjaman.jenis');
    Route::post('pinjaman/jenis', [PinjamanController::class, 'jenisStore'])->name('pinjaman.jenis.store');
    Route::put('pinjaman/jenis/{jenisPinjaman}', [PinjamanController::class, 'jenisUpdate'])->name('pinjaman.jenis.update');
    Route::delete('pinjaman/jenis/{jenisPinjaman}', [PinjamanController::class, 'jenisDestroy'])->name('pinjaman.jenis.destroy');
    Route::get('pinjaman/ajukan', [PinjamanController::class, 'ajukan'])->name('pinjaman.ajukan');
    Route::post('pinjaman/ajukan', [PinjamanController::class, 'storeAjukan'])->name('pinjaman.store-ajukan');
    Route::get('pinjaman/get-jenis', [PinjamanController::class, 'getJenis'])->name('pinjaman.get-jenis');

    // Arisan RT
    Route::get('arisan', [ArisanController::class, 'index'])->name('arisan.index');
    Route::get('arisan/create', [ArisanController::class, 'create'])->name('arisan.create');
    Route::post('arisan', [ArisanController::class, 'store'])->name('arisan.store');
    Route::get('arisan/{arisan}', [ArisanController::class, 'show'])->name('arisan.show');
    Route::get('arisan/{arisan}/edit', [ArisanController::class, 'edit'])->name('arisan.edit');
    Route::put('arisan/{arisan}', [ArisanController::class, 'update'])->name('arisan.update');
    Route::delete('arisan/{arisan}', [ArisanController::class, 'destroy'])->name('arisan.destroy');
    Route::post('arisan/{arisan}/peserta', [ArisanController::class, 'tambahPeserta'])->name('arisan.peserta.tambah');
    Route::delete('arisan/{arisan}/peserta/{peserta}', [ArisanController::class, 'hapusPeserta'])->name('arisan.peserta.hapus');
    Route::post('arisan/{arisan}/undian', [ArisanController::class, 'undian'])->name('arisan.undian');
    Route::post('arisan/{arisan}/iuran', [ArisanController::class, 'bayarIuran'])->name('arisan.iuran.bayar');

    // Inventaris
    Route::get('barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
    Route::get('barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::get('barang/get-kode', [BarangController::class, 'getKode'])->name('barang.get-kode');

    // Rencana Pembelian
    Route::get('barang/rencana/list', [RencanaPembelianController::class, 'index'])->name('barang.rencana.index');
    Route::get('barang/rencana/create', [RencanaPembelianController::class, 'create'])->name('barang.rencana.create');
    Route::post('barang/rencana', [RencanaPembelianController::class, 'store'])->name('barang.rencana.store');
    Route::patch('barang/rencana/{rencana}/status', [RencanaPembelianController::class, 'updateStatus'])->name('barang.rencana.update-status');
    Route::delete('barang/rencana/{rencana}', [RencanaPembelianController::class, 'destroy'])->name('barang.rencana.destroy');

    // Peminjaman Barang
    Route::get('peminjaman', [PeminjamanBarangController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/create', [PeminjamanBarangController::class, 'create'])->name('peminjaman.create');
    Route::post('peminjaman', [PeminjamanBarangController::class, 'store'])->name('peminjaman.store');
    Route::get('peminjaman/{peminjaman}', [PeminjamanBarangController::class, 'show'])->name('peminjaman.show');
    Route::patch('peminjaman/{peminjaman}/kembalikan', [PeminjamanBarangController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::delete('peminjaman/{peminjaman}', [PeminjamanBarangController::class, 'destroy'])->name('peminjaman.destroy');

    // Bantuan Sosial
    Route::get('bantuan-sosial', [BantuanSosialController::class, 'index'])->name('bantuan-sosial.index');
    Route::get('bantuan-sosial/kurang-mampu', [BantuanSosialController::class, 'kurangMampu'])->name('bantuan-sosial.kurang-mampu');
    Route::get('bantuan-sosial/ajukan', [BantuanSosialController::class, 'ajukan'])->name('bantuan-sosial.ajukan');
    Route::post('bantuan-sosial/ajukan', [BantuanSosialController::class, 'storeAjukan'])->name('bantuan-sosial.store-ajukan');
    Route::get('bantuan-sosial/tambah-penerima', [BantuanSosialController::class, 'tambahPenerima'])->name('bantuan-sosial.tambah-penerima');
    Route::post('bantuan-sosial/tambah-penerima', [BantuanSosialController::class, 'storePenerima'])->name('bantuan-sosial.store-penerima');
    Route::get('bantuan-sosial/{penerimaBantuan}', [BantuanSosialController::class, 'show'])->name('bantuan-sosial.show');
    Route::get('bantuan-sosial/{penerimaBantuan}/edit', [BantuanSosialController::class, 'edit'])->name('bantuan-sosial.edit');
    Route::put('bantuan-sosial/{penerimaBantuan}', [BantuanSosialController::class, 'update'])->name('bantuan-sosial.update');
    Route::delete('bantuan-sosial/{penerimaBantuan}', [BantuanSosialController::class, 'destroy'])->name('bantuan-sosial.destroy');
    Route::patch('bantuan-sosial/pengajuan/{pengajuan}/status', [BantuanSosialController::class, 'updateStatusPengajuan'])->name('bantuan-sosial.pengajuan.status');
    Route::get('bantuan-sosial/get-warga', [BantuanSosialController::class, 'getWarga'])->name('bantuan-sosial.get-warga');

    // UMKM
    Route::get('umkm', [UMKMController::class, 'index'])->name('umkm.index');
    Route::get('umkm/create', [UMKMController::class, 'create'])->name('umkm.create');
    Route::get('umkm/daftarkan', [UMKMController::class, 'daftarkan'])->name('umkm.daftarkan');
    Route::post('umkm', [UMKMController::class, 'store'])->name('umkm.store');
    Route::post('umkm/daftarkan', [UMKMController::class, 'storeDaftarkan'])->name('umkm.store-daftarkan');
    Route::get('umkm/{umkm}/edit', [UMKMController::class, 'edit'])->name('umkm.edit');
    Route::put('umkm/{umkm}', [UMKMController::class, 'update'])->name('umkm.update');
    Route::delete('umkm/{umkm}', [UMKMController::class, 'destroy'])->name('umkm.destroy');

    // E-Visitor
    Route::get('visitor', [VisitorController::class, 'index'])->name('visitor.index');
    Route::get('visitor/create', [VisitorController::class, 'create'])->name('visitor.create');
    Route::post('visitor', [VisitorController::class, 'store'])->name('visitor.store');
    Route::get('visitor/{visitor}', [VisitorController::class, 'show'])->name('visitor.show');
    Route::get('visitor/{visitor}/edit', [VisitorController::class, 'edit'])->name('visitor.edit');
    Route::put('visitor/{visitor}', [VisitorController::class, 'update'])->name('visitor.update');
    Route::delete('visitor/{visitor}', [VisitorController::class, 'destroy'])->name('visitor.destroy');
    Route::patch('visitor/{visitor}/checkout', [VisitorController::class, 'checkout'])->name('visitor.checkout');

    // Surat Menyurat
    Route::get('surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('surat/create', [SuratController::class, 'create'])->name('surat.create');
    Route::post('surat', [SuratController::class, 'store'])->name('surat.store');
    Route::get('surat/{surat}', [SuratController::class, 'show'])->name('surat.show');
    Route::get('surat/{surat}/edit', [SuratController::class, 'edit'])->name('surat.edit');
    Route::put('surat/{surat}', [SuratController::class, 'update'])->name('surat.update');
    Route::delete('surat/{surat}', [SuratController::class, 'destroy'])->name('surat.destroy');
    Route::patch('surat/{surat}/status', [SuratController::class, 'updateStatus'])->name('surat.status');

    // Pengumuman
    Route::get('pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('pengumuman.show');
    Route::get('pengumuman/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::put('pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // Kalender
    Route::get('kalender', [KalenderController::class, 'index'])->name('kalender.index');

    // Jadwal Kegiatan
    Route::get('jadwal-kegiatan', [JadwalKegiatanController::class, 'index'])->name('jadwal-kegiatan.index');
    Route::get('jadwal-kegiatan/create', [JadwalKegiatanController::class, 'create'])->name('jadwal-kegiatan.create');
    Route::post('jadwal-kegiatan', [JadwalKegiatanController::class, 'store'])->name('jadwal-kegiatan.store');
    Route::get('jadwal-kegiatan/{jadwalKegiatan}', [JadwalKegiatanController::class, 'show'])->name('jadwal-kegiatan.show');
    Route::get('jadwal-kegiatan/{jadwalKegiatan}/edit', [JadwalKegiatanController::class, 'edit'])->name('jadwal-kegiatan.edit');
    Route::put('jadwal-kegiatan/{jadwalKegiatan}', [JadwalKegiatanController::class, 'update'])->name('jadwal-kegiatan.update');
    Route::delete('jadwal-kegiatan/{jadwalKegiatan}', [JadwalKegiatanController::class, 'destroy'])->name('jadwal-kegiatan.destroy');

    // Kegiatan RT
    Route::get('kegiatan-rt', [\App\Http\Controllers\KegiatanRTController::class, 'index'])->name('kegiatan-rt.index');
    Route::get('kegiatan-rt/create', [\App\Http\Controllers\KegiatanRTController::class, 'create'])->name('kegiatan-rt.create');
    Route::post('kegiatan-rt', [\App\Http\Controllers\KegiatanRTController::class, 'store'])->name('kegiatan-rt.store');
    Route::get('kegiatan-rt/{kegiatanRt}', [\App\Http\Controllers\KegiatanRTController::class, 'show'])->name('kegiatan-rt.show');
    Route::get('kegiatan-rt/{kegiatanRt}/edit', [\App\Http\Controllers\KegiatanRTController::class, 'edit'])->name('kegiatan-rt.edit');
    Route::put('kegiatan-rt/{kegiatanRt}', [\App\Http\Controllers\KegiatanRTController::class, 'update'])->name('kegiatan-rt.update');
    Route::delete('kegiatan-rt/{kegiatanRt}', [\App\Http\Controllers\KegiatanRTController::class, 'destroy'])->name('kegiatan-rt.destroy');

    // Notulen Rapat
    Route::get('notulen-rapat', [NotulenRapatController::class, 'index'])->name('notulen-rapat.index');
    Route::get('notulen-rapat/create', [NotulenRapatController::class, 'create'])->name('notulen-rapat.create');
    Route::post('notulen-rapat', [NotulenRapatController::class, 'store'])->name('notulen-rapat.store');
    Route::get('notulen-rapat/{notulenRapat}', [NotulenRapatController::class, 'show'])->name('notulen-rapat.show');
    Route::get('notulen-rapat/{notulenRapat}/edit', [NotulenRapatController::class, 'edit'])->name('notulen-rapat.edit');
    Route::put('notulen-rapat/{notulenRapat}', [NotulenRapatController::class, 'update'])->name('notulen-rapat.update');
    Route::delete('notulen-rapat/{notulenRapat}', [NotulenRapatController::class, 'destroy'])->name('notulen-rapat.destroy');

    // Struktur RT
    Route::get('struktur-rt', [StrukturRTController::class, 'index'])->name('struktur-rt.show');

    // Pengaturan
    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    Route::get('pengaturan/tata-tertib', [PengaturanController::class, 'tataTertib'])->name('pengaturan.tata-tertib');
    Route::put('pengaturan/tata-tertib', [PengaturanController::class, 'updateTataTertib'])->name('pengaturan.tata-tertib.update');
    Route::get('pengaturan/kelola-pengurus', [PengaturanController::class, 'kelolaPengurus'])->name('pengaturan.kelola-pengurus');
    Route::post('pengaturan/pengurus', [PengaturanController::class, 'storePengurus'])->name('pengaturan.pengurus.store');
    Route::put('pengaturan/pengurus/{id}', [PengaturanController::class, 'updatePengurus'])->name('pengaturan.pengurus.update');
    Route::delete('pengaturan/pengurus/{id}', [PengaturanController::class, 'destroyPengurus'])->name('pengaturan.pengurus.destroy');

    // Pengaduan / Saran
    Route::get('pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
    Route::post('pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
    Route::get('pengaduan/{pengaduan}', [PengaduanController::class, 'show'])->name('pengaduan.show');
    Route::patch('pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])->name('pengaduan.status');
    Route::post('pengaduan/{pengaduan}/balas', [PengaduanController::class, 'balas'])->name('pengaduan.balas');
    Route::delete('pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');

    // Polling Warga
    Route::get('polling', [PollingController::class, 'index'])->name('polling.index');
    Route::get('polling/create', [PollingController::class, 'create'])->name('polling.create');
    Route::post('polling', [PollingController::class, 'store'])->name('polling.store');
    Route::get('polling/{polling}', [PollingController::class, 'show'])->name('polling.show');
    Route::get('polling/{polling}/edit', [PollingController::class, 'edit'])->name('polling.edit');
    Route::put('polling/{polling}', [PollingController::class, 'update'])->name('polling.update');
    Route::post('polling/{polling}/vote', [PollingController::class, 'vote'])->name('polling.vote');
    Route::patch('polling/{polling}/close', [PollingController::class, 'close'])->name('polling.close');
    Route::patch('polling/{polling}/complete', [PollingController::class, 'complete'])->name('polling.complete');
    Route::delete('polling/{polling}', [PollingController::class, 'destroy'])->name('polling.destroy');

    // All Menu Pages
    $pages = [
        'penduduk-pindah',
        'pembayaran',
        'laporan-keuangan',
        'layanan-warga',
        'layanan',
        'surat-menunggu',
        'e-ktp',
        'buat-pengajuan',
        'jadwal-keamanan',
        'patroli-rt',
        'ronda-rapat',
        'video',
        'berita',
        'penghargaan',
        'voting-warga',
        'profil-saya',
    ];

    foreach ($pages as $page) {
        Route::get("/{$page}", [PageController::class, 'show'])->name($page);
    }
});
