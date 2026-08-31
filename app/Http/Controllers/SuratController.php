<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::with('anggotaKeluarga.kartuKeluarga');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_surat', 'like', "%{$search}%")
                  ->orWhere('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('jenis_surat', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%");
            });
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($jenis = $request->jenis_surat) {
            $query->where('jenis_surat', $jenis);
        }

        $surat = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Surat::count(),
            'pending' => Surat::where('status', 'pending')->count(),
            'diproses' => Surat::where('status', 'diproses')->count(),
            'selesai' => Surat::where('status', 'selesai')->count(),
        ];

        $jenisList = Surat::select('jenis_surat')->distinct()->pluck('jenis_surat');

        return view('surat.index', compact('surat', 'stats', 'jenisList'));
    }

    public function create()
    {
        $warga = AnggotaKeluarga::with('kartuKeluarga')->orderBy('nama_lengkap')->get();
        $jenisSurat = [
            'Surat Keterangan Domisili',
            'Surat Keterangan Usaha',
            'Surat Keterangan Tidak Mampu',
            'Surat Pengantar',
            'SKCK',
            'Surat Keterangan Kelakuan Baik',
            'Surat Keterangan Pindah',
            'Surat Keterangan Penghasilan',
            'Surat Keterangan Belum Menikah',
            'Lainnya',
        ];

        return view('surat.create', compact('warga', 'jenisSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'jenis_surat' => 'required|string|max:100',
            'keperluan' => 'required|string',
            'file_dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $warga = AnggotaKeluarga::findOrFail($request->anggota_keluarga_id);
        $validated['nama_pemohon'] = $warga->nama_lengkap;
        $validated['nik'] = $warga->nik;

        // Generate kode surat
        $today = now()->format('ymd');
        $count = Surat::whereDay('created_at', now()->day)->count() + 1;
        $validated['kode_surat'] = 'SR-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Handle file upload
        if ($request->hasFile('file_dokumen')) {
            $validated['file_dokumen'] = $request->file('file_dokumen')->store('dokumen-surat', 'public');
        }

        Surat::create($validated);

        return redirect()->route('surat.index')->with('success', 'Permohonan surat berhasil diajukan!');
    }

    public function show($id)
    {
        $surat = Surat::with('anggotaKeluarga.kartuKeluarga', 'pemroses')->findOrFail($id);
        return view('surat.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = Surat::findOrFail($id);
        $warga = AnggotaKeluarga::with('kartuKeluarga')->orderBy('nama_lengkap')->get();
        $jenisSurat = [
            'Surat Keterangan Domisili',
            'Surat Keterangan Usaha',
            'Surat Keterangan Tidak Mampu',
            'Surat Pengantar',
            'SKCK',
            'Surat Keterangan Kelakuan Baik',
            'Surat Keterangan Pindah',
            'Surat Keterangan Penghasilan',
            'Surat Keterangan Belum Menikah',
            'Lainnya',
        ];

        return view('surat.edit', compact('surat', 'warga', 'jenisSurat'));
    }

    public function update(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);

        $validated = $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'jenis_surat' => 'required|string|max:100',
            'keperluan' => 'required|string',
            'file_dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status' => 'required|in:pending,diproses,selesai,ditolak',
            'nomor_surat' => 'nullable|string|max:50',
            'catatan_admin' => 'nullable|string',
        ]);

        if ($request->hasFile('file_dokumen')) {
            $validated['file_dokumen'] = $request->file('file_dokumen')->store('dokumen-surat', 'public');
        }

        if ($request->status !== $surat->status) {
            if ($request->status === 'diproses' && !$surat->tanggal_proses) {
                $validated['tanggal_proses'] = now()->toDateString();
            }
            if ($request->status === 'selesai') {
                $validated['tanggal_selesai'] = now()->toDateString();
            }
        }

        $surat->update($validated);

        return redirect()->route('surat.show', $id)->with('success', 'Data surat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->delete();

        return redirect()->route('surat.index')->with('success', 'Permohonan surat berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak',
            'nomor_surat' => 'nullable|string|max:50',
            'catatan_admin' => 'nullable|string',
        ]);

        $updateData = $validated;

        if ($validated['status'] === 'diproses' && !$surat->tanggal_proses) {
            $updateData['tanggal_proses'] = now()->toDateString();
        }
        if ($validated['status'] === 'selesai') {
            $updateData['tanggal_selesai'] = now()->toDateString();
        }

        $surat->update($updateData);

        return redirect()->back()->with('success', 'Status surat berhasil diperbarui!');
    }
}
