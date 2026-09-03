@extends('layouts.app')

@section('title', 'Polling Warga')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Polling</span>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <h1 class="text-2xl font-bold text-slate-800">Polling Warga</h1>
                <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#10b981,#059669);color:#fff;font-size:13px;font-weight:700">{{ $stats['semua'] }}</span>
            </div>
        </div>
        <a href="{{ route('polling.create') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:12px;font-weight:600;font-size:14px;cursor:pointer;text-decoration:none;box-shadow:0 4px 12px rgba(16,185,129,0.3)">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Buat Polling
        </a>
    </div>

    @if(session('success'))
        <div style="padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;font-size:14px;font-weight:500">{{ session('success') }}</div>
    @endif

    {{-- Status Tabs --}}
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        @php $currentStatus = request('status', 'semua'); @endphp
        @foreach(['semua' => '📋 Semua', 'aktif' => '🟢 Aktif', 'selesai' => '🔵 Selesai', 'ditutup' => '🔒 Ditutup'] as $key => $label)
            <a href="{{ route('polling.index', ['status' => $key]) }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:20px;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.2s;{{ $currentStatus === $key ? 'background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 2px 8px rgba(16,185,129,0.3)' : 'background:#fff;color:#64748b;border:1px solid #e2e8f0' }}">
                {{ $label }}
                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:22px;height:22px;border-radius:11px;font-size:11px;font-weight:700;{{ $currentStatus === $key ? 'background:rgba(255,255,255,0.25)' : 'background:#f1f5f9;color:#64748b' }}">{{ $stats[$key] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Polling Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($pollings as $p)
            @php $sb = $p->status_badge; $results = $p->getResults(); @endphp
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;transition:all 0.2s" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                <div style="padding:20px">
                    {{-- Header --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                        <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;{{$sb['bg']}}">{{ $sb['icon'] }} {{ $sb['label'] }}</span>
                        <span style="display:flex;align-items:center;gap:4px;font-size:12px;color:#64748b">
                            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14z"/></svg>
                            {{ $p->votes_count }} suara
                        </span>
                    </div>

                    <h3 style="font-size:18px;font-weight:700;color:#1e293b;margin:0 0 6px 0">{{ $p->judul }}</h3>
                    @if($p->deskripsi)
                        <p style="font-size:13px;color:#64748b;margin:0 0 16px 0;line-height:1.5">{{ \Illuminate\Support\Str::limit($p->deskripsi, 100) }}</p>
                    @endif

                    {{-- Progress Bars --}}
                    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px">
                        @foreach($results as $op => $data)
                            <div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                    <span style="font-size:13px;font-weight:500;color:#374151">{{ $op }}</span>
                                    <span style="font-size:13px;font-weight:700;color:#10b981">{{ $data['percent'] }}%</span>
                                </div>
                                <div style="width:100%;height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                                    <div style="width:{{ $data['percent'] }}%;height:100%;background:linear-gradient(90deg,#10b981,#059669);border-radius:4px;transition:width 0.5s"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Date --}}
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:16px">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $p->tanggal_mulai->format('d M Y') }} — {{ $p->tanggal_selesai ? $p->tanggal_selesai->format('d M Y') : 'Tidak ada batas' }}
                    </div>

                    {{-- Detail Button --}}
                    <a href="{{ route('polling.show', $p) }}" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border-radius:10px;font-weight:600;font-size:13px;text-decoration:none;margin-bottom:12px">
                        👁 Lihat Detail
                    </a>

                    {{-- Action Buttons --}}
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        <a href="{{ route('polling.edit', $p) }}" style="display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border:1px solid #e2e8f0;border-radius:8px;background:#fff;color:#64748b;font-size:12px;font-weight:500;text-decoration:none">
                            ✏️ Edit
                        </a>
                        @if($p->status === 'aktif')
                            <form action="{{ route('polling.close', $p) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border:1px solid #fde68a;border-radius:8px;background:#fffbeb;color:#d97706;font-size:12px;font-weight:500;cursor:pointer">🔒 Tutup</button>
                            </form>
                            <form action="{{ route('polling.complete', $p) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border:1px solid #bbf7d0;border-radius:8px;background:#f0fdf4;color:#16a34a;font-size:12px;font-weight:500;cursor:pointer">✅ Selesai</button>
                            </form>
                        @endif
                        <form action="{{ route('polling.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin hapus polling ini?')" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" style="display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border:1px solid #fecaca;border-radius:8px;background:#fef2f2;color:#dc2626;font-size:12px;font-weight:500;cursor:pointer">🗑 Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column:span 2;text-align:center;padding:60px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:16px">
                <svg style="width:64px;height:64px;margin:0 auto 16px;color:#cbd5e1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <p style="font-size:16px;color:#64748b;font-weight:500">Belum ada polling</p>
                <a href="{{ route('polling.create') }}" style="display:inline-block;margin-top:12px;color:#10b981;font-weight:600;font-size:14px;text-decoration:none">+ Buat Polling Baru</a>
            </div>
        @endforelse
    </div>

    @if($pollings->hasPages())
        <div style="display:flex;justify-content:center">{{ $pollings->links() }}</div>
    @endif
</div>
@endsection
