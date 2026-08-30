@extends('layouts.app')

@section('title', 'Pengajuan Setoran Tabungan')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    {{-- Breadcrumb --}}
    <div style="margin-bottom: 0.75rem;">
        <a href="{{ route('tabungan.index') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; font-size: 0.875rem; text-decoration: none;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Tabungan
        </a>
    </div>

    {{-- Stepper --}}
    <div style="display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 1.5rem; background: white; border-radius: 9999px; padding: 0.75rem 2rem; border: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">1</div>
            <span style="font-size: 0.8125rem; font-weight: 600; color: #1e293b;">Data</span>
        </div>
        <div style="flex: 1; height: 2px; background: #e2e8f0; margin: 0 1rem;"></div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">2</div>
            <span style="font-size: 0.8125rem; font-weight: 500; color: #94a3b8;">Pembayaran</span>
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
            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #14b8a6, #0d9488); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
                <h2 style="font-size: 1.125rem; font-weight: 700; color: #1e293b;">Pengajuan Setoran Tabungan</h2>
                <p style="font-size: 0.8125rem; color: #94a3b8;">Isi formulir setoran, lalu klik Ajukan Setoran</p>
            </div>
        </div>

        @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; margin-bottom: 1.25rem;">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('tabungan.store-setoran') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                {{-- Left Column --}}
                <div>
                    {{-- Identitas Penabung --}}
                    <div style="margin-bottom: 1.25rem;">
                        <h4 style="font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Identitas Penabung</h4>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Pilih Warga <span style="color: #ef4444;">*</span></label>
                            <select name="anggota_keluarga_id" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; background: white;">
                                <option value="">— Pilih Warga —</option>
                                @foreach($wargas as $w)
                                <option value="{{ $w->id }}" {{ old('anggota_keluarga_id') == $w->id ? 'selected' : '' }}>{{ $w->nama_lengkap }} — {{ $w->no_hp ?? '-' }}</option>
                                @endforeach
                            </select>
                            @error('anggota_keluarga_id')<p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Jenis Tabungan</label>
                            <select name="jenis_tabungan" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; background: white;">
                                <option value="sukarela" {{ old('jenis_tabungan') == 'sukarela' ? 'selected' : '' }}>Sukarela</option>
                                <option value="wajib" {{ old('jenis_tabungan') == 'wajib' ? 'selected' : '' }}>Wajib</option>
                                <option value="investasi" {{ old('jenis_tabungan') == 'investasi' ? 'selected' : '' }}>Investasi</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div>
                    {{-- Rekening / Kas Tujuan --}}
                    <div style="margin-bottom: 1.25rem;">
                        <h4 style="font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Rekening / Kas Tujuan</h4>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Setoran Masuk ke Kas <span style="color: #ef4444;">*</span></label>
                            <select name="rekening_kas_id" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; background: white;">
                                <option value="">— Pilih Rekening / Kas Tujuan —</option>
                                @foreach($rekenings as $rk)
                                <option value="{{ $rk->id }}" {{ old('rekening_kas_id') == $rk->id ? 'selected' : '' }}>{{ $rk->nama }} — Saldo: Rp {{ number_format($rk->saldo, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                            @error('rekening_kas_id')<p style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Info Box --}}
                        <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 0.5rem; padding: 0.75rem 1rem; display: flex; align-items: flex-start; gap: 0.5rem;">
                            <svg style="width: 16px; height: 16px; color: #f97316; flex-shrink: 0; margin-top: 2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p style="font-size: 0.75rem; color: #92400e;"><strong>Mode Admin</strong> — Setoran yang diinput admin akan langsung otomatis terverifikasi tanpa perlu bukti transfer.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Setoran --}}
            <div style="margin-top: 0.5rem;">
                <h4 style="font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Detail Setoran</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Nominal Setor <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="nominal" value="{{ old('nominal') }}" min="10000" required placeholder="Contoh: 50000" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                        <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                            <button type="button" onclick="document.querySelector('[name=nominal]').value=10000" style="padding: 4px 12px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.75rem; background: white; cursor: pointer; color: #475569;">Rp 10.000</button>
                            <button type="button" onclick="document.querySelector('[name=nominal]').value=25000" style="padding: 4px 12px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.75rem; background: white; cursor: pointer; color: #475569;">Rp 25.000</button>
                            <button type="button" onclick="document.querySelector('[name=nominal]').value=50000" style="padding: 4px 12px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.75rem; background: white; cursor: pointer; color: #475569;">Rp 50.000</button>
                            <button type="button" onclick="document.querySelector('[name=nominal]').value=100000" style="padding: 4px 12px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.75rem; background: white; cursor: pointer; color: #475569;">Rp 100.000</button>
                            <button type="button" onclick="document.querySelector('[name=nominal]').value=200000" style="padding: 4px 12px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.75rem; background: white; cursor: pointer; color: #475569;">Rp 200.000</button>
                        </div>
                        <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.375rem;">Minimal Rp 10.000</p>
                        @error('nominal')<p style="font-size: 0.75rem; color: #ef4444;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Keterangan <span style="color: #94a3b8; font-weight: 400;">(opsional)</span></label>
                        <textarea name="keterangan" rows="3" placeholder="Contoh: Setoran bulan Februari..." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; resize: none;">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Info Box --}}
            <div style="margin-top: 1.25rem; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.5rem; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 16px; height: 16px; color: #3b82f6; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p style="font-size: 0.8125rem; color: #1e40af;">Setoran yang diinput admin akan <strong>langsung terupdate</strong> ke saldo warga dan kas RT.</p>
            </div>

            {{-- Buttons --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('tabungan.index') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; color: #64748b; font-size: 0.875rem; font-weight: 600; text-decoration: none;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
                <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.625rem 1.5rem; background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; border: none; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Ajukan Setoran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
