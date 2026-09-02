@extends('layouts.app')

@section('title', 'Saran & Pengaduan')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Saran & Pengaduan</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Saran & Pengaduan</h1>
        </div>
        <a href="{{ route('pengaduan.create') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:12px;font-weight:600;font-size:14px;cursor:pointer;text-decoration:none;box-shadow:0 4px 12px rgba(16,185,129,0.3)">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Kirim Pesan
        </a>
    </div>

    @if(session('success'))
        <div style="padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;font-size:14px;font-weight:500">{{ session('success') }}</div>
    @endif

    {{-- Table --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
        <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:8px">
                <svg style="width:18px;height:18px;color:#64748b" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                <span style="font-weight:700;color:#1e293b;font-size:15px">Semua Pengaduan</span>
            </div>
        </div>

        <div style="padding:16px 20px">
            {{-- Filter --}}
            <form method="GET" style="display:flex;gap:12px;align-items:center;margin-bottom:16px;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="font-size:13px;color:#64748b">Tampilkan</span>
                    <select name="per_page" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span style="font-size:13px;color:#64748b">entri</span>
                </div>
                <div style="flex:1"></div>
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="font-size:13px;color:#64748b">Cari:</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="kata kunci pencarian"
                        style="padding:8px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none;width:220px" />
                </div>
            </form>

            {{-- Table --}}
            <div style="overflow-x:auto">
                <table style="width:100%;border-collapse:collapse;font-size:14px">
                    <thead>
                        <tr style="border-bottom:2px solid #e2e8f0">
                            <th style="text-align:left;padding:12px 16px;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:700">Kode Tiket</th>
                            <th style="text-align:left;padding:12px 16px;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:700">Pengirim</th>
                            <th style="text-align:left;padding:12px 16px;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:700">Judul</th>
                            <th style="text-align:left;padding:12px 16px;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:700">Kategori</th>
                            <th style="text-align:left;padding:12px 16px;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:700">Status</th>
                            <th style="text-align:left;padding:12px 16px;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:700">Tanggal</th>
                            <th style="text-align:center;padding:12px 16px;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;font-weight:700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengaduans as $p)
                            @php $sb = $p->status_badge; @endphp
                            <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.2s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <td style="padding:14px 16px">
                                    <span style="font-family:monospace;font-size:12px;padding:4px 10px;background:#f1f5f9;border-radius:6px;font-weight:600;color:#475569">{{ $p->kode_tiket }}</span>
                                </td>
                                <td style="padding:14px 16px;font-weight:500;color:#1e293b">{{ $p->user->name ?? '-' }}</td>
                                <td style="padding:14px 16px">
                                    <span style="font-weight:600;color:#1e293b">{{ $p->judul }}</span>
                                    @if($p->privasi === 'privat')
                                        <span style="margin-left:4px" title="Privat">🔒</span>
                                    @endif
                                </td>
                                <td style="padding:14px 16px">
                                    @if($p->kategori)
                                        @php $kb = $p->kategori_badge; @endphp
                                        <span style="padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500;{{$kb}}">{{ $p->kategori }}</span>
                                    @else
                                        <span style="color:#94a3b8">—</span>
                                    @endif
                                </td>
                                <td style="padding:14px 16px">
                                    <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;{{$sb['bg']}}">{{ $sb['icon'] }} {{ $sb['label'] }}</span>
                                </td>
                                <td style="padding:14px 16px;color:#64748b;font-size:13px">{{ $p->created_at->format('d/m/Y') }}</td>
                                <td style="padding:14px 16px;text-align:center">
                                    <a href="{{ route('pengaduan.show', $p) }}" style="display:inline-flex;align-items:center;gap:4px;padding:6px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;color:#16a34a;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.2s">
                                        👁 Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding:40px;text-align:center;color:#94a3b8">
                                    <svg style="width:48px;height:48px;margin:0 auto 12px;color:#cbd5e1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p style="font-size:14px">Belum ada pengaduan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pengaduans->hasPages())
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:16px;border-top:1px solid #f1f5f9">
                    <span style="font-size:13px;color:#64748b">Menampilkan {{ $pengaduans->firstItem() }} sampai {{ $pengaduans->lastItem() }} dari {{ $pengaduans->total() }} entri</span>
                    <div style="display:flex;gap:4px">
                        {{ $pengaduans->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
