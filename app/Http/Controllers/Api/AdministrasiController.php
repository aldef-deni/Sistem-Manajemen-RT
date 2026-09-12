<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Models\IuranWarga;
use App\Models\JenisIuran;
use App\Models\KartuKeluarga;
use App\Models\Pengaduan;
use App\Models\RekeningKas;
use App\Models\TransaksiKas;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * CRUD ringkas untuk pekerjaan administrasi dari aplikasi Android.
 *
 * Seluruh rute kelas ini hanya dibuka untuk Administrator dan Ketua RT.
 * Transaksi keuangan selalu memperbarui saldo di transaksi database yang sama.
 */
class AdministrasiController extends Controller
{
    public function wargaOptions(): JsonResponse
    {
        return response()->json([
            'kartu_keluarga' => KartuKeluarga::query()
                ->with('kepalaKeluarga')
                ->orderBy('no_kk')
                ->get()
                ->map(fn (KartuKeluarga $kk): array => [
                    'id' => $kk->id,
                    'no_kk' => $kk->no_kk,
                    'alamat' => $kk->alamat,
                    'kepala' => $kk->kepalaKeluarga?->nama_lengkap,
                ])
                ->values(),
        ]);
    }

    public function storeWarga(Request $request): JsonResponse
    {
        $data = $this->validateWarga($request);

        $warga = DB::transaction(function () use ($data): AnggotaKeluarga {
            $kartuKeluargaId = $data['kartu_keluarga_id'] ?? null;
            if (! $kartuKeluargaId) {
                $kk = KartuKeluarga::create([
                    'no_kk' => $data['no_kk'],
                    'alamat' => $data['alamat'],
                    'rt' => $data['rt'] ?? null,
                    'rw' => $data['rw'] ?? null,
                ]);
                $kartuKeluargaId = $kk->id;
            }

            return AnggotaKeluarga::create($this->wargaValues($data, (int) $kartuKeluargaId));
        });

        return response()->json([
            'pesan' => 'Data warga berhasil ditambahkan.',
            'data' => $this->wargaPayload($warga->load('kartuKeluarga')),
        ], 201);
    }

    public function updateWarga(Request $request, AnggotaKeluarga $warga): JsonResponse
    {
        $data = $this->validateWarga($request, $warga);
        $warga->update($this->wargaValues($data, (int) $data['kartu_keluarga_id']));

        return response()->json([
            'pesan' => 'Data warga berhasil diperbarui.',
            'data' => $this->wargaPayload($warga->fresh('kartuKeluarga')),
        ]);
    }

    public function destroyWarga(AnggotaKeluarga $warga): JsonResponse
    {
        try {
            $warga->delete();
        } catch (QueryException) {
            throw ValidationException::withMessages([
                'warga' => 'Data warga memiliki riwayat transaksi dan belum dapat dihapus.',
            ]);
        }

        return response()->json(['pesan' => 'Data warga berhasil dihapus.']);
    }

    public function storeTransaksiKas(Request $request): JsonResponse
    {
        $data = $this->validateTransaksiKas($request);
        $transaksi = DB::transaction(function () use ($request, $data): TransaksiKas {
            $rekening = RekeningKas::query()->lockForUpdate()->findOrFail($data['rekening_kas_id']);
            $this->applySaldo($rekening, $data['jenis'], (float) $data['nominal']);

            return TransaksiKas::create([
                ...$data,
                'user_id' => $request->user()->id,
            ]);
        });

        return response()->json([
            'pesan' => 'Transaksi kas berhasil dicatat.',
            'data' => $this->transaksiPayload($transaksi->load('rekening')),
        ], 201);
    }

