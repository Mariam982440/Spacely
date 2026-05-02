@extends('layouts.architect')
@section('title', 'Messages')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Messages</h1>
            <p class="text-stone-400 text-sm mt-1">{{ $unreadCount }} message(s) non lu(s)</p>
        </div>
    </div>

    @if($conversations->count())
        <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
            @foreach($conversations as $message)
                @php
                    $contact = $message->sender_id === auth()->id()
                        ? $message->receiver
                        : $message->sender;
                @endphp

                <a href="{{ route('architect.messages.show', $contact) }}"
                   class="flex items-center gap-4 px-6 py-4 border-b border-stone-100 last:border-0 hover:bg-stone-50 transition">
                    <div class="w-11 h-11 rounded-full bg-green-100 flex items-center justify-center text-green-800 text-sm font-medium shrink-0">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-4">
                            <p class="text-sm font-medium text-stone-800 truncate">{{ $contact->name }}</p>
                            <p class="text-xs text-stone-400 shrink-0">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <p class="text-sm text-stone-500 truncate mt-1">{{ $message->body }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucune conversation</h3>
            <p class="text-stone-400 text-sm">Vos échanges avec les clients apparaîtront ici.</p>
        </div>
    @endif

@endsection
