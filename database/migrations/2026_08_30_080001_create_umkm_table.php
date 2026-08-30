<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('anggota_keluarga_id')->nullable()->constrained('anggota_keluarga')->nullOnDelete();
            $table->string('nama_usaha');
            $table->string('kategori'); // Kuliner, Fashion, Jasa, Pertanian, Kerajinan, Teknologi, Kesehatan, Pendidikan, Lainnya
            $table->text('deskripsi_usaha');
            $table->text('produk_layanan')->nullable();
            $table->string('alamat_lokasi')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('foto_usaha')->nullable();
            $table->string('status')->default('aktif'); // aktif, nonaktif, pending_review
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm');
    }
};
