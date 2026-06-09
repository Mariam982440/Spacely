@extends('layouts.client')
@section('title', $profile->user->name)

@section('content')

    {{-- ── Photo de couverture ── --}}
    <div class="h-52 rounded-t-2xl overflow-hidden bg-gradient-to-r from-green-900 via-green-700 to-green-500">
        @if($profile->cover_photo)
            <img src="{{ Storage::url($profile->cover_photo) }}"
                 alt="Couverture {{ $profile->user->name }}"
                 class="w-full h-full object-cover">
        @endif
    </div>

    {{-- ── Header card ── --}}
    <div class="bg-white border border-stone-200 rounded-b-2xl px-8 pb-6 mb-6 shadow-sm">
        <div class="flex items-end justify-between -mt-10 mb-4">
            <div class="w-20 h-20 rounded-full border-4 border-white shadow-md
                        bg-green-100 flex items-center justify-center overflow-hidden">
                @if($profile->profile_picture)
                    <img src="{{ Storage::url($profile->profile_picture) }}"
                         alt="{{ $profile->user->name }}" class="w-full h-full object-cover">
                @else
                    <span class="font-serif text-2xl text-green-800">
                        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                    </span>
                @endif
            </div>
            <a href="{{ route('client.messages.show', $profile->user) }}"
               class="flex items-center gap-2 px-4 py-2 border border-stone-200
                      text-stone-700 text-sm rounded-xl hover:bg-stone-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
                Contacter
            </a>
        </div>

        <div class="flex items-center gap-3 mb-2">
            <h1 class="font-serif text-2xl font-semibold text-stone-900">{{ $profile->user->name }}</h1>
            @if($profile->is_verified)
                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-full">
                    ✓ Vérifié
                </span>
            @endif
        </div>

        <div class="flex items-center gap-5 text-sm text-stone-400 mb-4">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $profile->city }}
            </span>
            <span>{{ $profile->experience_years }} ans d'expérience</span>
            <span>{{ $profile->projects->count() }} projets</span>
        </div>

        @if($profile->projects->count())
            <div class="flex flex-wrap gap-2">
                @foreach($profile->projects->flatMap->tags->unique('id')->take(5) as $tag)
                    <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── Grille principale ── --}}
    <div class="grid grid-cols-3 gap-6">

        <div class="col-span-2 flex flex-col gap-5">

            @if($profile->bio)
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">La philosophie</p>
                    <blockquote class="border-l-4 border-green-600 bg-green-50 px-5 py-4
                                       text-green-900 font-serif text-base italic rounded-r-xl mb-4">
                        "{{ Str::limit($profile->bio, 120) }}"
                    </blockquote>
                    <p class="text-stone-500 text-sm leading-relaxed font-light">{{ $profile->bio }}</p>
                </div>
            @endif

            @if($profile->projects->count())
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-5">Réalisations</p>

                    @php
                        $favoritedIds = \App\Models\Favorite::where('user_id', auth()->id())
                            ->where('favoritable_type', 'App\Models\Project')
                            ->pluck('favoritable_id')
                            ->toArray();
                    @endphp

                    <div class="grid grid-cols-2 gap-3">
                        @foreach($profile->projects as $project)
                            <div class="relative aspect-video rounded-xl overflow-hidden bg-stone-100 group">
                                @if($project->images->first())
                                    <img src="{{ Storage::url($project->images->first()->image_path) }}"
                                         alt="{{ $project->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-green-50">
                                        <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor"
                                             stroke-width="1.5" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t
                                            from-black/50 to-transparent px-3 py-2">
                                    <p class="text-white text-xs font-medium truncate">{{ $project->title }}</p>
                                    <div class="flex gap-1 mt-1">
                                        @foreach($project->tags->take(2) as $tag)
                                            <span class="bg-white/20 text-white text-xs px-1.5 py-0.5 rounded">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Bouton cœur --}}
                                @php $isFav = in_array($project->id, $favoritedIds); @endphp
                                <button onclick="toggleFavorite(this, {{ $project->id }}, 'App\\Models\\Project')"
                                        data-favorited="{{ $isFav ? 'true' : 'false' }}"
                                        class="absolute top-2 right-2 w-8 h-8 rounded-full flex items-center
                                               justify-center transition
                                               {{ $isFav ? 'bg-red-500 text-white' : 'bg-white/80 text-stone-500 hover:bg-white' }}">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24"
                                         fill="{{ $isFav ? 'currentColor' : 'none' }}"
                                         stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Formulaire réservation --}}
        <div>
            <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                    Réserver une consultation
                </p>

                @php
                    $slots = $profile->availabilities
                        ->flatMap->timeSlots
                        ->sortBy('start_at')
                        ->groupBy(fn($slot) => $slot->start_at->format('Y-m-d'));
                @endphp

                @if($slots->count())
                    <form method="POST" action="{{ route('client.bookings.store', $profile) }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-stone-700 mb-2">Créneau</label>
                            <div class="flex flex-col gap-2 max-h-48 overflow-y-auto pr-1">
                                @foreach($slots as $date => $dateSlots)
                                    <p class="text-xs text-stone-400 uppercase tracking-wide mt-1">
                                        {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM') }}
                                    </p>
                                    @foreach($dateSlots as $slot)
                                        <label class="flex items-center gap-3 p-2.5 rounded-xl border
                                                      border-stone-100 hover:bg-green-50 cursor-pointer
                                                      hover:border-green-200 transition">
                                            <input type="radio" name="time_slot_id"
                                                   value="{{ $slot->id }}"
                                                   class="accent-green-600" required>
                                            <span class="text-sm text-stone-700">
                                                {{ $slot->start_at->format('H:i') }}
                                                → {{ $slot->end_at->format('H:i') }}
                                            </span>
                                        </label>
                                    @endforeach
                                @endforeach
                            </div>
                            @error('time_slot_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="subject" class="block text-sm font-medium text-stone-700 mb-1.5">Sujet</label>
                            <input type="text" id="subject" name="subject"
                                   value="{{ old('subject') }}"
                                   placeholder="ex: Rénovation salon"
                                   class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200
                                          rounded-xl text-sm outline-none transition
                                          focus:ring-2 focus:ring-green-200 focus:border-green-400">
                            @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-5">
                            <label for="message" class="block text-sm font-medium text-stone-700 mb-1.5">
                                Message (optionnel)
                            </label>
                            <textarea id="message" name="message" rows="3"
                                      placeholder="Décrivez votre projet..."
                                      class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200
                                             rounded-xl text-sm outline-none transition resize-none
                                             focus:ring-2 focus:ring-green-200 focus:border-green-400">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5
                                       bg-green-700 text-white text-sm font-medium rounded-xl
                                       hover:bg-green-800 transition">
                            Demander une consultation
                        </button>
                    </form>
                @else
                    <div class="text-center py-8">
                        <p class="text-stone-400 text-sm mb-3">Aucun créneau disponible.</p>
                        <a href="{{ route('client.messages.show', $profile->user) }}"
                           class="text-sm text-green-600 hover:text-green-800 font-medium transition">
                            Contacter directement →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
const csrf = "{{ csrf_token() }}";

async function toggleFavorite(btn, id, type) {
    const isFav = btn.dataset.favorited === 'true';
    const svg   = btn.querySelector('svg');

    const res = await fetch("{{ route('client.favorites.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            favoritable_id:   id,
            favoritable_type: type,
            _remove:          isFav,
        }),
    });

    if (res.ok || res.status === 422) {
        if (isFav) {
            btn.dataset.favorited = 'false';
            btn.className = btn.className
                .replace('bg-red-500 text-white', 'bg-white/80 text-stone-500 hover:bg-white');
            svg.setAttribute('fill', 'none');
        } else {
            btn.dataset.favorited = 'true';
            btn.className = btn.className
                .replace('bg-white/80 text-stone-500 hover:bg-white', 'bg-red-500 text-white');
            svg.setAttribute('fill', 'currentColor');
        }
    }
}
</script>
@endpush