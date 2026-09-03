@extends('layouts.app')

@section('title', 'Detail Polling')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('polling.index') }}" class="text-emerald-600 hover:underline font-medium">Polling</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $polling->judul }}</h1>
        </div>
        <a href="{{ route('polling.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid #e2e8f0;border-radius:10px;color:#64748b;font-size:13px;font-weight:500;text-decoration:none;background:#fff">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div style="padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;font-size:14px;font-weight:500">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;font-size:14px;font-weight:500">{{ session('error') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
        <div class="space-y-6">
            @php $sb = $polling->status_badge; @endphp
            {{-- Info --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:20px">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                        <span style="padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;{{$sb['bg']}}">{{ $sb['icon'] }} {{ $sb['label'] }}</span>
                        <span style="display:flex;align-items:center;gap:4px;font-size:13px;color:#64748b">
                            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14z"/></svg>
                            {{ $polling->votes_count }} suara
                        </span>
                    </div>
                    @if($polling->deskripsi)
                        <p style="font-size:14px;color:#475569;line-height:1.7;margin:0 0 16px 0">{{ $polling->deskripsi }}</p>
                    @endif
                    <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#94a3b8">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $polling->tanggal_mulai->format('d M Y') }} — {{ $polling->tanggal_selesai ? $polling->tanggal_selesai->format('d M Y') : 'Tidak ada batas' }}
                    </div>
                </div>
            </div>

            {{-- Voting / Results --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9">
                    <span style="font-weight:700;color:#1e293b;font-size:15px">{{ $polling->status === 'aktif' && (!$userVote || $polling->izinkan_ganti) ? '🗳️ Pilih Jawaban' : '📊 Hasil Polling' }}</span>
                </div>
                <div style="padding:20px">
                    @if($polling->status === 'aktif' && (!$userVote || $polling->izinkan_ganti))
                        {{-- Voting Form --}}
                        <form action="{{ route('polling.vote', $polling) }}" method="POST">
                            @csrf
                            <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px">
                                @foreach($polling->opsi as $op)
                                    <label style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:2px solid {{ ($userVote && $userVote->pilihan === $op) ? '#10b981' : '#e2e8f0' }};border-radius:12px;cursor:pointer;transition:all 0.2s;background:{{ ($userVote && $userVote->pilihan === $op) ? '#f0fdf4' : '#fff' }}">
                                        <input type="radio" name="pilihan" value="{{ $op }}" {{ ($userVote && $userVote->pilihan === $op) ? 'checked' : '' }} required style="width:18px;height:18px;accent-color:#10b981" />
                                        <span style="font-size:14px;font-weight:500;color:#1e293b">{{ $op }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit" style="width:100%;padding:12px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer">
                                {{ $userVote ? '🔄 Ganti Suara' : '🗳️ Kirim Suara' }}
                            </button>
                        </form>
                    @else
                        {{-- Results --}}
                        @if($userVote)
                            <div style="padding:10px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;margin-bottom:16px;font-size:13px;color:#166534">
                                ✅ Anda sudah memilih: <strong>{{ $userVote->pilihan }}</strong>
                            </div>
                        @endif
                        <div style="display:flex;flex-direction:column;gap:14px">
                            @foreach($results as $op => $data)
                                @php $isWinner = $data['percent'] === max(array_column($results, 'percent')) && $data['percent'] > 0; @endphp
                                <div>
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                                        <span style="font-size:14px;font-weight:{{ $isWinner ? '700' : '500' }};color:#1e293b">
                                            {{ $op }}
                                            @if($isWinner) <span style="font-size:11px;color:#10b981">🏆</span> @endif
                                        </span>
                                        <span style="font-size:14px;font-weight:700;color:#10b981">{{ $data['percent'] }}%</span>
                                    </div>
                                    <div style="width:100%;height:12px;background:#f1f5f9;border-radius:6px;overflow:hidden">
                                        <div style="width:{{ $data['percent'] }}%;height:100%;background:{{ $isWinner ? 'linear-gradient(90deg,#10b981,#059669)' : 'linear-gradient(90deg,#94a3b8,#64748b)' }};border-radius:6px;transition:width 0.5s"></div>
                                    </div>
                                    <div style="font-size:12px;color:#94a3b8;margin-top:4px">{{ $data['count'] }} suara</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
                    <div style="display:flex;flex-direction:column;gap:10px">
                        <div style="display:flex;justify-content:space-between">
                            <span style="font-size:13px;color:#64748b">Status</span>
                            <span style="padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;{{$sb['bg']}}">{{ $sb['label'] }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="font-size:13px;color:#64748b">Total Suara</span>
                            <span style="font-size:13px;font-weight:700;color:#1e293b">{{ $polling->votes_count }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="font-size:13px;color:#64748b">Opsi</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ count($polling->opsi) }} opsi</span>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="font-size:13px;color:#64748b">Mulai</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $polling->tanggal_mulai->format('d M Y') }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="font-size:13px;color:#64748b">Selesai</span>
                            <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $polling->tanggal_selesai ? $polling->tanggal_selesai->format('d M Y') : '-' }}</span>
                        </div>
                        <div style="border-top:1px solid #f1f5f9;padding-top:10px;display:flex;flex-direction:column;gap:4px">
                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#94a3b8">
                                <span>Real-time</span>
                                <span>{{ $polling->tampilkan_hasil ? '✅ Ya' : '❌ Tidak' }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#94a3b8">
                                <span>Ganti Suara</span>
                                <span>{{ $polling->izinkan_ganti ? '✅ Ya' : '❌ Tidak' }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#94a3b8">
                                <span>Anonim</span>
                                <span>{{ $polling->anonim ? '✅ Ya' : '❌ Tidak' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('polling.edit', $polling) }}" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;color:#d97706;font-weight:600;font-size:13px;text-decoration:none">
                ✏️ Edit Polling
            </a>
        </div>
    </div>
</div>
@endsection
