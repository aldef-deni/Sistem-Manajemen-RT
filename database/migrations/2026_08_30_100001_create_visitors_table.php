<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kunjungan')->unique();
            $table->string('tipe_kunjungan'); // singkat, menginap
            $table->string('nama_tamu');
            $table->string('nik')->nullable();
            $table->string('no_hp');
            $table->string('email')->nullable();
            $table->string('no_plat')->nullable();
            $table->string('jenis_kendaraan')->nullable(); // Motor, Mobil, Tak Kendaraan
            $table->string('tujuan_blok');
            $table->string('nama_tujuan')->nullable();
            $table->json('kepentingan'); // ['Kunjungan Biasa','Antar Paket',...]
            $table->text('deskripsi_kepentingan')->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->string('foto_dokumentasi')->nullable();
            $table->string('tipe_foto')->nullable(); // Foto Wajah, Foto KTP, etc
            $table->string('wa_host')->nullable();
            $table->string('jam_checkin');
            $table->string('jam_checkout')->nullable();
            $table->date('tanggal');
            $table->string('durasi')->nullable();
            $table->string('status')->default('checkin'); // checkin, checkout, menginap
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
