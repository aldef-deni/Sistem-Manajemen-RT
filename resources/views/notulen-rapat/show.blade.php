@extends('layouts.app')

@section('title', 'Detail Notulen Rapat')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('notulen-rapat.index') }}" class="text-emerald-600 hover:underline font-medium">Notulen Rapat</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $notulen->judul_rapat }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('notulen-rapat.edit', $notulen) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-medium transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form action="{{ route('notulen-rapat.destroy', $notulen) }}" method="POST" onsubmit="return confirm('Yakin hapus notulen ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-medium transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Info Rapat --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span style="font-weight:600;color:#1e293b;font-size:15px">Informasi Rapat</span>
                </div>
                <div style="padding:20px">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div>
                            <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px">Judul Rapat</div>
                            <div style="font-weight:600;color:#1e293b">{{ $notulen->judul_rapat }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px">Tanggal</div>
                            <div style="font-weight:600;color:#1e293b">{{ $notulan->tanggal->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px">Waktu</div>
                            <div style="font-weight:600;color:#1e293b">{{ $notulen->waktu_mulai }} - {{ $notulen->waktu_selesai }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px">Tempat</div>
                            <div style="font-weight:600;color:#1e293b">{{ $notulen->tempat }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px">Tim / Proyek</div>
                            <div style="font-weight:600;color:#1e293b">{{ $notulen->tim_proyek ?: '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px">Status</div>
                            @php $sb = $notulen->status_badge; @endphp
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:20px;font-size:13px;font-weight:600;{{ str_replace('bg-', 'background:', str_replace('text-', 'color:', explode(' ', $sb['bg'])[0])) }}">
                                {{ $sb['icon'] }} {{ $sb['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Hadir --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center">
                            <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14zM9 7a3 3 0 116 0 3 3 0 01-6 0z"/></svg>
                        </div>
                        <span style="font-weight:600;color:#1e293b;font-size:15px">Daftar Hadir</span>
                    </div>
                    <span style="font-size:13px;color:#64748b">{{ $notulen->hadir->where('hadir', true)->count() }}/{{ $notulen->hadir->count() }} hadir</span>
                </div>
                <div style="padding:16px 20px">
                    @forelse($notulen->hadir as $h)
                        <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f8fafc">
                            <div style="width:36px;height:36px;border-radius:50%;{{ $h->hadir ? 'background:linear-gradient(135deg,#10b981,#059669)' : 'background:#f1f5f9' }};display:flex;align-items:center;justify-content:center">
                                @if($h->hadir)
                                    <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <svg style="width:16px;height:16px;color:#94a3b8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                @endif
                            </div>
                            <div style="flex:1">
                                <div style="font-weight:600;color:#1e293b;font-size:14px">{{ $h->nama_peserta }}</div>
                                @if($h->ulasan)
                                    <div style="font-size:12px;color:#94a3b8;margin-top:2px">{{ $h->ulasan }}</div>
                                @endif
                            </div>
                            <span style="font-size:12px;padding:3px 10px;border-radius:12px;font-weight:500;{{ $h->hadir ? 'background:#dcfce7;color:#16a34a' : 'background:#f1f5f9;color:#94a3b8' }}">
                                {{ $h->hadir ? 'Hadir' : 'Tidak Hadir' }}
                            </span>
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:14px">Belum ada data kehadiran</div>
                    @endforelse
                </div>
            </div>

            {{-- Poin Pembahasan --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span style="font-weight:600;color:#1e293b;font-size:15px">Poin Pembahasan</span>
                </div>
                <div style="padding:16px 20px">
                    @forelse($notulen->poin as $i => $p)
                        <div style="display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid #f8fafc">
                            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <span style="color:#fff;font-size:12px;font-weight:700">{{ $i + 1 }}</span>
                            </div>
                            <div style="flex:1;font-size:14px;color:#334155;line-height:1.6">{{ $p->topik }}</div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:14px">Belum ada poin pembahasan</div>
                    @endforelse
                </div>
            </div>

            {{-- Catatan --}}
            @if($notulen->catatan)
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <span style="font-weight:600;color:#1e293b;font-size:15px">Catatan & Kesimpulan</span>
                </div>
                <div style="padding:20px;font-size:14px;color:#475569;line-height:1.7;white-space:pre-line">{{ $notulen->catatan }}</div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Ringkasan --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9">
                    <span style="font-weight:600;color:#1e293b;font-size:15px">Ringkasan</span>
                </div>
                <div style="padding:16px 20px">
                    <div style="display:flex;flex-direction:column;gap:12px">
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;color:#64748b">Moderator</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $notulen->moderator }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;color:#64748b">Notulis</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $notulen->notulis }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;color:#64748b">Peserta</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $notulen->hadir->count() }} orang</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;color:#64748b">Hadir</span>
                            <span style="font-size:13px;font-weight:600;color:#16a34a">{{ $notulen->hadir->where('hadir', true)->count() }} orang</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;color:#64748b">Poin Bahas</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $notulen->poin->count() }} poin</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;color:#64748b">Dilihat</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $notulen->dilihat }} kali</span>
                        </div>
                        <div style="border-top:1px solid #f1f5f9;padding-top:12px;display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;color:#64748b">Dibuat oleh</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $notulen->user->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aksi --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9">
                    <span style="font-weight:600;color:#1e293b;font-size:15px">Aksi</span>
                </div>
                <div style="padding:16px 20px;display:flex;flex-direction:column;gap:8px">
                    <a href="{{ route('notulen-rapat.edit', $notulen) }}" style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;color:#d97706;font-weight:600;font-size:13px;text-decoration:none;transition:all 0.2s">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Notulen
                    </a>
                    <form action="{{ route('notulen-rapat.destroy', $notulen) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus notulen ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#dc2626;font-weight:600;font-size:13px;width:100%;cursor:pointer;transition:all 0.2s">
                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Notulen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
