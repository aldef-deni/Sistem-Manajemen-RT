<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $members = DB::table('anggota_keluarga')
                ->where('nik', 'like', '331501000000000%')
                ->get(['id', 'nik']);

            foreach ($members as $member) {
                $oldNik = (string) $member->nik;

                if (strlen($oldNik) !== 17) {
                    continue;
                }

                // Data contoh lama dibentuk dari Nomor KK 16 digit ditambah
                // nomor anggota. Buang digit pengisi pada posisi ke-15 saja.
                $newNik = substr($oldNik, 0, 14).substr($oldNik, 15);

                $collision = DB::table('anggota_keluarga')
                    ->where('nik', $newNik)
                    ->where('id', '!=', $member->id)
                    ->exists();

                if ($collision) {
                    throw new RuntimeException('Normalisasi NIK dibatalkan karena ditemukan NIK tujuan yang sudah dipakai.');
                }

                DB::table('anggota_keluarga')->where('id', $member->id)->update(['nik' => $newNik]);

                foreach (['penerima_bantuan', 'pengajuan_kurang_mampu', 'surat', 'visitors'] as $table) {
                    if (Schema::hasTable($table) && Schema::hasColumn($table, 'nik')) {
                        DB::table($table)->where('nik', $oldNik)->update(['nik' => $newNik]);
                    }
                }
            }

            $identifiers = DB::table('anggota_keluarga')->pluck('nik');
            $invalid = $identifiers->first(fn ($nik) => preg_match('/^\d{16}$/D', (string) $nik) !== 1);

            if ($invalid !== null) {
                throw new RuntimeException('Migrasi dihentikan: masih ada NIK data warga yang bukan 16 digit angka.');
            }

            if ($identifiers->duplicates()->isNotEmpty()) {
                throw new RuntimeException('Migrasi dihentikan: ditemukan NIK data warga yang sama.');
            }
        });

        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->unique('nik', 'anggota_keluarga_nik_unique');
        });
    }

    public function down(): void
    {
        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->dropUnique('anggota_keluarga_nik_unique');
        });

        // Data identitas yang sudah dibetulkan sengaja tidak dibuat salah lagi.
    }
};
