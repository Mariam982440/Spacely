{{-- ════════════════════════════════════════════════════
     resources/views/client/messages/index.blade.php
════════════════════════════════════════════════════ --}}
@extends('layouts.client')
@section('title', 'Messages')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Messages</h1>
            @if($unreadCount > 0)
                <p class="text-stone-400 text-sm mt-1">
                    <span class="text-green-700 font-medium">{{ $unreadCount }}</span> non lu(s)
                </p>
            @endif
        </div>
    </div>

    @if($conversations->count())
        <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
            @foreach($conversations as $lastMessage)
                @php
                    $interlocutor = $lastMessage->sender_id === auth()->id()
                        ? $lastMessage->receiver
                        : $lastMessage->sender;
                    $isUnread = !$lastMessage->is_read && $lastMessage->receiver_id === auth()->id();
                @endphp

                <a href="{{ route('client.messages.show', $interlocutor) }}"
                   class="flex items-center gap-4 px-6 py-4 border-b border-stone-50
                          hover:bg-stone-50 transition last:border-none">

                    <div class="w-11 h-11 rounded-full bg-green-100 flex items-center
                                justify-center text-green-800 text-sm font-medium shrink-0">
                        {{ strtoupper(substr($interlocutor->name, 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-0.5">
                            <p class="text-sm font-medium text-stone-800 {{ $isUnread ? 'font-semibold' : '' }}">
                                {{ $interlocutor->name }}
                            </p>
                            <span class="text-xs text-stone-400 shrink-0">
                                {{ $lastMessage->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm text-stone-400 truncate">
                            @if($lastMessage->sender_id === auth()->id())
                                <span class="text-stone-300">Vous : </span>
                            @endif
                            {{ $lastMessage->body }}
                        </p>
                    </div>

                    @if($isUnread)
                        <div class="w-2.5 h-2.5 rounded-full bg-green-600 shrink-0"></div>
                    @endif
                </a>
            @endforeach
        </div>

    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
            </div>
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucun message</h3>
            <p class="text-stone-400 text-sm">
                Contactez un architecte depuis son profil pour démarrer une conversation.
            </p>
        </div>
    @endif

@endsection