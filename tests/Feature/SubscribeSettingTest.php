<?php

namespace Tests\Feature;

use App\Models\SettingRT;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubscribeSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        Storage::fake('local');
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
                'roles' => ['ketua', 'pengurus', 'warga'],
                'harga' => 25000,
                'bank' => 'Bank BCA',
                'nomor_rekening' => '1234567890',
                'nama_rekening' => 'Deni Afrizal',
            ])
            ->assertRedirect(route('subscribe.index'))
            ->assertSessionHas('success');

        $this->assertSame('1', SettingRT::get('subscribe_enabled'));
        $this->assertSame(['ketua', 'pengurus', 'warga'], json_decode(SettingRT::get('subscribe_roles'), true));
        $this->assertSame('25000', SettingRT::get('subscribe_price'));
        $this->assertSame('Bank BCA', SettingRT::get('subscribe_bank'));
        $this->assertSame('1234567890', SettingRT::get('subscribe_account_number'));
        $this->assertSame('Deni Afrizal', SettingRT::get('subscribe_account_name'));
        $this->assertCount(1, json_decode(SettingRT::get('subscribe_payment_methods'), true));
    }

    public function test_data_pembayaran_wajib_saat_subscribe_diaktifkan(): void
    {
        $this->actingAs($this->sebagai('admin'))
            ->from(route('subscribe.index'))
            ->put(route('subscribe.update'), ['status' => 'aktif'])
            ->assertRedirect(route('subscribe.index'))
            ->assertSessionHasErrors(['roles', 'harga', 'payment_methods']);

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

    public function test_administrator_dapat_memilih_satu_atau_beberapa_role(): void
    {
        $this->actingAs($this->sebagai('admin'))
            ->put(route('subscribe.update'), [
                'status' => 'aktif',
                'roles' => ['pengurus', 'warga'],
                'harga' => 50000,
                'bank' => 'Bank Mandiri',
                'nomor_rekening' => '9876543210',
                'nama_rekening' => 'Pengurus RT',
            ])
            ->assertRedirect(route('subscribe.index'));

        $this->assertSame(['pengurus', 'warga'], json_decode(SettingRT::get('subscribe_roles'), true));

        $this->actingAs($this->sebagai('admin'))
            ->get(route('subscribe.index'))
            ->assertOk()
            ->assertSee('value="pengurus"', false)
            ->assertSee('value="warga"', false);
    }

    public function test_administrator_dapat_menyimpan_beberapa_bank_dan_ewallet(): void
    {
        $this->actingAs($this->sebagai('admin'))
            ->put(route('subscribe.update'), [
                'status' => 'aktif',
                'roles' => ['warga'],
                'harga' => 35000,
                'payment_methods' => [
                    [
                        'type' => 'bank',
                        'provider' => 'BCA',
                        'account_number' => '7510466351',
                        'account_name' => 'Deni Afrizal, SE.',
                    ],
                    [
                        'type' => 'ewallet',
                        'provider' => 'DANA',
                        'account_number' => '081234567890',
                        'account_name' => 'Deni Afrizal',
                    ],
                ],
            ])
            ->assertRedirect(route('subscribe.index'))
            ->assertSessionHas('success');

        $methods = json_decode(SettingRT::get('subscribe_payment_methods'), true);

        $this->assertCount(2, $methods);
        $this->assertSame('bank', $methods[0]['type']);
        $this->assertSame('BCA', $methods[0]['provider']);
        $this->assertSame('ewallet', $methods[1]['type']);
        $this->assertSame('DANA', $methods[1]['provider']);
        $this->assertStringStartsWith('pm_', $methods[1]['id']);

        $this->actingAs($this->sebagai('admin'))
            ->get(route('subscribe.index'))
            ->assertOk()
            ->assertSee('Tambah Bank atau E-Wallet')
            ->assertSee('7510466351')
            ->assertSee('081234567890');
    }

    public function test_tujuan_pembayaran_yang_sama_tidak_boleh_duplikat(): void
    {
        $method = [
            'type' => 'ewallet',
            'provider' => 'DANA',
            'account_number' => '081234567890',
            'account_name' => 'Deni Afrizal',
        ];

        $this->actingAs($this->sebagai('admin'))
            ->from(route('subscribe.index'))
            ->put(route('subscribe.update'), [
                'status' => 'aktif',
                'roles' => ['warga'],
                'harga' => 35000,
                'payment_methods' => [$method, $method],
            ])
            ->assertRedirect(route('subscribe.index'))
            ->assertSessionHasErrors('payment_methods');

        $this->assertNull(SettingRT::get('subscribe_enabled'));
    }

    public function test_administrator_dapat_upload_melihat_dan_menghapus_qris(): void
    {
        $payload = [
            'status' => 'aktif',
            'roles' => ['warga'],
            'harga' => 35000,
            'payment_methods' => [[
                'type' => 'bank',
                'provider' => 'BCA',
                'account_number' => '7510466351',
                'account_name' => 'Deni Afrizal, SE.',
                'qris' => UploadedFile::fake()->image('qris-bca.png', 600, 600),
            ]],
        ];

        $this->actingAs($this->sebagai('admin'))
            ->put(route('subscribe.update'), $payload)
            ->assertRedirect(route('subscribe.index'))
            ->assertSessionHas('success');

        $method = json_decode(SettingRT::get('subscribe_payment_methods'), true)[0];
        $this->assertNotNull($method['qris_path']);
        Storage::disk('local')->assertExists($method['qris_path']);

        $this->actingAs($this->sebagai('admin'))
            ->get(route('subscribe.index'))
            ->assertOk()
            ->assertSee('QRIS tersimpan')
            ->assertSee(route('subscribe.payment.qris', ['paymentMethod' => $method['id']]));

        $this->actingAs($this->sebagai('admin'))
            ->get(route('subscribe.payment.qris', ['paymentMethod' => $method['id']]))
            ->assertOk();

        $this->actingAs($this->sebagai('warga'))
            ->get(route('subscribe.payment.index'))
            ->assertOk()
            ->assertSee('QRIS tersedia')
            ->assertSee('Perbesar QRIS');

        $this->actingAs($this->sebagai('admin'))
            ->put(route('subscribe.update'), [
                'status' => 'aktif',
                'roles' => ['warga'],
                'harga' => 35000,
                'payment_methods' => [[
                    'existing_id' => $method['id'],
                    'type' => 'bank',
                    'provider' => 'BCA',
                    'account_number' => '7510466351',
                    'account_name' => 'Deni Afrizal, SE.',
                    'remove_qris' => '1',
                ]],
            ])
            ->assertRedirect(route('subscribe.index'));

        $updatedMethod = json_decode(SettingRT::get('subscribe_payment_methods'), true)[0];
        $this->assertNull($updatedMethod['qris_path']);
        Storage::disk('local')->assertMissing($method['qris_path']);
    }

    public function test_role_administrator_tidak_dapat_dimasukkan_ke_subscribe(): void
    {
        $this->actingAs($this->sebagai('admin'))
            ->from(route('subscribe.index'))
            ->put(route('subscribe.update'), [
                'status' => 'aktif',
                'roles' => ['admin'],
                'harga' => 25000,
                'bank' => 'Bank BCA',
                'nomor_rekening' => '1234567890',
                'nama_rekening' => 'Deni Afrizal',
            ])
            ->assertRedirect(route('subscribe.index'))
            ->assertSessionHasErrors('roles.0');

        $this->assertNull(SettingRT::get('subscribe_enabled'));
        $this->assertNull(SettingRT::get('subscribe_roles'));
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
