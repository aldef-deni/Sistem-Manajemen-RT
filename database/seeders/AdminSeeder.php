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

        User::updateOrCreate(
            ['email' => 'ketua@sistemrt.com'],
            [
                'name'     => 'Ketua RT',
                'username' => 'ketua',
                'no_hp'    => '081211122233',
                'role'     => 'ketua',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'pengurus@sistemrt.com'],
            [
                'name'     => 'Pengurus RT',
                'username' => 'pengurus',
                'no_hp'    => '081244455566',
                'role'     => 'pengurus',
                'password' => Hash::make('password'),
            ]
        );
    }
}
