@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('pengaduan.index') }}" class="text-emerald-600 hover:underline font-medium">Saran & Pengaduan</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Pengaduan</h1>
        </div>
        <a href="{{ route('pengaduan.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid #e2e8f0;border-radius:10px;color:#64748b;font-size:13px;font-weight:500;text-decoration:none;background:#fff">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div style="padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;font-size:14px;font-weight:500">{{ session('success') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
        <div class="space-y-6">
            {{-- Detail --}}
            @php $sb = $pengaduan->status_badge; @endphp
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:10px">
                        <span style="font-family:monospace;font-size:13px;padding:4px 12px;background:#f1f5f9;border-radius:6px;font-weight:600;color:#475569">{{ $pengaduan->kode_tiket }}</span>
                        @if($pengaduan->privasi === 'privat')
                            <span style="padding:3px 10px;border-radius:20px;font-size:11px;background:#fef3c7;color:#d97706;font-weight:600">🔒 Privat</span>
                        @else
                            <span style="padding:3px 10px;border-radius:20px;font-size:11px;background:#dbeafe;color:#2563eb;font-weight:600">🌍 Publik</span>
                        @endif
                    </div>
                    <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;{{$sb['bg']}}">{{ $sb['icon'] }} {{ $sb['label'] }}</span>
                </div>
                <div style="padding:20px">
                    <h2 style="font-size:20px;font-weight:700;color:#1e293b;margin:0 0 8px 0">{{ $pengaduan->judul }}</h2>
                    @if($pengaduan->kategori)
                        @php $kb = $pengaduan->kategori_badge; @endphp
                        <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500;margin-bottom:12px;{{$kb}}">{{ $pengaduan->kategori }}</span>
                    @endif
                    <div style="font-size:14px;color:#475569;line-height:1.8;white-space:pre-line;margin-top:8px">{{ $pengaduan->isi_pengaduan }}</div>
                    @if($pengaduan->lampiran)
                        <div style="margin-top:16px;padding:12px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0">
                            <div style="display:flex;align-items:center;gap:8px">
                                <svg style="width:18px;height:18px;color:#64748b" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <a href="{{ asset($pengaduan->lampiran) }}" target="_blank" style="font-size:13px;color:#2563eb;font-weight:500;text-decoration:none">Lampiran</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Balasan --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                    <svg style="width:18px;height:18px;color:#64748b" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span style="font-weight:700;color:#1e293b;font-size:15px">Balasan</span>
                </div>
                <div style="padding:16px 20px">
                    @forelse($pengaduan->replies as $b)
                        <div style="padding:12px;background:{{ $b->user->role === 'admin' ? '#f0fdf4' : '#f8fafc' }};border-radius:10px;margin-bottom:10px;border:1px solid {{ $b->user->role === 'admin' ? '#bbf7d0' : '#e2e8f0' }}">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                                <div style="width:28px;height:28px;border-radius:50%;background:{{ $b->user->role === 'admin' ? 'linear-gradient(135deg,#10b981,#059669)' : 'linear-gradient(135deg,#3b82f6,#2563eb)' }};display:flex;align-items:center;justify-content:center">
                                    <span style="color:#fff;font-size:11px;font-weight:700">{{ strtoupper(substr($b->user->name, 0, 1)) }}</span>
                                </div>
                                <span style="font-weight:600;font-size:13px;color:#1e293b">{{ $b->user->name }}</span>
                                @if($b->user->role === 'admin')
                                    <span style="padding:2px 8px;border-radius:10px;font-size:10px;background:#dcfce7;color:#16a34a;font-weight:600">Admin</span>
                                @endif
                                <span style="font-size:11px;color:#94a3b8;margin-left:auto">{{ $b->created_at->diffForHumans() }}</span>
                            </div>
                            <div style="font-size:13px;color:#475569;line-height:1.6">{{ $b->pesan }}</div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px">Belum ada balasan</div>
                    @endforelse

                    {{-- Form Balas --}}
                    <form action="{{ route('pengaduan.balas', $pengaduan) }}" method="POST" style="margin-top:16px">
                        @csrf
                        <div style="display:flex;gap:8px">
                            <input type="text" name="pesan" placeholder="Tulis balasan..." required
                                style="flex:1;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:13px;outline:none" />
                            <button type="submit" style="padding:10px 16px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:10px;cursor:pointer;font-size:13px;font-weight:600">
                                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9">
                    <span style="font-weight:700;color:#1e293b;font-size:15px">Ringkasan</span>
                </div>
                <div style="padding:16px 20px">
                    <div style="display:flex;flex-direction:column;gap:12px">
                        <div style="display:flex;justify-content:space-between">
                            <span style="font-size:13px;color:#64748b">Pengirim</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $pengaduan->user->name ?? '-' }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="font-size:13px;color:#64748b">Kategori</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $pengaduan->kategori ?? '-' }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="font-size:13px;color:#64748b">Privasi</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $pengaduan->privasi === 'publik' ? '🌍 Publik' : '🔒 Privat' }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="font-size:13px;color:#64748b">Tanggal</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $pengaduan->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div style="border-top:1px solid #f1f5f9;padding-top:12px">
                            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Update Status</label>
                            <form action="{{ route('pengaduan.status', $pengaduan) }}" method="POST">
                                @csrf @method('PATCH')
                                <div style="display:flex;flex-direction:column;gap:6px">
                                    @foreach(['diterima' => '📨 Diterima', 'diproses' => '🔄 Diproses', 'selesai' => '✅ Selesai', 'ditolak' => '❌ Ditolak'] as $val => $lbl)
                                        <label style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;cursor:pointer;border:1px solid {{ $pengaduan->status === $val ? '#10b981' : '#e2e8f0' }};background:{{ $pengaduan->status === $val ? '#f0fdf4' : '#fff' }}">
                                            <input type="radio" name="status" value="{{ $val }}" {{ $pengaduan->status === $val ? 'checked' : '' }} onchange="this.form.submit()" style="accent-color:#10b981" />
                                            <span style="font-size:13px;font-weight:500;color:#374151">{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengaduan.destroy', $pengaduan) }}" method="POST" onsubmit="return confirm('Yakin hapus pengaduan ini?')">
                @csrf @method('DELETE')
                <button type="submit" style="width:100%;padding:10px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#dc2626;font-weight:600;font-size:13px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Pengaduan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
