<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sistemrt.com'],
            [
                'name'     => 'Administrator',
                'username' => 'admin',
                'no_hp'    => '081234567890',
                'role'     => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'warga@sistemrt.com'],
            [
                'name'     => 'Warga RT',
                'username' => 'warga',
                'no_hp'    => '081298765432',
                'role'     => 'warga',
                'password' => Hash::make('password'),
            ]
        );
    }
}
