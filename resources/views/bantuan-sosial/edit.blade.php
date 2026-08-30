@extends('layouts.app')

@section('title', 'Edit Penerima Bantuan')

@section('content')
<div style="display:flex;flex-direction:column;gap:16px;max-width:800px;">
    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#94a3b8;">
        <a href="{{ route('dashboard') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('bantuan-sosial.index') }}" style="color:#0d9488;font-weight:600;text-decoration:none;">Bantuan Sosial</a>
        <span>/</span>
        <span style="color:#1e293b;font-weight:600;">Edit</span>
    </div>

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3);">
                <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h1 style="font-size:22px;font-weight:700;color:#1e293b;">Edit Penerima Bantuan</h1>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('bantuan-sosial.update', $penerima->id) }}" style="display:flex;flex-direction:column;gap:20px;">
        @csrf @method('PUT')

        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:20px;display:flex;flex-direction:column;gap:20px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Pilih Warga *</label>
                    <select name="anggota_keluarga_id" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                        @foreach($warga as $w)
                        <option value="{{ $w->id }}" {{ $penerima->anggota_keluarga_id == $w->id ? 'selected' : '' }}>
                            {{ $w->nama_lengkap }} - {{ $w->nik }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:12px;">Jenis Bantuan *</label>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                        @foreach(['BLT', 'Sembako', 'PKH', 'BPNT', 'Lansia', 'Lainnya'] as $jb)
                        <label style="display:flex;align-items:center;gap:8px;padding:12px;border-radius:10px;border:2px solid {{ in_array($jb, $penerima->jenis_bantuan ?? []) ? '#0d9488' : '#e2e8f0' }};cursor:pointer;{{ in_array($jb, $penerima->jenis_bantuan ?? []) ? 'background:#f0fdfa;' : '' }}" class="jenis-edit-card">
                            <input type="checkbox" name="jenis_bantuan[]" value="{{ $jb }}" {{ in_array($jb, $penerima->jenis_bantuan ?? []) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#0d9488;">
                            <span>{{ $jb }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Tahun *</label>
                        <select name="tahun" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                            @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $penerima->tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Status</label>
                        <select name="status" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;background:white;">
                            <option value="aktif" {{ $penerima->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $penerima->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Keterangan</label>
                    <textarea name="keterangan" rows="2" style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e2e8f0;font-size:14px;resize:vertical;">{{ $penerima->keterangan }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <a href="{{ route('bantuan-sosial.index') }}" style="padding:10px 24px;border-radius:10px;font-weight:600;font-size:14px;color:#64748b;background:white;border:1px solid #e2e8f0;text-decoration:none;">← Batal</a>
            <button type="submit" style="padding:10px 24px;border-radius:10px;font-weight:600;font-size:14px;color:white;background:linear-gradient(135deg,#0d9488,#0f766e);border:none;cursor:pointer;box-shadow:0 4px 12px rgba(13,148,136,0.3);">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.jenis-edit-card').forEach(card => {
    const cb = card.querySelector('input[type="checkbox"]');
    cb.addEventListener('change', function() {
        card.style.borderColor = this.checked ? '#0d9488' : '#e2e8f0';
        card.style.background = this.checked ? '#f0fdfa' : 'white';
    });
});
</script>
@endsection
