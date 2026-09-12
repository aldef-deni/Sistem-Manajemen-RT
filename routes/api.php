<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdministrasiController;
use App\Http\Controllers\Api\BerandaController;
use App\Http\Controllers\Api\ChatApiController;
use App\Http\Controllers\Api\InformasiController;
use App\Http\Controllers\Api\KelolaController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\ProfilApiController;
use App\Http\Controllers\Api\ResidentRegistrationApiController;
use App\Http\Controllers\Api\SubscribeApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API untuk aplikasi mobile
|--------------------------------------------------------------------------
| Memakai token Sanctum. Seluruh balasan berbahasa Indonesia supaya aplikasi
| bisa menampilkannya langsung tanpa penerjemahan.
*/

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('daftar/verifikasi', [ResidentRegistrationApiController::class, 'verify'])->middleware('throttle:10,1');
Route::post('daftar', [ResidentRegistrationApiController::class, 'store'])->middleware('throttle:6,1');

Route::middleware(['auth:sanctum', 'subscription.access'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('saya', [AuthController::class, 'saya']);
    Route::put('saya', [ProfilApiController::class, 'perbarui']);
    Route::put('saya/password', [ProfilApiController::class, 'gantiPassword']);
    Route::post('saya/foto', [ProfilApiController::class, 'gantiFoto']);

    // Fitur lintas halaman. Ketiganya tetap dapat dibuka ketika akses berbayar
    // sedang terkunci agar pengguna dapat membayar dan menghubungi pengurus.
    Route::get('notifikasi', [NotificationApiController::class, 'index']);
    Route::patch('notifikasi/{notification}/baca', [NotificationApiController::class, 'markRead']);
    Route::post('notifikasi/baca-semua', [NotificationApiController::class, 'markAllRead']);

    Route::get('subscribe', [SubscribeApiController::class, 'index']);
    Route::post('subscribe/pembayaran', [SubscribeApiController::class, 'store']);
    Route::get('subscribe/qris/{paymentMethod}', [SubscribeApiController::class, 'qris']);

    Route::middleware('role:admin')->prefix('subscribe')->group(function () {
        Route::get('verifikasi', [SubscribeApiController::class, 'verifications']);
        Route::get('verifikasi/{payment}/bukti', [SubscribeApiController::class, 'proof']);
        Route::patch('verifikasi/{payment}/aktifkan', [SubscribeApiController::class, 'approve']);
        Route::patch('verifikasi/{payment}/tolak', [SubscribeApiController::class, 'reject']);
    });

    Route::middleware('role:admin,ketua,pengurus,warga')->prefix('chat')->group(function () {
        Route::get('/', [ChatApiController::class, 'index']);
        Route::post('pribadi', [ChatApiController::class, 'storePrivate']);
        Route::post('grup', [ChatApiController::class, 'storeGroup']);
        Route::get('{conversation}', [ChatApiController::class, 'show']);
        Route::get('{conversation}/pesan', [ChatApiController::class, 'messages']);
        Route::post('{conversation}/pesan', [ChatApiController::class, 'storeMessage']);
        Route::put('{conversation}/grup', [ChatApiController::class, 'updateGroup']);
        Route::post('{conversation}/keluar', [ChatApiController::class, 'leave']);
    });

    Route::get('beranda', [BerandaController::class, 'index']);

    // Informasi
    Route::get('pengumuman', [InformasiController::class, 'pengumuman']);
    Route::get('pengumuman/{pengumuman}', [InformasiController::class, 'pengumumanDetail']);
    Route::get('kegiatan', [InformasiController::class, 'kegiatan']);
    Route::get('kegiatan/{kegiatan}', [InformasiController::class, 'kegiatanDetail']);
    Route::get('jadwal', [InformasiController::class, 'jadwal']);
    Route::get('struktur-rt', [InformasiController::class, 'strukturRT']);
    Route::get('umkm', [InformasiController::class, 'umkm']);

    // Layanan mandiri
    Route::get('iuran-saya', [LayananController::class, 'iuranSaya']);
    Route::get('pengaduan', [LayananController::class, 'pengaduan']);
    Route::post('pengaduan', [LayananController::class, 'kirimPengaduan']);
    Route::get('pengaduan/{pengaduan}', [LayananController::class, 'pengaduanDetail']);
    Route::get('polling', [LayananController::class, 'polling']);
    Route::post('polling/{polling}/pilih', [LayananController::class, 'pilihPolling']);

    /*
    |----------------------------------------------------------------------
    | Pengurus RT ke atas
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,ketua,pengurus')->prefix('kelola')->group(function () {
        Route::get('ringkasan', [KelolaController::class, 'ringkasan']);
        Route::get('warga', [KelolaController::class, 'warga']);
        Route::get('kas', [KelolaController::class, 'kas']);
        Route::get('iuran', [KelolaController::class, 'iuran']);
        Route::patch('iuran/{iuran}/lunas', [KelolaController::class, 'tandaiLunas']);
        Route::patch('pengaduan/{pengaduan}/status', [KelolaController::class, 'ubahStatusPengaduan']);
        Route::post('pengaduan/{pengaduan}/balas', [KelolaController::class, 'balasPengaduan']);
    });

    /*
    |----------------------------------------------------------------------
    | Administrasi akun — Administrator & Ketua RT
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,ketua')->prefix('kelola')->group(function () {
        Route::get('warga/opsi', [AdministrasiController::class, 'wargaOptions']);
        Route::post('warga', [AdministrasiController::class, 'storeWarga']);
        Route::put('warga/{warga}', [AdministrasiController::class, 'updateWarga']);
        Route::delete('warga/{warga}', [AdministrasiController::class, 'destroyWarga']);

        Route::post('kas/transaksi', [AdministrasiController::class, 'storeTransaksiKas']);
        Route::put('kas/transaksi/{transaksi}', [AdministrasiController::class, 'updateTransaksiKas']);
        Route::delete('kas/transaksi/{transaksi}', [AdministrasiController::class, 'destroyTransaksiKas']);

        Route::get('iuran/opsi', [AdministrasiController::class, 'iuranOptions']);
        Route::post('iuran', [AdministrasiController::class, 'storeIuran']);
        Route::put('iuran/{iuran}', [AdministrasiController::class, 'updateIuran']);
        Route::delete('iuran/{iuran}', [AdministrasiController::class, 'destroyIuran']);

        Route::get('akun', [KelolaController::class, 'akun']);
        Route::get('akun/opsi', [AdministrasiController::class, 'akunOptions']);
        Route::post('akun', [AdministrasiController::class, 'storeAkun']);
        Route::put('akun/{akun}', [AdministrasiController::class, 'updateAkun']);
        Route::delete('akun/{akun}', [AdministrasiController::class, 'destroyAkun']);
        Route::patch('akun/{akun}/peran', [KelolaController::class, 'ubahPeran']);
        Route::patch('akun/{akun}/reset-password', [KelolaController::class, 'resetPassword']);
        Route::delete('pengaduan/{pengaduan}', [AdministrasiController::class, 'destroyPengaduan']);
    });
});
