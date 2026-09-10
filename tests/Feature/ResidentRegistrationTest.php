<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\User;
use Database\Seeders\KartuKeluargaSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ResidentRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_menyediakan_tautan_pendaftaran_warga(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Daftar akun Warga')
            ->assertSee(route('register.resident.identity'));
    }

    public function test_verifikasi_hanya_menerima_nik_dan_nomor_kk_yang_cocok(): void
    {
        $this->resident('3273010101010001', '3273010101010002');

        $this->post(route('register.resident.verify'), [
            'nik' => '3273010101010001',
            'no_kk' => '3273010101019999',
        ])
            ->assertSessionHasErrors('identity')
            ->assertSessionMissing('resident_registration')
            ->assertSessionMissing('_old_input');
    }

    public function test_anggota_yang_bukan_kepala_keluarga_tidak_dapat_mendaftar(): void
    {
        $this->resident('3273010101010003', '3273010101010004', 'Anak');

        $this->post(route('register.resident.verify'), [
            'nik' => '3273010101010003',
            'no_kk' => '3273010101010004',
        ])
            ->assertSessionHasErrors('identity')
            ->assertSessionMissing('resident_registration');
    }

    public function test_kepala_keluarga_dapat_membuat_akun_warga_yang_langsung_tertaut(): void
    {
        $member = $this->resident('3273010101010005', '3273010101010006');

        $this->post(route('register.resident.verify'), [
            'nik' => $member->nik,
            'no_kk' => $member->kartuKeluarga->no_kk,
        ])->assertRedirect(route('register.resident.account'));

        $this->get(route('register.resident.account'))
            ->assertOk()
            ->assertSee($member->nama_lengkap)
            ->assertDontSee($member->nik)
            ->assertDontSee($member->kartuKeluarga->no_kk);

        $response = $this->post(route('register.resident.store'), [
            'name' => 'Nama yang Disuntikkan',
            'role' => 'admin',
            'username' => 'warga-mandiri',
            'email' => 'warga.mandiri@example.test',
            'no_hp' => '081234567890',
            'password' => 'rahasia-aman',
            'password_confirmation' => 'rahasia-aman',
        ]);

        $user = User::where('username', 'warga-mandiri')->firstOrFail();

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame($member->nama_lengkap, $user->name);
        $this->assertSame('warga', $user->role);
        $this->assertSame($member->id, $user->anggota_keluarga_id);
        $this->assertTrue(Hash::check('rahasia-aman', $user->password));
        $this->assertFalse(session()->has('resident_registration'));
    }

    public function test_data_kepala_keluarga_yang_sudah_tertaut_tidak_dapat_mendaftar_lagi(): void
    {
        $member = $this->resident('3273010101010007', '3273010101010008');
        User::factory()->create([
            'name' => $member->nama_lengkap,
            'username' => 'sudah-tertaut',
            'role' => 'warga',
            'anggota_keluarga_id' => $member->id,
        ]);

        $this->post(route('register.resident.verify'), [
            'nik' => $member->nik,
            'no_kk' => $member->kartuKeluarga->no_kk,
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('status');
    }

    public function test_verifikasi_tidak_dapat_dilewati_dan_berakhir_setelah_lima_belas_menit(): void
    {
        $this->get(route('register.resident.account'))
            ->assertRedirect(route('register.resident.identity'));

        $member = $this->resident('3273010101010009', '3273010101010010');
        $this->post(route('register.resident.verify'), [
            'nik' => $member->nik,
            'no_kk' => $member->kartuKeluarga->no_kk,
        ])->assertRedirect(route('register.resident.account'));

        $this->travel(16)->minutes();

        $this->get(route('register.resident.account'))
            ->assertRedirect(route('register.resident.identity'))
            ->assertSessionHasErrors('identity');
    }

    public function test_admin_menghubungkan_warga_ke_kepala_keluarga_dan_pengelola_ke_data_dirinya(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-penghubung',
            'role' => 'admin',
        ]);
        $head = $this->resident('3273010101010011', '3273010101010012');
        $member = $this->resident('3273010101010013', '3273010101010014', 'Anak');

        $this->actingAs($admin)->post(route('akun.store'), [
            'name' => 'Akun Warga Anak',
            'username' => 'warga-anak',
            'email' => 'warga.anak@example.test',
            'role' => 'warga',
            'anggota_keluarga_id' => $member->id,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('anggota_keluarga_id');

        $this->actingAs($admin)->post(route('akun.store'), [
            'name' => 'Akun Warga Kepala',
            'username' => 'warga-kepala',
            'email' => 'warga.kepala@example.test',
            'role' => 'warga',
            'anggota_keluarga_id' => $head->id,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('akun.index'));

        $this->actingAs($admin)->post(route('akun.store'), [
            'name' => 'Pengurus Tertaut',
            'username' => 'pengurus-tertaut',
            'email' => 'pengurus.tertaut@example.test',
            'role' => 'pengurus',
            'anggota_keluarga_id' => $member->id,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('akun.index'));

        $this->assertDatabaseHas('users', [
            'username' => 'warga-kepala',
            'role' => 'warga',
            'anggota_keluarga_id' => $head->id,
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'pengurus-tertaut',
            'role' => 'pengurus',
            'anggota_keluarga_id' => $member->id,
        ]);
    }

    public function test_database_menolak_satu_data_warga_dipakai_dua_akun(): void
    {
        $member = $this->resident('3273010101010015', '3273010101010016');
        User::factory()->create([
            'username' => 'tautan-pertama',
            'anggota_keluarga_id' => $member->id,
        ]);

        $this->expectException(QueryException::class);

        User::factory()->create([
            'username' => 'tautan-kedua',
            'anggota_keluarga_id' => $member->id,
        ]);
    }

    public function test_data_kk_baru_langsung_dapat_dipakai_kepala_keluarga_untuk_mendaftar(): void
    {
        $ketua = User::factory()->create([
            'username' => 'ketua-input-kk',
            'role' => 'ketua',
        ]);

        $this->actingAs($ketua)->post(route('kartu-keluarga.store'), [
            'no_kk' => '3273010101010020',
            'alamat' => 'Jalan Pendaftaran Langsung',
            'rt' => '001',
            'rw' => '002',
            'anggota' => [[
                'nik' => '3273010101010021',
                'nama_lengkap' => 'Kepala Keluarga Baru',
                'jenis_kelamin' => 'L',
                'status_hubungan' => 'Kepala Keluarga',
                'domisili' => 'Tetap',
            ]],
        ])->assertRedirect(route('kartu-keluarga.index'));

        $member = AnggotaKeluarga::where('nik', '3273010101010021')->firstOrFail();
        $this->assertNull($member->akun, 'Input KK tidak boleh membuat akun atau password bawaan secara diam-diam.');

        auth()->logout();
        $this->app['auth']->forgetGuards();

        $this->post(route('register.resident.verify'), [
            'nik' => $member->nik,
            'no_kk' => '3273010101010020',
        ])->assertRedirect(route('register.resident.account'));

        $this->post(route('register.resident.store'), [
            'username' => 'kepala-baru',
            'email' => 'kepala.baru@example.test',
            'password' => 'rahasia-aman',
            'password_confirmation' => 'rahasia-aman',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'username' => 'kepala-baru',
            'role' => 'warga',
            'anggota_keluarga_id' => $member->id,
        ]);
    }

    public function test_form_kk_menolak_identitas_yang_bukan_16_digit_dan_nik_ganda(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-validasi-identitas',
            'role' => 'admin',
        ]);

        $payload = [
            'no_kk' => '32730101010100220',
            'alamat' => 'Jalan Identitas Tidak Valid',
            'anggota' => [[
                'nik' => '327301010101002A',
                'nama_lengkap' => 'Identitas Tidak Valid',
                'status_hubungan' => 'Kepala Keluarga',
            ]],
        ];

        $this->actingAs($admin)
            ->post(route('kartu-keluarga.store'), $payload)
            ->assertSessionHasErrors(['no_kk', 'anggota.0.nik']);

        $payload['no_kk'] = '3273010101010022';
        $payload['anggota'] = [
            [
                'nik' => '3273010101010023',
                'nama_lengkap' => 'Kepala Duplikat',
                'status_hubungan' => 'Kepala Keluarga',
            ],
            [
                'nik' => '3273010101010023',
                'nama_lengkap' => 'Anak Duplikat',
                'status_hubungan' => 'Anak',
            ],
        ];

        $this->actingAs($admin)
            ->post(route('kartu-keluarga.store'), $payload)
            ->assertSessionHasErrors(['anggota.1.nik']);
    }

    public function test_edit_kk_menolak_nik_yang_sudah_dipakai_warga_lain(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-edit-identitas',
            'role' => 'admin',
        ]);
        $first = $this->resident('3273010101010024', '3273010101010025');
        $second = $this->resident('3273010101010026', '3273010101010027');

        $this->actingAs($admin)->put(route('kartu-keluarga.update', $second->kartuKeluarga), [
            'no_kk' => $second->kartuKeluarga->no_kk,
            'alamat' => $second->kartuKeluarga->alamat,
            'anggota' => [[
                'id' => $second->id,
                'nik' => $first->nik,
                'nama_lengkap' => $second->nama_lengkap,
                'status_hubungan' => 'Kepala Keluarga',
            ]],
        ])->assertSessionHasErrors('anggota.0.nik');

        $this->assertSame('3273010101010026', $second->fresh()->nik);
    }

    public function test_data_contoh_memakai_nik_dan_nomor_kk_16_digit_yang_unik(): void
    {
        $this->seed(KartuKeluargaSeeder::class);

        $niks = AnggotaKeluarga::pluck('nik');
        $familyNumbers = KartuKeluarga::pluck('no_kk');

        $this->assertNotEmpty($niks);
        $this->assertCount($niks->count(), $niks->unique());
        $this->assertTrue($niks->every(fn ($nik) => preg_match('/^\d{16}$/D', $nik) === 1));
        $this->assertTrue($familyNumbers->every(fn ($number) => preg_match('/^\d{16}$/D', $number) === 1));
    }

    public function test_migrasi_menormalkan_nik_data_contoh_lama_dan_memasang_constraint_unik(): void
    {
        Schema::table('anggota_keluarga', function ($table) {
            $table->dropUnique('anggota_keluarga_nik_unique');
        });

        $legacy = $this->resident('33150100000000021', '3315010000000002');
        $migration = require database_path('migrations/2026_09_10_000004_normalize_resident_identifiers.php');
        $migration->up();

        $this->assertSame('3315010000000021', $legacy->fresh()->nik);

        $this->expectException(QueryException::class);
        AnggotaKeluarga::create([
            'kartu_keluarga_id' => $legacy->kartu_keluarga_id,
            'nik' => '3315010000000021',
            'nama_lengkap' => 'NIK Duplikat',
            'status_hubungan' => 'Anak',
        ]);
    }

    public function test_detail_kk_menampilkan_status_akun_login_sebenarnya(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-status-akun',
            'role' => 'admin',
        ]);
        $member = $this->resident('3273010101010028', '3273010101010029');

        $this->actingAs($admin)
            ->get(route('kartu-keluarga.show', $member->kartuKeluarga))
            ->assertOk()
            ->assertSee('Belum daftar')
            ->assertDontSee('Username = NIK');

        User::factory()->create([
            'name' => $member->nama_lengkap,
            'username' => 'kepala-tertaut',
            'role' => 'warga',
            'anggota_keluarga_id' => $member->id,
        ]);

        $this->actingAs($admin)
            ->get(route('kartu-keluarga.show', $member->kartuKeluarga))
            ->assertOk()
            ->assertSee('Tertaut')
            ->assertSee('kepala-tertaut');
    }

    private function resident(string $nik, string $noKk, string $status = 'Kepala Keluarga'): AnggotaKeluarga
    {
        $family = KartuKeluarga::create([
            'no_kk' => $noKk,
            'rt' => '001',
            'rw' => '002',
            'alamat' => 'Jalan Pendaftaran Warga',
        ]);

        return AnggotaKeluarga::create([
            'kartu_keluarga_id' => $family->id,
            'nik' => $nik,
            'nama_lengkap' => 'Warga Uji '.substr($nik, -4),
            'no_hp' => '081234567890',
            'jenis_kelamin' => 'L',
            'status_hubungan' => $status,
            'domisili' => 'Tetap',
            'role' => 'Warga',
        ]);
    }
}
