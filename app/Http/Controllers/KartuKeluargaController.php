<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuKeluargaController extends Controller
{
    public function index(Request $request)
    {
        $query = KartuKeluarga::with('kepalaKeluarga');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('no_kk', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        $kartuKeluargas = $query->latest()->paginate(10)->withQueryString();
        $totalKK = KartuKeluarga::count();

        return view('kartu-keluarga.index', compact('kartuKeluargas', 'totalKK'));
    }

    public function create()
    {
        return view('kartu-keluarga.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kk'     => 'required|string|size:20|unique:kartu_keluarga,no_kk',
            'rt'        => 'nullable|string|max:5',
            'rw'        => 'nullable|string|max:5',
            'alamat'    => 'required|string',
            'desa'      => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'kode_pos'  => 'nullable|string|max:10',
            // Anggota
            'anggota'                  => 'required|array|min:1',
            'anggota.*.nik'            => 'required|string|size:16|unique:anggota_keluarga,nik',
            'anggota.*.nama_lengkap'   => 'required|string|max:100',
            'anggota.*.no_hp'          => 'nullable|string|max:20',
            'anggota.*.jenis_kelamin'  => 'nullable|in:L,P',
            'anggota.*.tanggal_lahir'  => 'nullable|date',
            'anggota.*.status_hubungan'=> 'required|string|max:50',
            'anggota.*.domisili'       => 'nullable|string|max:50',
            'anggota.*.role'           => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($validated) {
            $kk = KartuKeluarga::create([
                'no_kk'     => $validated['no_kk'],
                'rt'        => $validated['rt'],
                'rw'        => $validated['rw'],
                'alamat'    => $validated['alamat'],
                'desa'      => $validated['desa'],
                'kecamatan' => $validated['kecamatan'],
                'kabupaten' => $validated['kabupaten'],
                'kode_pos'  => $validated['kode_pos'],
            ]);

            foreach ($validated['anggota'] as $i => $anggota) {
                $kk->anggota()->create([
                    'nik'             => $anggota['nik'],
                    'nama_lengkap'    => $anggota['nama_lengkap'],
                    'no_hp'           => $anggota['no_hp'] ?? null,
                    'jenis_kelamin'   => $anggota['jenis_kelamin'] ?? null,
                    'tanggal_lahir'   => $anggota['tanggal_lahir'] ?? null,
                    'status_hubungan' => $i === 0 ? 'Kepala Keluarga' : ($anggota['status_hubungan'] ?? 'Warga'),
                    'domisili'        => $anggota['domisili'] ?? 'Tetap',
                    'role'            => $anggota['role'] ?? 'Warga',
                ]);
            }
        });

        return redirect()->route('kartu-keluarga.index')->with('success', 'Kartu Keluarga berhasil ditambahkan!');
    }

    public function show(KartuKeluarga $kartu_keluarga)
    {
        $kartu_keluarga->load('anggota');
        return view('kartu-keluarga.show', compact('kartu_keluarga'));
    }

    public function edit(KartuKeluarga $kartu_keluarga)
    {
        $kartu_keluarga->load('anggota');
        return view('kartu-keluarga.edit', compact('kartu_keluarga'));
    }

    public function update(Request $request, KartuKeluarga $kartu_keluarga)
    {
        $validated = $request->validate([
            'no_kk'     => 'required|string|size:20|unique:kartu_keluarga,no_kk,' . $kartu_keluarga->id,
            'rt'        => 'nullable|string|max:5',
            'rw'        => 'nullable|string|max:5',
            'alamat'    => 'required|string',
            'desa'      => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'kode_pos'  => 'nullable|string|max:10',
            'anggota'                  => 'required|array|min:1',
            'anggota.*.id'             => 'nullable|integer',
            'anggota.*.nik'            => 'required|string|size:16',
            'anggota.*.nama_lengkap'   => 'required|string|max:100',
            'anggota.*.no_hp'          => 'nullable|string|max:20',
            'anggota.*.jenis_kelamin'  => 'nullable|in:L,P',
            'anggota.*.tanggal_lahir'  => 'nullable|date',
            'anggota.*.status_hubungan'=> 'required|string|max:50',
            'anggota.*.domisili'       => 'nullable|string|max:50',
            'anggota.*.role'           => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($validated, $kartu_keluarga) {
            $kartu_keluarga->update([
                'no_kk'     => $validated['no_kk'],
                'rt'        => $validated['rt'],
                'rw'        => $validated['rw'],
                'alamat'    => $validated['alamat'],
                'desa'      => $validated['desa'],
                'kecamatan' => $validated['kecamatan'],
                'kabupaten' => $validated['kabupaten'],
                'kode_pos'  => $validated['kode_pos'],
            ]);

            // Collect existing IDs to keep
            $existingIds = [];
            foreach ($validated['anggota'] as $i => $anggota) {
                if (!empty($anggota['id'])) {
                    // Update existing
                    AnggotaKeluarga::where('id', $anggota['id'])
                        ->where('kartu_keluarga_id', $kartu_keluarga->id)
                        ->update([
                            'nik'             => $anggota['nik'],
                            'nama_lengkap'    => $anggota['nama_lengkap'],
                            'no_hp'           => $anggota['no_hp'] ?? null,
                            'jenis_kelamin'   => $anggota['jenis_kelamin'] ?? null,
                            'tanggal_lahir'   => $anggota['tanggal_lahir'] ?: null,
                            'status_hubungan' => $i === 0 ? 'Kepala Keluarga' : ($anggota['status_hubungan'] ?? 'Warga'),
                            'domisili'        => $anggota['domisili'] ?? 'Tetap',
                            'role'            => $anggota['role'] ?? 'Warga',
                        ]);
                    $existingIds[] = $anggota['id'];
                } else {
                    // Create new
                    $new = $kartu_keluarga->anggota()->create([
                        'nik'             => $anggota['nik'],
                        'nama_lengkap'    => $anggota['nama_lengkap'],
                        'no_hp'           => $anggota['no_hp'] ?? null,
                        'jenis_kelamin'   => $anggota['jenis_kelamin'] ?? null,
                        'tanggal_lahir'   => $anggota['tanggal_lahir'] ?? null,
                        'status_hubungan' => $i === 0 ? 'Kepala Keluarga' : ($anggota['status_hubungan'] ?? 'Warga'),
                        'domisili'        => $anggota['domisili'] ?? 'Tetap',
                        'role'            => $anggota['role'] ?? 'Warga',
                    ]);
                    $existingIds[] = $new->id;
                }
            }

            // Delete removed members
            $kartu_keluarga->anggota()->whereNotIn('id', $existingIds)->delete();
        });

        return redirect()->route('kartu-keluarga.index')->with('success', 'Kartu Keluarga berhasil diperbarui!');
    }

    public function destroy(KartuKeluarga $kartu_keluarga)
    {
        $kartu_keluarga->delete();
        return redirect()->route('kartu-keluarga.index')->with('success', 'Kartu Keluarga berhasil dihapus!');
    }
}