    public function updateTransaksiKas(Request $request, TransaksiKas $transaksi): JsonResponse
    {
        $data = $this->validateTransaksiKas($request);

        DB::transaction(function () use ($transaksi, $data): void {
            $rekeningLama = RekeningKas::query()->lockForUpdate()->findOrFail($transaksi->rekening_kas_id);
            $this->reverseSaldo($rekeningLama, $transaksi->jenis, (float) $transaksi->nominal);

            $rekeningBaru = $rekeningLama->id === (int) $data['rekening_kas_id']
                ? $rekeningLama->fresh()
                : RekeningKas::query()->lockForUpdate()->findOrFail($data['rekening_kas_id']);
            $this->applySaldo($rekeningBaru, $data['jenis'], (float) $data['nominal']);
            $transaksi->update($data);
        });

        return response()->json([
            'pesan' => 'Transaksi kas berhasil diperbarui.',
            'data' => $this->transaksiPayload($transaksi->fresh('rekening')),
        ]);
    }

    public function destroyTransaksiKas(TransaksiKas $transaksi): JsonResponse
    {
        DB::transaction(function () use ($transaksi): void {
            $rekening = RekeningKas::query()->lockForUpdate()->findOrFail($transaksi->rekening_kas_id);
            $this->reverseSaldo($rekening, $transaksi->jenis, (float) $transaksi->nominal);
            $transaksi->delete();
        });

        return response()->json(['pesan' => 'Transaksi kas berhasil dihapus.']);
    }

    public function iuranOptions(): JsonResponse
    {
        return response()->json([
            'warga' => AnggotaKeluarga::query()
                ->with('kartuKeluarga')
                ->orderBy('nama_lengkap')
                ->get()
                ->map(fn (AnggotaKeluarga $item): array => [
                    'id' => $item->id,
                    'nama' => $item->nama_lengkap,
                    'nik' => $item->nik,
                    'no_kk' => $item->kartuKeluarga?->no_kk,
                ])->values(),
            'jenis_iuran' => JenisIuran::query()->where('is_active', true)->orderBy('nama')->get()
                ->map(fn (JenisIuran $item): array => [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'nominal' => (int) $item->nominal_default,
                ])->values(),
        ]);
    }

    public function storeIuran(Request $request): JsonResponse
    {
        $data = $this->validateIuran($request);
        $iuran = IuranWarga::create($data);

        return response()->json([
            'pesan' => 'Tagihan iuran berhasil ditambahkan.',
            'data' => $this->iuranPayload($iuran->load(['anggota', 'jenisIuran'])),
        ], 201);
    }

    public function updateIuran(Request $request, IuranWarga $iuran): JsonResponse
    {
        $data = $this->validateIuran($request, $iuran);
        $iuran->update($data);

        return response()->json([
            'pesan' => 'Tagihan iuran berhasil diperbarui.',
            'data' => $this->iuranPayload($iuran->fresh(['anggota', 'jenisIuran'])),
        ]);
    }

    public function destroyIuran(IuranWarga $iuran): JsonResponse
    {
        $iuran->delete();

        return response()->json(['pesan' => 'Tagihan iuran berhasil dihapus.']);
    }

    public function akunOptions(): JsonResponse
    {
        return response()->json([
            'warga' => AnggotaKeluarga::query()
                ->orderBy('nama_lengkap')
                ->get(['id', 'nama_lengkap', 'nik'])
                ->map(fn (AnggotaKeluarga $item): array => [
                    'id' => $item->id,
                    'nama' => $item->nama_lengkap,
                    'nik' => $item->nik,
                    'terpakai' => User::query()->where('anggota_keluarga_id', $item->id)->exists(),
                ])->values(),
        ]);
    }

    public function storeAkun(Request $request): JsonResponse
    {
        $data = $this->validateAkun($request);
        $akun = User::create([
            ...$data,
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'pesan' => 'Akun berhasil ditambahkan.',
            'data' => $this->akunPayload($akun),
        ], 201);
    }

    public function updateAkun(Request $request, User $akun): JsonResponse
    {
        if ($akun->role === 'admin' && $akun->id !== $request->user()->id) {
            throw ValidationException::withMessages([
                'akun' => 'Akun Administrator hanya dapat diubah oleh pemiliknya sendiri.',
            ]);
        }

        $data = $this->validateAkun($request, $akun);
        if ($akun->role === 'admin') {
            $data['role'] = 'admin';
        }
        if ($akun->id === $request->user()->id) {
            $data['role'] = $akun->role;
        }
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
            $akun->tokens()->delete();
        }
        $akun->update($data);

