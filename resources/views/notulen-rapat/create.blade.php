@extends('layouts.app')

@section('title', 'Tambah Notulen')

@section('content')
<div>
    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
        <a href="{{ route('notulen-rapat.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:white;border:1.5px solid #e2e8f0;border-radius:10px;color:#64748b;font-size:13px;font-weight:500;text-decoration:none">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <div>
            <h1 style="font-size:22px;font-weight:700;color:#1e293b;margin:0">Buat Notulen Baru</h1>
            <p style="font-size:13px;color:#64748b;margin:2px 0 0">Isi informasi rapat, daftar hadir, poin pembahasan, dan status</p>
        </div>
    </div>

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px 18px;margin-bottom:20px">
            <p style="font-size:14px;font-weight:600;color:#991b1b;margin:0 0 4px">Terjadi kesalahan:</p>
            <ul style="margin:0;padding-left:18px;font-size:13px;color:#b91c1c">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('notulen-rapat.store') }}" style="display:flex;flex-direction:column;gap:24px">
        @csrf

        {{-- Section 1: Informasi Rapat --}}
        <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div style="padding:18px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#14b8a6,#0d9488);display:flex;align-items:center;justify-content:center">
                    <svg style="width:16px;height:16px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0;text-transform:uppercase;letter-spacing:0.5px">Informasi Rapat</h3>
            </div>
            <div style="padding:24px">
                <div style="margin-bottom:16px">
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Judul Rapat <span style="color:#ef4444">*</span></label>
                    <input type="text" name="judul_rapat" value="{{ old('judul_rapat') }}" placeholder="Contoh: Rapat Evaluasi/Acarian RT 05 Jul 2026" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tanggal <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Waktu Mulai <span style="color:#ef4444">*</span></label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', '19:00') }}" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Waktu Selesai <span style="color:#ef4444">*</span></label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', '21:00') }}" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tempat / Platform <span style="color:#ef4444">*</span></label>
                        <input type="text" name="tempat" value="{{ old('tempat') }}" placeholder="Balai Warga RT" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tim / Proyek <span style="color:#ef4444">*</span></label>
                        <select name="tim_proyek" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:white;outline:none">
                            <option value="">Pilih Tim</option>
                            @foreach(['Keamanan','Kebersihan','Keuangan','Sosial','Umum'] as $t)
                                <option value="{{ $t }}" {{ old('tim_proyek') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Moderator <span style="color:#ef4444">*</span></label>
                        <input type="text" name="moderator" value="{{ old('moderator') }}" placeholder="Nama moderator / pimpinan rapat" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Notulis <span style="color:#ef4444">*</span></label>
                        <input type="text" name="notulis" value="{{ old('notulis') }}" placeholder="Nama penulis notulen" required style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Daftar Hadir --}}
        <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div style="padding:18px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#14b8a6,#0d9488);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0;text-transform:uppercase;letter-spacing:0.5px">Daftar Hadir</h3>
                </div>
                <a href="#" onclick="addPeserta();return false" style="font-size:13px;color:#14b8a6;font-weight:600;text-decoration:none">Tambahkan semua peserta rapat</a>
            </div>
            <div style="padding:24px">
                <div id="pesertaList" style="display:flex;flex-direction:column;gap:10px">
                    <div class="peserta-row" style="display:grid;grid-template-columns:2fr 3fr auto;gap:12px;align-items:center">
                        <input type="text" name="peserta_nama[]" placeholder="Nama lengkap" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;outline:none">
                        <input type="text" name="peserta_ulasan[]" placeholder="Ulasan/RT di.wilayah..." style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;outline:none">
                        <div style="display:flex;align-items:center;gap:8px">
                            <label style="display:flex;align-items:center;gap:4px;font-size:12px;color:#64748b;cursor:pointer">
                                <input type="checkbox" name="peserta_hadir[]" value="1" checked style="width:16px;height:16px;accent-color:#14b8a6"> Hadir
                            </label>
                            <button type="button" onclick="this.closest('.peserta-row').remove()" style="width:28px;height:28px;border-radius:6px;background:#fef2f2;color:#ef4444;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addPeserta()" style="margin-top:14px;width:100%;padding:10px;border:2px dashed #d1d5db;border-radius:10px;background:#f9fafb;color:#64748b;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all 0.2s" onmouseover="this.style.borderColor='#14b8a6';this.style.color='#14b8a6'" onmouseout="this.style.borderColor='#d1d5db';this.style.color='#64748b'">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Peserta
                </button>
            </div>
        </div>

        {{-- Section 3: Poin Pembahasan --}}
        <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div style="padding:18px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#14b8a6,#0d9488);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0;text-transform:uppercase;letter-spacing:0.5px">Poin Pembahasan</h3>
                </div>
                <span style="font-size:12px;color:#94a3b8">Urutan kembali semua poin pembahasan</span>
            </div>
            <div style="padding:24px">
                <div id="poinList" style="display:flex;flex-direction:column;gap:10px">
                    <div class="poin-row" style="display:grid;grid-template-columns:40px 1fr auto;gap:10px;align-items:center">
                        <span style="width:32px;height:32px;border-radius:50%;background:#f0fdfa;color:#0d9488;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700">1</span>
                        <input type="text" name="poin_topik[]" placeholder="Topik / agenda pembahasan..." style="padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;outline:none">
                        <div style="display:flex;align-items:center;gap:6px">
                            <span style="padding:3px 8px;border-radius:4px;font-size:11px;font-weight:600;background:#fef3c7;color:#d97706">Sedang</span>
                            <button type="button" onclick="this.closest('.poin-row').remove();reorderPoin()" style="width:28px;height:28px;border-radius:6px;background:#fef2f2;color:#ef4444;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addPoin()" style="margin-top:14px;width:100%;padding:10px;border:2px dashed #d1d5db;border-radius:10px;background:#f9fafb;color:#64748b;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all 0.2s" onmouseover="this.style.borderColor='#14b8a6';this.style.color='#14b8a6'" onmouseout="this.style.borderColor='#d1d5db';this.style.color='#64748b'">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Poin Pembahasan
                </button>
            </div>
        </div>

        {{-- Section 4: Catatan & Status --}}
        <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
            <div style="padding:18px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#14b8a6,#0d9488);display:flex;align-items:center;justify-content:center">
                    <svg style="width:16px;height:16px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 style="font-size:15px;font-weight:700;color:#1e293b;margin:0;text-transform:uppercase;letter-spacing:0.5px">Catatan & Status</h3>
            </div>
            <div style="padding:24px">
                <div style="margin-bottom:20px">
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Catatan / Kesimpulan Rapat</label>
                    <textarea name="catatan" rows="5" placeholder="Tuliskan keputusan, tindak lanjut, atau catatan penting dari rapat..." style="width:100%;padding:14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;outline:none;resize:vertical">{{ old('catatan') }}</textarea>
                </div>
                <div style="margin-bottom:24px">
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:10px">Status Notulen</label>
                    <div style="display:flex;gap:12px">
                        <label style="flex:1;cursor:pointer">
                            <input type="radio" name="status" value="draft" {{ old('status', 'draft') == 'draft' ? 'checked' : '' }} style="display:none">
                            <div onclick="this.previousElementSibling.checked=true;updateStatusStyle(this)" class="status-option" style="padding:12px;border:2px solid #e2e8f0;border-radius:10px;text-align:center;transition:all 0.2s;{{ old('status', 'draft') == 'draft' ? 'border-color:#f59e0b;background:#fffbeb' : '' }}">
                                <span style="font-size:13px;font-weight:600;color:#374151;display:flex;align-items:center;justify-content:center;gap:6px">✎ Draft</span>
                            </div>
                        </label>
                        <label style="flex:1;cursor:pointer">
                            <input type="radio" name="status" value="menunggu" {{ old('status') == 'menunggu' ? 'checked' : '' }} style="display:none">
                            <div onclick="this.previousElementSibling.checked=true;updateStatusStyle(this)" class="status-option" style="padding:12px;border:2px solid #e2e8f0;border-radius:10px;text-align:center;transition:all 0.2s;{{ old('status') == 'menunggu' ? 'border-color:#f97316;background:#fff7ed' : '' }}">
                                <span style="font-size:13px;font-weight:600;color:#374151;display:flex;align-items:center;justify-content:center;gap:6px">⏳ Menunggu Persetujuan</span>
                            </div>
                        </label>
                        <label style="flex:1;cursor:pointer">
                            <input type="radio" name="status" value="final" {{ old('status') == 'final' ? 'checked' : '' }} style="display:none">
                            <div onclick="this.previousElementSibling.checked=true;updateStatusStyle(this)" class="status-option" style="padding:12px;border:2px solid #e2e8f0;border-radius:10px;text-align:center;transition:all 0.2s;{{ old('status') == 'final' ? 'border-color:#10b981;background:#f0fdf4' : '' }}">
                                <span style="font-size:13px;font-weight:600;color:#374151;display:flex;align-items:center;justify-content:center;gap:6px">✓ Final</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div style="display:flex;gap:12px;justify-content:end">
                    <a href="{{ route('notulen-rapat.index') }}" style="padding:10px 24px;background:#f1f5f9;color:#64748b;border-radius:10px;font-weight:600;font-size:14px;text-decoration:none;display:flex;align-items:center;gap:6px">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" style="padding:10px 28px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:white;border:none;border-radius:10px;font-weight:600;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(20,184,166,0.3)">
                        <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Notulen
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let poinCount = 1;
function addPoin() {
    poinCount++;
    const row = document.createElement('div');
    row.className = 'poin-row';
    row.style.cssText = 'display:grid;grid-template-columns:40px 1fr auto;gap:10px;align-items:center';
    row.innerHTML = `<span style="width:32px;height:32px;border-radius:50%;background:#f0fdfa;color:#0d9488;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700">${poinCount}</span>
        <input type="text" name="poin_topik[]" placeholder="Topik / agenda pembahasan..." style="padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;outline:none">
        <div style="display:flex;align-items:center;gap:6px">
            <span style="padding:3px 8px;border-radius:4px;font-size:11px;font-weight:600;background:#fef3c7;color:#d97706">Sedang</span>
            <button type="button" onclick="this.closest('.poin-row').remove();reorderPoin()" style="width:28px;height:28px;border-radius:6px;background:#fef2f2;color:#ef4444;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center"><svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>`;
    document.getElementById('poinList').appendChild(row);
}

