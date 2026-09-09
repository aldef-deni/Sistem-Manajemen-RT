<?php

namespace Tests\Feature;

use App\Models\SubscribePayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeIncomeTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, string $username): User
    {
        return User::factory()->create([
            'name' => ucfirst($username),
            'username' => $username,
            'role' => $role,
        ]);
    }

    private function payment(User $user, User $admin, array $overrides = []): SubscribePayment
    {
        return SubscribePayment::create(array_merge([
            'user_id' => $user->id,
            'amount' => 25000,
            'destination_bank' => 'Bank BCA',
            'destination_account_number' => '1234567890',
            'destination_account_name' => 'Pengelola RT',
            'sender_bank' => 'Bank BRI',
            'sender_account_name' => $user->name,
            'paid_at' => '2026-09-09',
            'proof_path' => 'subscribe-proofs/test.jpg',
            'status' => SubscribePayment::STATUS_APPROVED,
            'verified_by' => $admin->id,
            'verified_at' => '2026-09-10 10:00:00',
            'starts_at' => '2026-09-10 10:00:00',
            'ends_at' => '2026-10-10 10:00:00',
        ], $overrides));
    }

    public function test_hanya_administrator_dapat_membuka_dan_mengekspor_pendapatan(): void
    {
        $admin = $this->user('admin', 'admin-laporan');
        $warga = $this->user('warga', 'warga-laporan');

        $this->actingAs($admin)
            ->get(route('subscribe.income.index'))
            ->assertOk()
            ->assertSee('Pendapatan Subscribe')
            ->assertSee('Export PDF')
            ->assertSee('Export Excel');

        foreach (['subscribe.income.index', 'subscribe.income.export.pdf', 'subscribe.income.export.excel'] as $routeName) {
            $this->actingAs($warga)->get(route($routeName))->assertForbidden();
        }
    }

    public function test_laporan_hanya_menghitung_pembayaran_disetujui_sesuai_bulan(): void
    {
        Carbon::setTestNow('2026-09-15 12:00:00');

        try {
            $admin = $this->user('admin', 'admin-filter');
            $september = $this->user('warga', 'warga-september');
            $agustus = $this->user('warga', 'warga-agustus');
            $pending = $this->user('warga', 'warga-pending');

            $this->payment($september, $admin, ['amount' => 125000]);
            $this->payment($agustus, $admin, [
                'amount' => 50000,
                'paid_at' => '2026-08-09',
                'verified_at' => '2026-08-10 10:00:00',
                'starts_at' => '2026-08-10 10:00:00',
                'ends_at' => '2026-09-09 10:00:00',
            ]);
            $this->payment($pending, $admin, [
                'amount' => 75000,
                'status' => SubscribePayment::STATUS_PENDING,
                'verified_by' => null,
                'verified_at' => null,
                'starts_at' => null,
                'ends_at' => null,
            ]);

            $this->actingAs($admin)
                ->get(route('subscribe.income.index'))
                ->assertOk()
                ->assertSee('Rp 125.000')
                ->assertSee($september->name)
                ->assertDontSee($agustus->name)
                ->assertDontSee($pending->name);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_filter_rentang_tanggal_dan_validasinya_berfungsi(): void
    {
        $admin = $this->user('admin', 'admin-rentang');
        $warga = $this->user('warga', 'warga-rentang');
        $this->payment($warga, $admin, ['amount' => 88000, 'verified_at' => '2026-07-18 09:00:00']);

        $this->actingAs($admin)
            ->get(route('subscribe.income.index', [
                'period' => 'range',
                'start_date' => '2026-07-01',
                'end_date' => '2026-07-31',
            ]))
            ->assertOk()
            ->assertSee('01 Jul 2026 - 31 Jul 2026')
            ->assertSee('Rp 88.000')
            ->assertSee($warga->name);

        $this->actingAs($admin)
            ->get(route('subscribe.income.index', [
                'period' => 'range',
                'start_date' => '2026-07-31',
                'end_date' => '2026-07-01',
            ]))
            ->assertSessionHasErrors('end_date');
    }

    public function test_export_pdf_dan_excel_menghasilkan_berkas_sesuai_filter(): void
    {
        Carbon::setTestNow('2026-09-15 12:00:00');

        try {
            $admin = $this->user('admin', 'admin-export');
            $warga = $this->user('warga', 'warga-export');
            $this->payment($warga, $admin, ['amount' => 99000]);

            $params = ['period' => 'month', 'month' => '2026-09'];

            $pdf = $this->actingAs($admin)->get(route('subscribe.income.export.pdf', $params));
            $pdf->assertOk()
                ->assertHeader('content-type', 'application/pdf')
                ->assertDownload('pendapatan-subscribe-2026-09.pdf');
            $this->assertStringStartsWith('%PDF', $pdf->getContent());

            $excel = $this->actingAs($admin)->get(route('subscribe.income.export.excel', $params));
            $excel->assertOk()
                ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->assertDownload('pendapatan-subscribe-2026-09.xlsx');
            $this->assertStringStartsWith('PK', $excel->streamedContent());
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_submenu_pendapatan_tampil_pada_dashboard_administrator(): void
    {
        $admin = $this->user('admin', 'admin-menu');

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Verifikasi Pembayaran')
            ->assertSee('Pendapatan')
            ->assertSee(route('subscribe.income.index'));
    }
}