        return response()->json([
            'pesan' => 'Akun berhasil diperbarui.',
            'data' => $this->akunPayload($akun->fresh()),
        ]);
    }

    public function destroyAkun(Request $request, User $akun): JsonResponse
    {
        abort_if($akun->id === $request->user()->id, 422, 'Akun sendiri tidak dapat dihapus.');
        abort_if($akun->role === 'admin', 422, 'Akun Administrator tidak dapat dihapus.');

        $akun->tokens()->delete();
        $akun->delete();

        return response()->json(['pesan' => 'Akun berhasil dihapus.']);
    }

    public function destroyPengaduan(Pengaduan $pengaduan): JsonResponse
    {
        $ticket = $pengaduan->kode_tiket;
        $pengaduan->delete();

        Notification::send(
            User::query()->whereIn('role', ['admin', 'ketua'])->get(),
            new SystemNotification(
                category: 'complaint',
                title: 'Pengaduan dihapus',
                message: 'Pengaduan '.$ticket.' telah dihapus oleh '.auth()->user()->name.'.',
                routeName: 'pengaduan.index',
                tone: 'rose',
                context: ['action' => 'dihapus'],
            )
        );

        return response()->json(['pesan' => 'Pengaduan berhasil dihapus.']);
    }

    private function validateWarga(Request $request, ?AnggotaKeluarga $warga = null): array
    {
        $creating = $warga === null;

        return $request->validate([
            'kartu_keluarga_id' => [$creating ? 'nullable' : 'required', 'integer', 'exists:kartu_keluarga,id'],
            'no_kk' => [$creating ? 'required_without:kartu_keluarga_id' : 'nullable', 'nullable', 'string', 'min:16', 'max:20', Rule::unique('kartu_keluarga', 'no_kk')],
            'alamat' => [$creating ? 'required_without:kartu_keluarga_id' : 'nullable', 'nullable', 'string', 'max:1000'],
            'rt' => ['nullable', 'string', 'max:5'],
            'rw' => ['nullable', 'string', 'max:5'],
            'nik' => ['required', 'string', 'size:16', Rule::unique('anggota_keluarga', 'nik')->ignore($warga?->id)],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'status_hubungan' => ['required', 'string', 'max:50'],
            'status_kawin' => ['nullable', 'string', 'max:50'],
            'domisili' => ['required', 'string', 'max:50'],
            'role_keluarga' => ['nullable', 'string', 'max:50'],
        ]);
    }

    private function wargaValues(array $data, int $kartuKeluargaId): array
    {
        return [
            'kartu_keluarga_id' => $kartuKeluargaId,
            'nik' => $data['nik'],
            'nama_lengkap' => $data['nama_lengkap'],
            'no_hp' => $data['no_hp'] ?? null,
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'status_hubungan' => $data['status_hubungan'],
            'status_kawin' => $data['status_kawin'] ?? null,
            'domisili' => $data['domisili'],
            'role' => $data['role_keluarga'] ?? 'Warga',
        ];
    }

    private function validateTransaksiKas(Request $request): array
    {
        return $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', Rule::in(['masuk', 'keluar'])],
            'kategori' => ['required', 'string', 'max:100'],
            'rekening_kas_id' => ['required', 'integer', 'exists:rekening_kas,id'],
            'nominal' => ['required', 'numeric', 'min:1', 'max:100000000'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    private function applySaldo(RekeningKas $rekening, string $jenis, float $nominal): void
    {
        if ($jenis === 'keluar' && (float) $rekening->saldo < $nominal) {
            throw ValidationException::withMessages(['nominal' => 'Saldo rekening tidak mencukupi.']);
        }

        $jenis === 'masuk' ? $rekening->increment('saldo', $nominal) : $rekening->decrement('saldo', $nominal);
    }

    private function reverseSaldo(RekeningKas $rekening, string $jenis, float $nominal): void
    {
        $jenis === 'masuk' ? $rekening->decrement('saldo', $nominal) : $rekening->increment('saldo', $nominal);
    }

    private function validateIuran(Request $request, ?IuranWarga $iuran = null): array
    {
        $unique = Rule::unique('iuran_warga')->where(fn ($query) => $query
            ->where('anggota_keluarga_id', $request->input('anggota_keluarga_id'))
            ->where('jenis_iuran_id', $request->input('jenis_iuran_id'))
            ->where('bulan', $request->input('bulan'))
            ->where('tahun', $request->input('tahun')));
        if ($iuran) {
            $unique->ignore($iuran->id);
        }

        return $request->validate([
            'anggota_keluarga_id' => ['required', 'integer', 'exists:anggota_keluarga,id', $unique],
            'jenis_iuran_id' => ['required', 'integer', 'exists:jenis_iuran,id'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2100'],
            'nominal' => ['required', 'integer', 'min:0', 'max:100000000'],
            'status' => ['required', Rule::in(['belum_bayar', 'lunas'])],
            'tanggal_bayar' => ['nullable', 'date'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'anggota_keluarga_id.unique' => 'Tagihan warga untuk jenis dan periode ini sudah ada.',
        ]);
    }

    private function validateAkun(Request $request, ?User $akun = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($akun?->id)],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($akun?->id)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in(['ketua', 'pengurus', 'warga'])],
            'anggota_keluarga_id' => ['nullable', 'integer', 'exists:anggota_keluarga,id', Rule::unique('users', 'anggota_keluarga_id')->ignore($akun?->id)],
            'password' => [$akun ? 'nullable' : 'required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    private function wargaPayload(AnggotaKeluarga $item): array
    {
        return [
            'id' => $item->id,
            'kartu_keluarga_id' => $item->kartu_keluarga_id,
            'nama' => $item->nama_lengkap,
            'nik' => $item->nik,
            'no_hp' => $item->no_hp,
            'jenis_kelamin' => $item->jenis_kelamin,
            'kelamin' => $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'tanggal_lahir' => $item->tanggal_lahir?->toDateString(),
            'status_hubungan' => $item->status_hubungan,
            'hubungan' => $item->status_hubungan,
            'status_kawin' => $item->status_kawin,
            'domisili' => $item->domisili,
            'role_keluarga' => $item->role,
            'no_kk' => $item->kartuKeluarga?->no_kk,
            'alamat' => $item->kartuKeluarga?->alamat,
        ];
    }

    private function transaksiPayload(TransaksiKas $item): array
    {
        return [
            'id' => $item->id,
            'jenis' => $item->jenis,
            'masuk' => $item->jenis === 'masuk',
            'kategori' => $item->kategori,
            'rekening_kas_id' => $item->rekening_kas_id,
            'nominal' => (float) $item->nominal,
            'tanggal' => $item->tanggal?->toDateString(),
            'keterangan' => $item->keterangan,
            'rekening' => $item->rekening?->nama,
        ];
    }

    private function iuranPayload(IuranWarga $item): array
    {
        return [
            'id' => $item->id,
            'anggota_keluarga_id' => $item->anggota_keluarga_id,
            'warga_nama' => $item->anggotaKeluarga?->nama_lengkap,
            'jenis_iuran_id' => $item->jenis_iuran_id,
            'bulan' => $item->bulan,
            'tahun' => $item->tahun,
            'warga' => $item->anggota?->nama_lengkap ?? '-',
            'jenis' => $item->jenisIuran?->nama ?? 'Iuran',
            'periode' => $item->periode,
            'nominal' => (float) $item->nominal,
            'status' => $item->status,
            'tanggal_bayar' => $item->tanggal_bayar?->toDateString(),
            'catatan' => $item->catatan,
            'lunas' => $item->status === 'lunas',
        ];
    }

    private function akunPayload(User $item): array
    {
        return [
            'id' => $item->id,
            'nama' => $item->name,
            'username' => $item->username,
            'email' => $item->email,
            'no_hp' => $item->no_hp,
            'peran' => $item->role,
            'peran_label' => $item->role_label,
            'anggota_keluarga_id' => $item->anggota_keluarga_id,
            'tertaut' => (bool) $item->anggota_keluarga_id,
        ];
    }
}
