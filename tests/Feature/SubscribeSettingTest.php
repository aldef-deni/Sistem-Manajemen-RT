<?php

namespace Tests\Feature;

use App\Models\SettingRT;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function sebagai(string $role): User
    {
        return User::where('role', $role)->firstOrFail();
    }

    public function test_hanya_administrator_dapat_membuka_pengaturan_subscribe(): void
    {
        $this->actingAs($this->sebagai('admin'))
            ->get(route('subscribe.index'))
            ->assertOk()
            ->assertSee('Pengaturan Subscribe')
            ->assertSee('Tidak Aktif');

        foreach (['ketua', 'pengurus', 'warga'] as $role) {
            $this->actingAs($this->sebagai($role))
                ->get(route('subscribe.index'))
                ->assertForbidden();
        }
    }

    public function test_administrator_dapat_mengaktifkan_dan_menyimpan_pengaturan_subscribe(): void
    {
        $this->actingAs($this->sebagai('admin'))
            ->put(route('subscribe.update'), [
                'status' => 'aktif',
                'harga' => 25000,
                'bank' => 'Bank BCA',
                'nomor_rekening' => '1234567890',
                'nama_rekening' => 'Deni Afrizal',
            ])
            ->assertRedirect(route('subscribe.index'))
            ->assertSessionHas('success');

        $this->assertSame('1', SettingRT::get('subscribe_enabled'));
        $this->assertSame('25000', SettingRT::get('subscribe_price'));
        $this->assertSame('Bank BCA', SettingRT::get('subscribe_bank'));
        $this->assertSame('1234567890', SettingRT::get('subscribe_account_number'));
        $this->assertSame('Deni Afrizal', SettingRT::get('subscribe_account_name'));
    }

    public function test_data_pembayaran_wajib_saat_subscribe_diaktifkan(): void
    {
        $this->actingAs($this->sebagai('admin'))
            ->from(route('subscribe.index'))
            ->put(route('subscribe.update'), ['status' => 'aktif'])
            ->assertRedirect(route('subscribe.index'))
            ->assertSessionHasErrors(['harga', 'bank', 'nomor_rekening', 'nama_rekening']);

        $this->assertNull(SettingRT::get('subscribe_enabled'));
    }

    public function test_administrator_dapat_menonaktifkan_subscribe(): void
    {
        SettingRT::set('subscribe_enabled', '1');

        $this->actingAs($this->sebagai('admin'))
            ->put(route('subscribe.update'), [
                'status' => 'nonaktif',
                'harga' => 25000,
                'bank' => 'Bank BCA',
                'nomor_rekening' => '1234567890',
                'nama_rekening' => 'Deni Afrizal',
            ])
            ->assertRedirect(route('subscribe.index'));

        $this->assertSame('0', SettingRT::get('subscribe_enabled'));
    }

    public function test_menu_subscribe_hanya_tampil_untuk_administrator(): void
    {
        $this->actingAs($this->sebagai('admin'))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Subscribe');

        $this->actingAs($this->sebagai('ketua'))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Subscribe');
    }
}
