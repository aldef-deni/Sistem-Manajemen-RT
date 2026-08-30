<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arisan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('nominal_iuran', 15, 2);
            $table->enum('periode', ['mingguan', 'bulanan'])->default('bulanan');
            $table->date('tanggal_mulai');
            $table->enum('mode_undian', ['manual', 'otomatis'])->default('manual');
            $table->integer('jumlah_pemenang_per_pertemuan')->default(1);
            $table->foreignId('rekening_kas_id')->nullable()->constrained('rekening_kas')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arisan');
    }
};
