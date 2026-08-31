@extends('layouts.app')

@section('title', 'Tambah Pengumuman')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('pengumuman.index') }}" class="text-teal-600 hover:underline font-medium">Pengumuman</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Tambah</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Pengumuman</h1>
        </div>
        <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="flex gap-6">
            {{-- Main Form --}}
            <div class="flex-1 space-y-6">
                {{-- Judul --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <label class="text-sm font-bold text-slate-700 block mb-2">Judul Pengumuman <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" maxlength="200"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                        placeholder="Masukkan judul pengumuman" required>
                    <p class="text-xs text-slate-400 mt-1.5">Maksimal 200 karakter</p>
                    @error('judul') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori & Target --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoriList as $k)
                                    <option value="{{ $k }}" {{ old('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                            @error('kategori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-2">Pengumuman Untuk</label>
                            <select name="target" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                @foreach($targetList as $val => $label)
                                    <option value="{{ $val }}" {{ old('target', 'semua') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Isi Pengumuman --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 pb-2">
                        <label class="text-sm font-bold text-slate-700 block mb-2">Isi Pengumuman <span class="text-red-500">*</span></label>
                    </div>
                    <div class="px-6 pb-6">
                        {{-- Toolbar --}}
                        <div id="editor-toolbar" class="flex flex-wrap items-center gap-1 p-2 bg-slate-50 border border-slate-300 border-b-0 rounded-t-xl">
                            <select id="format-block" class="px-2 py-1 text-xs border border-slate-300 rounded bg-white focus:ring-1 focus:ring-teal-500">
                                <option value="p">Normal</option>
                                <option value="h1">Heading 1</option>
                                <option value="h2">Heading 2</option>
                                <option value="h3">Heading 3</option>
                            </select>
                            <select id="font-name" class="px-2 py-1 text-xs border border-slate-300 rounded bg-white focus:ring-1 focus:ring-teal-500">
                                <option value="sans-serif">Sans Serif</option>
                                <option value="serif">Serif</option>
                                <option value="monospace">Monospace</option>
                            </select>
                            <select id="font-size" class="px-2 py-1 text-xs border border-slate-300 rounded bg-white focus:ring-1 focus:ring-teal-500">
                                <option value="3">Normal</option>
                                <option value="1">Kecil</option>
                                <option value="5">Besar</option>
                                <option value="7">Sangat Besar</option>
                            </select>
                            <div class="w-px h-5 bg-slate-300 mx-1"></div>
                            <button type="button" onclick="execCmd('bold')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Bold"><strong>B</strong></button>
                            <button type="button" onclick="execCmd('italic')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Italic"><em>I</em></button>
                            <button type="button" onclick="execCmd('underline')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Underline"><u>U</u></button>
                            <button type="button" onclick="execCmd('strikeThrough')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Strike"><s>S</s></button>
                            <div class="w-px h-5 bg-slate-300 mx-1"></div>
                            <button type="button" onclick="execCmd('justifyLeft')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Rata Kiri">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16"/></svg>
                            </button>
                            <button type="button" onclick="execCmd('justifyCenter')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Rata Tengah">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16"/></svg>
                            </button>
                            <button type="button" onclick="execCmd('justifyRight')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Rata Kanan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M4 18h16"/></svg>
                            </button>
                            <div class="w-px h-5 bg-slate-300 mx-1"></div>
                            <button type="button" onclick="execCmd('insertUnorderedList')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Bullet List">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </button>
                            <button type="button" onclick="execCmd('insertOrderedList')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Numbered List">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                            </button>
                            <div class="w-px h-5 bg-slate-300 mx-1"></div>
                            <input type="color" id="text-color" value="#000000" class="w-6 h-6 rounded cursor-pointer border border-slate-300" onchange="execCmdArg('foreColor', this.value)" title="Warna Teks">
                            <input type="color" id="bg-color" value="#ffffff" class="w-6 h-6 rounded cursor-pointer border border-slate-300" onchange="execCmdArg('hiliteColor', this.value)" title="Warna Latar">
                            <div class="w-px h-5 bg-slate-300 mx-1"></div>
                            <button type="button" onclick="execCmd('removeFormat')" class="p-1.5 rounded hover:bg-slate-200 transition" title="Clear Format">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        {{-- Editor --}}
                        <div id="editor" contenteditable="true"
                            class="w-full min-h-[250px] px-4 py-3 border border-slate-300 border-t-0 rounded-b-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 prose prose-sm max-w-none"
                            style="line-height: 1.8;"
                            oninput="updateContent()"></div>
                        <textarea name="isi" id="isi-hidden" class="hidden">@old('isi')</textarea>
                        <p class="text-xs text-slate-400 mt-1.5">Isiarkan pengumuman dengan lengkap dan jelas</p>
                        @error('isi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Tanggal & Lampiran --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-2">Tanggal Publish <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_publish" value="{{ old('tanggal_publish', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition" required>
                            <p class="text-xs text-slate-400 mt-1">Tanggal pengumuman mulai ditampilkan</p>
                            @error('tanggal_publish') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-2">Tanggal Berakhir</label>
                            <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                            <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ada batas waktu</p>
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="text-sm font-bold text-slate-700 block mb-2">Lampiran (Opsional)</label>
                        <input type="file" name="lampiran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        <p class="text-xs text-slate-400 mt-1">Format: PDF, DOC, DOCX, JPG, PNG • Maksimal 5MB</p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <label class="text-sm font-bold text-slate-700 block mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white" required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Pengumuman belum ditampilkan)</option>
                        <option value="publish" {{ old('status', 'publish') == 'publish' ? 'selected' : '' }}>Publish (Pengumuman ditampilkan)</option>
                    </select>
                    @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">
                        &larr; Batal
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="submit" name="status" value="draft" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Simpan Draft
                        </button>
                        <button type="submit" name="status" value="publish" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-teal-500/25 hover:shadow-xl transition-all text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            Simpan & Publish
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const editor = document.getElementById('editor');
const hiddenIsi = document.getElementById('isi-hidden');

// Load old content
if (hiddenIsi.value) {
    editor.innerHTML = hiddenIsi.value;
}

function execCmd(cmd) {
    document.execCommand(cmd, false, null);
    editor.focus();
}

function execCmdArg(cmd, arg) {
    document.execCommand(cmd, false, arg);
    editor.focus();
}

document.getElementById('format-block').addEventListener('change', function() {
    document.execCommand('formatBlock', false, this.value);
    editor.focus();
});

document.getElementById('font-name').addEventListener('change', function() {
    document.execCommand('fontName', false, this.value);
    editor.focus();
});

document.getElementById('font-size').addEventListener('change', function() {
    document.execCommand('fontSize', false, this.value);
    editor.focus();
});

function updateContent() {
    hiddenIsi.value = editor.innerHTML;
}

// Sync before submit
document.querySelector('form').addEventListener('submit', function() {
    updateContent();
});
</script>
@endsection
