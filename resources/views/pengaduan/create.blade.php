@extends('layouts.app')

@section('title', 'Kirim Saran/Pengaduan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('pengaduan.index') }}" class="text-emerald-600 hover:underline font-medium">Saran & Pengaduan</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Kirim</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Kirim Pengaduan</h1>
            <p class="text-sm text-slate-500 mt-1">Sampaikan pengaduan atau saran Anda kepada RT</p>
        </div>
        <a href="{{ route('pengaduan.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid #e2e8f0;border-radius:10px;color:#64748b;font-size:13px;font-weight:500;text-decoration:none;background:#fff">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div style="padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;font-size:14px">
            @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
    @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
        <div>
            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:24px">
                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Judul *</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Judul singkat pengaduan..." required
                            style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'" />
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Kategori *</label>
                        <select name="kategori" required style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;background:#fff">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Keamanan" {{ old('kategori') == 'Keamanan' ? 'selected' : '' }}>🔒 Keamanan</option>
                            <option value="Kebersihan" {{ old('kategori') == 'Kebersihan' ? 'selected' : '' }}>🧹 Kebersihan</option>
                            <option value="Keuangan" {{ old('kategori') == 'Keuangan' ? 'selected' : '' }}>💰 Keuangan</option>
                            <option value="Infrastruktur" {{ old('kategori') == 'Infrastruktur' ? 'selected' : '' }}>🏗️ Infrastruktur</option>
                            <option value="Sosial" {{ old('kategori') == 'Sosial' ? 'selected' : '' }}>🤝 Sosial</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>📋 Lainnya</option>
                        </select>
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Isi Pengaduan *</label>
                        <textarea name="isi_pengaduan" rows="6" required placeholder="Jelaskan pengaduan Anda secara lengkap dan jelas..."
                            style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;resize:vertical;transition:border-color 0.2s" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">{{ old('isi_pengaduan') }}</textarea>
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:10px">Privasi</label>
                        <div style="display:flex;gap:16px">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:2px solid {{ old('privasi', 'publik') == 'publik' ? '#10b981' : '#e2e8f0' }};border-radius:10px;background:{{ old('privasi', 'publik') == 'publik' ? '#f0fdf4' : '#fff' }}">
                                <input type="radio" name="privasi" value="publik" {{ old('privasi', 'publik') == 'publik' ? 'checked' : '' }} style="accent-color:#10b981" />
                                <span style="font-size:13px;font-weight:500;color:#374151">🌍 Publik</span>
                                <span style="font-size:11px;color:#94a3b8">(terlihat semua warga)</span>
                            </label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:2px solid {{ old('privasi') == 'privat' ? '#10b981' : '#e2e8f0' }};border-radius:10px;background:{{ old('privasi') == 'privat' ? '#f0fdf4' : '#fff' }}">
                                <input type="radio" name="privasi" value="privat" {{ old('privasi') == 'privat' ? 'checked' : '' }} style="accent-color:#10b981" />
                                <span style="font-size:13px;font-weight:500;color:#374151">🔒 Privat</span>
                                <span style="font-size:11px;color:#94a3b8">(hanya admin & saya)</span>
                            </label>
                        </div>
                    </div>

                    <div style="margin-bottom:24px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Lampiran <span style="font-weight:400;color:#94a3b8">(Optional)</span></label>
                        <input type="file" name="lampiran" accept=".jpg,.jpeg,.png,.pdf"
                            style="width:100%;padding:10px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:13px;outline:none;background:#fff" />
                        <p style="font-size:11px;color:#94a3b8;margin-top:4px">Format: JPG, PNG, PDF. Maksimal 5MB</p>
                    </div>

                    <div style="display:flex;gap:12px;justify-content:flex-end">
                        <a href="{{ route('pengaduan.index') }}" style="padding:10px 24px;border:1px solid #e2e8f0;border-radius:10px;color:#64748b;font-weight:600;font-size:14px;text-decoration:none">Batal</a>
                        <button type="submit" style="padding:10px 28px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(16,185,129,0.3)">
                            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Pengaduan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Info --}}
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:20px;margin-top:16px">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
                    <span style="font-size:16px">ℹ️</span>
                    <span style="font-weight:700;color:#166534;font-size:14px">Informasi Penting</span>
                </div>
                <ul style="font-size:13px;color:#166534;line-height:2;margin:0;padding-left:20px">
                    <li>Pastikan isi pengaduan jelas dan lengkap</li>
                    <li>Admin akan merespon dalam 1-3 hari kerja</li>
                    <li>Pantau status pengaduan di halaman daftar</li>
                    <li>Pengaduan privat hanya bisa dilihat admin dan Anda</li>
                </ul>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9">
                    <span style="font-weight:700;color:#1e293b;font-size:15px">Kategori Pengaduan</span>
                </div>
                <div style="padding:16px 20px">
                    <div style="display:flex;flex-direction:column;gap:8px">
                        @foreach(['Keamanan' => ['icon' => '🔒', 'color' => '#fee2e2', 'text' => '#dc2626'], 'Kebersihan' => ['icon' => '🧹', 'color' => '#dcfce7', 'text' => '#16a34a'], 'Keuangan' => ['icon' => '💰', 'color' => '#fef3c7', 'text' => '#d97706'], 'Infrastruktur' => ['icon' => '🏗️', 'color' => '#e0e7ff', 'text' => '#4f46e5'], 'Sosial' => ['icon' => '🤝', 'color' => '#fce7f3', 'text' => '#db2777'], 'Lainnya' => ['icon' => '📋', 'color' => '#f1f5f9', 'text' => '#64748b']] as $nama => $data)
                            <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:8px;background:{{$data['color']}}">
                                <span>{{ $data['icon'] }}</span>
                                <span style="font-size:13px;font-weight:600;color:{{$data['text']}}">{{ $nama }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
