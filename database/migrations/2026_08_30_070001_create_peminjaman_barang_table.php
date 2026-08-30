<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('barang_id')->constrained('barang');
            $table->integer('jumlah_pinjam')->default(1);
            $table->string('kondisi_saat_pinjam')->default('Baik');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_rencana_kembali');
            $table->date('tanggal_kembali')->nullable();
            $table->text('keperluan')->nullable();
            $table->string('nama_peminjam');
            $table->string('no_hp_peminjam')->nullable();
            $table->foreignId('anggota_keluarga_id')->nullable()->constrained('anggota_keluarga')->nullOnDelete();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->string('kondisi_saat_kembali')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barang');
    }
};
