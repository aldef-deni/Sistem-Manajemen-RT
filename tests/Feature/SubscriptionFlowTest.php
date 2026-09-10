<?php

namespace Tests\Feature;

use App\Models\SettingRT;
use App\Models\SubscribePayment;
use App\Models\User;
use App\Support\SubscriptionPaymentMethods;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionFlowTest extends TestCase
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

    /** @param list<string> $roles */
    private function aktifkanUntuk(array $roles): void
    {
        SettingRT::set('subscribe_enabled', '1');
        SettingRT::set('subscribe_roles', json_encode($roles, JSON_THROW_ON_ERROR));
        SettingRT::set('subscribe_price', '25000');
        SettingRT::set('subscribe_bank', 'Bank BCA');
        SettingRT::set('subscribe_account_number', '1234567890');
        SettingRT::set('subscribe_account_name', 'Pengelola RT');
    }

    private function kirimPembayaran(User $user): SubscribePayment
    {
        $this->actingAs($user)
            ->post(route('subscribe.payment.store'), [
                'sender_bank' => 'Bank BRI',
                'sender_account_name' => $user->name,
                'paid_at' => now()->toDateString(),
                'proof' => UploadedFile::fake()->image('bukti-transfer.jpg'),
                'notes' => 'Pembayaran subscribe bulan ini.',
            ])
            ->assertRedirect(route('subscribe.payment.index'));

        return SubscribePayment::where('user_id', $user->id)->latest('id')->firstOrFail();
    }

    public function test_menu_terkunci_dan_url_dilindungi_untuk_role_yang_belum_subscribe(): void
    {
        $this->aktifkanUntuk(['warga']);
        $warga = $this->sebagai('warga');

        $this->actingAs($warga)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Keuangan terkunci')
            ->assertSee('Kegiatan &amp; Info terkunci', false)
            ->assertSee('Aspirasi &amp; Partisipasi terkunci', false)
            ->assertSee('subscribe-required-modal');

        $this->actingAs($warga)
            ->get(route('pengumuman.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('show_subscribe_modal', true);

        $this->actingAs($warga)->get(route('profil-saya'))->assertOk();
        $this->actingAs($this->sebagai('ketua'))->get(route('pengumuman.index'))->assertOk();
        $this->actingAs($this->sebagai('admin'))->get(route('pengumuman.index'))->assertOk();
    }

    public function test_api_terproteksi_mengembalikan_payment_required_tanpa_memblokir_profil(): void
    {
        $this->aktifkanUntuk(['warga']);
        $warga = $this->sebagai('warga');
        Sanctum::actingAs($warga);

        $this->getJson('/api/pengumuman')
            ->assertStatus(402)
            ->assertJsonPath('code', 'SUBSCRIPTION_REQUIRED')
            ->assertJsonPath('subscription_status', 'required');

        $this->getJson('/api/saya')->assertOk();
    }

    public function test_pengguna_mengunggah_bukti_secara_privat_dan_admin_mendapat_notifikasi(): void
    {
        $this->aktifkanUntuk(['warga']);
        $warga = $this->sebagai('warga');
        $payment = $this->kirimPembayaran($warga);

        $this->assertSame(SubscribePayment::STATUS_PENDING, $payment->status);
        $this->assertSame('user:'.$warga->id, $payment->pending_key);
        $this->assertSame(25000, $payment->amount);
        Storage::disk('local')->assertExists($payment->proof_path);
        $this->assertFalse(file_exists(public_path($payment->proof_path)));

        $this->actingAs($this->sebagai('admin'))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pembayaran Subscribe Pending')
            ->assertSee(route('subscribe.verifications.index'));

        $this->actingAs($this->sebagai('admin'))
            ->get(route('subscribe.verifications.index'))
            ->assertOk()
            ->assertSee($warga->name)
            ->assertSee('Rp 25.000');
    }

    public function test_form_pembayaran_menyediakan_preview_bukti_sebelum_dikirim(): void
    {
        $this->aktifkanUntuk(['warga']);

        $this->actingAs($this->sebagai('warga'))
            ->get(route('subscribe.payment.index'))
            ->assertOk()
            ->assertSee('Preview bukti pembayaran')
            ->assertSee('id="proof-preview-image"', false)
            ->assertSee('id="proof-preview-pdf"', false)
            ->assertSee('URL.createObjectURL', false)
            ->assertSee('Ganti file');
    }

    public function test_warga_memilih_tujuan_dan_admin_melihat_rekening_yang_dituju(): void
    {
        $this->aktifkanUntuk(['warga']);
        $methods = app(SubscriptionPaymentMethods::class)->normalize([
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
        ]);
        SettingRT::set('subscribe_payment_methods', json_encode($methods, JSON_THROW_ON_ERROR));
        $warga = $this->sebagai('warga');

        $this->actingAs($warga)
            ->get(route('subscribe.payment.index'))
            ->assertOk()
            ->assertSee('Pilih Tujuan Pembayaran')
            ->assertSee('BCA')
            ->assertSee('DANA')
            ->assertSee('081234567890');

        $this->actingAs($warga)
            ->post(route('subscribe.payment.store'), [
                'payment_method_id' => $methods[1]['id'],
                'sender_bank' => 'Bank BRI',
                'sender_account_name' => $warga->name,
                'paid_at' => now()->toDateString(),
                'proof' => UploadedFile::fake()->image('bukti-dana.jpg'),
            ])
            ->assertRedirect(route('subscribe.payment.index'));

        $payment = SubscribePayment::where('user_id', $warga->id)->latest('id')->firstOrFail();
        $this->assertSame('ewallet', $payment->destination_type);
        $this->assertSame('DANA', $payment->destination_bank);
        $this->assertSame('081234567890', $payment->destination_account_number);
        $this->assertSame('Deni Afrizal', $payment->destination_account_name);

        $this->actingAs($this->sebagai('admin'))
            ->get(route('subscribe.verifications.index'))
            ->assertOk()
            ->assertSee('Tujuan Pembayaran')
            ->assertSee('E-Wallet')
            ->assertSee('DANA')
            ->assertSee('081234567890')
            ->assertSee('Deni Afrizal');
    }

    public function test_warga_tidak_dapat_mengirim_ke_tujuan_yang_tidak_terdaftar(): void
    {
        $this->aktifkanUntuk(['warga']);
        $warga = $this->sebagai('warga');

        $this->actingAs($warga)
            ->from(route('subscribe.payment.index'))
            ->post(route('subscribe.payment.store'), [
                'payment_method_id' => 'pm_tujuan_palsu',
                'sender_bank' => 'Bank BRI',
                'sender_account_name' => $warga->name,
                'paid_at' => now()->toDateString(),
                'proof' => UploadedFile::fake()->image('bukti-transfer.jpg'),
            ])
            ->assertRedirect(route('subscribe.payment.index'))
            ->assertSessionHasErrors('payment_method_id');

        $this->assertDatabaseCount('subscribe_payments', 0);
    }

    public function test_hanya_admin_dapat_melihat_bukti_dan_memverifikasi_pembayaran(): void
    {
        $this->aktifkanUntuk(['warga']);
        $warga = $this->sebagai('warga');
        $payment = $this->kirimPembayaran($warga);

        $this->actingAs($warga)
            ->get(route('subscribe.verifications.proof', $payment))
            ->assertForbidden();

        $this->actingAs($this->sebagai('admin'))
            ->get(route('subscribe.verifications.proof', $payment))
            ->assertOk();

        Carbon::setTestNow('2026-09-10 10:00:00');

        try {
            $this->actingAs($this->sebagai('admin'))
                ->patch(route('subscribe.verifications.approve', $payment))
                ->assertSessionHas('success');

            $payment->refresh();
            $this->assertSame(SubscribePayment::STATUS_APPROVED, $payment->status);
            $this->assertNull($payment->pending_key);
            $this->assertSame('2026-09-10 10:00:00', $payment->starts_at->format('Y-m-d H:i:s'));
            $this->assertSame('2026-10-10 10:00:00', $payment->ends_at->format('Y-m-d H:i:s'));

            $this->actingAs($warga)->get(route('pengumuman.index'))->assertOk();
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_akses_terkunci_kembali_setelah_30_hari(): void
    {
        $this->aktifkanUntuk(['warga']);
        $warga = $this->sebagai('warga');

        SubscribePayment::create([
            'user_id' => $warga->id,
            'amount' => 25000,
            'destination_bank' => 'Bank BCA',
            'destination_account_number' => '1234567890',
            'destination_account_name' => 'Pengelola RT',
            'sender_bank' => 'Bank BRI',
            'sender_account_name' => $warga->name,
            'paid_at' => '2026-08-11',
            'proof_path' => 'subscribe-proofs/lama.jpg',
            'status' => SubscribePayment::STATUS_APPROVED,
            'verified_at' => '2026-08-11 10:00:00',
            'starts_at' => '2026-08-11 10:00:00',
            'ends_at' => '2026-09-10 10:00:00',
        ]);

        Carbon::setTestNow('2026-09-10 10:00:00');

        try {
            $this->actingAs($warga)
                ->get(route('pengumuman.index'))
                ->assertRedirect(route('dashboard'));

            $this->actingAs($warga)
                ->get(route('dashboard'))
                ->assertOk()
                ->assertSee('Masa subscribe telah berakhir');
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_pembayaran_dapat_ditolak_dan_pengguna_bisa_mengirim_ulang(): void
    {
        $this->aktifkanUntuk(['warga']);
        $warga = $this->sebagai('warga');
        $payment = $this->kirimPembayaran($warga);

        $this->actingAs($this->sebagai('admin'))
            ->patch(route('subscribe.verifications.reject', $payment), [
                'rejection_reason' => 'Nominal pada bukti tidak terlihat jelas.',
            ])
            ->assertSessionHas('success');

        $payment->refresh();
        $this->assertSame(SubscribePayment::STATUS_REJECTED, $payment->status);
        $this->assertNull($payment->pending_key);

        $newPayment = $this->kirimPembayaran($warga);
        $this->assertNotSame($payment->id, $newPayment->id);
        $this->assertSame(SubscribePayment::STATUS_PENDING, $newPayment->status);
    }
}
