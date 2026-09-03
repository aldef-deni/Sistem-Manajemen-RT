@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<style>
    .profil-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; }
    .profil-input {
        width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px;
        font-size:14px; color:#1e293b; outline:none; background:#fff; transition:border-color .15s, box-shadow .15s;
    }
    .profil-input:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.12); }
    .profil-input:disabled { background:#f8fafc; color:#94a3b8; cursor:not-allowed; }
    .profil-label { display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; }
    .pw-input-wrap { position:relative; }
    .pw-input-wrap .profil-input { padding-right:40px; }
    .eye-btn {
        position:absolute; right:10px; top:50%; transform:translateY(-50%);
        background:none; border:none; cursor:pointer; color:#94a3b8; padding:4px;
    }
    .eye-btn:hover { color:#64748b; }
    .badge-pill {
        display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:999px;
        font-size:11px; font-weight:700;
    }
</style>

<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div>
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
            <a href="{{ route('dashboard') }}" style="color:#10b981" class="font-medium hover:underline">Dashboard</a>
            <span>/</span>
            <span class="text-slate-700 font-medium">Pengaturan</span>
            <span>/</span>
            <span class="text-slate-700 font-medium">Profil Saya</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Profil Saya</h1>
        <p class="text-sm text-slate-400 mt-0.5">Kelola informasi akun dan data diri Anda</p>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div style="padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;font-size:14px;font-weight:500;display:flex;align-items:center;gap:10px">
            <svg style="width:18px;height:18px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;font-size:14px">
            @foreach($errors->all() as $e) <div style="display:flex;align-items:center;gap:8px;padding:2px 0"><span>⚠️</span>{{ $e }}</div> @endforeach
        </div>
    @endif

    <div style="display:grid;grid-template-columns:380px 1fr;gap:24px;align-items:start">

        {{-- ====== KOLOM KIRI ====== --}}
        <div style="display:flex;flex-direction:column;gap:24px">

            {{-- Kartu Ringkasan Profil --}}
            <div class="profil-card">
                {{-- Banner --}}
                <div style="height:110px;background:linear-gradient(135deg,#0d9488,#0f766e 55%,#0b6653);position:relative">
                    <svg style="position:absolute;right:-20px;top:-30px;width:180px;height:180px;color:rgba(255,255,255,.08)" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>

                {{-- Avatar --}}
                <div style="text-align:center;margin-top:-48px;position:relative;z-index:1">
                    <div style="position:relative;width:96px;height:96px;margin:0 auto">
                        <div style="width:96px;height:96px;border-radius:50%;border:4px solid #fff;box-shadow:0 4px 14px rgba(0,0,0,.12);overflow:hidden;background:linear-gradient(135deg,#14b8a6,#0f766e);display:flex;align-items:center;justify-content:center">
                            @if($user->foto_url)
                                <img src="{{ $user->foto_url }}" alt="Foto Profil" style="width:100%;height:100%;object-fit:cover">
                            @else
                                <span style="color:#fff;font-size:32px;font-weight:800">{{ $user->initial }}</span>
                            @endif
                        </div>
                        <span style="position:absolute;bottom:4px;right:4px;width:16px;height:16px;border-radius:50%;background:#22c55e;border:3px solid #fff"></span>
                    </div>
                    <h2 style="font-size:17px;font-weight:800;color:#1e293b;margin-top:10px">{{ $user->name }}</h2>
                    <div style="display:flex;justify-content:center;gap:8px;margin-top:8px">
                        <span class="badge-pill" style="background:#dbeafe;color:#1d4ed8">👤 {{ $user->role_label }}</span>
                        <span class="badge-pill" style="background:#dcfce7;color:#15803d">
                            <span style="width:6px;height:6px;border-radius:50%;background:#22c55e;display:inline-block"></span>
                            Aktif
                        </span>
                    </div>

                    {{-- Upload foto --}}
                    <form action="{{ route('profil.foto') }}" method="POST" enctype="multipart/form-data" style="margin-top:14px">
                        @csrf
                        <label for="foto-upload" style="display:inline-block;font-size:12px;color:#10b981;font-weight:600;cursor:pointer;background:#f0fdf4;border:1px dashed #6ee7b7;padding:8px 16px;border-radius:10px;transition:background .15s" onmouseover="this.style.background='#d1fae5'" onmouseout="this.style.background='#f0fdf4'">
                            📷 Unggah Foto Baru
                        </label>
                        <input type="file" id="foto-upload" name="foto" accept="image/jpeg,image/png,image/webp" style="display:none" onchange="this.form.submit()">
                    </form>
                    <p style="font-size:11px;color:#94a3b8;margin-top:8px">JPG, PNG, WEBP maks. 2MB</p>

                    @if($user->foto)
                        <form action="{{ route('profil.foto.hapus') }}" method="POST" style="margin-top:10px" onsubmit="return confirm('Yakin ingin menghapus foto profil?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:#dc2626;background:#fef2f2;border:1px solid #fecaca;padding:7px 14px;border-radius:10px;cursor:pointer">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus Foto
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Ringkasan akun --}}
                <div style="margin:18px 0 0;border-top:1px solid #f1f5f9;padding:16px 20px">
                    <div style="display:flex;justify-content:space-between;padding:7px 0">
                        <span style="font-size:13px;color:#64748b">Username</span>
                        <span style="font-size:13px;font-weight:600;color:#1e293b">@if($user->username){{ $user->username }}@else—@endif</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:7px 0;border-top:1px dashed #f1f5f9">
                        <span style="font-size:13px;color:#64748b">Email</span>
                        <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $user->email }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:7px 0;border-top:1px dashed #f1f5f9">
                        <span style="font-size:13px;color:#64748b">No HP</span>
                        <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $user->no_hp ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:7px 0;border-top:1px dashed #f1f5f9">
                        <span style="font-size:13px;color:#64748b">Bergabung</span>
                        <span style="font-size:13px;font-weight:600;color:#1e293b">{{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Kartu Ganti Password --}}
            <div class="profil-card" style="border-top:4px solid #dc2626">
                <div style="padding:18px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px">
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#f87171,#dc2626);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg style="width:18px;height:18px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:#1e293b">Ganti Password</h3>
                        <p style="font-size:12px;color:#94a3b8">Minimal 6 karakter</p>
                    </div>
                </div>

                <form action="{{ route('profil.password') }}" method="POST" style="padding:20px">
                    @csrf @method('PUT')
                    <div style="margin-bottom:14px">
                        <label class="profil-label" for="pw_lama">Password Lama</label>
                        <div class="pw-input-wrap">
                            <input type="password" id="pw_lama" name="password_lama" placeholder="Password saat ini" class="profil-input">
                            <button type="button" class="eye-btn" onclick="togglePw('pw_lama', this)">👁</button>
                        </div>
                    </div>
                    <div style="margin-bottom:14px">
                        <label class="profil-label" for="pw_baru">Password Baru</label>
                        <div class="pw-input-wrap">
                            <input type="password" id="pw_baru" name="password_baru" placeholder="Minimal 6 karakter" class="profil-input">
                            <button type="button" class="eye-btn" onclick="togglePw('pw_baru', this)">👁</button>
                        </div>
                    </div>
                    <div style="margin-bottom:18px">
                        <label class="profil-label" for="pw_konfirmasi">Konfirmasi Password Baru</label>
                        <div class="pw-input-wrap">
                            <input type="password" id="pw_konfirmasi" name="password_baru_confirmation" placeholder="Ulangi password baru" class="profil-input">
                            <button type="button" class="eye-btn" onclick="togglePw('pw_konfirmasi', this)">👁</button>
                        </div>
                    </div>
                    <button type="submit" style="width:100%;padding:12px;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 12px rgba(220,38,38,.25)">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Ubah Password
                    </button>
                </form>
            </div>
        </div>

        {{-- ====== KOLOM KANAN ====== --}}
        <div class="profil-card">
            <div style="padding:18px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#34d399,#10b981);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg style="width:18px;height:18px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:#1e293b">Edit Informasi Profil</h3>
                    <p style="font-size:12px;color:#94a3b8">Data akan diperbarui di akun dan data warga</p>
                </div>
            </div>

            <form action="{{ route('profil.update') }}" method="POST" style="padding:20px">
                @csrf @method('PUT')

                <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
                    <svg style="width:16px;height:16px;color:#10b981" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span style="font-size:13px;font-weight:800;color:#334155;letter-spacing:.5px">INFORMASI AKUN</span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <label class="profil-label" for="nama">Nama Lengkap *</label>
                        <input type="text" id="nama" name="name" value="{{ old('name', $user->name) }}" class="profil-input" required>
                    </div>
                    <div>
                        <label class="profil-label" for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username ?? '') }}" class="profil-input" required>
                        <p style="font-size:11px;color:#94a3b8;margin-top:4px">Username dipakai untuk login</p>
                    </div>
                    <div>
                        <label class="profil-label" for="email">Email</label>
                        <div style="position:relative">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#94a3b8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="profil-input" style="padding-left:38px">
                        </div>
                    </div>
                    <div>
                        <label class="profil-label" for="no_hp">No HP / WhatsApp</label>
                        <div style="position:relative">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#94a3b8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp ?? '') }}" placeholder="08xxxxxxxxxx" class="profil-input" style="padding-left:38px">
                        </div>
                    </div>
                </div>

                {{-- Status koneksi data warga --}}
                @if($warga)
                    <div style="margin-top:16px;padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;display:flex;align-items:center;gap:10px">
                        <svg style="width:18px;height:18px;color:#16a34a;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="font-size:12.5px;color:#166534">Akun terhubung dengan data warga <strong>{{ $warga->nama_lengkap }}</strong> (NIK {{ $warga->nik }})</span>
                    </div>
                @else
                    <div style="margin-top:16px;padding:12px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;display:flex;align-items:center;gap:10px">
                        <svg style="width:18px;height:18px;color:#f59e0b;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span style="font-size:12.5px;color:#92400e">Akun ini belum terhubung dengan data warga. Hubungi admin untuk menghubungkan data.</span>
                    </div>
                @endif

                {{-- Tombol aksi --}}
                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:18px;border-top:1px solid #f1f5f9">
                    <a href="{{ route('dashboard') }}" style="padding:11px 24px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;font-weight:600;color:#475569;text-decoration:none;transition:background .15s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                        Batal
                    </a>
                    <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:linear-gradient(135deg,#34d399,#10b981);color:#fff;border:none;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer;box-shadow:0 4px 12px rgba(16,185,129,.25)">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePw(id, btn) {
        var input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁';
        }
    }
</script>
@endsection