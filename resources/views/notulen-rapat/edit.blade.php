@extends('layouts.app')

@section('title', 'Edit Notulen Rapat')

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
                <span class="text-slate-700 font-medium">Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Notulen</h1>
        </div>
        <a href="{{ route('notulen-rapat.show', $notulen) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('notulen-rapat.update', $notulen) }}" method="POST">
        @csrf @method('PUT')

        {{-- Informasi Rapat --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;margin-bottom:24px">
            <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center">
                    <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span style="font-weight:600;color:#1e293b;font-size:15px">Informasi Rapat</span>
            </div>
            <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div style="grid-column:span 2">
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Judul Rapat *</label>
                    <input type="text" name="judul_rapat" value="{{ old('judul_rapat', $notulen->judul_rapat) }}" required
                        style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;transition:border-color 0.2s" />
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tanggal *</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $notulen->tanggal->format('Y-m-d')) }}" required
                        style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Waktu Mulai *</label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $notulen->waktu_mulai) }}" required
                            style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Waktu Selesai *</label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $notulen->waktu_selesai) }}" required
                            style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tempat / Platform *</label>
                    <input type="text" name="tempat" value="{{ old('tempat', $notulen->tempat) }}" required
                        style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tim / Proyek</label>
                    <select name="tim_proyek" style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;background:#fff">
                        <option value="">Pilih Tim...</option>
                        @foreach(['Umum','Keamanan','Kebersihan','Keuangan','Sosial'] as $t)
                            <option value="{{ $t }}" {{ old('tim_proyek', $notulen->tim_proyek) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Moderator *</label>
                    <input type="text" name="moderator" value="{{ old('moderator', $notulen->moderator) }}" required
                        style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Notulis *</label>
                    <input type="text" name="notulis" value="{{ old('notulis', $notulen->notulis) }}" required
                        style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                </div>
            </div>
        </div>

        {{-- Daftar Hadir --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;margin-bottom:24px">
            <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                <div style="display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14z"/></svg>
                    </div>
                    <span style="font-weight:600;color:#1e293b;font-size:15px">Daftar Hadir</span>
                </div>
                <button type="button" onclick="addPeserta()" style="background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer">+ Tambah Peserta</button>
            </div>
            <div style="padding:16px 20px">
                <div id="peserta-container">
                    @forelse($notulen->hadir as $h)
                    <div class="peserta-row" style="display:grid;grid-template-columns:2fr 2fr auto auto;gap:10px;margin-bottom:10px;align-items:end">
                        <div>
                            <input type="text" name="peserta_nama[]" value="{{ $h->nama_peserta }}" placeholder="Nama Peserta"
                                style="width:100%;padding:8px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none" />
                        </div>
                        <div>
                            <input type="text" name="peserta_ulasan[]" value="{{ $h->ulasan }}" placeholder="Ulasan / Catatan"
                                style="width:100%;padding:8px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none" />
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;padding-bottom:2px">
                            <input type="checkbox" name="peserta_hadir[]" value="1" {{ $h->hadir ? 'checked' : '' }}
                                style="width:16px;height:16px;accent-color:#10b981;cursor:pointer" />
                            <span style="font-size:12px;color:#64748b">Hadir</span>
                        </div>
                        <button type="button" onclick="this.closest('.peserta-row').remove()" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:6px">
                            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @empty
                    <div class="peserta-row" style="display:grid;grid-template-columns:2fr 2fr auto auto;gap:10px;margin-bottom:10px;align-items:end">
                        <div>
                            <input type="text" name="peserta_nama[]" placeholder="Nama Peserta"
                                style="width:100%;padding:8px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none" />
                        </div>
                        <div>
                            <input type="text" name="peserta_ulasan[]" placeholder="Ulasan / Catatan"
                                style="width:100%;padding:8px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none" />
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;padding-bottom:2px">
                            <input type="checkbox" name="peserta_hadir[]" value="1" checked style="width:16px;height:16px;accent-color:#10b981;cursor:pointer" />
                            <span style="font-size:12px;color:#64748b">Hadir</span>
                        </div>
                        <button type="button" onclick="this.closest('.peserta-row').remove()" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:6px">
                            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Poin Pembahasan --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;margin-bottom:24px">
            <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                <div style="display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span style="font-weight:600;color:#1e293b;font-size:15px">Poin Pembahasan</span>
                </div>
                <button type="button" onclick="addPoin()" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer">+ Tambah Poin</button>
            </div>
            <div style="padding:16px 20px">
                <div id="poin-container">
                    @forelse($notulen->poin as $i => $p)
                    <div class="poin-row" style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                        <span style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0">{{ $i + 1 }}</span>
                        <input type="text" name="poin_topik[]" value="{{ $p->topik }}" placeholder="Topik / agenda pembahasan..."
                            style="flex:1;padding:8px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none" />
                        <button type="button" onclick="this.closest('.poin-row').remove()" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:4px">
                            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @empty
                    <div class="poin-row" style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                        <span style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0">1</span>
                        <input type="text" name="poin_topik[]" placeholder="Topik / agenda pembahasan..."
                            style="flex:1;padding:8px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none" />
                        <button type="button" onclick="this.closest('.poin-row').remove()" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:4px">
                            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Catatan & Status --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;margin-bottom:24px">
            <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);display:flex;align-items:center;justify-content:center">
                    <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <span style="font-weight:600;color:#1e293b;font-size:15px">Catatan & Status</span>
            </div>
            <div style="padding:20px">
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Catatan / Kesimpulan Rapat</label>
                    <textarea name="catatan" rows="4" placeholder="Tuliskan keputusan, tindak-lanjut, atau catatan penting dari rapat..."
                        style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;resize:vertical">{{ old('catatan', $notulen->catatan) }}</textarea>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:10px">Status Notulen</label>
                    <div style="display:flex;gap:10px">
                        @foreach([
                            ['val' => 'draft', 'label' => '✎ Draft', 'bg' => '#fffbeb', 'border' => '#fde68a', 'color' => '#d97706', 'active_bg' => '#fef3c7'],
                            ['val' => 'menunggu', 'label' => '⏳ Menunggu Persetujuan', 'bg' => '#fff7ed', 'border' => '#fed7aa', 'color' => '#c2410c', 'active_bg' => '#ffedd5'],
                            ['val' => 'final', 'label' => '✓ Final', 'bg' => '#f0fdf4', 'border' => '#bbf7d0', 'color' => '#16a34a', 'active_bg' => '#dcfce7'],
                        ] as $opt)
                            <label style="flex:1;text-align:center;padding:12px;border-radius:12px;border:2px solid {{ old('status', $notulen->status) == $opt['val'] ? $opt['color'] : $opt['border'] }};background:{{ old('status', $notulen->status) == $opt['val'] ? $opt['active_bg'] : $opt['bg'] }};cursor:pointer;transition:all 0.2s;font-weight:600;font-size:13px;color:{{ $opt['color'] }}">
                                <input type="radio" name="status" value="{{ $opt['val'] }}" {{ old('status', $notulen->status) == $opt['val'] ? 'checked' : '' }} style="display:none">
                                {{ $opt['label'] }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div style="display:flex;justify-content:flex-end;gap:12px">
            <a href="{{ route('notulen-rapat.show', $notulen) }}" style="padding:12px 24px;border:1px solid #e2e8f0;border-radius:12px;color:#64748b;font-weight:600;font-size:14px;text-decoration:none;transition:all 0.2s">Batal</a>
            <button type="submit" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;padding:12px 32px;border-radius:12px;font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(16,185,129,0.3)">
                <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
let pesertaIdx = {{ $notulen->hadir->count() ?: 1 }};
let poinIdx = {{ $notulen->poin->count() ?: 1 }};

function addPeserta() {
    const c = document.getElementById('peserta-container');
    const row = document.createElement('div');
    row.className = 'peserta-row';
    row.style.cssText = 'display:grid;grid-template-columns:2fr 2fr auto auto;gap:10px;margin-bottom:10px;align-items:end';
    row.innerHTML = `
        <div><input type="text" name="peserta_nama[]" placeholder="Nama Peserta" style="width:100%;padding:8px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none"/></div>
        <div><input type="text" name="peserta_ulasan[]" placeholder="Ulasan / Catatan" style="width:100%;padding:8px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none"/></div>
        <div style="display:flex;align-items:center;gap:6px;padding-bottom:2px"><input type="checkbox" name="peserta_hadir[]" value="1" checked style="width:16px;height:16px;accent-color:#10b981;cursor:pointer"/><span style="font-size:12px;color:#64748b">Hadir</span></div>
        <button type="button" onclick="this.closest('.peserta-row').remove()" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:6px"><svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
    c.appendChild(row);
}

function addPoin() {
    poinIdx++;
    const c = document.getElementById('poin-container');
    const row = document.createElement('div');
    row.className = 'poin-row';
    row.style.cssText = 'display:flex;align-items:center;gap:10px;margin-bottom:10px';
    row.innerHTML = `
        <span style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0">${poinIdx}</span>
        <input type="text" name="poin_topik[]" placeholder="Topik / agenda pembahasan..." style="flex:1;padding:8px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none"/>
        <button type="button" onclick="this.closest('.poin-row').remove()" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:4px"><svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
    c.appendChild(row);
}
</script>
@endsection
