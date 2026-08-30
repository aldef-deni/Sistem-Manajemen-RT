@extends('layouts.app')

@section('title', 'Jenis Pinjaman')

@section('content')
<div style="space-y: 1.25rem;">
    {{-- Header --}}
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: linear-gradient(135deg, #14b8a6, #0d9488); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Jenis Pinjaman</h1>
                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #64748b;">
                    <a href="{{ route('dashboard') }}" style="color: #0d9488; font-weight: 500; text-decoration: none;">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('pinjaman.index') }}" style="color: #0d9488; font-weight: 500; text-decoration: none;">Pinjaman</a>
                    <span>/</span>
                    <span style="color: #334155; font-weight: 500;">Jenis Pinjaman</span>
                </div>
            </div>
        </div>
        <button onclick="openModal()" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Jenis Pinjaman
        </button>
    </div>

    @if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem;">{{ session('success') }}</div>
    @endif

    {{-- Stat Cards --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
        <div style="background: white; border: 1px solid #e2e8f0; border-left: 4px solid #14b8a6; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 44px; height: 44px; border-radius: 0.75rem; background: #f0fdfa; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 22px; height: 22px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p style="font-size: 0.6875rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Jenis</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">{{ $totalJenis }}</p>
            </div>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-left: 4px solid #10b981; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 44px; height: 44px; border-radius: 0.75rem; background: #ecfdf5; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 22px; height: 22px; color: #10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p style="font-size: 0.6875rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Jenis Aktif</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">{{ $jenisAktif }}</p>
            </div>
        </div>
        <div style="background: white; border: 1px solid #e2e8f0; border-left: 4px solid #f97316; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 44px; height: 44px; border-radius: 0.75rem; background: #fff7ed; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 1.125rem; font-weight: 700; color: #f97316;">%</span>
            </div>
            <div>
                <p style="font-size: 0.6875rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Rata-rata Bunga</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">{{ number_format($rataRataBunga, 1) }}%</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; overflow: hidden;">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;">
            <h3 style="font-weight: 700; color: #1e293b; font-size: 0.9375rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 18px; height: 18px; color: #14b8a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Daftar Jenis Pinjaman
            </h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">No</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Nama Jenis Pinjaman</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Bunga/Tahun</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Denda/Hari</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Tenor Maks</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Status</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenis as $j)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #64748b;">{{ $loop->iteration }}</td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; color: #1e293b;">{{ $j->nama }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <span style="display: inline-block; padding: 3px 10px; font-size: 0.75rem; font-weight: 600; background: #0d9488; color: white; border-radius: 9999px;">{{ number_format($j->bunga_persen, 2) }}%</span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <span style="font-size: 0.875rem; font-weight: 600; color: #ef4444;">{{ number_format($j->denda_persen, 2) }}%</span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: center; font-size: 0.875rem; color: #475569;">{{ $j->tenor_bulan }} bulan</td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            @if($j->status === 'aktif')
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #ecfdf5; color: #047857; border-radius: 9999px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span> Aktif
                            </span>
                            @else
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #64748b; border-radius: 9999px;">Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <div style="display: inline-flex; gap: 0.375rem;">
                                <button onclick="editJenis({{ $j->id }}, '{{ $j->nama }}', {{ $j->bunga_persen }}, {{ $j->denda_persen }}, {{ $j->tenor_bulan }}, '{{ $j->status }}')" style="padding: 6px; color: #14b8a6; background: none; border: none; border-radius: 0.5rem; cursor: pointer;" title="Edit">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('pinjaman.jenis.destroy', $j) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus jenis pinjaman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 6px; color: #ef4444; background: none; border: none; border-radius: 0.5rem; cursor: pointer;" title="Hapus">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 3rem 1rem; text-align: center; color: #94a3b8; font-size: 0.875rem;">Belum ada jenis pinjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal --}}
<div id="jenisModal" style="display: none; position: fixed; inset: 0; z-index: 50; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);" onclick="if(event.target===this)closeModal()">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 1rem; width: 480px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
            <h2 id="modalTitle" style="font-size: 1.125rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <span style="color: #14b8a6;">💎</span> Tambah Jenis Pinjaman
            </h2>
            <button onclick="closeModal()" style="width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #64748b;">×</button>
        </div>
        <form id="jenisForm" method="POST" style="padding: 1.5rem;">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" id="jenisId" value="">
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Nama Jenis Pinjaman <span style="color: #ef4444;">*</span></label>
                <input type="text" name="nama" id="inputNama" required placeholder="Contoh: Pinjaman Usaha, Pinjaman Pendidikan" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Bunga per Tahun (%) <span style="color: #ef4444;">*</span></label>
                    <div style="position: relative;">
                        <input type="number" name="bunga_persen" id="inputBunga" required step="0.01" min="0" value="12" style="width: 100%; padding: 0.5rem 2.5rem 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.875rem;">%</span>
                    </div>
                    <p style="font-size: 0.6875rem; color: #94a3b8; margin-top: 0.25rem; display: flex; align-items: center; gap: 4px;">
                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Bunga flat per tahun
                    </p>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Denda Keterlambatan (%/hari) <span style="color: #ef4444;">*</span></label>
                    <div style="position: relative;">
                        <input type="number" name="denda_persen" id="inputDenda" required step="0.01" min="0" value="0.5" style="width: 100%; padding: 0.5rem 2.5rem 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.875rem;">%</span>
                    </div>
                    <p style="font-size: 0.6875rem; color: #94a3b8; margin-top: 0.25rem; display: flex; align-items: center; gap: 4px;">
                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Denda dihitung dari nominal angsuran
                    </p>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Tenor Maksimal (Bulan) <span style="color: #ef4444;">*</span></label>
                    <div style="position: relative;">
                        <input type="number" name="tenor_bulan" id="inputTenor" required min="1" value="12" style="width: 100%; padding: 0.5rem 4rem 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); padding: 2px 8px; background: #f1f5f9; border-radius: 0.25rem; font-size: 0.75rem; color: #64748b;">bulan</span>
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">Status</label>
                    <select name="status" id="inputStatus" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; background: white;">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                <button type="button" onclick="closeModal()" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #475569; background: white; cursor: pointer;">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </button>
                <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1.25rem; background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(20,184,166,0.3);">
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTitle').innerHTML = '<span style="color: #14b8a6;">💎</span> Tambah Jenis Pinjaman';
    document.getElementById('jenisForm').action = '{{ route("pinjaman.jenis.store") }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('jenisId').value = '';
    document.getElementById('inputNama').value = '';
    document.getElementById('inputBunga').value = '12';
    document.getElementById('inputDenda').value = '0.5';
    document.getElementById('inputTenor').value = '12';
    document.getElementById('inputStatus').value = 'aktif';
    document.getElementById('jenisModal').style.display = 'block';
}

function editJenis(id, nama, bunga, denda, tenor, status) {
    document.getElementById('modalTitle').innerHTML = '<span style="color: #14b8a6;">💎</span> Edit Jenis Pinjaman';
    document.getElementById('jenisForm').action = '{{ route("pinjaman.jenis") }}/' + id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('jenisId').value = id;
    document.getElementById('inputNama').value = nama;
    document.getElementById('inputBunga').value = bunga;
    document.getElementById('inputDenda').value = denda;
    document.getElementById('inputTenor').value = tenor;
    document.getElementById('inputStatus').value = status;
    document.getElementById('jenisModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('jenisModal').style.display = 'none';
}
</script>
@endsection
