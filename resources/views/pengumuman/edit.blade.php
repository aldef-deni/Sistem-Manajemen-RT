@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="text-teal-600 hover:underline font-medium">Dashboard</a>
        <span>/</span>
        <a href="{{ route('pengumuman.index') }}" class="text-teal-600 hover:underline font-medium">Pengumuman</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Edit</span>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Pengumuman</h1>
        </div>
        <a href="{{ route('pengumuman.show', $pengumuman->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="flex gap-6">
            <div class="flex-1 space-y-6">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <label class="text-sm font-bold text-slate-700 block mb-2">Judul Pengumuman <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul', $pengumuman->judul) }}" maxlength="200"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition" required>
                    <p class="text-xs text-slate-400 mt-1.5">Maksimal 200 karakter</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white" required>
                                @foreach($kategoriList as $k)
                                    <option value="{{ $k }}" {{ old('kategori', $pengumuman->kategori) == $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-2">Pengumuman Untuk</label>
                            <select name="target" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                @foreach($targetList as $val => $label)
                                    <option value="{{ $val }}" {{ old('target', $pengumuman->target) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 pb-2">
                        <label class="text-sm font-bold text-slate-700 block mb-2">Isi Pengumuman <span class="text-red-500">*</span></label>
                    </div>
                    <div class="px-6 pb-6">
                        <div id="editor-toolbar" class="flex flex-wrap items-center gap-1 p-2 bg-slate-50 border border-slate-300 border-b-0 rounded-t-xl">
                            <select id="format-block" class="px-2 py-1 text-xs border border-slate-300 rounded bg-white"><option value="p">Normal</option><option value="h1">Heading 1</option><option value="h2">Heading 2</option><option value="h3">Heading 3</option></select>
                            <div class="w-px h-5 bg-slate-300 mx-1"></div>
                            <button type="button" onclick="execCmd('bold')" class="p-1.5 rounded hover:bg-slate-200"><strong>B</strong></button>
                            <button type="button" onclick="execCmd('italic')" class="p-1.5 rounded hover:bg-slate-200"><em>I</em></button>
                            <button type="button" onclick="execCmd('underline')" class="p-1.5 rounded hover:bg-slate-200"><u>U</u></button>
                            <div class="w-px h-5 bg-slate-300 mx-1"></div>
                            <button type="button" onclick="execCmd('insertUnorderedList')" class="p-1.5 rounded hover:bg-slate-200" title="Bullet List">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </button>
                            <button type="button" onclick="execCmd('insertOrderedList')" class="p-1.5 rounded hover:bg-slate-200" title="Numbered List">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                            </button>
                        </div>
                        <div id="editor" contenteditable="true"
                            class="w-full min-h-[250px] px-4 py-3 border border-slate-300 border-t-0 rounded-b-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 prose prose-sm max-w-none"
                            style="line-height: 1.8;"
                            oninput="updateContent()"></div>
                        <textarea name="isi" id="isi-hidden" class="hidden">{{ old('isi', $pengumuman->isi) }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-2">Tanggal Publish <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_publish" value="{{ old('tanggal_publish', $pengumuman->tanggal_publish->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition" required>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700 block mb-2">Tanggal Berakhir</label>
                            <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir', $pengumuman->tanggal_berakhir?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                        </div>
                    </div>
                    <div class="mt-5">
                        <label class="text-sm font-bold text-slate-700 block mb-2">Lampiran (Opsional)</label>
                        <input type="file" name="lampiran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        @if($pengumuman->lampiran)
                            <a href="{{ asset('storage/' . $pengumuman->lampiran) }}" target="_blank" class="text-xs text-teal-600 hover:underline mt-1 inline-block">📄 Lampiran saat ini</a>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <label class="text-sm font-bold text-slate-700 block mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white" required>
                        <option value="draft" {{ old('status', $pengumuman->status) == 'draft' ? 'selected' : '' }}>Draft (Belum ditampilkan)</option>
                        <option value="publish" {{ old('status', $pengumuman->status) == 'publish' ? 'selected' : '' }}>Publish (Ditampilkan)</option>
                    </select>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('pengumuman.show', $pengumuman->id) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-medium text-sm">&larr; Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const editor = document.getElementById('editor');
const hiddenIsi = document.getElementById('isi-hidden');
if (hiddenIsi.value) editor.innerHTML = hiddenIsi.value;
function execCmd(cmd) { document.execCommand(cmd, false, null); editor.focus(); }
document.getElementById('format-block').addEventListener('change', function() { document.execCommand('formatBlock', false, this.value); editor.focus(); });
function updateContent() { hiddenIsi.value = editor.innerHTML; }
document.querySelector('form').addEventListener('submit', function() { updateContent(); });
</script>
@endsection
