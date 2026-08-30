@extends('layouts.app')

@section('title', 'Pengajuan Penarikan Tabungan')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    {{-- Breadcrumb --}}
    <div style="margin-bottom: 0.75rem;">
        <a href="{{ route('tabungan.index') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; font-size: 0.875rem; text-decoration: none;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    {{-- Stepper --}}
    <div style="display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 1.5rem; background: white; border-radius: 9999px; padding: 0.75rem 2rem; border: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #f97316, #ea580c); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">1</div>
            <span style="font-size: 0.8125rem; font-weight: 600; color: #1e293b;">Data</span>
        </div>
        <div style="flex: 1; height: 2px; background: #e2e8f0; margin: 0 1rem;"></div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">2</div>
            <span style="font-size: 0.8125rem; font-weight: 500; color: #94a3b8;">Nominal</span>
        </div>
        <div style="flex: 1; height: 2px; background: #e2e8f0; margin: 0 1rem;"></div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">3</div>
            <span style="font-size: 0.8125rem; font-weight: 500; color: #94a3b8;">Konfirmasi</span>
        </div>
    </div>

    {{-- Form Card --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        {{-- Header --}}
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #f97316, #ea580c); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </div>
            <div>
                <h2 style="font-size: 1.125rem; font-weight: 700; color: #1e293b;">Pengajuan Penarikan Tabungan</h2>
                <p style="font-size: 0.8125rem; color: #94a3b8;">Isi formulir penarikan, lalu klik Ajukan Penarikan</p>
            </div>
        </div>

        @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; margin-bottom: 1.25rem;">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('tabungan.store-penarikan') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                {{-- Left Column --}}
                <div>
                    {{-- Pilih Warga --}}
                    <div style="margin-bottom: 1.25rem;">
                        <h4 style="font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Pilih Warga</h4>
                        <div>
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Warga <span style="color: #ef4444;">*</span></label>
                            <select name="anggota_keluarga_id" id="wargaSelect" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; background: white;" onchange="updateSaldo()">
                                <option value="">— Pilih Warga —</option>
                                @foreach($wargas as $w)
                                @if($w->tabungan)
                                <option value="{{ $w->id }}" data-saldo="{{ $w->tabungan->saldo }}" data-nama="{{ $w->nama_lengkap }}">{{ $w->nama_lengkap }} — Saldo: Rp {{ number_format($w->tabungan->saldo, 0, ',', '.') }}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('anggota_keluarga_id')<p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Detail Penarikan --}}
                    <div>
                        <h4 style="font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Detail Penarikan</h4>
                        <div>
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Nominal Tarik <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="nominal" id="nominalInput" value="{{ old('nominal') }}" min="10000" required placeholder="Contoh: 100000" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;" oninput="updateRingkasan()">
                            <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                                <button type="button" onclick="setNominal(50000)" style="padding: 4px 12px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.75rem; background: white; cursor: pointer; color: #475569;">Rp 50.000</button>
                                <button type="button" onclick="setNominal(100000)" style="padding: 4px 12px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.75rem; background: white; cursor: pointer; color: #475569;">Rp 100.000</button>
                                <button type="button" onclick="setNominal(200000)" style="padding: 4px 12px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.75rem; background: white; cursor: pointer; color: #475569;">Rp 200.000</button>
                                <button type="button" onclick="setNominal(500000)" style="padding: 4px 12px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.75rem; background: white; cursor: pointer; color: #475569;">Rp 500.000</button>
                            </div>
                            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.375rem;">Minimal Rp 10.000. Pastikan saldo mencukupi.</p>
                            @error('nominal')<p style="font-size: 0.75rem; color: #ef4444;">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div>
                    {{-- Alasan Penarikan --}}
                    <div style="margin-bottom: 1.25rem;">
                        <h4 style="font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Alasan Penarikan</h4>
                        <div>
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Keterangan <span style="color: #ef4444;">*</span></label>
                            <textarea name="keterangan" rows="4" required placeholder="Contoh: Kebutuhan mendesak, biaya sekolah, dll..." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; resize: none;">{{ old('keterangan') }}</textarea>
                            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.375rem;">Wajib diisi. Akan ditampilkan kepada admin untuk persetujuan.</p>
                            @error('keterangan')<p style="font-size: 0.75rem; color: #ef4444;">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Ringkasan Penarikan --}}
                    <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 0.75rem; padding: 1rem;">
                        <h4 style="font-size: 0.6875rem; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px;">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Ringkasan Penarikan
                        </h4>
                        <div style="display: flex; justify-content: space-between; padding: 0.375rem 0; border-bottom: 1px dashed #fed7aa;">
                            <span style="font-size: 0.8125rem; color: #92400e;">Nama Warga</span>
                            <span id="ringNama" style="font-size: 0.8125rem; font-weight: 600; color: #1e293b;">—</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.375rem 0; border-bottom: 1px dashed #fed7aa;">
                            <span style="font-size: 0.8125rem; color: #92400e;">Saldo Saat Ini</span>
                            <span id="ringSaldo" style="font-size: 0.8125rem; font-weight: 600; color: #1e293b;">—</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.375rem 0; border-bottom: 1px dashed #fed7aa;">
                            <span style="font-size: 0.8125rem; color: #92400e;">Nominal Tarik</span>
                            <span id="ringTarik" style="font-size: 0.8125rem; font-weight: 600; color: #ef4444;">—</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.375rem 0;">
                            <span style="font-size: 0.8125rem; color: #92400e;">Sisa Saldo</span>
                            <span id="ringSisa" style="font-size: 0.8125rem; font-weight: 700; color: #1e293b;">—</span>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div style="margin-top: 1rem; background: #fef3c7; border: 1px solid #fde68a; border-radius: 0.5rem; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg style="width: 16px; height: 16px; color: #d97706; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p style="font-size: 0.8125rem; color: #92400e;">Penarikan diproses setelah disetujui admin dalam <strong>1×24 jam</strong>.</p>
                    </div>
                </div>
            </div>

            {{-- Warning --}}
            <div style="margin-top: 1.25rem; background: #fefce8; border: 1px solid #fde68a; border-radius: 0.5rem; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 16px; height: 16px; color: #ca8a04; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <p style="font-size: 0.8125rem; color: #854d0e;">Pastikan nominal dan alasan penarikan sudah benar sebelum mengajukan.</p>
            </div>

            {{-- Buttons --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('tabungan.index') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; color: #64748b; font-size: 0.875rem; font-weight: 600; text-decoration: none;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
                <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.625rem 1.5rem; background: linear-gradient(135deg, #f97316, #ea580c); color: white; border: none; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(249,115,22,0.3);">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Ajukan Penarikan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
var currentSaldo = 0;

function setNominal(val) {
    document.getElementById('nominalInput').value = val;
    updateRingkasan();
}

function updateSaldo() {
    var sel = document.getElementById('wargaSelect');
    var opt = sel.options[sel.selectedIndex];
    if (opt && opt.value) {
        currentSaldo = parseFloat(opt.dataset.saldo) || 0;
        document.getElementById('ringNama').textContent = opt.dataset.nama || '—';
        document.getElementById('ringSaldo').textContent = 'Rp ' + currentSaldo.toLocaleString('id-ID');
    } else {
        currentSaldo = 0;
        document.getElementById('ringNama').textContent = '—';
        document.getElementById('ringSaldo').textContent = '—';
    }
    updateRingkasan();
}

function updateRingkasan() {
    var nominal = parseFloat(document.getElementById('nominalInput').value) || 0;
    document.getElementById('ringTarik').textContent = nominal > 0 ? '- Rp ' + nominal.toLocaleString('id-ID') : '—';
    var sisa = currentSaldo - nominal;
    document.getElementById('ringSisa').textContent = sisa >= 0 ? 'Rp ' + sisa.toLocaleString('id-ID') : 'Rp ' + sisa.toLocaleString('id-ID');
    document.getElementById('ringSisa').style.color = sisa < 0 ? '#ef4444' : '#1e293b';
}
</script>
@endsection