function reorderPoin() {
    const rows = document.querySelectorAll('.poin-row');
    rows.forEach((r, i) => {
        r.querySelector('span').textContent = i + 1;
    });
    poinCount = rows.length;
}

function addPeserta() {
    const row = document.createElement('div');
    row.className = 'peserta-row';
    row.style.cssText = 'display:grid;grid-template-columns:2fr 3fr auto;gap:12px;align-items:center';
    row.innerHTML = `<input type="text" name="peserta_nama[]" placeholder="Nama lengkap" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;outline:none">
        <input type="text" name="peserta_ulasan[]" placeholder="Ulasan/RT di.wilayah..." style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155;outline:none">
        <div style="display:flex;align-items:center;gap:8px">
            <label style="display:flex;align-items:center;gap:4px;font-size:12px;color:#64748b;cursor:pointer"><input type="checkbox" name="peserta_hadir[]" value="1" checked style="width:16px;height:16px;accent-color:#14b8a6"> Hadir</label>
            <button type="button" onclick="this.closest('.peserta-row').remove()" style="width:28px;height:28px;border-radius:6px;background:#fef2f2;color:#ef4444;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center"><svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>`;
    document.getElementById('pesertaList').appendChild(row);
}

function updateStatusStyle(el) {
    el.parentElement.parentElement.parentElement.querySelectorAll('.status-option').forEach(o => {
        o.style.borderColor = '#e2e8f0';
        o.style.background = 'white';
    });
    const val = el.previousElementSibling.value;
    if (val === 'draft') { el.style.borderColor = '#f59e0b'; el.style.background = '#fffbeb'; }
    else if (val === 'menunggu') { el.style.borderColor = '#f97316'; el.style.background = '#fff7ed'; }
    else if (val === 'final') { el.style.borderColor = '#10b981'; el.style.background = '#f0fdf4'; }
}
</script>
@endsection
