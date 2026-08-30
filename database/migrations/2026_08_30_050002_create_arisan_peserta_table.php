<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arisan_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arisan_id')->constrained('arisan')->cascadeOnDelete();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluarga');
            $table->integer('urutan')->nullable();
            $table->boolean('sudah_dapat')->default(false);
            $table->date('tanggal_dapat')->nullable();
            $table->timestamps();

            $table->unique(['arisan_id', 'anggota_keluarga_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arisan_peserta');
    }
};
