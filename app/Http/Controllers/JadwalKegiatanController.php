<?php

namespace App\Http\Controllers;

use App\Models\JadwalKegiatan;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalKegiatan::with('penanggungJawab', 'pembuat');

        if ($kategori = $request->kategori) {
            $query->where('kategori', $kategori);
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $jadwal = $query->latest('tanggal_mulai')->paginate(15)->withQueryString();

        $todaySchedule = JadwalKegiatan::whereDate('tanggal_mulai', Carbon::today())
            ->where('status', 'aktif')
            ->with('penanggungJawab')
            ->get();

        $stats = [
            'total' => JadwalKegiatan::count(),
            'harian' => JadwalKegiatan::where('jenis_jadwal', 'Harian')->where('status', 'aktif')->count(),
            'mingguan' => JadwalKegiatan::where('jenis_jadwal', 'Mingguan')->where('status', 'aktif')->count(),
            'hari_ini' => $todaySchedule->count(),
        ];

        return view('jadwal-kegiatan.index', compact('jadwal', 'todaySchedule', 'stats'));
    }

    public function create()
    {
        $warga = AnggotaKeluarga::orderBy('nama_lengkap')->get();
        $kategoriList = ['Keamanan', 'Kebersihan', 'Sosial', 'Keagamaan', 'Olahraga', 'Gotong Royong', 'Lainnya'];
        $jenisList = ['Harian', 'Mingguan', 'Bulanan', 'Sekali'];

        return view('jadwal-kegiatan.create', compact('warga', 'kategoriList', 'jenisList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'jenis_jadwal' => 'required|string|max:50',
            'lokasi' => 'nullable|string|max:200',
            'penanggung_jawab_id' => 'nullable|exists:anggota_keluarga,id',
            'deskripsi' => 'nullable|string',
            'petugas' => 'nullable|array',
            'tanggal_mulai' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,selesai,dibatalkan',
        ]);

        $validated['dibuat_oleh'] = auth()->id();

        JadwalKegiatan::create($validated);

        return redirect()->route('jadwal-kegiatan.index')->with('success', 'Jadwal kegiatan berhasil dibuat!');
    }

    public function show($id)
    {
        $jadwal = JadwalKegiatan::with('penanggungJawab', 'pembuat')->findOrFail($id);
        return view('jadwal-kegiatan.show', compact('jadwal'));
    }

    public function edit($id)
    {
        $jadwal = JadwalKegiatan::findOrFail($id);
        $warga = AnggotaKeluarga::orderBy('nama_lengkap')->get();
        $kategoriList = ['Keamanan', 'Kebersihan', 'Sosial', 'Keagamaan', 'Olahraga', 'Gotong Royong', 'Lainnya'];
        $jenisList = ['Harian', 'Mingguan', 'Bulanan', 'Sekali'];

        return view('jadwal-kegiatan.edit', compact('jadwal', 'warga', 'kategoriList', 'jenisList'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalKegiatan::findOrFail($id);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'jenis_jadwal' => 'required|string|max:50',
            'lokasi' => 'nullable|string|max:200',
            'penanggung_jawab_id' => 'nullable|exists:anggota_keluarga,id',
            'deskripsi' => 'nullable|string',
            'petugas' => 'nullable|array',
            'tanggal_mulai' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'tanggal_selesai' => 'nullable|date',
            'status' => 'required|in:aktif,selesai,dibatalkan',
        ]);

        $jadwal->update($validated);

        return redirect()->route('jadwal-kegiatan.index')->with('success', 'Jadwal kegiatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jadwal = JadwalKegiatan::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal-kegiatan.index')->with('success', 'Jadwal kegiatan berhasil dihapus!');
    }
}
