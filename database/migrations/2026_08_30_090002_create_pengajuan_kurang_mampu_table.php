<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_kurang_mampu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluarga')->cascadeOnDelete();
            $table->string('nik')->nullable();
            $table->string('no_kk')->nullable();
            $table->decimal('penghasilan_per_bulan', 15, 2)->default(0);
            $table->string('pekerjaan')->nullable();
            $table->integer('jumlah_tanggungan')->default(0);
            $table->string('status_rumah')->default('Milik Sendiri'); // Milik Sendiri, Kontrak, Sewa, Numpang
            $table->string('kondisi_rumah')->default('Baik'); // Baik, Sedang, Rusak, Sangat Rusak
            $table->text('alasan_pengajuan');
            $table->text('keterangan')->nullable();
            $table->string('foto_rumah')->nullable();
            $table->string('status')->default('menunggu'); // menunggu, disetujui, ditolak
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kurang_mampu');
    }
};
