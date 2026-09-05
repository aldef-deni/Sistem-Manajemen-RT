<?php

namespace App\Http\Controllers;

use App\Support\SafeUpload;

use App\Models\StrukturRT;
use App\Models\PengurusRT;
use App\Models\SettingRT;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $struktur = StrukturRT::with(['pengurus' => function($q) {
            $q->orderBy('urutan');
        }])->first();

        $logoUrl = SettingRT::get('logo_rt_url', '/images/default-logo.png');
        $grupWa = SettingRT::get('grup_wa_link', '');
        $youtube = SettingRT::get('youtube_channel', '');
        $rumahIbadah = SettingRT::get('rumah_ibadah', '');

        return view('pengaturan.index', compact('struktur', 'logoUrl', 'grupWa', 'youtube', 'rumahIbadah'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_rt' => 'nullable|string|max:200',
            'nomor_rt' => 'nullable|string|max:20',
            'nomor_rw' => 'nullable|string|max:20',
            'alamat_rt' => 'nullable|string|max:500',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon_rt' => 'nullable|string|max:20',
            'email_rt' => 'nullable|email|max:100',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'peraturan' => 'nullable|string',
            'grup_wa' => 'nullable|string|max:500',
            'youtube' => 'nullable|string|max:500',
            'rumah_ibadah' => 'nullable|string',
        ]);

        $struktur = StrukturRT::firstOrCreate(['id' => 1], [
            'nama_rt' => 'RT 005',
            'nomor_rt' => '005',
            'nomor_rw' => '003',
            'alamat_rt' => 'Jl. Merdeka No. 10',
            'kelurahan' => 'Sukamaju',
            'kecamatan' => 'Cilandak',
            'kota' => 'Jakarta Selatan',
            'provinsi' => 'DKI Jakarta',
        ]);

        $struktur->update(collect($validated)->only([
            'nama_rt', 'nomor_rt', 'nomor_rw', 'alamat_rt', 'kelurahan',
            'kecamatan', 'kota', 'provinsi', 'kode_pos', 'telepon_rt', 'email_rt',
            'visi', 'misi', 'peraturan',
        ])->toArray());

        if ($request->has('grup_wa')) SettingRT::set('grup_wa_link', $request->grup_wa, 'Link Grup WhatsApp');
        if ($request->has('youtube')) SettingRT::set('youtube_channel', $request->youtube, 'Channel YouTube');
        if ($request->has('rumah_ibadah')) SettingRT::set('rumah_ibadah', $request->rumah_ibadah, 'Rumah Ibadah');

        return redirect()->route('pengaturan')->with('success', 'Pengaturan berhasil disimpan!');
    }

    public function tataTertib()
    {
        $struktur = StrukturRT::firstOrCreate(['id' => 1], [
            'nama_rt' => 'RT 005',
            'nomor_rt' => '005',
            'nomor_rw' => '003',
            'alamat_rt' => 'Jl. Merdeka No. 10',
            'kelurahan' => 'Sukamaju',
            'kecamatan' => 'Cilandak',
            'kota' => 'Jakarta Selatan',
            'provinsi' => 'DKI Jakarta',
        ]);

        return view('pengaturan.tata-tertib', compact('struktur'));
    }

    public function updateTataTertib(Request $request)
    {
        $validated = $request->validate([
            'peraturan' => 'nullable|string',
        ]);

        $struktur = StrukturRT::firstOrCreate(['id' => 1]);
        $struktur->update(['peraturan' => $validated['peraturan'] ?? null]);

        return redirect()->route('pengaturan.tata-tertib')->with('success', 'Tata tertib berhasil disimpan!');
    }

    public function kelolaPengurus()
    {
        $struktur = StrukturRT::with(['pengurus' => function($q) {
            $q->orderBy('urutan');
        }])->first();

        return view('pengaturan.kelola-pengurus', compact('struktur'));
    }

    public function storePengurus(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $struktur = StrukturRT::firstOrCreate(['id' => 1]);
        $validated['struktur_rt_id'] = $struktur->id;
        $validated['urutan'] = PengurusRT::where('struktur_rt_id', $struktur->id)->max('urutan') + 1;

        if ($request->hasFile('foto')) {
            $validated['foto'] = SafeUpload::store(
                $request->file('foto'),
                'pengurus',
                'pengurus',
                SafeUpload::IMAGE
            );
        }

        PengurusRT::create($validated);

        return redirect()->route('pengaturan.kelola-pengurus')->with('success', 'Pengurus berhasil ditambahkan!');
    }

    public function updatePengurus(Request $request, $id)
    {
        $pengurus = PengurusRT::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|string|in:aktif,tidak_aktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = SafeUpload::store(
                $request->file('foto'),
                'pengurus',
                'pengurus',
                SafeUpload::IMAGE
            );
        }

        $pengurus->update($validated);

        return redirect()->route('pengaturan.kelola-pengurus')->with('success', 'Pengurus berhasil diupdate!');
    }

    public function destroyPengurus($id)
    {
        $pengurus = PengurusRT::findOrFail($id);
        $pengurus->delete();

        return redirect()->route('pengaturan.kelola-pengurus')->with('success', 'Pengurus berhasil dihapus!');
    }
}
