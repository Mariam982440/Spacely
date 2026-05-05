@extends('layouts.client')
@section('title', 'Architectes')

@section('content')

    {{-- ── En-tête + filtres ── --}}
    <div class="mb-8">
        <h1 class="font-serif text-2xl font-semibold text-stone-900 mb-6">
            Trouver un architecte
        </h1>

        <form method="GET" action="{{ route('client.architects.index') }}"
              class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm
                     flex items-end gap-4 flex-wrap">

            {{-- Ville --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-stone-500 mb-1.5">Ville</label>
                <input type="text" name="city"
                       value="{{ request('city') }}"
                       placeholder="ex: Casablanca"
                       class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl
                              text-sm outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">
            </div>

            {{-- Style --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-stone-500 mb-1.5">Style</label>
                <select name="tag"
                        class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl
                               text-sm outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400 cursor-pointer">
                    <option value="">Tous les styles</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Expérience --}}
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-medium text-stone-500 mb-1.5">Expérience min.</label>
                <select name="experience"
                        class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl
                               text-sm outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400 cursor-pointer">
                    <option value="">Toute expérience</option>
                    <option value="2"  {{ request('experience') == 2  ? 'selected' : '' }}>2+ ans</option>
                    <option value="5"  {{ request('experience') == 5  ? 'selected' : '' }}>5+ ans</option>
                    <option value="10" {{ request('experience') == 10 ? 'selected' : '' }}>10+ ans</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-green-700 text-white text-sm font-medium
                               rounded-xl hover:bg-green-800 transition">
                    Rechercher
                </button>
                @if(request()->hasAny(['city', 'tag', 'experience']))
                    <a href="{{ route('client.architects.index') }}"
                       class="px-4 py-2.5 border border-stone-200 text-stone-500 text-sm
                              rounded-xl hover:bg-stone-50 transition">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Grille architectes ── --}}
    @if($architects->count())
        <p class="text-sm text-stone-400 mb-4">
            {{ $architects->total() }} architecte(s) trouvé(s)
        </p>

        <div class="grid grid-cols-3 gap-5 mb-8">
            @foreach($architects as $profile)
                <a href="{{ route('client.architects.show', $profile) }}"
                   class="bg-white border border-stone-200 rounded-2xl overflow-hidden
                          shadow-sm hover:shadow-md transition group block">

                    {{-- Image projet --}}
                    <div class="aspect-video bg-stone-100 overflow-hidden">
                        @if($profile->projects->first()?->images->first())
                            <img src="{{ Storage::url($profile->projects->first()->images->first()->image_path) }}"
                                 alt="{{ $profile->user->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-green-50">
                                <span class="font-serif text-4xl text-green-300">
                                    {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="font-medium text-stone-900 group-hover:text-green-700 transition">
                                {{ $profile->user->name }}
                            </h3>
                            <span class="text-xs text-stone-400 shrink-0">
                                {{ $profile->experience_years }} ans
                            </span>
                        </div>

                        <p class="text-xs text-stone-400 flex items-center gap-1 mb-3">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $profile->city }}
                        </p>

                        {{-- Tags --}}
                        @php
                            $tags = $profile->projects->flatMap->tags->unique('id')->take(3);
                        @endphp
                        @if($tags->count())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($tags as $tag)
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-stone-100">
                            <span class="text-xs text-stone-400">
                                {{ $profile->projects_count }} projet(s)
                            </span>
                            <span class="text-xs text-green-600 font-medium">
                                Voir le profil →
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{ $architects->links() }}

    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
            </div>
            <h3 class="font-serif text-lg text-stone-700 mb-2">Aucun architecte trouvé</h3>
            <p class="text-stone-400 text-sm">Essayez d'autres critères de recherche.</p>
        </div>
    @endif

@endsection