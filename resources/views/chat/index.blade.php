@extends('layouts.app')

@section('title', 'Chat Warga')
@section('page-title', 'Chat Warga')
@section('page-subtitle', 'Berkomunikasi langsung dengan warga dan pengurus RT')

@php
    $activeOther = $activeConversation && ! $activeConversation->isGroup()
        ? $activeConversation->otherUser($user)
        : null;
    $activeTitle = $activeConversation?->displayName($user);
    $activeMemberIds = $activeConversation?->users->pluck('id')->all() ?? [];
    $activeIsOwner = $activeConversation?->isGroup() && $activeParticipant?->role === 'owner';
    $lastInitialMessageId = $initialMessages->max('id') ?? 0;
@endphp

@push('styles')
<style>
    .chat-shell {
        display: grid;
        grid-template-columns: 340px minmax(0, 1fr);
        height: calc(100vh - 132px);
        min-height: 620px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 20px 50px rgba(15, 23, 42, .07);
    }
    .chat-sidebar { min-width: 0; border-right: 1px solid #e2e8f0; background: #fff; }
    .chat-list { scrollbar-color: #cbd5e1 transparent; }
    .conversation-card { transition: background .16s ease, border-color .16s ease; }
    .conversation-card:hover { background: #f8fafc; }
    .conversation-card.active { background: linear-gradient(135deg, #eff6ff, #eef2ff); }
    .chat-avatar { position: relative; display: grid; flex: none; place-items: center; overflow: hidden; border-radius: 999px; font-weight: 800; }
    .chat-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .chat-avatar-sm { width: 42px; height: 42px; font-size: 13px; }
    .chat-avatar-md { width: 48px; height: 48px; font-size: 14px; }
    .chat-avatar-lg { width: 52px; height: 52px; font-size: 15px; }
    .chat-panel { display: flex; min-width: 0; flex-direction: column; background: #f8fafc; }
    .chat-messages {
        position: relative;
        background-color: #f4f7fb;
        background-image:
            radial-gradient(circle at 18px 18px, rgba(37, 99, 235, .035) 2px, transparent 2px),
            radial-gradient(circle at 58px 58px, rgba(16, 185, 129, .035) 2px, transparent 2px);
        background-size: 76px 76px;
        scrollbar-color: #cbd5e1 transparent;
    }
    .message-row { display: flex; align-items: flex-end; gap: 8px; }
    .message-row.mine { justify-content: flex-end; }
    .message-bubble { max-width: min(72%, 620px); border-radius: 17px; padding: 9px 12px 7px; box-shadow: 0 1px 2px rgba(15, 23, 42, .08); }
    .message-bubble.theirs { border: 1px solid #e2e8f0; border-bottom-left-radius: 5px; background: #fff; color: #1e293b; }
    .message-bubble.mine { border-bottom-right-radius: 5px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .message-body { white-space: pre-wrap; overflow-wrap: anywhere; font-size: 13px; line-height: 1.55; }
    .chat-composer textarea { max-height: 128px; min-height: 44px; resize: none; }
    .chat-modal { background: rgba(15, 23, 42, .55); backdrop-filter: blur(5px); }
    .contact-option:has(input:checked) { border-color: #2563eb; background: #eff6ff; box-shadow: 0 0 0 2px rgba(37, 99, 235, .08); }
    .filter-pill.active { background: #0f172a; color: white; box-shadow: 0 4px 10px rgba(15, 23, 42, .14); }
    @media (max-width: 900px) {
        .chat-shell { display: block; height: calc(100vh - 118px); min-height: 560px; }
        .chat-sidebar { height: 100%; border-right: 0; }
        .chat-panel { height: 100%; }
        .chat-shell.has-active .chat-sidebar { display: none; }
        .chat-shell:not(.has-active) .chat-panel { display: none; }
        .message-bubble { max-width: 84%; }
    }
</style>
@endpush

@section('content')
<div class="space-y-3">
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold">Data belum dapat disimpan.</p>
            <ul class="mt-1 list-inside list-disc">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div id="chatApp"
         class="chat-shell {{ $activeConversation ? 'has-active' : '' }}"
         data-current-user-id="{{ $user->id }}"
         data-is-group="{{ $activeConversation?->isGroup() ? '1' : '0' }}"
         data-messages-url="{{ $activeConversation ? route('chat.messages.index', $activeConversation) : '' }}"
         data-send-url="{{ $activeConversation ? route('chat.messages.store', $activeConversation) : '' }}"
         data-last-message-id="{{ $lastInitialMessageId }}">

        {{-- Daftar percakapan --}}
        <aside class="chat-sidebar flex flex-col">
            <div class="border-b border-slate-100 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Pesan</h2>
                        <p class="text-xs text-slate-500">{{ $conversations->count() }} percakapan</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" data-open-modal="privateChatModal" title="Chat pribadi baru"
                                class="grid h-10 w-10 place-items-center rounded-xl border border-blue-200 bg-blue-50 text-blue-600 transition hover:-translate-y-0.5 hover:bg-blue-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72A7.49 7.49 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8zM12 7v6m-3-3h6"/></svg>
                        </button>
                        <button type="button" data-open-modal="groupChatModal" title="Buat grup baru"
                                class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 transition hover:-translate-y-0.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0zm5 1v6m-3-3h6"/></svg>
                        </button>
                    </div>
                </div>

                <div class="relative mt-4">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input id="conversationSearch" type="search" placeholder="Cari percakapan..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100">
                </div>

                <div class="mt-3 flex gap-2">
                    <button type="button" class="filter-pill active rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-500 transition" data-filter="all">Semua</button>
                    <button type="button" class="filter-pill rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-500 transition hover:bg-slate-100" data-filter="private">Japri</button>
                    <button type="button" class="filter-pill rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-500 transition hover:bg-slate-100" data-filter="group">Grup</button>
                </div>
            </div>

            <div id="conversationList" class="chat-list min-h-0 flex-1 overflow-y-auto py-2">
                @forelse($conversations as $conversation)
                    @php
                        $other = $conversation->isGroup() ? null : $conversation->otherUser($user);
                        $title = $conversation->displayName($user);
                        $preview = $conversation->latestMessage?->body;
                        $isCurrent = $activeConversation?->id === $conversation->id;
                    @endphp
                    <a href="{{ route('chat.show', $conversation) }}"
                       class="conversation-card {{ $isCurrent ? 'active' : '' }} mx-2 flex items-center gap-3 rounded-xl border-l-4 px-3 py-3 no-underline {{ $isCurrent ? 'border-blue-500' : 'border-transparent' }}"
                       data-kind="{{ $conversation->type }}"
                       data-search="{{ Str::lower($title.' '.$conversation->users->pluck('name')->implode(' ')) }}">
                        @if($conversation->isGroup())
                            <div class="chat-avatar chat-avatar-md bg-gradient-to-br from-violet-500 to-indigo-600 text-white shadow-sm">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        @elseif($other?->foto_url)
                            <div class="chat-avatar chat-avatar-md border border-slate-200 bg-slate-100"><img src="{{ $other->foto_url }}" alt="Avatar {{ $other->name }}"></div>
                        @else
                            <div class="chat-avatar chat-avatar-md bg-gradient-to-br from-blue-500 to-cyan-500 text-white">{{ $other?->initial ?? '?' }}</div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="truncate text-sm font-bold {{ ($conversation->unread_count ?? 0) > 0 ? 'text-slate-950' : 'text-slate-800' }}">{{ $title }}</p>
                                <time class="shrink-0 text-[10px] {{ ($conversation->unread_count ?? 0) > 0 ? 'font-bold text-blue-600' : 'text-slate-400' }}">
                                    {{ $conversation->last_message_at?->isToday() ? $conversation->last_message_at->format('H:i') : $conversation->last_message_at?->format('d/m') }}
                                </time>
                            </div>
                            <div class="mt-1 flex items-center gap-2">
                                <p class="min-w-0 flex-1 truncate text-xs {{ ($conversation->unread_count ?? 0) > 0 ? 'font-semibold text-slate-700' : 'text-slate-500' }}">
                                    @if($preview)
                                        @if($conversation->latestMessage?->user_id === $user->id)<span class="text-slate-400">Anda: </span>@endif{{ $preview }}
                                    @elseif($conversation->isGroup())
                                        {{ $conversation->users->count() }} anggota · Grup baru
                                    @else
                                        Mulai percakapan baru
                                    @endif
                                </p>
                                @if(($conversation->unread_count ?? 0) > 0)
                                    <span class="grid min-w-5 shrink-0 place-items-center rounded-full bg-blue-600 px-1.5 py-0.5 text-[10px] font-bold text-white">
                                        {{ $conversation->unread_count > 99 ? '99+' : $conversation->unread_count }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="flex h-full min-h-72 flex-col items-center justify-center px-8 text-center">
                        <div class="grid h-16 w-16 place-items-center rounded-2xl bg-blue-50 text-blue-500">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72A7.49 7.49 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h3 class="mt-4 text-sm font-bold text-slate-800">Belum ada percakapan</h3>
                        <p class="mt-1 text-xs leading-5 text-slate-500">Mulai chat pribadi atau buat grup bersama warga dan pengurus RT.</p>
                    </div>
                @endforelse
                <div id="conversationEmptySearch" class="hidden px-6 py-12 text-center text-sm text-slate-400">Percakapan tidak ditemukan.</div>
            </div>
        </aside>

        {{-- Ruang percakapan --}}
        <section class="chat-panel">
            @if($activeConversation)
                <header class="flex min-h-[76px] items-center gap-3 border-b border-slate-200 bg-white px-4 py-3 sm:px-5">
                    <a href="{{ route('chat.index') }}" class="grid h-9 w-9 shrink-0 place-items-center rounded-xl text-slate-500 transition hover:bg-slate-100 md:hidden" aria-label="Kembali ke daftar chat">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7"/></svg>
                    </a>

                    @if($activeConversation->isGroup())
                        <div class="chat-avatar chat-avatar-lg bg-gradient-to-br from-violet-500 to-indigo-600 text-white shadow-md shadow-indigo-500/20">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    @elseif($activeOther?->foto_url)
                        <div class="chat-avatar chat-avatar-lg border border-slate-200 bg-slate-100"><img src="{{ $activeOther->foto_url }}" alt="Avatar {{ $activeOther->name }}"></div>
                    @else
                        <div class="chat-avatar chat-avatar-lg bg-gradient-to-br from-blue-500 to-cyan-500 text-white">{{ $activeOther?->initial ?? '?' }}</div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <h2 class="truncate text-sm font-bold text-slate-900 sm:text-base">{{ $activeTitle }}</h2>
                        <p class="truncate text-xs text-slate-500">
                            @if($activeConversation->isGroup())
                                {{ $activeConversation->users->count() }} anggota · {{ $activeConversation->users->pluck('name')->implode(', ') }}
                            @else
                                {{ $activeOther?->role_label ?? 'Akun tidak tersedia' }}
                            @endif
                        </p>
                    </div>

                    @if($activeConversation->isGroup())
                        @if($activeIsOwner)
                            <button type="button" data-open-modal="manageGroupModal" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" title="Kelola grup">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="hidden sm:inline">Kelola</span>
                            </button>
                        @else
                            <form method="POST" action="{{ route('chat.leave', $activeConversation) }}" onsubmit="return confirm('Keluar dari grup {{ addslashes($activeTitle) }}?')">
                                @csrf
                                <button class="inline-flex h-10 items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-100" title="Keluar grup">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span class="hidden sm:inline">Keluar</span>
                                </button>
                            </form>
                        @endif
                    @endif
                </header>

                <div id="messageList" class="chat-messages min-h-0 flex-1 space-y-2 overflow-y-auto px-3 py-5 sm:px-6">
                    @forelse($initialMessages as $message)
                        @php $mine = $message->user_id === $user->id; @endphp
                        <div class="message-row {{ $mine ? 'mine' : '' }}" data-message-id="{{ $message->id }}">
                            @unless($mine)
                                @if($message->user?->foto_url)
                                    <div class="chat-avatar h-7 w-7 border border-white bg-slate-200 text-[10px] text-slate-600 shadow"><img src="{{ $message->user->foto_url }}" alt="Avatar {{ $message->user->name }}"></div>
                                @else
                                    <div class="chat-avatar h-7 w-7 bg-slate-300 text-[10px] text-slate-700">{{ $message->user?->initial ?? '?' }}</div>
                                @endif
                            @endunless
                            <div class="message-bubble {{ $mine ? 'mine' : 'theirs' }}">
                                @if($activeConversation->isGroup() && ! $mine)
                                    <p class="mb-1 text-[10px] font-bold text-blue-600">{{ $message->user?->name ?? 'Akun dihapus' }} <span class="font-medium text-slate-400">· {{ $message->user?->role_label }}</span></p>
                                @endif
                                <p class="message-body">{{ $message->body }}</p>
                                <div class="mt-1 flex items-center justify-end gap-1.5 text-[9px] {{ $mine ? 'text-blue-100' : 'text-slate-400' }}">
                                    <time datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->format('H:i') }}</time>
                                    @if($mine)
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 4 4L18 6m-6 10 2 2 6-7"/></svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div id="emptyMessages" class="flex h-full min-h-72 flex-col items-center justify-center text-center">
                            <div class="grid h-20 w-20 place-items-center rounded-full bg-white text-blue-500 shadow-sm ring-1 ring-slate-200">
                                <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M7 8h10M7 12h6m-9 8 2.6-3.2A8 8 0 114 12v8z"/></svg>
                            </div>
                            <h3 class="mt-4 text-sm font-bold text-slate-800">Mulai percakapan</h3>
                            <p class="mt-1 max-w-sm text-xs leading-5 text-slate-500">Kirim pesan pertama kepada {{ $activeTitle }}. Pesan hanya dapat dibaca oleh anggota percakapan ini.</p>
                        </div>
                    @endforelse
                </div>

                <form id="messageForm" action="{{ route('chat.messages.store', $activeConversation) }}" method="POST" class="chat-composer border-t border-slate-200 bg-white p-3 sm:p-4">
                    @csrf
                    <div class="flex items-end gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-1.5 transition focus-within:border-blue-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100">
                        <textarea id="messageInput" name="body" rows="1" maxlength="4000" required placeholder="Tulis pesan..."
                                  class="min-w-0 flex-1 border-0 bg-transparent px-3 py-2.5 text-sm leading-5 text-slate-800 outline-none placeholder:text-slate-400"></textarea>
                        <button id="sendMessageButton" type="submit" class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20 transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-50" aria-label="Kirim pesan">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12-2-9 19 9-19 9 2-9Zm0 0h10"/></svg>
                        </button>
                    </div>
                    <div class="mt-1.5 flex items-center justify-between px-2 text-[10px] text-slate-400">
                        <span>Enter untuk kirim · Shift + Enter untuk baris baru</span>
                        <span id="messageError" class="font-medium text-rose-500"></span>
                    </div>
                </form>
            @else
                <div class="flex h-full flex-col items-center justify-center px-8 text-center">
                    <div class="relative">
                        <div class="grid h-28 w-28 place-items-center rounded-[32px] bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-xl shadow-blue-500/25">
                            <svg class="h-14 w-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72A7.49 7.49 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <span class="absolute -right-2 -top-2 grid h-9 w-9 place-items-center rounded-full border-4 border-slate-50 bg-emerald-500 text-white">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m5 13 4 4L19 7"/></svg>
                        </span>
                    </div>
                    <h2 class="mt-7 text-xl font-extrabold text-slate-900">Terhubung dengan lingkungan RT</h2>
                    <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">Pilih percakapan di sebelah kiri, mulai chat pribadi, atau buat grup untuk berkomunikasi dengan warga dan pengurus.</p>
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        <button type="button" data-open-modal="privateChatModal" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-500/20 transition hover:bg-blue-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72A7.49 7.49 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Chat pribadi
                        </button>
                        <button type="button" data-open-modal="groupChatModal" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Buat grup
                        </button>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>

{{-- Modal chat pribadi --}}
<div id="privateChatModal" class="chat-modal fixed inset-0 z-[70] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="privateChatTitle">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div><h3 id="privateChatTitle" class="font-bold text-slate-900">Chat pribadi baru</h3><p class="text-xs text-slate-500">Pilih satu akun untuk memulai japri</p></div>
            <button type="button" data-close-modal class="grid h-9 w-9 place-items-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg></button>
        </div>
        <form method="POST" action="{{ route('chat.private.store') }}">
            @csrf
            <div class="p-5">
                <input type="search" data-contact-search="privateContacts" placeholder="Cari nama atau role..." class="mb-3 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100">
                <div id="privateContacts" class="max-h-80 space-y-2 overflow-y-auto pr-1">
                    @forelse($contacts as $contact)
                        <label class="contact-option flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-3 transition hover:bg-slate-50" data-contact="{{ Str::lower($contact->name.' '.$contact->role_label) }}">
                            <input type="radio" name="user_id" value="{{ $contact->id }}" class="h-4 w-4 border-slate-300 text-blue-600 focus:ring-blue-500" required>
                            @if($contact->foto_url)
                                <span class="chat-avatar chat-avatar-sm bg-slate-100"><img src="{{ $contact->foto_url }}" alt="Avatar {{ $contact->name }}"></span>
                            @else
                                <span class="chat-avatar chat-avatar-sm bg-gradient-to-br from-blue-500 to-cyan-500 text-white">{{ $contact->initial }}</span>
                            @endif
                            <span class="min-w-0 flex-1"><span class="block truncate text-sm font-semibold text-slate-800">{{ $contact->name }}</span><span class="block text-xs text-slate-500">{{ $contact->role_label }}</span></span>
                        </label>
                    @empty
                        <p class="py-8 text-center text-sm text-slate-400">Belum ada akun lain yang dapat diajak chat.</p>
                    @endforelse
                </div>
            </div>
            <div class="flex justify-end gap-2 border-t border-slate-100 bg-slate-50 px-5 py-4">
                <button type="button" data-close-modal class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200">Batal</button>
                <button type="submit" {{ $contacts->isEmpty() ? 'disabled' : '' }} class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 disabled:opacity-50">Mulai chat</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal grup baru --}}
<div id="groupChatModal" class="chat-modal fixed inset-0 z-[70] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="groupChatTitle">
    <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div><h3 id="groupChatTitle" class="font-bold text-slate-900">Buat grup chat</h3><p class="text-xs text-slate-500">Satukan warga dan pengurus dalam satu percakapan</p></div>
            <button type="button" data-close-modal class="grid h-9 w-9 place-items-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg></button>
        </div>
        <form method="POST" action="{{ route('chat.groups.store') }}">
            @csrf
            <div class="space-y-4 p-5">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Nama grup</label>
                    <input type="text" name="name" maxlength="100" required placeholder="Contoh: Warga Blok A" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                </div>
                <div>
                    <div class="mb-2 flex items-center justify-between"><label class="text-xs font-bold uppercase tracking-wide text-slate-500">Pilih anggota</label><span class="text-[11px] text-slate-400">Minimal 1 orang</span></div>
                    <input type="search" data-contact-search="groupContacts" placeholder="Cari nama atau role..." class="mb-3 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-emerald-400 focus:bg-white focus:ring-4 focus:ring-emerald-100">
                    <div id="groupContacts" class="max-h-64 grid gap-2 overflow-y-auto pr-1 sm:grid-cols-2">
                        @foreach($contacts as $contact)
                            <label class="contact-option flex cursor-pointer items-center gap-2.5 rounded-xl border border-slate-200 p-2.5 transition hover:bg-slate-50" data-contact="{{ Str::lower($contact->name.' '.$contact->role_label) }}">
                                <input type="checkbox" name="member_ids[]" value="{{ $contact->id }}" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                @if($contact->foto_url)
                                    <span class="chat-avatar h-9 w-9 bg-slate-100"><img src="{{ $contact->foto_url }}" alt="Avatar {{ $contact->name }}"></span>
                                @else
                                    <span class="chat-avatar h-9 w-9 bg-gradient-to-br from-emerald-500 to-teal-500 text-xs text-white">{{ $contact->initial }}</span>
                                @endif
                                <span class="min-w-0"><span class="block truncate text-xs font-semibold text-slate-800">{{ $contact->name }}</span><span class="block text-[10px] text-slate-500">{{ $contact->role_label }}</span></span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 border-t border-slate-100 bg-slate-50 px-5 py-4">
                <button type="button" data-close-modal class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200">Batal</button>
                <button type="submit" {{ $contacts->isEmpty() ? 'disabled' : '' }} class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 disabled:opacity-50">Buat grup</button>
            </div>
        </form>
    </div>
</div>

@if($activeIsOwner)
    {{-- Modal kelola grup --}}
    <div id="manageGroupModal" class="chat-modal fixed inset-0 z-[70] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="manageGroupTitle">
        <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div><h3 id="manageGroupTitle" class="font-bold text-slate-900">Kelola grup</h3><p class="text-xs text-slate-500">Ubah nama dan keanggotaan grup</p></div>
                <button type="button" data-close-modal class="grid h-9 w-9 place-items-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg></button>
            </div>
            <form method="POST" action="{{ route('chat.groups.update', $activeConversation) }}">
                @csrf @method('PUT')
                <div class="space-y-4 p-5">
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Nama grup</label>
                        <input type="text" name="name" value="{{ $activeConversation->name }}" maxlength="100" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100">
                    </div>
                    <div>
                        <div class="mb-2 flex items-center justify-between"><label class="text-xs font-bold uppercase tracking-wide text-slate-500">Anggota grup</label><span class="rounded-full bg-blue-50 px-2 py-1 text-[10px] font-semibold text-blue-600">Anda adalah pemilik</span></div>
                        <input type="search" data-contact-search="manageGroupContacts" placeholder="Cari anggota..." class="mb-3 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100">
                        <div id="manageGroupContacts" class="max-h-64 grid gap-2 overflow-y-auto pr-1 sm:grid-cols-2">
                            @foreach($contacts as $contact)
                                <label class="contact-option flex cursor-pointer items-center gap-2.5 rounded-xl border border-slate-200 p-2.5 transition hover:bg-slate-50" data-contact="{{ Str::lower($contact->name.' '.$contact->role_label) }}">
                                    <input type="checkbox" name="member_ids[]" value="{{ $contact->id }}" @checked(in_array($contact->id, $activeMemberIds, true)) class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    @if($contact->foto_url)
                                        <span class="chat-avatar h-9 w-9 bg-slate-100"><img src="{{ $contact->foto_url }}" alt="Avatar {{ $contact->name }}"></span>
                                    @else
                                        <span class="chat-avatar h-9 w-9 bg-gradient-to-br from-blue-500 to-indigo-500 text-xs text-white">{{ $contact->initial }}</span>
                                    @endif
                                    <span class="min-w-0"><span class="block truncate text-xs font-semibold text-slate-800">{{ $contact->name }}</span><span class="block text-[10px] text-slate-500">{{ $contact->role_label }}</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex justify-end border-t border-slate-100 bg-slate-50 px-5 py-4">
                    <div class="flex gap-2">
                        <button type="button" data-close-modal class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200">Batal</button>
                        <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Simpan perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
(() => {
    const app = document.getElementById('chatApp');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    document.querySelectorAll('[data-open-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            const modal = document.getElementById(button.dataset.openModal);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(() => modal.querySelector('input:not([type="hidden"])')?.focus(), 50);
        });
    });

    const closeModal = (modal) => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    document.querySelectorAll('.chat-modal').forEach((modal) => {
        modal.querySelectorAll('[data-close-modal]').forEach((button) => button.addEventListener('click', () => closeModal(modal)));
        modal.addEventListener('click', (event) => { if (event.target === modal) closeModal(modal); });
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') document.querySelectorAll('.chat-modal.flex').forEach(closeModal);
    });

    document.querySelectorAll('[data-contact-search]').forEach((input) => {
        input.addEventListener('input', () => {
            const query = input.value.trim().toLocaleLowerCase('id');
            document.querySelectorAll(`#${input.dataset.contactSearch} [data-contact]`).forEach((item) => {
                item.classList.toggle('hidden', !item.dataset.contact.includes(query));
            });
        });
    });

    const conversationSearch = document.getElementById('conversationSearch');
    let conversationFilter = 'all';
    const filterConversations = () => {
        if (!conversationSearch) return;
        const query = conversationSearch.value.trim().toLocaleLowerCase('id');
        let visible = 0;
        document.querySelectorAll('.conversation-card').forEach((item) => {
            const matchesText = item.dataset.search.includes(query);
            const matchesKind = conversationFilter === 'all' || item.dataset.kind === conversationFilter;
            item.classList.toggle('hidden', !(matchesText && matchesKind));
            if (matchesText && matchesKind) visible++;
        });
        document.getElementById('conversationEmptySearch')?.classList.toggle('hidden', visible !== 0);
    };
    conversationSearch?.addEventListener('input', filterConversations);
    document.querySelectorAll('.filter-pill').forEach((button) => {
        button.addEventListener('click', () => {
            conversationFilter = button.dataset.filter;
            document.querySelectorAll('.filter-pill').forEach((pill) => pill.classList.remove('active'));
            button.classList.add('active');
            filterConversations();
        });
    });

    if (!app?.dataset.messagesUrl) return;

    const list = document.getElementById('messageList');
    const form = document.getElementById('messageForm');
    const input = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendMessageButton');
    const errorLabel = document.getElementById('messageError');
    const isGroup = app.dataset.isGroup === '1';
    let lastId = Number(app.dataset.lastMessageId || 0);
    let polling = false;

    const scrollToBottom = (smooth = false) => {
        if (!list) return;
        list.scrollTo({ top: list.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
    };
    scrollToBottom();

    const renderMessage = (message) => {
        if (document.querySelector(`[data-message-id="${message.id}"]`)) return;
        document.getElementById('emptyMessages')?.remove();

        const row = document.createElement('div');
        row.className = `message-row ${message.is_mine ? 'mine' : ''}`;
        row.dataset.messageId = message.id;

        if (!message.is_mine) {
            const avatar = document.createElement('div');
            avatar.className = 'chat-avatar h-7 w-7 bg-slate-300 text-[10px] text-slate-700';
            if (message.user_avatar) {
                const image = document.createElement('img');
                image.src = message.user_avatar;
                image.alt = `Avatar ${message.user_name}`;
                avatar.appendChild(image);
            } else {
                avatar.textContent = message.user_initial;
            }
            row.appendChild(avatar);
        }

        const bubble = document.createElement('div');
        bubble.className = `message-bubble ${message.is_mine ? 'mine' : 'theirs'}`;

        if (isGroup && !message.is_mine) {
            const sender = document.createElement('p');
            sender.className = 'mb-1 text-[10px] font-bold text-blue-600';
            sender.textContent = `${message.user_name} · ${message.role_label}`;
            bubble.appendChild(sender);
        }

        const body = document.createElement('p');
        body.className = 'message-body';
        body.textContent = message.body;
        bubble.appendChild(body);

        const meta = document.createElement('div');
        meta.className = `mt-1 flex items-center justify-end gap-1.5 text-[9px] ${message.is_mine ? 'text-blue-100' : 'text-slate-400'}`;
        const time = document.createElement('time');
        time.dateTime = message.sent_at;
        time.textContent = message.time;
        meta.appendChild(time);
        if (message.is_mine) {
            const tick = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            tick.setAttribute('class', 'h-3.5 w-3.5');
            tick.setAttribute('fill', 'none');
            tick.setAttribute('stroke', 'currentColor');
            tick.setAttribute('viewBox', '0 0 24 24');
            tick.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 4 4L18 6m-6 10 2 2 6-7"/>';
            meta.appendChild(tick);
        }
        bubble.appendChild(meta);
        row.appendChild(bubble);
        list.appendChild(row);
        lastId = Math.max(lastId, Number(message.id));
    };

    const fetchMessages = async () => {
        if (polling || document.hidden) return;
        polling = true;
        try {
            const url = new URL(app.dataset.messagesUrl, window.location.origin);
            url.searchParams.set('after_id', lastId);
            const response = await fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
            if (!response.ok) return;
            const payload = await response.json();
            const nearBottom = list.scrollHeight - list.scrollTop - list.clientHeight < 140;
            payload.messages.forEach(renderMessage);
            if (payload.messages.length && nearBottom) scrollToBottom(true);
        } catch (_) {
            // Polling berikutnya mencoba kembali tanpa mengganggu pengguna.
        } finally {
            polling = false;
        }
    };

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const body = input.value.trim();
        if (!body) return;
        sendButton.disabled = true;
        errorLabel.textContent = '';
        try {
            const response = await fetch(app.dataset.sendUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: new FormData(form),
            });
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.message || Object.values(payload.errors || {})[0]?.[0] || 'Pesan gagal dikirim.');
            renderMessage(payload.message);
            input.value = '';
            input.style.height = 'auto';
            scrollToBottom(true);
        } catch (error) {
            errorLabel.textContent = error.message;
        } finally {
            sendButton.disabled = false;
            input.focus();
        }
    });

    input?.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });
    input?.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = `${Math.min(input.scrollHeight, 128)}px`;
    });

    setInterval(fetchMessages, 3500);
})();
</script>
@endpush
