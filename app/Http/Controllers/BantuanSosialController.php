<?php

namespace App\Http\Controllers;

use App\Models\PenerimaBantuan;
use App\Models\PengajuanKurangMampu;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BantuanSosialController extends Controller
{
    /**
     * Dashboard Bantuan Sosial - List Penerima Bantuan
     */
    public function index(Request $request)
    {
        $query = PenerimaBantuan::with('anggota.kartuKeluarga');

        // Filter tahun
        if ($request->filled('tahun') && $request->tahun !== 'all') {
            $query->where('tahun', $request->tahun);
        }

        // Filter jenis bantuan
        if ($request->filled('jenis')) {
            $query->whereJsonContains('jenis_bantuan', $request->jenis);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            })->orWhere('nik', 'like', "%{$search}%");
        }

        $penerima = $query->latest()->paginate(15)->withQueryString();

        // Stats
        $totalPenerima = PenerimaBantuan::count();
        $aktif = PenerimaBantuan::where('status', 'aktif')->count();
        $perluDitinjau = PengajuanKurangMampu::where('status', 'menunggu')->count();
        
        // Count unique jenis bantuan that are active
        $jenisAktif = PenerimaBantuan::where('status', 'aktif')
            ->pluck('jenis_bantuan')
            ->flatten()
            ->unique()
            ->count();

        // Available years
        $years = PenerimaBantuan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        return view('bantuan-sosial.index', compact(
            'penerima', 'totalPenerima', 'aktif', 'perluDitinjau', 'jenisAktif', 'years'
        ));
    }

    /**
     * List Warga Kurang Mampu (Pengajuan)
     */
    public function kurangMampu(Request $request)
    {
        $query = PengajuanKurangMampu::with('anggota.kartuKeluarga');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tahun
        if ($request->filled('tahun') && $request->tahun !== 'all') {
            $query->whereYear('created_at', $request->tahun);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            })->orWhere('nik', 'like', "%{$search}%");
        }

        $pengajuan = $query->latest()->paginate(15)->withQueryString();

        $totalPengajuan = PengajuanKurangMampu::count();
        $menunggu = PengajuanKurangMampu::where('status', 'menunggu')->count();
        $disetujui = PengajuanKurangMampu::where('status', 'disetujui')->count();
        $ditolak = PengajuanKurangMampu::where('status', 'ditolak')->count();

        return view('bantuan-sosial.kurang-mampu', compact(
            'pengajuan', 'totalPengajuan', 'menunggu', 'disetujui', 'ditolak'
        ));
    }

    /**
     * Form Ajukan Data Warga Kurang Mampu
     */
    public function ajukan()
    {
        $warga = AnggotaKeluarga::with('kartuKeluarga')
            ->orderBy('nama_lengkap')
            ->get();

        return view('bantuan-sosial.ajukan', compact('warga'));
    }

    /**
     * Store Ajukan Data
     */
    public function storeAjukan(Request $request)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'penghasilan_per_bulan' => 'required|numeric|min:0',
            'pekerjaan' => 'nullable|string|max:255',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'status_rumah' => 'required|in:Milik Sendiri,Kontrak,Sewa,Numpang',
            'kondisi_rumah' => 'required|in:Baik,Sedang,Rusak,Sangat Rusak',
            'alasan_pengajuan' => 'required|string',
            'keterangan' => 'nullable|string',
            'foto_rumah' => 'nullable|image|max:2048',
        ]);

        $anggota = AnggotaKeluarga::findOrFail($request->anggota_keluarga_id);

        $fotoPath = null;
        if ($request->hasFile('foto_rumah')) {
            $fotoPath = $request->file('foto_rumah')->store('bantuan-sosial/foto-rumah', 'public');
        }

        PengajuanKurangMampu::create([
            'anggota_keluarga_id' => $request->anggota_keluarga_id,
            'nik' => $anggota->nik,
            'no_kk' => $anggota->kartuKeluarga->no_kk ?? null,
            'penghasilan_per_bulan' => $request->penghasilan_per_bulan,
            'pekerjaan' => $request->pekerjaan,
            'jumlah_tanggungan' => $request->jumlah_tanggungan,
            'status_rumah' => $request->status_rumah,
            'kondisi_rumah' => $request->kondisi_rumah,
            'alasan_pengajuan' => $request->alasan_pengajuan,
            'keterangan' => $request->keterangan,
            'foto_rumah' => $fotoPath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('bantuan-sosial.kurang-mampu')
            ->with('success', 'Pengajuan data kurang mampu berhasil dikirim!');
    }

    /**
     * Form Tambah Penerima Bantuan
     */
    public function tambahPenerima()
    {
        $warga = AnggotaKeluarga::with('kartuKeluarga')
            ->orderBy('nama_lengkap')
            ->get();

        return view('bantuan-sosial.tambah-penerima', compact('warga'));
    }

    /**
     * Store Penerima Bantuan
     */
    public function storePenerima(Request $request)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'jenis_bantuan' => 'required|array|min:1',
            'tahun' => 'required|integer|min:2020|max:2030',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        $anggota = AnggotaKeluarga::findOrFail($request->anggota_keluarga_id);

        PenerimaBantuan::create([
            'anggota_keluarga_id' => $request->anggota_keluarga_id,
            'nik' => $anggota->nik,
            'no_kk' => $anggota->kartuKeluarga->no_kk ?? null,
            'jenis_bantuan' => $request->jenis_bantuan,
            'tahun' => $request->tahun,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('bantuan-sosial.index')
            ->with('success', 'Data penerima bantuan berhasil ditambahkan!');
    }

    /**
     * Show detail penerima
     */
    public function show(PenerimaBantuan $penerimaBantuan)
    {
        $penerimaBantuan->load('anggota.kartuKeluarga');
        return view('bantuan-sosial.show', ['penerima' => $penerimaBantuan]);
    }

    /**
     * Edit penerima bantuan
     */
    public function edit(PenerimaBantuan $penerimaBantuan)
    {
        $penerimaBantuan->load('anggota.kartuKeluarga');
        $warga = AnggotaKeluarga::with('kartuKeluarga')->orderBy('nama_lengkap')->get();
        return view('bantuan-sosial.edit', ['penerima' => $penerimaBantuan, 'warga' => $warga]);
    }

    /**
     * Update penerima bantuan
     */
    public function update(Request $request, PenerimaBantuan $penerimaBantuan)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'jenis_bantuan' => 'required|array|min:1',
            'tahun' => 'required|integer|min:2020|max:2030',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        $anggota = AnggotaKeluarga::findOrFail($request->anggota_keluarga_id);

        $penerimaBantuan->update([
            'anggota_keluarga_id' => $request->anggota_keluarga_id,
            'nik' => $anggota->nik,
            'no_kk' => $anggota->kartuKeluarga->no_kk ?? null,
            'jenis_bantuan' => $request->jenis_bantuan,
            'tahun' => $request->tahun,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('bantuan-sosial.index')
            ->with('success', 'Data penerima bantuan berhasil diupdate!');
    }

    /**
     * Delete penerima bantuan
     */
    public function destroy(PenerimaBantuan $penerimaBantuan)
    {
        $penerimaBantuan->delete();
        return redirect()->route('bantuan-sosial.index')
            ->with('success', 'Data penerima bantuan berhasil dihapus!');
    }

    /**
     * Approve / Reject pengajuan
     */
    public function updateStatusPengajuan(Request $request, PengajuanKurangMampu $pengajuan)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $pengajuan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // If approved, create penerima bantuan automatically
        if ($request->status === 'disetujui') {
            $existing = PenerimaBantuan::where('anggota_keluarga_id', $pengajuan->anggota_keluarga_id)
                ->where('tahun', date('Y'))
                ->first();

            if (!$existing) {
                PenerimaBantuan::create([
                    'anggota_keluarga_id' => $pengajuan->anggota_keluarga_id,
                    'nik' => $pengajuan->nik,
                    'no_kk' => $pengajuan->no_kk,
                    'jenis_bantuan' => ['BLT', 'Sembako'],
                    'tahun' => (int) date('Y'),
                    'status' => 'aktif',
                    'keterangan' => 'Disetujui dari pengajuan kurang mampu',
                ]);
            }
        }

        return redirect()->route('bantuan-sosial.kurang-mampu')
            ->with('success', 'Pengajuan berhasil ' . ($request->status === 'disetujui' ? 'disetujui' : 'ditolak') . '!');
    }

    /**
     * Get warga data via AJAX
     */
    public function getWarga(Request $request)
    {
        $anggota = AnggotaKeluarga::with('kartuKeluarga')
            ->where('id', $request->id)
            ->first();

        if (!$anggota) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'nik' => $anggota->nik,
            'no_kk' => $anggota->kartuKeluarga->no_kk ?? '-',
            'nama' => $anggota->nama_lengkap,
            'alamat' => $anggota->kartuKeluarga->alamat ?? '-',
        ]);
    }
}
