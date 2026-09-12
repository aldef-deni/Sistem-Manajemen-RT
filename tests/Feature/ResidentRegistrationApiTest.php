<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResidentRegistrationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_kepala_keluarga_dapat_mendaftar_mandiri_dan_pengurus_menerima_notifikasi(): void
    {
        [$kepala, $admin, $ketua] = $this->registrationFixture();

        $verification = $this->postJson('/api/daftar/verifikasi', [
            'nik' => $kepala->nik,
            'no_kk' => $kepala->kartuKeluarga->no_kk,
        ])->assertOk()->assertJsonStructure(['token_verifikasi', 'nama', 'berlaku_menit']);

        $this->postJson('/api/daftar', [
            'token_verifikasi' => $verification->json('token_verifikasi'),
            'username' => 'keluarga-mandiri',
            'email' => 'keluarga.mandiri@sistemrt.test',
            'no_hp' => '081234567890',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertCreated()->assertJsonPath('username', 'keluarga-mandiri');

        $this->assertDatabaseHas('users', [
            'username' => 'keluarga-mandiri',
            'role' => 'warga',
            'anggota_keluarga_id' => $kepala->id,
        ]);
        $this->assertSame('resident', $admin->notifications()->firstOrFail()->data['category']);
        $this->assertSame('resident', $ketua->notifications()->firstOrFail()->data['category']);

        $this->postJson('/api/daftar', [
            'token_verifikasi' => $verification->json('token_verifikasi'),
            'username' => 'percobaan-kedua',
            'email' => 'kedua@sistemrt.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertUnprocessable()->assertJsonValidationErrors('identity');
    }

    public function test_anggota_non_kepala_keluarga_tidak_lolos_verifikasi(): void
    {
        [$kepala] = $this->registrationFixture();
        $anak = AnggotaKeluarga::create([
            'kartu_keluarga_id' => $kepala->kartu_keluarga_id,
            'nik' => '3301020304050608',
            'nama_lengkap' => 'Anak Keluarga Uji',
            'jenis_kelamin' => 'P',
            'status_hubungan' => 'Anak',
            'domisili' => 'Tetap',
            'role' => 'Warga',
        ]);

        $this->postJson('/api/daftar/verifikasi', [
            'nik' => $anak->nik,
            'no_kk' => $kepala->kartuKeluarga->no_kk,
        ])->assertUnprocessable()->assertJsonValidationErrors('identity');
    }

    /** @return array{AnggotaKeluarga, User, User} */
    private function registrationFixture(): array
    {
        $kk = KartuKeluarga::create([
            'no_kk' => '3301020304050607',
            'alamat' => 'Jalan Pengujian Nomor 7',
        ]);
        $kepala = AnggotaKeluarga::create([
            'kartu_keluarga_id' => $kk->id,
            'nik' => '3301020304050607',
            'nama_lengkap' => 'Kepala Keluarga Uji',
            'no_hp' => '081111111111',
            'jenis_kelamin' => 'L',
            'status_hubungan' => 'Kepala Keluarga',
            'domisili' => 'Tetap',
            'role' => 'Warga',
        ]);
        $admin = User::create([
            'name' => 'Administrator Uji',
            'username' => 'admin-uji-registrasi',
            'email' => 'admin.registrasi@sistemrt.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $ketua = User::create([
            'name' => 'Ketua Uji',
            'username' => 'ketua-uji-registrasi',
            'email' => 'ketua.registrasi@sistemrt.test',
            'password' => Hash::make('password'),
            'role' => 'ketua',
        ]);

        return [$kepala->load('kartuKeluarga'), $admin, $ketua];
    }
}
