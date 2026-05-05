@extends('layouts.client')
@section('title', 'Conversation avec ' . $user->name)

@section('content')

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('client.messages.index') }}"
           class="px-3 py-2 border border-stone-200 text-stone-500
                  text-sm rounded-xl hover:bg-stone-50 transition">
            ← Retour
        </a>
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center
                        justify-center text-green-800 text-sm font-medium">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <p class="font-medium text-stone-800">{{ $user->name }}</p>
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden"
         style="height:520px; display:flex; flex-direction:column;">

        <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-3" id="messages-container">
            @forelse($messages as $message)
                @php $isMine = $message->sender_id === auth()->id(); @endphp
                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-sm">
                        <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                                    {{ $isMine
                                       ? 'bg-green-700 text-white rounded-br-sm'
                                       : 'bg-stone-100 text-stone-800 rounded-bl-sm' }}">
                            {{ $message->body }}
                        </div>
                        <p class="text-xs text-stone-400 mt-1 {{ $isMine ? 'text-right' : '' }}">
                            {{ $message->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="flex-1 flex items-center justify-center">
                    <p class="text-stone-400 text-sm">Aucun message pour le moment.</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-stone-100 p-4 flex items-end gap-3">
            <textarea id="message-input" rows="2"
                      placeholder="Écrire un message..."
                      class="flex-1 px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl
                             text-sm text-stone-800 outline-none transition resize-none
                             focus:ring-2 focus:ring-green-200 focus:border-green-400"></textarea>
            <button onclick="sendMessage()"
                    class="px-4 py-2.5 bg-green-700 text-white text-sm rounded-xl
                           hover:bg-green-800 transition shrink-0">
                Envoyer
            </button>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const myId     = {{ auth()->id() }};
    const storeUrl = "{{ route('client.messages.store', $user) }}";
    const csrf     = "{{ csrf_token() }}";

    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;

    function appendBubble(body, isMine, time) {
        const div = document.createElement('div');
        div.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;
        div.innerHTML = `
            <div class="max-w-sm">
                <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                            ${isMine ? 'bg-green-700 text-white rounded-br-sm'
                                     : 'bg-stone-100 text-stone-800 rounded-bl-sm'}">
                    ${body}
                </div>
                <p class="text-xs text-stone-400 mt-1 ${isMine ? 'text-right' : ''}">
                    ${time}
                </p>
            </div>`;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    async function sendMessage() {
        const input = document.getElementById('message-input');
        const body  = input.value.trim();
        if (!body) return;

        const time = new Date().toLocaleTimeString('fr-FR', { hour:'2-digit', minute:'2-digit' });
        appendBubble(body, true, time);
        input.value = '';

        await fetch(storeUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ body }),
        });
    }

    document.getElementById('message-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.ctrlKey) { e.preventDefault(); sendMessage(); }
    });

    if (typeof Echo !== 'undefined') {
        const otherId = {{ $user->id }};
        const ids = [myId, otherId].sort((a, b) => a - b);
        Echo.private(`chat.${ids[0]}.${ids[1]}`).listen('.message.sent', (data) => {
            if (data.sender_id === myId) return;
            appendBubble(data.body, false, data.created_at);
        });
    }
</script>
@endpush