<?php

namespace App\Http\Controllers;

use App\Models\NotulenRapat;
use App\Models\NotulenHadir;
use App\Models\NotulenPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotulenRapatController extends Controller
{
    public function index(Request $request)
    {
        $query = NotulenRapat::with(['hadir', 'poin', 'user']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul_rapat', 'like', "%{$s}%")
                  ->orWhere('moderator', 'like', "%{$s}%")
                  ->orWhere('tempat', 'like', "%{$s}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tim_proyek')) {
            $query->where('tim_proyek', $request->tim_proyek);
        }
        if ($request->filled('dari_tanggal')) {
            $query->where('tanggal', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->where('tanggal', '<=', $request->sampai_tanggal);
        }

        $notulens = $query->latest('tanggal')->paginate(10)->withQueryString();

        $stats = [
            'total' => NotulenRapat::count(),
            'final' => NotulenRapat::where('status', 'final')->count(),
            'menunggu' => NotulenRapat::where('status', 'menunggu')->count(),
            'draft' => NotulenRapat::where('status', 'draft')->count(),
        ];

        return view('notulen-rapat.index', compact('notulens', 'stats'));
    }

    public function create()
    {
        return view('notulen-rapat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_rapat' => 'required|string|max:200',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'tempat' => 'required|string|max:200',
            'tim_proyek' => 'nullable|string|max:100',
            'moderator' => 'required|string|max:100',
            'notulis' => 'required|string|max:100',
            'catatan' => 'nullable|string',
            'status' => 'required|in:draft,menunggu,final',
            'peserta_nama.*' => 'nullable|string|max:100',
            'peserta_ulasan.*' => 'nullable|string',
            'peserta_hadir.*' => 'nullable',
            'poin_topik.*' => 'nullable|string|max:200',
        ]);

        $validated['user_id'] = Auth::id();
        unset($validated['peserta_nama'], $validated['peserta_ulasan'], $validated['peserta_hadir'], $validated['poin_topik']);

        $notulen = NotulenRapat::create($validated);

        // Save hadir
        if ($request->filled('peserta_nama')) {
            foreach ($request->peserta_nama as $i => $nama) {
                if (!empty($nama)) {
                    NotulenHadir::create([
                        'notulen_rapat_id' => $notulen->id,
                        'nama_peserta' => $nama,
                        'ulasan' => $request->peserta_ulasan[$i] ?? null,
                        'hadir' => isset($request->peserta_hadir[$i]) && $request->peserta_hadir[$i] == '1',
                    ]);
                }
            }
        }

        // Save poin
        if ($request->filled('poin_topik')) {
            $urutan = 0;
            foreach ($request->poin_topik as $topik) {
                if (!empty($topik)) {
                    NotulenPoin::create([
                        'notulen_rapat_id' => $notulen->id,
                        'topik' => $topik,
                        'urutan' => $urutan++,
                    ]);
                }
            }
        }

        return redirect()->route('notulen-rapat.show', $notulen)->with('success', 'Notulen berhasil dibuat!');
    }

    public function show(NotulenRapat $notulenRapat)
    {
        $notulenRapat->increment('dilihat');
        $notulenRapat->load(['hadir', 'poin', 'user']);
        return view('notulen-rapat.show', ['notulen' => $notulenRapat]);
    }

    public function edit(NotulenRapat $notulenRapat)
    {
        $notulenRapat->load(['hadir', 'poin']);
        return view('notulen-rapat.edit', ['notulen' => $notulenRapat]);
    }

    public function update(Request $request, NotulenRapat $notulenRapat)
    {
        $validated = $request->validate([
            'judul_rapat' => 'required|string|max:200',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'tempat' => 'required|string|max:200',
            'tim_proyek' => 'nullable|string|max:100',
            'moderator' => 'required|string|max:100',
            'notulis' => 'required|string|max:100',
            'catatan' => 'nullable|string',
            'status' => 'required|in:draft,menunggu,final',
        ]);

        unset($validated['peserta_nama'], $validated['peserta_ulasan'], $validated['peserta_hadir'], $validated['poin_topik']);
        $notulenRapat->update($validated);

        // Re-sync hadir
        $notulenRapat->hadir()->delete();
        if ($request->filled('peserta_nama')) {
            foreach ($request->peserta_nama as $i => $nama) {
                if (!empty($nama)) {
                    NotulenHadir::create([
                        'notulen_rapat_id' => $notulenRapat->id,
                        'nama_peserta' => $nama,
                        'ulasan' => $request->peserta_ulasan[$i] ?? null,
                        'hadir' => isset($request->peserta_hadir[$i]) && $request->peserta_hadir[$i] == '1',
                    ]);
                }
            }
        }

        // Re-sync poin
        $notulenRapat->poin()->delete();
        if ($request->filled('poin_topik')) {
            $urutan = 0;
            foreach ($request->poin_topik as $topik) {
                if (!empty($topik)) {
                    NotulenPoin::create([
                        'notulen_rapat_id' => $notulenRapat->id,
                        'topik' => $topik,
                        'urutan' => $urutan++,
                    ]);
                }
            }
        }

        return redirect()->route('notulen-rapat.show', $notulenRapat)->with('success', 'Notulen berhasil diupdate!');
    }

    public function destroy(NotulenRapat $notulenRapat)
    {
        $notulenRapat->delete();
        return redirect()->route('notulen-rapat.index')->with('success', 'Notulen berhasil dihapus!');
    }
}
