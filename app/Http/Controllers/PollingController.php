<?php

namespace App\Http\Controllers;

use App\Models\Polling;
use App\Models\PollingVote;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

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

        $polling = Polling::create($validated);
        $this->notifyUsers(
            'Polling baru',
            $polling->judul.' — '.Str::limit(strip_tags((string) $polling->deskripsi), 110),
            $polling,
            false,
        );

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
        $this->notifyUsers('Polling diperbarui', 'Polling “'.$polling->judul.'” telah diperbarui.', $polling);

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
        $this->notifyUsers('Polling ditutup', 'Polling “'.$polling->judul.'” telah ditutup.', $polling);
        return back()->with('success', 'Polling berhasil ditutup!');
    }

    public function complete(Polling $polling)
    {
        $polling->update(['status' => 'selesai']);
        $this->notifyUsers('Polling selesai', 'Hasil polling “'.$polling->judul.'” telah tersedia.', $polling);
        return back()->with('success', 'Polling ditandai selesai!');
    }

    public function destroy(Polling $polling)
    {
        $judul = $polling->judul;
        $polling->delete();
        $this->notifyUsers('Polling dihapus', 'Polling “'.$judul.'” dihapus oleh '.Auth::user()->name.'.', null, true);
        return redirect()->route('polling.index')->with('success', 'Polling berhasil dihapus!');
    }

    private function notifyUsers(string $title, string $message, ?Polling $polling, bool $managersOnly = false): void
    {
        Notification::send(
            User::query()->when($managersOnly, fn ($query) => $query->whereIn('role', ['admin', 'ketua']))->get(),
            new SystemNotification(
                category: 'polling',
                title: $title,
                message: $message,
                routeName: $polling ? 'polling.show' : 'polling.index',
                routeParams: $polling ? ['polling' => $polling->id] : [],
                tone: $polling?->status === 'aktif' ? 'emerald' : 'blue',
                context: $polling ? ['polling_id' => $polling->id] : [],
            )
        );
    }
}
