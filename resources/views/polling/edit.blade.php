@extends('layouts.app')

@section('title', 'Edit Polling')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('polling.index') }}" class="text-emerald-600 hover:underline font-medium">Polling</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Polling</h1>
        </div>
        <a href="{{ route('polling.show', $polling) }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid #e2e8f0;border-radius:10px;color:#64748b;font-size:13px;font-weight:500;text-decoration:none;background:#fff">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div style="padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;font-size:14px">
            @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
    @endif

    <form action="{{ route('polling.update', $polling) }}" method="POST">
        @csrf @method('PUT')
        <div style="background:#fff;border:2px solid #e2e8f0;border-radius:16px;padding:24px">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center">
                    <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <span style="font-weight:700;color:#1e293b;font-size:15px">Edit Polling</span>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Judul Polling *</label>
                <input type="text" name="judul" value="{{ old('judul', $polling->judul) }}" required
                    style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Deskripsi / Konteks</label>
                <textarea name="deskripsi" rows="3"
                    style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;resize:vertical">{{ old('deskripsi', $polling->deskripsi) }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $polling->tanggal_mulai->format('Y-m-d')) }}" required
                        style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $polling->tanggal_selesai ? $polling->tanggal_selesai->format('Y-m-d') : '') }}"
                        style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                </div>
            </div>

            <div style="margin-bottom:20px">
                <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Opsi Jawaban *</label>
                <div id="opsi-container">
                    @foreach($polling->opsi as $i => $op)
                    <div class="opsi-row" style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                        <span style="width:32px;height:32px;border-radius:50%;background:#10b981;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0">{{ $i + 1 }}</span>
                        <input type="text" name="opsi[]" value="{{ $op }}" required
                            style="flex:1;padding:10px 14px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                        <button type="button" onclick="removeOpsi(this)" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:4px;font-size:18px">×</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addOpsi()" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:2px dashed #10b981;border-radius:10px;background:#f0fdf4;color:#10b981;font-size:13px;font-weight:600;cursor:pointer">
                    + Tambah Opsi
                </button>
            </div>

            <div style="margin-bottom:24px">
                <label style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border:1px solid #e2e8f0;border-radius:10px;cursor:pointer;margin-bottom:10px">
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b">Tampilkan Hasil Real-time</div>
                        <div style="font-size:12px;color:#94a3b8">Warga bisa melihat hasil polling sebelum voting</div>
                    </div>
                    <input type="checkbox" name="tampilkan_hasil" value="1" {{ old('tampilkan_hasil', $polling->tampilkan_hasil) ? 'checked' : '' }}
                        style="width:44px;height:24px;accent-color:#10b981;cursor:pointer" />
                </label>
                <label style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border:1px solid #e2e8f0;border-radius:10px;cursor:pointer;margin-bottom:10px">
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b">Izinkan Ganti Suara</div>
                        <div style="font-size:12px;color:#94a3b8">Warga bisa mengubah pilihan setelah voting</div>
                    </div>
                    <input type="checkbox" name="izinkan_ganti" value="1" {{ old('izinkan_ganti', $polling->izinkan_ganti) ? 'checked' : '' }}
                        style="width:44px;height:24px;accent-color:#10b981;cursor:pointer" />
                </label>
                <label style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border:1px solid #e2e8f0;border-radius:10px;cursor:pointer">
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b">Polling Anonim</div>
                        <div style="font-size:12px;color:#94a3b8">Identitas pemberi suara tidak ditampilkan pada hasil</div>
                    </div>
                    <input type="checkbox" name="anonim" value="1" {{ old('anonim', $polling->anonim) ? 'checked' : '' }}
                        style="width:44px;height:24px;accent-color:#10b981;cursor:pointer" />
                </label>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px">
                <a href="{{ route('polling.show', $polling) }}" style="padding:12px 24px;border:1px solid #e2e8f0;border-radius:12px;color:#64748b;font-weight:600;font-size:14px;text-decoration:none">Batal</a>
                <button type="submit" style="padding:12px 32px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:12px;font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(16,185,129,0.3)">
                    <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let opsiIdx = {{ count($polling->opsi) }};
function addOpsi() {
    opsiIdx++;
    const c = document.getElementById('opsi-container');
    const row = document.createElement('div');
    row.className = 'opsi-row';
    row.style.cssText = 'display:flex;align-items:center;gap:10px;margin-bottom:10px';
    row.innerHTML = `<span style="width:32px;height:32px;border-radius:50%;background:#10b981;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0">${opsiIdx}</span>
        <input type="text" name="opsi[]" placeholder="Opsi ${opsiIdx}..." required style="flex:1;padding:10px 14px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none"/>
        <button type="button" onclick="removeOpsi(this)" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:4px;font-size:18px">×</button>`;
    c.appendChild(row);
}
function removeOpsi(btn) {
    const rows = document.querySelectorAll('.opsi-row');
    if (rows.length <= 2) return alert('Minimal 2 opsi!');
    btn.closest('.opsi-row').remove();
    renumberOpsi();
}
function renumberOpsi() {
    document.querySelectorAll('.opsi-row span').forEach((s, i) => s.textContent = i + 1);
}
</script>
@endsection
