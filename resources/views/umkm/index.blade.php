@extends('layouts.app')

@section('title', 'Direktori UMKM Warga')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#0d9488,#0f766e);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,148,136,0.3)">
                <svg style="width:22px;height:22px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h1 style="font-size:1.25rem;font-weight:700;color:#0f172a">Direktori UMKM Warga</h1>
                <p style="font-size:0.8rem;color:#64748b">Dashboard / UMKM Warga</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('umkm.daftarkan') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;border:2px solid #0d9488;color:#0d9488;font-weight:600;font-size:0.875rem;text-decoration:none;transition:all 0.2s" onmouseover="this.style.background='#f0fdfa'" onmouseout="this.style.background='transparent'">
                <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Daftarkan Usaha Saya
            </a>
            <a href="{{ route('umkm.create') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;background:linear-gradient(135deg,#0d9488,#0f766e);color:white;font-weight:600;font-size:0.875rem;text-decoration:none;box-shadow:0 4px 12px rgba(13,148,136,0.3);transition:all 0.2s">
                <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah (Admin)
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total UMKM --}}
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px">
            <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#e0f2fe,#bae6fd);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg style="width:24px;height:24px;color:#0284c7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p style="font-size:0.75rem;color:#64748b;margin:0">Total UMKM</p>
                <p style="font-size:1.5rem;font-weight:700;color:#0f172a;margin:0">{{ $totalUmk }}</p>
            </div>
        </div>

        {{-- Aktif --}}
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px">
            <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg style="width:24px;height:24px;color:#16a34a" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p style="font-size:0.75rem;color:#64748b;margin:0">Aktif</p>
                <p style="font-size:1.5rem;font-weight:700;color:#0f172a;margin:0">{{ $aktif }}</p>
            </div>
        </div>

        {{-- Menunggu Review --}}
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px">
            <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#fef3c7,#fde68a);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg style="width:24px;height:24px;color:#d97706" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p style="font-size:0.75rem;color:#64748b;margin:0">Menunggu Review</p>
                <p style="font-size:1.5rem;font-weight:700;color:#0f172a;margin:0">{{ $pending }}</p>
            </div>
        </div>

        {{-- Kategori --}}
        <div style="background:white;border-radius:14px;padding:20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px">
            <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg style="width:24px;height:24px;color:#7c3aed" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            </div>
            <div>
                <p style="font-size:0.75rem;color:#64748b;margin:0">Kategori</p>
                <p style="font-size:1.5rem;font-weight:700;color:#0f172a;margin:0">{{ $kategoriCount }}</p>
            </div>
        </div>
    </div>

    {{-- Category Tabs --}}
    @php
        $categories = ['Semua', 'Kuliner', 'Fashion', 'Jasa', 'Pertanian', 'Kerajinan', 'Teknologi', 'Kesehatan', 'Pendidikan', 'Lainnya'];
        $activeKategori = request('kategori', 'Semua');
    @endphp
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        @foreach($categories as $cat)
            @php
                $isActive = $activeKategori === $cat;
                $bg = $isActive ? 'linear-gradient(135deg,#0d9488,#0f766e)' : 'white';
                $color = $isActive ? 'white' : '#475569';
                $border = $isActive ? 'none' : '1px solid #e2e8f0';
            @endphp
            <a href="{{ route('umkm.index', array_merge(request()->except('kategori', 'page'), ['kategori' => $cat])) }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;background:{{ $bg }};color:{{ $color }};border:{{ $border }};font-size:0.8rem;font-weight:600;text-decoration:none;transition:all 0.2s;white-space:nowrap">
                @if($cat === 'Semua')
                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                @endif
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('umkm.index') }}">
        @if(request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif
        <div style="position:relative">
            <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);width:20px;height:20px;color:#94a3b8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama usaha, layanan, atau pemilik..." style="width:100%;padding:12px 16px 12px 44px;border-radius:12px;border:1px solid #e2e8f0;font-size:0.875rem;outline:none;transition:border-color 0.2s" onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e2e8f0'">
        </div>
    </form>

    {{-- UMKM Cards --}}
    @if($umkms->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($umkms as $item)
                @php
                    $kategoriColors = [
                        'Kuliner' => ['bg' => '#fef2f2', 'text' => '#dc2626', 'border' => '#fecaca'],
                        'Fashion' => ['bg' => '#fdf4ff', 'text' => '#a855f7', 'border' => '#e9d5ff'],
                        'Jasa' => ['bg' => '#eff6ff', 'text' => '#2563eb', 'border' => '#bfdbfe'],
                        'Pertanian' => ['bg' => '#f0fdf4', 'text' => '#16a34a', 'border' => '#bbf7d0'],
                        'Kerajinan' => ['bg' => '#fffbeb', 'text' => '#d97706', 'border' => '#fde68a'],
                        'Teknologi' => ['bg' => '#f5f3ff', 'text' => '#7c3aed', 'border' => '#ddd6fe'],
                        'Kesehatan' => ['bg' => '#ecfdf5', 'text' => '#059669', 'border' => '#a7f3d0'],
                        'Pendidikan' => ['bg' => '#eff6ff', 'text' => '#3b82f6', 'border' => '#bfdbfe'],
                        'Lainnya' => ['bg' => '#f8fafc', 'text' => '#475569', 'border' => '#e2e8f0'],
                    ];
                    $kc = $kategoriColors[$item->kategori] ?? $kategoriColors['Lainnya'];

                    $statusColors = [
                        'aktif' => ['bg' => '#dcfce7', 'text' => '#16a34a', 'label' => 'Aktif'],
                        'nonaktif' => ['bg' => '#fee2e2', 'text' => '#dc2626', 'label' => 'Nonaktif'],
                        'pending_review' => ['bg' => '#fef3c7', 'text' => '#d97706', 'label' => 'Pending Review'],
                    ];
                    $sc = $statusColors[$item->status] ?? $statusColors['aktif'];
                @endphp
                <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                    {{-- Foto --}}
                    <div style="height:160px;background:linear-gradient(135deg,#ccfbf1,#99f6e4);display:flex;align-items:center;justify-content:center;position:relative">
                        @if($item->foto_usaha)
                            <img src="{{ Storage::url($item->foto_usaha) }}" alt="{{ $item->nama_usaha }}" style="width:100%;height:100%;object-fit:cover">
                        @else
                            <svg style="width:56px;height:56px;color:#0d9488;opacity:0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        @endif
                    </div>

                    <div style="padding:16px">
                        {{-- Nama + Kategori --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin:0">{{ $item->nama_usaha }}</h3>
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;background:{{ $kc['bg'] }};color:{{ $kc['text'] }};font-size:0.7rem;font-weight:600;border:1px solid {{ $kc['border'] }}">
                                {{ $item->kategori }}
                            </span>
                        </div>

                        {{-- Pemilik --}}
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px">
                            <svg style="width:14px;height:14px;color:#64748b" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span style="font-size:0.8rem;color:#64748b">{{ $item->user->name ?? 'Admin' }}</span>
                            <span style="display:inline-flex;align-items:center;gap:3px;padding:2px 8px;border-radius:6px;background:#dcfce7;color:#16a34a;font-size:0.65rem;font-weight:600">
                                <svg style="width:10px;height:10px" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Usaha Saya
                            </span>
                            <span style="display:inline-flex;align-items:center;padding:2px 8px;border-radius:6px;background:{{ $sc['bg'] }};color:{{ $sc['text'] }};font-size:0.65rem;font-weight:600">{{ $sc['label'] }}</span>
                        </div>

                        {{-- Deskripsi --}}
                        <p style="font-size:0.8rem;color:#64748b;margin:0 0 10px 0;line-height:1.5">{{ Str::limit($item->deskripsi_usaha, 80) }}</p>

                        {{-- Lokasi --}}
                        @if($item->alamat_lokasi)
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                            <svg style="width:14px;height:14px;color:#64748b" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span style="font-size:0.8rem;color:#64748b">{{ $item->alamat_lokasi }}</span>
                        </div>
                        @endif

                        {{-- Jam Operasional --}}
                        @if($item->jam_operasional)
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:12px">
                            <svg style="width:14px;height:14px;color:#64748b" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span style="font-size:0.8rem;color:#64748b">{{ $item->jam_operasional }}</span>
                        </div>
                        @endif

                        {{-- Contact Buttons --}}
                        <div style="display:flex;gap:8px;margin-bottom:12px">
                            @if($item->whatsapp)
                            <a href="https://wa.me/{{ $item->whatsapp }}" target="_blank" style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;background:#dcfce7;color:#16a34a;font-size:0.75rem;font-weight:600;text-decoration:none;border:1px solid #bbf7d0;transition:all 0.2s" onmouseover="this.style.background='#bbf7d0'" onmouseout="this.style.background='#dcfce7'">
                                <svg style="width:14px;height:14px" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WA
                            </a>
                            @endif
                            @if($item->no_telepon)
                            <a href="tel:{{ $item->no_telepon }}" style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;background:#ede9fe;color:#7c3aed;font-size:0.75rem;font-weight:600;text-decoration:none;border:1px solid #ddd6fe;transition:all 0.2s" onmouseover="this.style.background='#ddd6fe'" onmouseout="this.style.background='#ede9fe'">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Telepon
                            </a>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div style="display:flex;gap:8px;border-top:1px solid #f1f5f9;padding-top:12px">
                            <a href="{{ route('umkm.edit', $item->id) }}" style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;background:white;color:#475569;font-size:0.75rem;font-weight:600;text-decoration:none;border:1px solid #e2e8f0;transition:all 0.2s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form action="{{ route('umkm.destroy', $item->id) }}" method="POST" style="margin:0" onsubmit="return confirm('Yakin hapus UMKM ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;background:white;color:#dc2626;font-size:0.75rem;font-weight:600;border:1px solid #fecaca;cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">
                                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($umkms->hasPages())
        <div style="display:flex;justify-content:center">
            {{ $umkms->appends(request()->query())->links() }}
        </div>
        @endif
    @else
        <div style="background:white;border-radius:14px;padding:48px;text-align:center;border:1px solid #e2e8f0">
            <svg style="width:56px;height:56px;color:#cbd5e1;margin:0 auto 16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <h3 style="font-size:1rem;font-weight:600;color:#475569;margin:0 0 8px 0">Belum ada UMKM</h3>
            <p style="font-size:0.85rem;color:#94a3b8;margin:0">Silakan tambahkan UMKM baru atau daftarkan usaha Anda.</p>
        </div>
    @endif
</div>
@endsection
