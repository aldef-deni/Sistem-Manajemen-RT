<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rencana_pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rencana')->unique();
            $table->string('nama_barang');
            $table->string('kategori');
            $table->integer('jumlah')->default(1);
            $table->string('satuan')->default('unit');
            $table->enum('prioritas', ['tinggi', 'sedang', 'rendah'])->default('sedang');
            $table->decimal('estimasi_harga', 15, 2)->default(0);
            $table->string('sumber_dana')->nullable();
            $table->date('tanggal_rencana');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['direncanakan', 'disetujui', 'terbeli', 'hibah', 'dibatalkan'])->default('direncanakan');
            $table->foreignId('barang_id')->nullable()->constrained('barang')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rencana_pembelian');
    }
};
