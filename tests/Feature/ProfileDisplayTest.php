<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\User;
use App\Support\SafeUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfileDisplayTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> */
    private array $uploadedPaths = [];

    protected function tearDown(): void
    {
        foreach ($this->uploadedPaths as $path) {
            SafeUpload::delete($path);
        }

        parent::tearDown();
    }

    public function test_status_profil_membaca_relasi_warga_bukan_kemiripan_nomor_hp(): void
    {
        $family = KartuKeluarga::create([
            'no_kk' => '3273010101010101',
            'alamat' => 'Alamat pengujian',
        ]);
        $resident = AnggotaKeluarga::create([
            'kartu_keluarga_id' => $family->id,
            'nik' => '3273010101010102',
            'nama_lengkap' => 'Fajar Nugroho',
            'no_hp' => '081111116012',
            'status_hubungan' => 'Kepala Keluarga',
        ]);
        $user = User::factory()->create([
            'name' => 'Fajar Nugroho',
            'username' => 'fajar-profile-test',
            'role' => 'warga',
            'no_hp' => '081277774623',
            'anggota_keluarga_id' => $resident->id,
        ]);

        $this->actingAs($user)
            ->get(route('profil-saya'))
            ->assertOk()
            ->assertSee('Akun terhubung dengan data warga')
            ->assertSee('Fajar Nugroho')
            ->assertDontSee('Akun ini belum terhubung dengan data warga');
    }

    public function test_foto_baru_tampil_pada_avatar_header(): void
    {
        $user = User::factory()->create([
            'name' => 'Warga Berfoto',
            'username' => 'warga-berfoto',
            'role' => 'warga',
        ]);

        $this->actingAs($user)
            ->post(route('profil.foto'), [
                'foto' => UploadedFile::fake()->image('foto-baru.jpg', 320, 320),
            ])
            ->assertRedirect();

        $user->refresh();
        $this->uploadedPaths[] = $user->foto;

        $this->actingAs($user)
            ->get(route('profil-saya'))
            ->assertOk()
            ->assertSee('data-header-avatar', false)
            ->assertSee($user->foto_url.'?v='.$user->updated_at->timestamp, false)
            ->assertSee('Foto profil Warga Berfoto');
    }
}
