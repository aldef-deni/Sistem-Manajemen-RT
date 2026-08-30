<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabungan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabungan_id')->constrained('tabungan');
            $table->enum('jenis', ['setoran', 'penarikan']);
            $table->decimal('nominal', 15, 2);
            $table->decimal('saldo_sebelum', 15, 2);
            $table->decimal('saldo_sesudah', 15, 2);
            $table->foreignId('rekening_kas_id')->nullable()->constrained('rekening_kas')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'dikonfirmasi', 'ditolak'])->default('dikonfirmasi');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabungan_transaksi');
    }
};
