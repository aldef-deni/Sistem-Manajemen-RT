@extends('layouts.app')

@section('title', 'Tambah Kartu Keluarga')
@section('page-title', 'Data Kartu Keluarga')
@section('page-subtitle', 'Tambah Baru')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Data Kartu Keluarga</h2>
            <p class="text-xs text-slate-400 mt-1">Formulir penambahan Kartu Keluarga baru</p>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
            <p class="font-semibold mb-1">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('kartu-keluarga.store') }}" id="kkForm">
        @csrf

        {{-- Section 1: Data KK --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100/50 border-b border-blue-100">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                    </svg>
                    <h3 class="text-sm font-bold text-blue-800">Data Kartu Keluarga</h3>
                </div>
            </div>
            <div class="p-6 space-y-5">
                {{-- No KK, RT, RW --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-6">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nomor KK <span class="text-red-500">*</span></label>
                        <input type="text" name="no_kk" value="{{ old('no_kk') }}" maxlength="20" required
                               placeholder="16 digit nomor KK"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">RT</label>
                        <input type="text" name="rt" value="{{ old('rt') }}" maxlength="5"
                               placeholder="001"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">RW</label>
                        <input type="text" name="rw" value="{{ old('rw') }}" maxlength="5"
                               placeholder="001"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="2" required
                              placeholder="Jalan, gang, nomor rumah..."
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all resize-none">{{ old('alamat') }}</textarea>
                </div>

                {{-- Desa, Kecamatan, Kabupaten, Kode Pos --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Desa / Kelurahan</label>
                        <input type="text" name="desa" value="{{ old('desa') }}"
                               placeholder="Nama desa"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
                               placeholder="Nama kecamatan"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kabupaten / Kota</label>
                        <input type="text" name="kabupaten" value="{{ old('kabupaten') }}"
                               placeholder="Nama kabupaten"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" maxlength="10"
                               placeholder="58211"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Anggota --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100/50 border-b border-blue-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="text-sm font-bold text-blue-800">Anggota Keluarga</h3>
                    <span class="text-xs text-slate-400">— baris pertama otomatis jadi Kepala Keluarga</span>
                </div>
                <button type="button" onclick="addAnggota()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Anggota
                </button>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full" id="anggotaTable">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase w-10">#</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase">NIK <span class="text-red-500">*</span></th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase">Nama Lengkap <span class="text-red-500">*</span></th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase">No. HP</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase">L/P</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase">Tgl Lahir</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase">Status Hub. <span class="text-red-500">*</span></th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase">Domisili</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase">Role</th>
                                <th class="px-3 py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="anggotaBody">
                            {{-- First row (Kepala Keluarga) --}}
                            <tr class="anggota-row border-b border-slate-100">
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">1</span>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="anggota[0][nik]" maxlength="16" required placeholder="16 digit"
                                           class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="anggota[0][nama_lengkap]" required placeholder="Nama lengkap"
                                           class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="anggota[0][no_hp]" placeholder="08xx..."
                                           class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                </td>
                                <td class="px-3 py-2">
                                    <select name="anggota[0][jenis_kelamin]"
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                        <option value="">-</option>
                                        <option value="L">L</option>
                                        <option value="P">P</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="date" name="anggota[0][tanggal_lahir]"
                                           class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="anggota[0][status_hubungan]" value="Kepala Keluarga" readonly
                                           class="w-full px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700 font-medium cursor-not-allowed">
                                </td>
                                <td class="px-3 py-2">
                                    <select name="anggota[0][domisili]"
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                        <option value="Tetap" selected>Tetap</option>
                                        <option value="Kontrakan">Kontrakan</option>
                                        <option value="Kos">Kos</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2">
                                    <select name="anggota[0][role]"
                                            class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                                        <option value="Warga">Warga</option>
                                        <option value="Admin">Admin</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2">
                                    <button type="button" onclick="removeAnggota(this)" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors" title="Hapus" disabled>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Info --}}
                <div class="mt-4 flex items-start gap-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                    <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs text-blue-600">Setiap anggota otomatis mendapat akun login dengan <strong>Username = NIK</strong> dan <strong>Password = password</strong>. Role default: <strong>Warga</strong>.</p>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('kartu-keluarga.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan KK & Buat User
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let anggotaIndex = 1;

function addAnggota() {
    const tbody = document.getElementById('anggotaBody');
    const row = document.createElement('tr');
    row.className = 'anggota-row border-b border-slate-100';
    row.innerHTML = `
        <td class="px-3 py-2">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">${anggotaIndex + 1}</span>
        </td>
        <td class="px-3 py-2">
            <input type="text" name="anggota[${anggotaIndex}][nik]" maxlength="16" required placeholder="16 digit"
                   class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
        </td>
        <td class="px-3 py-2">
            <input type="text" name="anggota[${anggotaIndex}][nama_lengkap]" required placeholder="Nama lengkap"
                   class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
        </td>
        <td class="px-3 py-2">
            <input type="text" name="anggota[${anggotaIndex}][no_hp]" placeholder="08xx..."
                   class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
        </td>
        <td class="px-3 py-2">
            <select name="anggota[${anggotaIndex}][jenis_kelamin]"
                    class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <option value="">-</option>
                <option value="L">L</option>
                <option value="P">P</option>
            </select>
        </td>
        <td class="px-3 py-2">
            <input type="date" name="anggota[${anggotaIndex}][tanggal_lahir]"
                   class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
        </td>
        <td class="px-3 py-2">
            <select name="anggota[${anggotaIndex}][status_hubungan]"
                    class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <option value="Istri">Istri</option>
                <option value="Anak">Anak</option>
                <option value="Orang Tua">Orang Tua</option>
                <option value="Mertua">Mertua</option>
                <option value="Adik">Adik</option>
                <option value="Kakak">Kakak</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </td>
        <td class="px-3 py-2">
            <select name="anggota[${anggotaIndex}][domisili]"
                    class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <option value="Tetap">Tetap</option>
                <option value="Kontrakan">Kontrakan</option>
                <option value="Kos">Kos</option>
            </select>
        </td>
        <td class="px-3 py-2">
            <select name="anggota[${anggotaIndex}][role]"
                    class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <option value="Warga">Warga</option>
                <option value="Admin">Admin</option>
            </select>
        </td>
        <td class="px-3 py-2">
            <button type="button" onclick="removeAnggota(this)" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors" title="Hapus">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    anggotaIndex++;
}

function removeAnggota(btn) {
    const row = btn.closest('tr');
    row.remove();
    renumberRows();
}

function renumberRows() {
    const rows = document.querySelectorAll('#anggotaBody .anggota-row');
    rows.forEach((row, i) => {
        row.querySelector('span.font-bold').textContent = i + 1;
    });
}
</script>
@endpush
@endsection
