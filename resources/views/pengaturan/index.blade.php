@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Pengaturan</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan</h1>
        </div>
    </div>

    {{-- Tabs --}}
    <div style="display:flex;gap:4px;background:#f1f5f9;border-radius:12px;padding:4px;width:fit-content">
        <a href="{{ route('pengaturan') }}" style="padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;{{ request()->routeIs('pengaturan') ? 'background:#fff;color:#1e293b;box-shadow:0 1px 3px rgba(0,0,0,0.1)' : 'color:#64748b' }}">
            ⚙️ Pengaturan Umum
        </a>
        <a href="{{ route('pengaturan.tata-tertib') }}" style="padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;{{ request()->routeIs('pengaturan.tata-tertib') ? 'background:#fff;color:#1e293b;box-shadow:0 1px 3px rgba(0,0,0,0.1)' : 'color:#64748b' }}">
            📜 Tata Tertib
        </a>
        <a href="{{ route('pengaturan.kelola-pengurus') }}" style="padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;{{ request()->routeIs('pengaturan.kelola-pengurus') ? 'background:#fff;color:#1e293b;box-shadow:0 1px 3px rgba(0,0,0,0.1)' : 'color:#64748b' }}">
            👥 Kelola Pengurus
        </a>
    </div>

    @if(session('success'))
        <div style="padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;font-size:14px;font-weight:500">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div style="padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;font-size:14px">
            @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
    @endif

    <form action="{{ route('pengaturan.update') }}" method="POST">
        @csrf @method('PUT')

        <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
            <div class="space-y-6">
                {{-- Detail RT/RW --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center">
                            <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span style="font-weight:700;color:#1e293b;font-size:15px">Detail RT / RW</span>
                    </div>
                    <div style="padding:20px">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Nama RT *</label>
                                <input type="text" name="nama_rt" value="{{ old('nama_rt', $struktur->nama_rt ?? '') }}" placeholder="RT 005"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Nomor RT</label>
                                <input type="text" name="nomor_rt" value="{{ old('nomor_rt', $struktur->nomor_rt ?? '') }}" placeholder="005"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Nomor RW</label>
                                <input type="text" name="nomor_rw" value="{{ old('nomor_rw', $struktur->nomor_rw ?? '') }}" placeholder="003"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Kode Pos</label>
                                <input type="text" name="kode_pos" value="{{ old('kode_pos', $struktur->kode_pos ?? '') }}" placeholder="12345"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div style="grid-column:span 2">
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Alamat Lengkap</label>
                                <input type="text" name="alamat_rt" value="{{ old('alamat_rt', $struktur->alamat_rt ?? '') }}" placeholder="Jl. Merdeka No. 10"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Kelurahan</label>
                                <input type="text" name="kelurahan" value="{{ old('kelurahan', $struktur->kelurahan ?? '') }}"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Kecamatan</label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan', $struktur->kecamatan ?? '') }}"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Kota</label>
                                <input type="text" name="kota" value="{{ old('kota', $struktur->kota ?? '') }}"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Provinsi</label>
                                <input type="text" name="provinsi" value="{{ old('provinsi', $struktur->provinsi ?? '') }}"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Telepon RT</label>
                                <input type="text" name="telepon_rt" value="{{ old('telepon_rt', $struktur->telepon_rt ?? '') }}" placeholder="(021) 1234-5678"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Email</label>
                                <input type="email" name="email_rt" value="{{ old('email_rt', $struktur->email_rt ?? '') }}" placeholder="rt005@example.com"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notifikasi --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center">
                            <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <span style="font-weight:700;color:#1e293b;font-size:15px">Notifikasi & Notulensi</span>
                    </div>
                    <div style="padding:20px">
                        <div style="margin-bottom:16px">
                            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">YouTube Data API Key</label>
                            <input type="text" name="youtube" value="{{ $youtube }}" placeholder="AIzaSy..."
                                style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            <p style="font-size:11px;color:#94a3b8;margin-top:4px">Masukkan kunci API YouTube untuk integrasi notulensi video</p>
                        </div>
                        <div style="padding:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;display:flex;align-items:center;gap:8px">
                            <svg style="width:16px;height:16px;color:#10b981;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span style="font-size:12px;color:#166534">Kunci API berhasil tervalidasi — Selamat datang di YouTube Data API v3</span>
                        </div>
                    </div>
                </div>

                {{-- Link Grup --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#25d366,#128c7e);display:flex;align-items:center;justify-content:center">
                            <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <span style="font-weight:700;color:#1e293b;font-size:15px">Link Grup WhatsApp</span>
                    </div>
                    <div style="padding:20px">
                        <div>
                            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">URL Grup WA</label>
                            <input type="url" name="grup_wa" value="{{ $grupWa }}" placeholder="https://chat.whatsapp.com/..."
                                style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                        </div>
                    </div>
                </div>

                {{-- Identitas RT --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);display:flex;align-items:center;justify-content:center">
                            <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span style="font-weight:700;color:#1e293b;font-size:15px">Identitas RT</span>
                    </div>
                    <div style="padding:20px">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Nama / Akun RT</label>
                                <input type="text" value="RT 005 / RW 003" disabled
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;background:#f8fafc;color:#64748b" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Informasi Lainnya</label>
                                <input type="text" value="Jakarta Selatan" disabled
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;background:#f8fafc;color:#64748b" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Bulan Aktif</label>
                                <input type="text" value="Per September 2026" disabled
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;background:#f8fafc;color:#64748b" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Rumah Ibadah</label>
                                <input type="text" name="rumah_ibadah" value="{{ $rumahIbadah }}" placeholder="Masjid, Gereja, Mushola..."
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Logo RT --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                        <span style="font-weight:700;color:#1e293b;font-size:15px">Logo RT</span>
                    </div>
                    <div style="padding:20px;text-align:center">
                        <div style="width:120px;height:120px;border-radius:16px;border:2px dashed #e2e8f0;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;background:#f8fafc;overflow:hidden">
                            @if($struktur && $struktur->logo_rt)
                                <img src="{{ asset($struktur->logo_rt) }}" alt="Logo RT" style="width:100%;height:100%;object-fit:contain">
                            @else
                                <svg style="width:40px;height:40px;color:#cbd5e1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                        </div>
                        <p style="font-size:11px;color:#94a3b8">Unggah logo dalam format PNG, JPG. Ukuran ideal: 200×200px</p>
                    </div>
                </div>

                {{-- Preview Struktur --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9">
                        <span style="font-weight:700;color:#1e293b;font-size:15px">Preview Struktur RT</span>
                    </div>
                    <div style="padding:20px">
                        <p style="font-size:13px;color:#64748b;margin-bottom:12px">Pengurus yang ditampilkan di halaman Struktur RT:</p>
                        @if($struktur && $struktur->pengurusAktif->count() > 0)
                            <div style="display:flex;flex-direction:column;gap:8px">
                                @foreach($struktur->pengurusAktif->take(5) as $p)
                                    <div style="display:flex;align-items:center;gap:8px;padding:8px;border-radius:8px;background:#f8fafc">
                                        <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                            <span style="color:#fff;font-size:11px;font-weight:700">{{ $p->initial }}</span>
                                        </div>
                                        <div>
                                            <div style="font-size:12px;font-weight:600;color:#1e293b">{{ $p->nama }}</div>
                                            <div style="font-size:10px;color:#64748b">{{ $p->jabatan }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="font-size:12px;color:#94a3b8;text-align:center">Belum ada pengurus</p>
                        @endif
                        <a href="{{ route('struktur-rt.show') }}" style="display:block;text-align:center;margin-top:12px;font-size:12px;color:#10b981;font-weight:600;text-decoration:none">Lihat Struktur Lengkap →</a>
                    </div>
                </div>

                {{-- Tombol --}}
                <button type="submit" style="width:100%;padding:14px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:12px;font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 12px rgba(16,185,129,0.3)">
                    <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
