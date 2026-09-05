<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menghubungkan akun pengguna dengan data kependudukannya.
     *
     * Tanpa kaitan ini aplikasi mobile tidak bisa menjawab "iuran saya" atau
     * "tabungan saya" — akun dan data warga sebelumnya dua dunia terpisah.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('anggota_keluarga_id')
                ->nullable()
                ->after('role')
                ->constrained('anggota_keluarga')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('anggota_keluarga_id');
        });
    }
};
