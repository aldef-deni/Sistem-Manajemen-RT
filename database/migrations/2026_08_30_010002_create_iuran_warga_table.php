<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iuran_warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluarga');
            $table->foreignId('jenis_iuran_id')->constrained('jenis_iuran');
            $table->integer('bulan'); // 1-12
            $table->integer('tahun');
            $table->decimal('nominal', 12, 0)->default(0);
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->date('tanggal_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['anggota_keluarga_id', 'jenis_iuran_id', 'bulan', 'tahun'], 'unique_iuran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iuran_warga');
    }
};
