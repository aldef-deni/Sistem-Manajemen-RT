@extends('layouts.app')

@section('title', 'Kelola Pengurus')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="text-emerald-600 hover:underline font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('pengaturan') }}" class="text-emerald-600 hover:underline font-medium">Pengaturan</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Kelola Pengurus</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Kelola Pengurus</h1>
        </div>
    </div>

    {{-- Tabs --}}
    <div style="display:flex;gap:4px;background:#f1f5f9;border-radius:12px;padding:4px;width:fit-content">
        <a href="{{ route('pengaturan') }}" style="padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;color:#64748b">
            ⚙️ Pengaturan Umum
        </a>
        <a href="{{ route('pengaturan.tata-tertib') }}" style="padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;color:#64748b">
            📜 Tata Tertib
        </a>
        <a href="{{ route('pengaturan.kelola-pengurus') }}" style="padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;background:#fff;color:#1e293b;box-shadow:0 1px 3px rgba(0,0,0,0.1)">
            👥 Kelola Pengurus
        </a>
    </div>

    @if(session('success'))
        <div style="padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;font-size:14px;font-weight:500">{{ session('success') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
        {{-- Form Tambah/Edit --}}
        <div>
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center">
                        <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <span style="font-weight:700;color:#1e293b;font-size:15px">Tambah Pengurus Baru</span>
                </div>
                <div style="padding:20px">
                    <form action="{{ route('pengaturan.pengurus.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                            <div style="grid-column:span 2">
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:4px">Nama Lengkap *</label>
                                <input type="text" name="nama" required placeholder="Nama lengkap pengurus"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:4px">Jabatan *</label>
                                <select name="jabatan" required style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;background:#fff">
                                    <option value="">Pilih Jabatan</option>
                                    <option value="Ketua RT">Ketua RT</option>
                                    <option value="Wakil Ketua RT">Wakil Ketua RT</option>
                                    <option value="Sekretaris">Sekretaris</option>
                                    <option value="Bendahara">Bendahara</option>
                                    <option value="Ketua Keamanan">Ketua Keamanan</option>
                                    <option value="Ketua Kebersihan">Ketua Kebersihan</option>
                                    <option value="Ketua Sosial">Ketua Sosial</option>
                                    <option value="Ketua Keagamaan">Ketua Keagamaan</option>
                                    <option value="Ketua Olahraga">Ketua Olahraga</option>
                                    <option value="Bendahara 2">Bendahara 2</option>
                                    <option value="Sie. Perlengkapan">Sie. Perlengkapan</option>
                                    <option value="Sie. Dokumentasi">Sie. Dokumentasi</option>
                                    <option value="Koordinator Blok A">Koordinator Blok A</option>
                                    <option value="Koordinator Blok B">Koordinator Blok B</option>
                                    <option value="Koordinator Blok C">Koordinator Blok C</option>
                                    <option value="Anggota">Anggota</option>
                                </select>
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:4px">Telepon</label>
                                <input type="text" name="telepon" placeholder="08xxx"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:4px">Email</label>
                                <input type="email" name="email" placeholder="email@example.com"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:4px">Foto</label>
                                <input type="file" name="foto" accept="image/*"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:13px;outline:none;background:#fff" />
                            </div>
                            <div style="grid-column:span 2">
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:4px">Alamat</label>
                                <input type="text" name="alamat" placeholder="Alamat lengkap"
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none" />
                            </div>
                            <div style="grid-column:span 2">
                                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:4px">Keterangan</label>
                                <textarea name="keterangan" rows="2" placeholder="Keterangan tambahan..."
                                    style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;resize:vertical"></textarea>
                            </div>
                        </div>
                        <button type="submit" style="width:100%;margin-top:16px;padding:12px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
                            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Tambah Pengurus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- List Pengurus --}}
        <div>
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center">
                            <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14z"/></svg>
                        </div>
                        <span style="font-weight:700;color:#1e293b;font-size:15px">Daftar Pengurus</span>
                    </div>
                    <span style="font-size:12px;color:#64748b;background:#f1f5f9;padding:4px 10px;border-radius:12px">{{ $struktur->pengurus->count() }} orang</span>
                </div>
                <div style="padding:16px 20px;max-height:600px;overflow-y:auto">
                    @forelse($struktur->pengurus as $p)
                        <div style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:12px;{{ $p->status === 'aktif' ? 'background:#f0fdf4;border:1px solid #bbf7d0' : 'background:#f8fafc;border:1px solid #e2e8f0' }};margin-bottom:10px">
                            <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden">
                                @if($p->foto)
                                    <img src="{{ asset($p->foto) }}" alt="{{ $p->nama }}" style="width:100%;height:100%;object-fit:cover">
                                @else
                                    <span style="color:#fff;font-size:16px;font-weight:700">{{ $p->initial }}</span>
                                @endif
                            </div>
                            <div style="flex:1">
                                <div style="font-weight:700;color:#1e293b;font-size:14px">{{ $p->nama }}</div>
                                <div style="font-size:12px;color:#64748b">{{ $p->jabatan }}</div>
                                @if($p->telepon)
                                    <div style="font-size:11px;color:#94a3b8;margin-top:2px">📱 {{ $p->telepon }}</div>
                                @endif
                            </div>
                            <div style="display:flex;gap:6px;flex-shrink:0">
                                <form action="{{ route('pengaturan.pengurus.update', $p->id) }}" method="POST" style="display:inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="nama" value="{{ $p->nama }}">
                                    <input type="hidden" name="jabatan" value="{{ $p->jabatan }}">
                                    <input type="hidden" name="status" value="{{ $p->status === 'aktif' ? 'tidak_aktif' : 'aktif' }}">
                                    <button type="submit" style="padding:6px 8px;border-radius:6px;border:1px solid {{ $p->status === 'aktif' ? '#fde68a' : '#bbf7d0' }};background:{{ $p->status === 'aktif' ? '#fffbeb' : '#f0fdf4' }};cursor:pointer;font-size:11px;color:{{ $p->status === 'aktif' ? '#d97706' : '#16a34a' }}">
                                        {{ $p->status === 'aktif' ? '⏸' : '▶' }}
                                    </button>
                                </form>
                                <form action="{{ route('pengaturan.pengurus.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pengurus ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:6px 8px;border-radius:6px;border:1px solid #fecaca;background:#fef2f2;cursor:pointer;font-size:11px;color:#dc2626">🗑</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:40px;color:#94a3b8">
                            <svg style="width:48px;height:48px;margin:0 auto 12px;color:#cbd5e1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-1m-5 5v-2a3 3 0 00-3-3H4a3 3 0 00-3 3v2h14z"/></svg>
                            <p style="font-size:14px">Belum ada pengurus</p>
                            <p style="font-size:12px;margin-top:4px">Tambahkan pengurus menggunakan form di sebelah kiri</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
