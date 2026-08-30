<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('bunga_persen', 5, 2)->default(0);
            $table->decimal('denda_persen', 5, 2)->default(0);
            $table->integer('tenor_bulan')->default(12);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_pinjaman');
    }
};
