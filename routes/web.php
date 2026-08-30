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

    // All Menu Pages
    $pages = [
        // Kependudukan
        'penduduk-pindah',
        'pembayaran',
        'laporan-keuangan',
        'arisan-rt',

        // Inventaris
        'data-barang',

        // Dokumentasi
        'layanan-warga',
        'layanan',
        'surat-menunggu',
        'e-ktp',
        'buat-pengajuan',

        // Pengaturan & Info
        'pengaturan',
        'kalender',
        'jadwal-keamanan',

        // Keamanan & Kebersihan
        'patroli-rt',
        'ronda-rapat',
        'struktur-rt',

        // Media
        'video',
        'berita',

        // Apresiasi & Partisipasi
        'penghargaan',
        'voting-warga',

        // Profil
        'profil-saya',
    ];

    foreach ($pages as $page) {
        Route::get("/{$page}", [PageController::class, 'show'])->name($page);
    }
});
