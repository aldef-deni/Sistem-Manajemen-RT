<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Akun bawaan sistem.
     *
     * Password TIDAK pernah ditulis di berkas ini. Repositori bersifat publik,
     * jadi password bawaan yang tertulis di sini sama saja dengan membuka
     * dashboard RT untuk siapa pun yang membaca kodenya.
     *
     * Akun yang sudah ada tidak pernah ditimpa passwordnya, sehingga seeder
     * aman dijalankan ulang tanpa mengembalikan password yang sudah diganti
     * pengurus lewat dashboard.
     */
    public function run(): void
    {
        $akun = [
            ['email' => 'admin@sistemrt.com',    'name' => 'Administrator', 'username' => 'admin',    'no_hp' => '081234567890', 'role' => 'admin',    'env' => 'ADMIN_PASSWORD'],
            ['email' => 'ketua@sistemrt.com',    'name' => 'Ketua RT',      'username' => 'ketua',    'no_hp' => '081211122233', 'role' => 'ketua',    'env' => 'KETUA_PASSWORD'],
            ['email' => 'pengurus@sistemrt.com', 'name' => 'Pengurus RT',   'username' => 'pengurus', 'no_hp' => '081244455566', 'role' => 'pengurus', 'env' => 'PENGURUS_PASSWORD'],
            ['email' => 'warga@sistemrt.com',    'name' => 'Warga RT',      'username' => 'warga',    'no_hp' => '081298765432', 'role' => 'warga',    'env' => 'WARGA_PASSWORD'],
        ];

        $dibuat = [];

        foreach ($akun as $data) {
            $user = User::firstOrNew(['email' => $data['email']]);

            $user->fill([
                'name'     => $data['name'],
                'username' => $data['username'],
                'no_hp'    => $data['no_hp'],
                'role'     => $data['role'],
            ]);

            if (! $user->exists) {
                $password = env($data['env']) ?: Str::password(16, symbols: false);
                $user->password = Hash::make($password);
                $dibuat[$data['username']] = $password;
            }

            $user->save();
        }

        if ($dibuat === []) {
            $this->command?->info('Akun bawaan sudah ada — password yang berlaku tidak diubah.');

            return;
        }

        $this->command?->warn('Akun baru dibuat. Catat password berikut sekarang, tidak akan ditampilkan lagi:');

        foreach ($dibuat as $username => $password) {
            $this->command?->line(sprintf('  %-9s %s', $username, $password));
        }
    }
}
