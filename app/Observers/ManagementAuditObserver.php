<?php

namespace App\Observers;

use App\Models\AnggotaKeluarga;
use App\Models\IuranWarga;
use App\Models\JadwalKegiatan;
use App\Models\KartuKeluarga;
use App\Models\KegiatanRT;
use App\Models\TransaksiKas;
use App\Models\UMKM;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

/**
 * Jejak perubahan operasional untuk Administrator dan Ketua RT.
 *
 * Observer dipakai agar perubahan dari website maupun API Android melewati
 * pintu notifikasi yang sama. Event tanpa pengguna aktif (seeder, queue, dan
 * maintenance CLI) sengaja diabaikan agar tidak membuat notifikasi palsu.
 */
class ManagementAuditObserver
{
    /** @var array<class-string<Model>, array{label:string, field:string, route:string, category:string}> */
    private const MAP = [
        AnggotaKeluarga::class => ['label' => 'Data warga', 'field' => 'nama_lengkap', 'route' => 'data-warga', 'category' => 'resident'],
        KartuKeluarga::class => ['label' => 'Kartu Keluarga', 'field' => 'no_kk', 'route' => 'kartu-keluarga.index', 'category' => 'resident'],
        IuranWarga::class => ['label' => 'Iuran warga', 'field' => 'id', 'route' => 'iuran-warga.index', 'category' => 'dues'],
        TransaksiKas::class => ['label' => 'Transaksi kas', 'field' => 'kategori', 'route' => 'kas-rt.index', 'category' => 'finance'],
        KegiatanRT::class => ['label' => 'Kegiatan RT', 'field' => 'judul', 'route' => 'kegiatan-rt.index', 'category' => 'activity'],
        JadwalKegiatan::class => ['label' => 'Jadwal kegiatan', 'field' => 'nama_kegiatan', 'route' => 'jadwal-kegiatan.index', 'category' => 'schedule'],
        UMKM::class => ['label' => 'Data UMKM', 'field' => 'nama_usaha', 'route' => 'umkm.index', 'category' => 'umkm'],
        User::class => ['label' => 'Akun pengguna', 'field' => 'name', 'route' => 'akun.index', 'category' => 'account'],
    ];

    private const IGNORED_CHANGES = ['updated_at', 'dilihat', 'jumlah_suara'];

    public function created(Model $model): void
    {
        $this->send($model, 'ditambahkan');
    }

    public function updated(Model $model): void
    {
        $meaningful = array_diff(array_keys($model->getChanges()), self::IGNORED_CHANGES);
        if ($meaningful !== []) {
            $this->send($model, 'diperbarui');
        }
    }

    public function deleted(Model $model): void
    {
        $this->send($model, 'dihapus');
    }

    private function send(Model $model, string $action): void
    {
        $actor = auth()->user();
        $config = self::MAP[$model::class] ?? null;
        if (! $actor || ! $config) {
            return;
        }

        $identity = trim((string) $model->getAttribute($config['field']));
        if ($config['field'] === 'id' && $identity !== '') {
            $identity = '#'.$identity;
        }

        $message = $config['label'].($identity !== '' ? ' "'.$identity.'"' : '').' '.$action.' oleh '.$actor->name.'.';

        Notification::send(
            User::query()->whereIn('role', ['admin', 'ketua'])->get(),
            new SystemNotification(
                category: $config['category'],
                title: $config['label'].' '.$action,
                message: $message,
                routeName: $config['route'],
                tone: $action === 'dihapus' ? 'rose' : ($action === 'ditambahkan' ? 'emerald' : 'blue'),
                context: ['model' => $model::class, 'model_id' => $model->getKey(), 'action' => $action],
            )
        );
    }
}
