<?php

namespace App\Http\Controllers;

use App\Models\Polling;
use App\Models\PollingVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollingController extends Controller
{
    public function index(Request $request)
    {
        $query = Polling::withCount('votes');

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $pollings = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'semua' => Polling::count(),
            'aktif' => Polling::where('status', 'aktif')->count(),
            'selesai' => Polling::where('status', 'selesai')->count(),
            'ditutup' => Polling::where('status', 'ditutup')->count(),
        ];

        return view('polling.index', compact('pollings', 'stats'));
    }

    public function create()
    {
        return view('polling.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'opsi' => 'required|array|min:2',
            'opsi.*' => 'required|string|max:100',
            'tampilkan_hasil' => 'nullable|boolean',
            'izinkan_ganti' => 'nullable|boolean',
            'anonim' => 'nullable|boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['tampilkan_hasil'] = $request->boolean('tampilkan_hasil');
        $validated['izinkan_ganti'] = $request->boolean('izinkan_ganti');
        $validated['anonim'] = $request->boolean('anonim');
        $validated['status'] = 'aktif';

        Polling::create($validated);

        return redirect()->route('polling.index')->with('success', 'Polling berhasil dibuat!');
    }

    public function show(Polling $polling)
    {
        $polling->loadCount('votes');
        $results = $polling->getResults();
        $userVote = $polling->userVote(Auth::id());

        return view('polling.show', compact('polling', 'results', 'userVote'));
    }

    public function edit(Polling $polling)
    {
        return view('polling.edit', compact('polling'));
    }

    public function update(Request $request, Polling $polling)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'opsi' => 'required|array|min:2',
            'opsi.*' => 'required|string|max:100',
            'tampilkan_hasil' => 'nullable|boolean',
            'izinkan_ganti' => 'nullable|boolean',
            'anonim' => 'nullable|boolean',
        ]);

        $validated['tampilkan_hasil'] = $request->boolean('tampilkan_hasil');
        $validated['izinkan_ganti'] = $request->boolean('izinkan_ganti');
        $validated['anonim'] = $request->boolean('anonim');

        $polling->update($validated);

        return redirect()->route('polling.show', $polling)->with('success', 'Polling berhasil diupdate!');
    }

    public function vote(Request $request, Polling $polling)
    {
        $request->validate([
            'pilihan' => 'required|string|in:' . implode(',', $polling->opsi),
        ]);

        if ($polling->status !== 'aktif') {
            return back()->with('error', 'Polling sudah tidak aktif!');
        }

        $existingVote = $polling->userVote(Auth::id());

        if ($existingVote) {
            if (!$polling->izinkan_ganti) {
                return back()->with('error', 'Anda sudah memberikan suara dan tidak diperbolehkan mengganti!');
            }
            $existingVote->update(['pilihan' => $request->pilihan]);
        } else {
            PollingVote::create([
                'polling_id' => $polling->id,
                'user_id' => Auth::id(),
                'pilihan' => $request->pilihan,
            ]);
            $polling->increment('jumlah_suara');
        }

        return back()->with('success', 'Suara berhasil dicatat!');
    }

    public function close(Polling $polling)
    {
        $polling->update(['status' => 'ditutup']);
        return back()->with('success', 'Polling berhasil ditutup!');
    }

    public function complete(Polling $polling)
    {
        $polling->update(['status' => 'selesai']);
        return back()->with('success', 'Polling ditandai selesai!');
    }

    public function destroy(Polling $polling)
    {
        $polling->delete();
        return redirect()->route('polling.index')->with('success', 'Polling berhasil dihapus!');
    }
}
