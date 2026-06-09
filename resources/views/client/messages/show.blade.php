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

        {{-- Messages --}}
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

        {{-- Formulaire d'envoi — simple POST classique --}}
        <div class="border-t border-stone-100 p-4">
            <form method="POST"
                  action="{{ route('client.messages.store', $user) }}"
                  class="flex items-end gap-3"
                  id="message-form">
                @csrf
                <textarea name="body"
                          id="message-input"
                          rows="2"
                          placeholder="Écrire un message... (Ctrl+Enter pour envoyer)"
                          class="flex-1 px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl
                                 text-sm text-stone-800 outline-none transition resize-none
                                 focus:ring-2 focus:ring-green-200 focus:border-green-400">{{ old('body') }}</textarea>
                <button type="submit"
                        class="px-4 py-2.5 bg-green-700 text-white text-sm rounded-xl
                               hover:bg-green-800 transition shrink-0">
                    Envoyer
                </button>
            </form>
            @error('body')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Scroll vers le bas au chargement
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;

    // Ctrl+Enter pour envoyer
    document.getElementById('message-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.ctrlKey) {
            e.preventDefault();
            document.getElementById('message-form').submit();
        }
    });

    // Écouter les messages entrants via Reverb
    @if(class_exists('\App\Events\MessageSent'))
    if (typeof Echo !== 'undefined') {
        const myId    = {{ auth()->id() }};
        const otherId = {{ $user->id }};
        const ids     = [myId, otherId].sort((a, b) => a - b);

        Echo.private(`chat.${ids[0]}.${ids[1]}`).listen('.message.sent', (data) => {
            if (data.sender_id === myId) return;

            const div = document.createElement('div');
            div.className = 'flex justify-start';
            div.innerHTML = `
                <div class="max-w-sm">
                    <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed bg-stone-100 text-stone-800 rounded-bl-sm">
                        ${data.body}
                    </div>
                    <p class="text-xs text-stone-400 mt-1">${data.created_at}</p>
                </div>`;
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        });
    }
    @endif
</script>
@endpush