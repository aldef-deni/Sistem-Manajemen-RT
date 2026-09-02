@extends('layouts.app')

@section('title', 'Tata Tertib')

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
                <span class="text-slate-700 font-medium">Tata Tertib</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Tata Tertib</h1>
        </div>
    </div>

    {{-- Tabs --}}
    <div style="display:flex;gap:4px;background:#f1f5f9;border-radius:12px;padding:4px;width:fit-content">
        <a href="{{ route('pengaturan') }}" style="padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;color:#64748b">
            ⚙️ Pengaturan Umum
        </a>
        <a href="{{ route('pengaturan.tata-tertib') }}" style="padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;background:#fff;color:#1e293b;box-shadow:0 1px 3px rgba(0,0,0,0.1)">
            📜 Tata Tertib
        </a>
        <a href="{{ route('pengaturan.kelola-pengurus') }}" style="padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;color:#64748b">
            👥 Kelola Pengurus
        </a>
    </div>

    @if(session('success'))
        <div style="padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;font-size:14px;font-weight:500">{{ session('success') }}</div>
    @endif

    <form action="{{ route('pengaturan.tata-tertib.update') }}" method="POST">
        @csrf @method('PUT')

        <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
            <div>
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center">
                                <svg style="width:16px;height:16px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <span style="font-weight:700;color:#1e293b;font-size:15px">Peraturan Tata Tertib</span>
                        </div>
                        <span style="font-size:11px;color:#94a3b8">Klik untuk mengedit</span>
                    </div>
                    <div style="padding:20px">
                        {{-- Rich Text Toolbar --}}
                        <div style="display:flex;gap:4px;padding:8px;background:#f8fafc;border:1px solid #e2e8f0;border-bottom:none;border-radius:10px 10px 0 0;flex-wrap:wrap">
                            <button type="button" onclick="execCmd('bold')" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;font-weight:700;font-size:13px" title="Bold">B</button>
                            <button type="button" onclick="execCmd('italic')" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;font-style:italic;font-size:13px" title="Italic">I</button>
                            <button type="button" onclick="execCmd('underline')" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;text-decoration:underline;font-size:13px" title="Underline">U</button>
                            <button type="button" onclick="execCmd('strikeThrough')" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;text-decoration:line-through;font-size:13px" title="Strike">S</button>
                            <div style="width:1px;background:#e2e8f0;margin:0 4px"></div>
                            <button type="button" onclick="execCmd('justifyLeft')" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;font-size:13px" title="Align Left">⬅</button>
                            <button type="button" onclick="execCmd('justifyCenter')" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;font-size:13px" title="Align Center">⬌</button>
                            <button type="button" onclick="execCmd('justifyRight')" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;font-size:13px" title="Align Right">➡</button>
                            <div style="width:1px;background:#e2e8f0;margin:0 4px"></div>
                            <button type="button" onclick="execCmd('insertUnorderedList')" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;font-size:13px" title="Bullet List">☰</button>
                            <button type="button" onclick="execCmd('insertOrderedList')" style="padding:6px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;font-size:13px" title="Numbered List">≡</button>
                        </div>

                        <textarea id="editor" name="peraturan" rows="20"
                            style="width:100%;padding:16px;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 10px 10px;font-size:14px;line-height:1.8;outline:none;resize:vertical;font-family:Georgia,serif;color:#334155"
                            placeholder="Tulis peraturan tata tertib di sini...">{{ old('peraturan', $struktur->peraturan ?? '') }}</textarea>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:16px">
                    <a href="{{ route('pengaturan') }}" style="padding:12px 24px;border:1px solid #e2e8f0;border-radius:12px;color:#64748b;font-weight:600;font-size:14px;text-decoration:none">Batal</a>
                    <button type="submit" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;padding:12px 32px;border-radius:12px;font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(16,185,129,0.3)">
                        <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Tata Tertib
                    </button>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Format Umum --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden">
                    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9">
                        <span style="font-weight:700;color:#1e293b;font-size:15px">Format Umum</span>
                    </div>
                    <div style="padding:16px 20px">
                        <div style="display:flex;flex-direction:column;gap:6px">
                            @foreach(['I. PENDAHULUAN', 'II. DEFINISI', 'III. PERATURAN UMUM', 'IV. PERATURAN KETERTIBAN', 'V. PERATURAN KEAMANAN', 'VI. PERATURAN KEBERSIHAN', 'VII. PERATURAN PEMBAYARAN', 'VIII. SANKSI / DENDA', 'IX. PENUTUP'] as $s)
                                <div style="padding:8px 12px;background:#f8fafc;border-radius:6px;font-size:12px;color:#475569;cursor:pointer;border:1px solid #f1f5f9" onclick="insertSection('{{ $s }}')">{{ $s }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #bbf7d0;border-radius:16px;padding:20px">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
                        <span style="font-size:16px">💡</span>
                        <span style="font-weight:700;color:#166534;font-size:14px">Tips</span>
                    </div>
                    <ul style="font-size:12px;color:#166534;line-height:1.8;margin:0;padding-left:16px">
                        <li>Gunakan format section untuk memudahkan navigasi</li>
                        <li>Tuliskan peraturan dengan jelas dan singkat</li>
                        <li>Sertakan sanksi yang adil untuk setiap pelanggaran</li>
                        <li>Update secara berkala sesuai kebutuhan warga</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function execCmd(cmd) {
    document.execCommand(cmd, false, null);
    document.getElementById('editor').focus();
}

function insertSection(title) {
    const editor = document.getElementById('editor');
    const current = editor.value;
    editor.value = current + '\n\n' + title + '\n\n';
    editor.focus();
}
</script>
@endsection
