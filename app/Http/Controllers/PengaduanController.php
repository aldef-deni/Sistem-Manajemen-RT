<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Support\SafeUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with(['user', 'replies']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_tiket', 'like', "%{$s}%")
                    ->orWhere('judul', 'like', "%{$s}%")
                    ->orWhere('kategori', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $pengaduans = $query->latest()->paginate(25)->withQueryString();

        return view('pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        return view('pengaduan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'isi_pengaduan' => 'required|string',
            'privasi' => 'required|in:publik,privat',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['kode_tiket'] = $this->generateKodeTiket();

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = SafeUpload::store(
                $request->file('lampiran'),
                'pengaduan',
                'lampiran',
                SafeUpload::DOCUMENT
            );
        }

        $pengaduan = Pengaduan::create($validated);

        Notification::send(
            User::query()
                ->whereIn('role', ['admin', 'ketua', 'pengurus'])
                ->where('id', '!=', Auth::id())
                ->get(),
            new SystemNotification(
                category: 'complaint',
                title: 'Pengaduan warga baru',
                message: Auth::user()->name.' mengirim pengaduan: '.$pengaduan->judul,
                routeName: 'pengaduan.show',
                routeParams: ['pengaduan' => $pengaduan->id],
                tone: 'amber',
                context: ['pengaduan_id' => $pengaduan->id],
            )
        );

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim! Kode tiket: '.$validated['kode_tiket']);
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load(['user', 'replies.user']);

        return view('pengaduan.show', compact('pengaduan'));
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'status' => 'required|in:diterima,diproses,selesai,ditolak',
        ]);

        $pengaduan->update($validated);

        if ($pengaduan->user_id !== Auth::id()) {
            $pengaduan->user?->notify(new SystemNotification(
                category: 'complaint',
                title: 'Status pengaduan diperbarui',
                message: 'Pengaduan '.$pengaduan->kode_tiket.' kini berstatus '.ucfirst($pengaduan->status).'.',
                routeName: 'pengaduan.show',
                routeParams: ['pengaduan' => $pengaduan->id],
                tone: $pengaduan->status === 'selesai' ? 'emerald' : ($pengaduan->status === 'ditolak' ? 'rose' : 'blue'),
                context: ['pengaduan_id' => $pengaduan->id],
            ));
        }

        return redirect()->route('pengaduan.show', $pengaduan)->with('success', 'Status pengaduan berhasil diupdate!');
    }

    public function balas(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'pesan' => 'required|string',
        ]);

        PengaduanBalasan::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => Auth::id(),
            'pesan' => $validated['pesan'],
        ]);

        $pengaduan->update([
            'balasan' => $validated['pesan'],
            'dibalas_oleh' => Auth::user()->name,
            'tanggal_balas' => now(),
        ]);

        if ($pengaduan->user_id !== Auth::id()) {
            $pengaduan->user?->notify(new SystemNotification(
                category: 'complaint',
                title: 'Balasan baru untuk pengaduan',
                message: Str::limit($validated['pesan'], 140),
                routeName: 'pengaduan.show',
                routeParams: ['pengaduan' => $pengaduan->id],
                tone: 'blue',
                context: ['pengaduan_id' => $pengaduan->id],
            ));
        }

        return redirect()->route('pengaduan.show', $pengaduan)->with('success', 'Balasan berhasil dikirim!');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dihapus!');
    }

    private function generateKodeTiket()
    {
        $date = now()->format('ymd');
        $last = Pengaduan::where('kode_tiket', 'like', "TKT{$date}%")->count() + 1;

        return 'TKT'.$date.str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
