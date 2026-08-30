<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->string('kategori'); // Elektronik, Perlengkapan, Furniture, ATK, Lainnya
            $table->string('kondisi')->default('Baik'); // Baik, Rusak Ringan, Rusak Berat, Perlu Perbaikan
            $table->integer('jumlah')->default(1);
            $table->string('satuan')->default('unit'); // unit, pcs, set, bh
            $table->string('lokasi')->nullable(); // Gudang, Ruang Rapat, dll
            $table->date('tanggal_pembelian')->nullable();
            $table->decimal('harga_pembelian', 15, 2)->default(0);
            $table->string('sumber_dana')->nullable(); // Kas RT, Iuran Khusus, Donasi
            $table->text('keterangan')->nullable();
            $table->string('foto_utama')->nullable();
            $table->json('foto_gallery')->nullable();
            $table->enum('status', ['aktif', 'dipinjam', 'dihapus'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
