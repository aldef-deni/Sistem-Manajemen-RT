<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('kategori', 50); // Umum, Keuangan, Keamanan, Kebersihan, Kegiatan, Darurat, Lainnya
            $table->string('target', 50)->default('semua'); // semua, rt, rw, per_blok, warga_tertentu
            $table->text('isi');
            $table->date('tanggal_publish');
            $table->date('tanggal_berakhir')->nullable();
            $table->string('lampiran')->nullable(); // file path
            $table->string('status')->default('draft'); // draft, publish
            $table->integer('dilihat')->default(0);
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
